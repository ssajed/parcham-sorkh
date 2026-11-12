@extends('admin.layouts.app')

@section('content')
    <div class="admin-page-heading">
        <div>
            <div class="admin-eyebrow">مدیریت کاربران</div>
            <h1 class="admin-page-title">همه کاربران</h1>
            <p class="admin-page-description">
                فهرست حساب‌های ثبت‌شده در سامانه پرچم سرخ
            </p>
        </div>

        <div class="admin-page-heading-actions">
            <span class="admin-role-badge">
                {{ number_format($users->total()) }} کاربر
            </span>
        </div>
    </div>

    <section class="admin-filter-panel">
        <form method="GET"
              action="{{ route('admin.users.index') }}"
              class="admin-filter-form">

            <div class="admin-form-group">
                <label for="q">جست‌وجوی کاربر</label>
                <input
                    type="search"
                    id="q"
                    name="q"
                    class="admin-form-control"
                    value="{{ $search }}"
                    placeholder="نام یا شماره موبایل..."
                >
            </div>

            <div class="admin-form-group">
                <label for="role">نقش کاربر</label>
                <select id="role" name="role" class="admin-form-control">
                    <option value="">همه نقش‌ها</option>
                    <option value="companion_seeker"
                        @selected($role === 'companion_seeker')>
                        همراه‌جو
                    </option>
                    <option value="companion_helper"
                        @selected($role === 'companion_helper')>
                        همراه‌یار
                    </option>
                    <option value="admin"
                        @selected($role === 'admin')>
                        مدیر سامانه
                    </option>
                </select>
            </div>

            <div class="admin-filter-actions">
                <button type="submit" class="admin-button admin-button-primary">
                    جست‌وجو
                </button>

                <a href="{{ route('admin.users.index') }}"
                   class="admin-button admin-button-secondary">
                    پاک کردن
                </a>
            </div>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <h2>فهرست کاربران</h2>
            <span class="admin-pagination-info">
                {{ number_format($users->firstItem() ?? 0) }}
                تا
                {{ number_format($users->lastItem() ?? 0) }}
                از
                {{ number_format($users->total()) }}
            </span>
        </div>

        @if ($users->isEmpty())
            <div class="admin-empty-state">
                <div class="admin-empty-state-icon">⌕</div>
                <h3>کاربری پیدا نشد</h3>
                <p>
                    با عبارت جست‌وجو یا فیلتر انتخاب‌شده، کاربری وجود ندارد.
                </p>
            </div>
        @else
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>کاربر</th>
                            <th>شماره موبایل</th>
                            <th>نقش</th>
                            <th>وضعیت حساب</th>
                            <th>تاریخ ثبت‌نام</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="admin-user-cell">
                                        <div class="admin-user-cell-avatar">
                                            {{ mb_substr($user->name ?: 'ک', 0, 1) }}
                                        </div>

                                        <div class="admin-user-cell-info">
                                            <strong>
                                                {{ $user->name ?: 'بدون نام' }}
                                            </strong>
                                            <small>
                                                شناسه: {{ $user->id }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    {{ $user->mobile ?: '—' }}
                                </td>

                                <td>
                                    @switch($user->role)
                                        @case('companion_seeker')
                                            همراه‌جو
                                            @break

                                        @case('companion_helper')
                                            همراه‌یار
                                            @break

                                        @case('admin')
                                            مدیر سامانه
                                            @break

                                        @default
                                            نامشخص
                                    @endswitch
                                </td>

                                <td>
                                    @switch($user->account_status)
                                        @case('approved')
                                            <span class="admin-status admin-status-approved">
                                                تأییدشده
                                            </span>
                                            @break

                                        @case('pending')
                                            <span class="admin-status admin-status-pending">
                                                در انتظار بررسی
                                            </span>
                                            @break

                                        @case('rejected')
                                            <span class="admin-status admin-status-rejected">
                                                ردشده
                                            </span>
                                            @break

                                        @default
                                            <span class="admin-status">
                                                نامشخص
                                            </span>
                                    @endswitch
                                </td>

                                <td>
                                    {{ $user->created_at?->format('Y/m/d') ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                <div class="admin-pagination-info">
                    صفحه {{ number_format($users->currentPage()) }}
                    از {{ number_format($users->lastPage()) }}
                </div>

                <div class="admin-pagination-links">
                    @if ($users->onFirstPage())
                        <span aria-disabled="true">قبلی</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}">قبلی</a>
                    @endif

                    @foreach ($users->getUrlRange(
                        max(1, $users->currentPage() - 2),
                        min($users->lastPage(), $users->currentPage() + 2)
                    ) as $page => $url)
                        @if ($page === $users->currentPage())
                            <span class="active" aria-current="page">
                                {{ number_format($page) }}
                            </span>
                        @else
                            <a href="{{ $url }}">
                                {{ number_format($page) }}
                            </a>
                        @endif
                    @endforeach

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}">بعدی</a>
                    @else
                        <span aria-disabled="true">بعدی</span>
                    @endif
                </div>
            </div>
        @endif
    </section>
@endsection