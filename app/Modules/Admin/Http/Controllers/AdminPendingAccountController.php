<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminPendingAccountController extends Controller
{
    /**
     * Display accounts waiting for admin review.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'role' => [
                'nullable',
                'in:companion_seeker,companion_helper',
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
                'vehicle_model',
                'vehicle_plate',
                'created_at',
            ])
            ->where('account_status', User::ACCOUNT_STATUS_PENDING)
            ->whereIn('role', [
                User::ROLE_COMPANION_SEEKER,
                User::ROLE_COMPANION_HELPER,
            ])
            ->when($role !== '', function ($query) use ($role): void {
                $query->where('role', $role);
            })
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

        return view('admin.accounts.pending', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
        ]);
    }

    /**
     * Approve a pending companion account.
     */
    public function approve(int $userId): RedirectResponse
    {
        DB::transaction(function () use ($userId): void {
            $user = $this->findPendingAccount($userId);

            $user->account_status = User::ACCOUNT_STATUS_APPROVED;
            $user->account_approved_at = now();
            $user->rejection_reason = null;

            $user->save();
        });

        return back()->with(
            'success',
            'حساب کاربر با موفقیت تأیید شد.'
        );
    }

    /**
     * Reject a pending companion account.
     */
    public function reject(Request $request, int $userId): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ], [
            'rejection_reason.required' => 'وارد کردن دلیل رد حساب الزامی است.',
            'rejection_reason.string' => 'دلیل رد حساب معتبر نیست.',
            'rejection_reason.max' => 'دلیل رد حساب نباید بیشتر از ۲۰۰۰ کاراکتر باشد.',
        ]);

        DB::transaction(function () use ($userId, $validated): void {
            $user = $this->findPendingAccount($userId);

            $user->account_status = User::ACCOUNT_STATUS_REJECTED;
            $user->account_approved_at = null;
            $user->rejection_reason = trim($validated['rejection_reason']);

            $user->save();
        });

        return back()->with(
            'success',
            'حساب کاربر با ثبت دلیل رد، رد شد.'
        );
    }

    /**
     * Retrieve an account that is still waiting for review.
     */
    private function findPendingAccount(int $userId): User
    {
        $user = User::query()
            ->whereKey($userId)
            ->where('account_status', User::ACCOUNT_STATUS_PENDING)
            ->whereIn('role', [
                User::ROLE_COMPANION_SEEKER,
                User::ROLE_COMPANION_HELPER,
            ])
            ->lockForUpdate()
            ->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'account' => 'این حساب وجود ندارد یا قبلاً بررسی و تعیین تکلیف شده است.',
            ]);
        }

        return $user;
    }
}
