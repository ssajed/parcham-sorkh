@extends('admin.layouts.app')

@section('content')
    <div class="admin-page-heading">
        <div>
            <div class="admin-eyebrow">مدیریت حساب‌ها</div>
            <h1 class="admin-page-title">بررسی و تأیید حساب‌ها</h1>
            <p class="admin-page-description">
                حساب‌های ثبت‌شده‌ای که هنوز توسط مدیریت بررسی و تعیین تکلیف نشده‌اند.
            </p>
        </div>

        <div class="admin-page-heading-actions">
            <span class="admin-role-badge">
                {{ number_format($users->total()) }} حساب در انتظار بررسی
            </span>
        </div>
    </div>

    <section class="admin-filter-panel">
        <form method="GET"
              action="{{ route('admin.accounts.pending') }}"
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
                <label for="role">نوع حساب</label>
                <select id="role" name="role" class="admin-form-control">
                    <option value="">همه حساب‌ها</option>
                    <option value="companion_seeker"
                        @selected($role === 'companion_seeker')>
                        همراه‌جو
                    </option>
                    <option value="companion_helper"
                        @selected($role === 'companion_helper')>
                        همراه‌یار
                    </option>
                </select>
            </div>

            <div class="admin-filter-actions">
                <button type="submit" class="admin-button admin-button-primary">
                    جست‌وجو
                </button>

                <a href="{{ route('admin.accounts.pending') }}"
                   class="admin-button admin-button-secondary">
                    پاک کردن
                </a>
            </div>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <h2>حساب‌های نیازمند بررسی</h2>

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
                <div class="admin-empty-state-icon">✓</div>
                <h3>حسابی در انتظار بررسی نیست</h3>
                <p>
                    در حال حاضر حسابی با وضعیت انتظار بررسی وجود ندارد.
                </p>
            </div>
        @else
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>کاربر</th>
                            <th>شماره موبایل</th>
                            <th>نوع حساب</th>
                            <th>اطلاعات خودرو</th>
                            <th>تاریخ ثبت‌نام</th>
                            <th>وضعیت و عملیات</th>
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

                                <td>{{ $user->mobile ?: '—' }}</td>

                                <td>
                                    @if ($user->role === 'companion_helper')
                                        <span class="admin-role-badge">
                                            همراه‌یار
                                        </span>
                                    @else
                                        <span class="admin-role-badge">
                                            همراه‌جو
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($user->role === 'companion_helper')
                                        <div>
                                            <strong>
                                                {{ $user->vehicle_model ?: 'مدل نامشخص' }}
                                            </strong>
                                            <small>
                                                پلاک:
                                                {{ $user->vehicle_plate ?: 'ثبت نشده' }}
                                            </small>
                                        </div>
                                    @else
                                        <span>—</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $user->created_at ? \App\Support\JalaliDate::format($user->created_at) : '—' }}
                                </td>

                                <td>
                                    <div style="display:flex; flex-direction:column; gap:10px; min-width:170px;">
                                        <span class="admin-status admin-status-pending">
                                            در انتظار بررسی
                                        </span>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.accounts.approve', $user->id) }}"
                                            onsubmit="return confirm('آیا از تأیید حساب این کاربر اطمینان دارید؟');"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="admin-button admin-button-primary"
                                                style="width:100%;"
                                            >
                                                تأیید حساب
                                            </button>
                                        </form>

                                        <details>
                                            <summary
                                                class="admin-button admin-button-secondary"
                                                style="cursor:pointer; text-align:center; list-style:none; color:#b91c1c;"
                                            >
                                                رد حساب
                                            </summary>

                                            <form
                                                method="POST"
                                                action="{{ route('admin.accounts.reject', $user->id) }}"
                                                style="margin-top:10px;"
                                                onsubmit="return confirm('آیا از رد حساب این کاربر اطمینان دارید؟');"
                                            >
                                                @csrf

                                                <div class="admin-form-group">
                                                    <label for="rejection_reason_{{ $user->id }}">
                                                        دلیل رد حساب
                                                    </label>

                                                    <textarea
                                                        id="rejection_reason_{{ $user->id }}"
                                                        name="rejection_reason"
                                                        class="admin-form-control"
                                                        rows="3"
                                                        maxlength="2000"
                                                        required
                                                        placeholder="دلیل رد حساب را بنویسید..."
                                                    >{{ old('rejection_reason') }}</textarea>

                                                    @error('rejection_reason')
                                                        <small style="color:#b91c1c;">
                                                            {{ $message }}
                                                        </small>
                                                    @enderror
                                                </div>

                                                <button
                                                    type="submit"
                                                    class="admin-button admin-button-secondary"
                                                    style="width:100%; color:#b91c1c;"
                                                >
                                                    ثبت رد حساب
                                                </button>
                                            </form>
                                        </details>
                                    </div>
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
