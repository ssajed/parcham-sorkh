<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
}