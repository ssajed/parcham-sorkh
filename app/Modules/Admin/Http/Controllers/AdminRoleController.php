<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class AdminRoleController extends Controller
{
    public function index(): View
    {
        $roles = [
            [
                'key' => 'admin',
                'name' => 'مدیر سامانه',
                'description' => 'دسترسی به پنل مدیریت و بخش‌های مدیریتی سامانه.',
                'permissions' => [
                    'مشاهده داشبورد مدیریت',
                    'مشاهده و جست‌وجوی کاربران',
                    'بررسی و تعیین وضعیت حساب‌ها',
                    'مشاهده فهرست مدیران سامانه',
                    'مشاهده نقش‌های سامانه',
                ],
            ],
            [
                'key' => 'companion_seeker',
                'name' => 'همراه‌جو',
                'description' => 'کاربری که برای دریافت همراهی، درخواست ثبت می‌کند.',
                'permissions' => [
                    'مدیریت اطلاعات حساب کاربری',
                    'ثبت درخواست همراهی',
                    'مشاهده وضعیت درخواست‌ها',
                    'ثبت امتیاز و نظر پس از همراهی',
                ],
            ],
            [
                'key' => 'companion_helper',
                'name' => 'همراه‌یار',
                'description' => 'کاربری که برای ارائه همراهی داوطلبانه ثبت‌نام می‌کند.',
                'permissions' => [
                    'مدیریت اطلاعات حساب کاربری',
                    'ثبت و مدیریت اطلاعات خودرو',
                    'مشاهده درخواست‌های همراهی',
                    'اعلام آمادگی برای همراهی',
                    'ثبت امتیاز و نظر پس از همراهی',
                ],
            ],
        ];

        return view('admin.roles.index', [
            'roles' => $roles,
        ]);
    }
}