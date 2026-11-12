<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'role' => [
                'nullable',
                'in:companion_seeker,companion_helper,admin',
            ],
        ]);

        $search = trim($validated['q'] ?? '');
        $role = $validated['role'] ?? '';

        $users = User::query()
            ->select([
                'id',
                'name',
                'mobile',
                'role',
                'account_status',
                'blocked_at',
                'blocked_reason',
                'created_at',
            ])
            ->when($role !== '', function (Builder $query) use ($role): void {
                $query->where('role', $role);
            })
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $subQuery) use ($search): void {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
        ]);
    }

    /**
     * Suspend a companion account without deleting its data or changing approval.
     */
    public function block(Request $request, int $userId): RedirectResponse
    {
        $validated = $request->validate([
            'blocked_reason' => ['required', 'string', 'max:2000'],
        ], [
            'blocked_reason.required' => 'ثبت دلیل مسدودسازی الزامی است.',
            'blocked_reason.max' => 'دلیل مسدودسازی نباید بیشتر از ۲۰۰۰ کاراکتر باشد.',
        ]);

        DB::transaction(function () use ($request, $userId, $validated): void {
            $user = User::query()
                ->whereKey($userId)
                ->lockForUpdate()
                ->first();

            if (! $user || $user->role === User::ROLE_ADMIN) {
                throw ValidationException::withMessages([
                    'user' => 'این حساب وجود ندارد یا امکان مسدودسازی آن وجود ندارد.',
                ]);
            }

            if ($user->isBlocked()) {
                throw ValidationException::withMessages([
                    'user' => 'این حساب قبلاً مسدود شده است.',
                ]);
            }

            $user->blocked_at = now();
            $user->blocked_reason = trim($validated['blocked_reason']);
            $user->blocked_by = $request->user()->getKey();
            $user->save();
        });

        return back()->with('success', 'حساب کاربر مسدود شد. کاربر پس از ورود، پیام مسدودسازی را مشاهده می‌کند.');
    }

    /**
     * Restore access to a previously suspended companion account.
     */
    public function unblock(int $userId): RedirectResponse
    {
        DB::transaction(function () use ($userId): void {
            $user = User::query()
                ->whereKey($userId)
                ->lockForUpdate()
                ->first();

            if (! $user || $user->role === User::ROLE_ADMIN || ! $user->isBlocked()) {
                throw ValidationException::withMessages([
                    'user' => 'حساب مسدودشده‌ای با این مشخصات پیدا نشد.',
                ]);
            }

            $user->blocked_at = null;
            $user->blocked_reason = null;
            $user->blocked_by = null;
            $user->save();
        });

        return back()->with('success', 'مسدودیت حساب برداشته شد و دسترسی کاربر به وضعیت قبلی بازگشت.');
    }
}
