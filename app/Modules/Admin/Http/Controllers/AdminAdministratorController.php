<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminAdministratorController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim($validated['q'] ?? '');

        $administrators = User::query()
            ->select([
                'id', 'name', 'mobile', 'role', 'account_status',
                'account_approved_at', 'created_at',
                'can_be_companion_seeker', 'can_be_companion_helper',
            ])
            ->where('role', User::ROLE_ADMIN)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $eligibleUsers = User::query()
            ->select(['id', 'name', 'mobile'])
            ->where('account_status', User::ACCOUNT_STATUS_APPROVED)
            ->where('role', '!=', User::ROLE_ADMIN)
            ->orderBy('name')
            ->get();

        return view('admin.administrators.index', [
            'administrators' => $administrators,
            'eligibleUsers' => $eligibleUsers,
            'search' => $search,
            'currentAdministratorId' => (int) $request->user()->getKey(),
        ]);
    }

    /**
     * Grant administrator access to an existing, approved account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ], [
            'user_id.required' => 'انتخاب حساب کاربری الزامی است.',
            'user_id.exists' => 'حساب انتخاب‌شده پیدا نشد.',
        ]);

        DB::transaction(function () use ($validated): void {
            $user = User::query()
                ->whereKey($validated['user_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($user->role === User::ROLE_ADMIN) {
                throw ValidationException::withMessages([
                    'user_id' => 'این حساب در حال حاضر مدیر سامانه است.',
                ]);
            }

            if (! $user->isAccountApproved()) {
                throw ValidationException::withMessages([
                    'user_id' => 'فقط حساب تأییدشده می‌تواند به مدیر سامانه تبدیل شود.',
                ]);
            }

            $user->role = User::ROLE_ADMIN;
            $user->save();
        });

        return back()->with('success', 'دسترسی مدیریت برای حساب انتخاب‌شده فعال شد.');
    }

    /**
     * Revoke administrator access without allowing the final active admin
     * or the currently signed-in administrator to remove their own access.
     */
    public function destroy(Request $request, int $userId): RedirectResponse
    {
        DB::transaction(function () use ($request, $userId): void {
            // Lock active administrators in a consistent order to serialize
            // concurrent attempts to remove administrator access.
            $activeAdministrators = User::query()
                ->where('role', User::ROLE_ADMIN)
                ->where('account_status', User::ACCOUNT_STATUS_APPROVED)
                ->orderBy('id')
                ->lockForUpdate()
                ->get(['id']);

            $administrator = User::query()
                ->whereKey($userId)
                ->where('role', User::ROLE_ADMIN)
                ->lockForUpdate()
                ->first();

            if (! $administrator) {
                throw ValidationException::withMessages([
                    'administrator' => 'حساب مدیر موردنظر پیدا نشد.',
                ]);
            }

            if ((int) $administrator->getKey() === (int) $request->user()->getKey()) {
                throw ValidationException::withMessages([
                    'administrator' => 'برای جلوگیری از قطع دسترسی ناخواسته، نمی‌توانید دسترسی حساب جاری خود را حذف کنید.',
                ]);
            }

            if (
                $administrator->isAccountApproved()
                && $activeAdministrators->count() <= 1
            ) {
                throw ValidationException::withMessages([
                    'administrator' => 'حذف دسترسی آخرین مدیر فعال سامانه امکان‌پذیر نیست.',
                ]);
            }

            // Preserve the independent companion permissions on the account.
            if ($administrator->can_be_companion_seeker) {
                $administrator->role = User::ROLE_COMPANION_SEEKER;
            } elseif ($administrator->can_be_companion_helper) {
                $administrator->role = User::ROLE_COMPANION_HELPER;
            } else {
                // The initial administrator may have been created by the CLI
                // before companion-role flags existed; restore a safe default.
                $administrator->can_be_companion_seeker = true;
                $administrator->role = User::ROLE_COMPANION_SEEKER;
            }

            $administrator->save();
        });

        return back()->with('success', 'دسترسی مدیریت از حساب انتخاب‌شده برداشته شد.');
    }
}
