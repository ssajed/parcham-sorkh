
@extends('admin.layouts.app')

@section('title', 'داشبورد مدیریت')

@section('breadcrumb', 'داشبورد')

@section('content')

    <section class="admin-page-heading">
        <div>
            <span class="admin-eyebrow">مدیریت پرچم سرخ</span>

            <h1 class="admin-page-title">داشبورد مدیریت</h1>

            <p class="admin-page-description">
                نمای کلی کاربران و وضعیت فعالیت سامانه
            </p>
        </div>
    </section>

    <section class="admin-welcome-panel">
        <div class="admin-welcome-content">
            <span class="admin-welcome-label">
                پنل مدیریت پرچم سرخ
            </span>

            <h2>به فضای مدیریت خوش آمدید</h2>

            <p>
                از این بخش می‌توانید کاربران، حساب‌های همراه‌جویان و
                همراه‌یاران و درخواست‌های بررسی حساب را مدیریت کنید.
            </p>
        </div>

        <div class="admin-welcome-decoration" aria-hidden="true">
            <span>پ</span>
        </div>
    </section>

    <section class="admin-section">
        <div class="admin-section-heading">
            <div>
                <h2>آمار کاربران</h2>
                <p>این آمار مستقیماً از حساب‌های ثبت‌شده در پایگاه داده محاسبه می‌شود.</p>
            </div>
        </div>

        <div class="admin-stats-grid">

            <article class="admin-stat-card">
                <div class="admin-stat-top">
                    <span class="admin-stat-icon admin-stat-icon-green">♙</span>
                    <span class="admin-stat-caption">کل کاربران</span>
                </div>

                <div class="admin-stat-value">
                    {{ isset($stats['total_users']) ? number_format($stats['total_users']) : '—' }}
                </div>

                <div class="admin-stat-bottom">
                    <span class="admin-stat-name">تمام حساب‌های سامانه</span>
                    <span class="admin-stat-indicator">آمار کاربران</span>
                </div>
            </article>

            <article class="admin-stat-card">
                <div class="admin-stat-top">
                    <span class="admin-stat-icon admin-stat-icon-blue">♧</span>
                    <span class="admin-stat-caption">همراه‌جویان</span>
                </div>

                <div class="admin-stat-value">
                    {{ isset($stats['companion_seekers']) ? number_format($stats['companion_seekers']) : '—' }}
                </div>

                <div class="admin-stat-bottom">
                    <span class="admin-stat-name">متقاضیان همراهی</span>
                    <span class="admin-stat-indicator">آمار کاربران</span>
                </div>
            </article>

            <article class="admin-stat-card">
                <div class="admin-stat-top">
                    <span class="admin-stat-icon admin-stat-icon-orange">♧</span>
                    <span class="admin-stat-caption">همراه‌یاران</span>
                </div>

                <div class="admin-stat-value">
                    {{ isset($stats['companion_helpers']) ? number_format($stats['companion_helpers']) : '—' }}
                </div>

                <div class="admin-stat-bottom">
                    <span class="admin-stat-name">ارائه‌دهندگان همراهی</span>
                    <span class="admin-stat-indicator">آمار کاربران</span>
                </div>
            </article>

            <article class="admin-stat-card">
                <div class="admin-stat-top">
                    <span class="admin-stat-icon admin-stat-icon-red">◷</span>
                    <span class="admin-stat-caption">در انتظار بررسی</span>
                </div>

                <div class="admin-stat-value">
                    {{ isset($stats['pending_accounts']) ? number_format($stats['pending_accounts']) : '—' }}
                </div>

                <div class="admin-stat-bottom">
                    <span class="admin-stat-name">حساب‌های تأییدنشده</span>
                    <span class="admin-stat-indicator">نیازمند بررسی</span>
                </div>
            </article>

        </div>
    </section>

    <div class="admin-dashboard-grid">

        <section class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <h2>وضعیت حساب‌های کاربری</h2>
                    <p>تفکیک حساب‌ها بر اساس وضعیت تأیید مدیریت</p>
                </div>
            </div>

            <div class="admin-quick-links">

                <div class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-green">✓</span>

                    <span class="admin-quick-text">
                        <strong>حساب‌های تأییدشده</strong>
                        <small>کاربرانی که مدیریت حساب آن‌ها را تأیید کرده است</small>
                    </span>

                    <strong>
                        {{ isset($stats['approved_accounts']) ? number_format($stats['approved_accounts']) : '—' }}
                    </strong>
                </div>

                <div class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-orange">◷</span>

                    <span class="admin-quick-text">
                        <strong>حساب‌های در انتظار بررسی</strong>
                        <small>کاربرانی که هنوز تأیید نهایی نشده‌اند</small>
                    </span>

                    <strong>
                        {{ isset($stats['pending_accounts']) ? number_format($stats['pending_accounts']) : '—' }}
                    </strong>
                </div>

                <div class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-red">×</span>

                    <span class="admin-quick-text">
                        <strong>حساب‌های ردشده</strong>
                        <small>حساب‌هایی که درخواست تأیید آن‌ها رد شده است</small>
                    </span>

                    <strong>
                        {{ isset($stats['rejected_accounts']) ? number_format($stats['rejected_accounts']) : '—' }}
                    </strong>
                </div>

            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <h2>بخش‌های مدیریت</h2>
                    <p>دسترسی به بخش‌های اصلی پنل</p>
                </div>
            </div>

            <div class="admin-quick-links">

                <a href="{{ route('admin.users.index') }}" class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-green">♙</span>

                    <span class="admin-quick-text">
                        <strong>مدیریت کاربران</strong>
                        <small>مشاهده و مدیریت حساب‌های سامانه</small>
                    </span>
                </a>

                <a href="{{ route('admin.accompaniment-requests.index') }}" class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-blue">↔</span>
                    <span class="admin-quick-text">
                        <strong>درخواست‌های همراهی</strong>
                        <small>مشاهده وضعیت درخواست‌ها و توافق‌ها</small>
                    </span>
                </a>

                <a href="{{ route('admin.accounts.pending') }}" class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-orange">✓</span>

                    <span class="admin-quick-text">
                        <strong>بررسی حساب‌ها</strong>
                        <small>بررسی وضعیت و تأیید حساب همراه‌یاران و همراه‌جویان</small>
                    </span>
                </a>

                <a href="{{ route('admin.roles.index') }}" class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-blue">⚙</span>

                    <span class="admin-quick-text">
                        <strong>مدیریت دسترسی‌ها</strong>
                        <small>مدیریت نقش‌ها و سطح دسترسی مدیران</small>
                    </span>
                </a>

            </div>
        </section>

    </div>

    <section class="admin-panel admin-activity-panel">
        <div class="admin-panel-header">
            <div>
                <h2>راهنمای بررسی حساب‌ها</h2>
                <p>مراحل تأیید حساب کاربران</p>
            </div>
        </div>

        <div class="admin-activity-list">

            <div class="admin-activity-item">
                <span class="admin-activity-marker admin-activity-marker-green"></span>

                <div class="admin-activity-content">
                    <strong>بررسی اطلاعات ثبت‌نام</strong>
                    <small>
                        اطلاعات هویتی، شماره همراه و اطلاعات خودرو در صورت وجود بررسی شود.
                    </small>
                </div>
            </div>

            <div class="admin-activity-item">
                <span class="admin-activity-marker admin-activity-marker-blue"></span>

                <div class="admin-activity-content">
                    <strong>بررسی دستی مدارک</strong>
                    <small>
                        مدارک ارسالی از طریق پیام‌رسان بله بررسی می‌شوند.
                        تصاویر مدارک در سایت ذخیره نمی‌شوند.
                    </small>
                </div>
            </div>

            <div class="admin-activity-item">
                <span class="admin-activity-marker admin-activity-marker-orange"></span>

                <div class="admin-activity-content">
                    <strong>ثبت نتیجه بررسی</strong>
                    <small>
                        پس از بررسی، وضعیت حساب توسط مدیریت تأیید یا رد می‌شود.
                    </small>
                </div>
            </div>

        </div>
    </section>

@endsection
