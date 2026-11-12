<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $approved = User::ACCOUNT_STATUS_APPROVED;

        $stats = [
            'total_users' => User::query()->count(),
            'companion_seekers' => User::query()
                ->where('account_status', $approved)
                ->where('can_be_companion_seeker', true)
                ->count(),
            'companion_helpers' => User::query()
                ->where('account_status', $approved)
                ->where('can_be_companion_helper', true)
                ->count(),
            'approved_accounts' => User::query()
                ->where('account_status', $approved)
                ->count(),
            'pending_accounts' => User::query()
                ->where('account_status', User::ACCOUNT_STATUS_PENDING)
                ->count(),
            'rejected_accounts' => User::query()
                ->where('account_status', User::ACCOUNT_STATUS_REJECTED)
                ->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
