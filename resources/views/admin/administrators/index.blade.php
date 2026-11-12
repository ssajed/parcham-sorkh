@extends('admin.layouts.app')

@section('title', 'مدیران سامانه')
@section('breadcrumb', 'مدیران سامانه')

@section('content')
    <div class="admin-page-heading">
        <div>
            <div class="admin-eyebrow">مدیریت دسترسی‌ها</div>
            <h1 class="admin-page-title">مدیران سامانه</h1>
            <p class="admin-page-description">
                فقط حساب‌های تأییدشده را می‌توان به مدیریت ارتقا داد. حذف دسترسی آخرین مدیر فعال و حذف دسترسی حساب جاری مجاز نیست.
            </p>
        </div>
        <div class="admin-page-heading-actions">
            <span class="admin-role-badge">{{ number_format($administrators->total()) }} مدیر</span>
        </div>
    </div>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <div>
                <h2>افزودن مدیر جدید</h2>
                <p>یک حساب موجود و تأییدشده را انتخاب کنید؛ حساب جدیدی ساخته نمی‌شود.</p>
            </div>
        </div>

        @if ($eligibleUsers->isEmpty())
            <div class="admin-empty-state">
                <h3>حساب واجد شرایطی وجود ندارد</h3>
                <p>برای افزودن مدیر، ابتدا باید یک حساب کاربری تأییدشده وجود داشته باشد.</p>
            </div>
        @else
            <form method="POST" action="{{ route('admin.administrators.store') }}" class="admin-filter-form">
                @csrf
                <div class="admin-form-group">
                    <label for="user_id">حساب کاربری</label>
                    <select id="user_id" name="user_id" class="admin-form-control" required>
                        <option value="">انتخاب حساب تأییدشده</option>
                        @foreach ($eligibleUsers as $eligibleUser)
                            <option value="{{ $eligibleUser->id }}" @selected((string) old('user_id') === (string) $eligibleUser->id)>
                                {{ $eligibleUser->name ?: 'بدون نام' }} — {{ $eligibleUser->mobile ?: 'بدون موبایل' }} (شناسه {{ $eligibleUser->id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-filter-actions">
                    <button type="submit" class="admin-button admin-button-primary">اعطای دسترسی مدیریت</button>
                </div>
            </form>
        @endif
    </section>

    <section class="admin-filter-panel">
        <form method="GET" action="{{ route('admin.administrators.index') }}" class="admin-filter-form">
            <div class="admin-form-group">
                <label for="q">جست‌وجوی مدیر</label>
                <input type="search" id="q" name="q" class="admin-form-control"
                       value="{{ $search }}" placeholder="نام یا شماره موبایل...">
            </div>
            <div class="admin-filter-actions">
                <button type="submit" class="admin-button admin-button-primary">جست‌وجو</button>
                <a href="{{ route('admin.administrators.index') }}" class="admin-button admin-button-secondary">پاک کردن</a>
            </div>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <h2>فهرست مدیران</h2>
            <span class="admin-pagination-info">
                {{ number_format($administrators->firstItem() ?? 0) }}
                تا {{ number_format($administrators->lastItem() ?? 0) }}
                از {{ number_format($administrators->total()) }}
            </span>
        </div>

        @if ($administrators->isEmpty())
            <div class="admin-empty-state">
                <h3>مدیری ثبت نشده است</h3>
                <p>هیچ حسابی با نقش مدیر سامانه پیدا نشد.</p>
            </div>
        @else
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>مدیر</th>
                            <th>شماره موبایل</th>
                            <th>وضعیت حساب</th>
                            <th>تاریخ تأیید</th>
                            <th>تاریخ عضویت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($administrators as $administrator)
                            <tr>
                                <td>
                                    <div class="admin-user-cell">
                                        <div class="admin-user-cell-avatar">{{ mb_substr($administrator->name ?: 'م', 0, 1) }}</div>
                                        <div class="admin-user-cell-info">
                                            <strong>{{ $administrator->name ?: 'بدون نام' }}</strong>
                                            <small>شناسه: {{ $administrator->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $administrator->mobile ?: '—' }}</td>
                                <td>
                                    @if ($administrator->account_status === 'approved')
                                        <span class="admin-status admin-status-approved">فعال و تأییدشده</span>
                                    @elseif ($administrator->account_status === 'pending')
                                        <span class="admin-status admin-status-pending">در انتظار بررسی</span>
                                    @else
                                        <span class="admin-status admin-status-rejected">ردشده</span>
                                    @endif
                                </td>
                                <td>{{ $administrator->account_approved_at ? \App\Support\JalaliDate::format($administrator->account_approved_at) : '—' }}</td>
                                <td>{{ $administrator->created_at ? \App\Support\JalaliDate::format($administrator->created_at) : '—' }}</td>
                                <td>
                                    @if ((int) $administrator->id === $currentAdministratorId)
                                        <span class="admin-status admin-status-pending">حساب جاری</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.administrators.revoke', $administrator->id) }}"
                                              onsubmit="return confirm('دسترسی مدیریت این حساب برداشته شود؟');">
                                            @csrf
                                            <button type="submit" class="admin-button admin-button-secondary">لغو دسترسی</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                <div class="admin-pagination-info">
                    صفحه {{ number_format($administrators->currentPage()) }}
                    از {{ number_format($administrators->lastPage()) }}
                </div>
                <div class="admin-pagination-links">
                    @if ($administrators->onFirstPage())
                        <span aria-disabled="true">قبلی</span>
                    @else
                        <a href="{{ $administrators->previousPageUrl() }}">قبلی</a>
                    @endif
                    @foreach ($administrators->getUrlRange(
                        max(1, $administrators->currentPage() - 2),
                        min($administrators->lastPage(), $administrators->currentPage() + 2)
                    ) as $page => $url)
                        @if ($page === $administrators->currentPage())
                            <span class="active" aria-current="page">{{ number_format($page) }}</span>
                        @else
                            <a href="{{ $url }}">{{ number_format($page) }}</a>
                        @endif
                    @endforeach
                    @if ($administrators->hasMorePages())
                        <a href="{{ $administrators->nextPageUrl() }}">بعدی</a>
                    @else
                        <span aria-disabled="true">بعدی</span>
                    @endif
                </div>
            </div>
        @endif
    </section>
@endsection
