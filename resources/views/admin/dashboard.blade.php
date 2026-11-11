
@extends('admin.layouts.app')

@section('title', 'داشبورد مدیریت')

@section('breadcrumb', 'داشبورد')

@section('content')

    {{-- Page heading --}}
    <section class="admin-page-heading">
        <div>
            <span class="admin-eyebrow">مدیریت پرچم سرخ</span>

            <h1 class="admin-page-title">
                داشبورد مدیریت
            </h1>

            <p class="admin-page-description">
                نمای کلی بخش‌های مدیریتی و محتوایی سامانه
            </p>
        </div>

        <div class="admin-page-heading-actions">
            <span class="admin-demo-label">
                <span class="admin-status-dot"></span>
                محیط نمایشی
            </span>
        </div>
    </section>

    {{-- Welcome panel --}}
    <section class="admin-welcome-panel">
        <div class="admin-welcome-content">
            <span class="admin-welcome-label">
                پنل مدیریت پرچم سرخ
            </span>

            <h2>به فضای مدیریت خوش آمدید</h2>

            <p>
                از این بخش می‌توانید نمای کلی صفحات، مطالب،
                دسته‌بندی‌ها و رسانه‌های عمومی سامانه را مشاهده کنید.
            </p>
        </div>

        <div class="admin-welcome-decoration" aria-hidden="true">
            <span>پ</span>
        </div>
    </section>

    {{-- Statistics --}}
    <section class="admin-section">
        <div class="admin-section-heading">
            <div>
                <h2>نمای کلی سامانه</h2>
                <p>تمام اعداد این بخش نمونه و غیرواقعی هستند.</p>
            </div>
        </div>

        <div class="admin-stats-grid">

            <article class="admin-stat-card">
                <div class="admin-stat-top">
                    <span class="admin-stat-icon admin-stat-icon-green">
                        ▤
                    </span>
                    <span class="admin-stat-caption">محتوای سایت</span>
                </div>

                <div class="admin-stat-value">۱۲</div>

                <div class="admin-stat-bottom">
                    <span class="admin-stat-name">صفحات نمونه</span>
                    <span class="admin-stat-indicator">نمایشی</span>
                </div>
            </article>

            <article class="admin-stat-card">
                <div class="admin-stat-top">
                    <span class="admin-stat-icon admin-stat-icon-blue">
                        ▧
                    </span>
                    <span class="admin-stat-caption">مطالب</span>
                </div>

                <div class="admin-stat-value">۲۸</div>

                <div class="admin-stat-bottom">
                    <span class="admin-stat-name">اخبار و مطالب</span>
                    <span class="admin-stat-indicator">نمایشی</span>
                </div>
            </article>

            <article class="admin-stat-card">
                <div class="admin-stat-top">
                    <span class="admin-stat-icon admin-stat-icon-orange">
                        ▦
                    </span>
                    <span class="admin-stat-caption">ساختار محتوا</span>
                </div>

                <div class="admin-stat-value">۸</div>

                <div class="admin-stat-bottom">
                    <span class="admin-stat-name">دسته‌بندی‌ها</span>
                    <span class="admin-stat-indicator">نمایشی</span>
                </div>
            </article>

            <article class="admin-stat-card">
                <div class="admin-stat-top">
                    <span class="admin-stat-icon admin-stat-icon-red">
                        ▣
                    </span>
                    <span class="admin-stat-caption">فایل‌های عمومی</span>
                </div>

                <div class="admin-stat-value">۴۶</div>

                <div class="admin-stat-bottom">
                    <span class="admin-stat-name">رسانه‌های نمونه</span>
                    <span class="admin-stat-indicator">نمایشی</span>
                </div>
            </article>

        </div>
    </section>

    {{-- Dashboard columns --}}
    <div class="admin-dashboard-grid">

        {{-- Recent content --}}
        <section class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <h2>آخرین مطالب</h2>
                    <p>نمونه‌ای از محتوای سامانه</p>
                </div>

                <a href="{{ url('/admin/posts') }}" class="admin-text-link">
                    مشاهده همه
                    <span aria-hidden="true">←</span>
                </a>
            </div>

            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>عنوان مطلب</th>
                            <th>دسته‌بندی</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <div class="admin-table-title">
                                    معرفی سامانه پرچم سرخ
                                </div>
                                <small class="admin-table-subtitle">
                                    مطلب نمونه
                                </small>
                            </td>
                            <td>معرفی</td>
                            <td>
                                <span class="admin-status admin-status-published">
                                    منتشرشده
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="admin-table-title">
                                    راهنمای استفاده از سامانه
                                </div>
                                <small class="admin-table-subtitle">
                                    مطلب نمونه
                                </small>
                            </td>
                            <td>راهنما</td>
                            <td>
                                <span class="admin-status admin-status-draft">
                                    پیش‌نویس
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="admin-table-title">
                                    پرسش‌های متداول
                                </div>
                                <small class="admin-table-subtitle">
                                    مطلب نمونه
                                </small>
                            </td>
                            <td>اطلاع‌رسانی</td>
                            <td>
                                <span class="admin-status admin-status-published">
                                    منتشرشده
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="admin-table-title">
                                    درباره پرچم سرخ
                                </div>
                                <small class="admin-table-subtitle">
                                    مطلب نمونه
                                </small>
                            </td>
                            <td>معرفی</td>
                            <td>
                                <span class="admin-status admin-status-review">
                                    در انتظار بررسی
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="admin-panel-footer">
                اطلاعات جدول صرفاً جهت نمایش رابط کاربری است.
            </div>
        </section>

        {{-- Quick access --}}
        <section class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <h2>دسترسی سریع</h2>
                    <p>میان‌بر بخش‌های عمومی مدیریت محتوا</p>
                </div>
            </div>

            <div class="admin-quick-links">

                <a href="{{ url('/admin/pages') }}" class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-green">
                        ▤
                    </span>

                    <span class="admin-quick-text">
                        <strong>مدیریت صفحات</strong>
                        <small>مشاهده صفحات سایت</small>
                    </span>

                    <span class="admin-quick-arrow" aria-hidden="true">←</span>
                </a>

                <a href="{{ url('/admin/posts') }}" class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-blue">
                        ▧
                    </span>

                    <span class="admin-quick-text">
                        <strong>مدیریت مطالب</strong>
                        <small>مشاهده اخبار و مطالب</small>
                    </span>

                    <span class="admin-quick-arrow" aria-hidden="true">←</span>
                </a>

                <a href="{{ url('/admin/categories') }}" class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-orange">
                        ▦
                    </span>

                    <span class="admin-quick-text">
                        <strong>دسته‌بندی‌ها</strong>
                        <small>مشاهده دسته‌بندی‌های محتوا</small>
                    </span>

                    <span class="admin-quick-arrow" aria-hidden="true">←</span>
                </a>

                <a href="{{ url('/admin/media') }}" class="admin-quick-link">
                    <span class="admin-quick-icon admin-quick-icon-red">
                        ▣
                    </span>

                    <span class="admin-quick-text">
                        <strong>کتابخانه رسانه</strong>
                        <small>مشاهده فایل‌های عمومی</small>
                    </span>

                    <span class="admin-quick-arrow" aria-hidden="true">←</span>
                </a>

            </div>
        </section>

    </div>

    {{-- Recent activity --}}
    <section class="admin-panel admin-activity-panel">
        <div class="admin-panel-header">
            <div>
                <h2>فعالیت‌های اخیر</h2>
                <p>رویدادهای نمونه در بخش مدیریت محتوا</p>
            </div>

            <span class="admin-demo-label">داده آزمایشی</span>
        </div>

        <div class="admin-activity-list">

            <div class="admin-activity-item">
                <span class="admin-activity-marker admin-activity-marker-green"></span>

                <div class="admin-activity-content">
                    <strong>یک صفحه محتوایی منتشر شد</strong>
                    <small>رویداد نمایشی</small>
                </div>

                <time>امروز</time>
            </div>

            <div class="admin-activity-item">
                <span class="admin-activity-marker admin-activity-marker-blue"></span>

                <div class="admin-activity-content">
                    <strong>اطلاعات یک مطلب ویرایش شد</strong>
                    <small>رویداد نمایشی</small>
                </div>

                <time>دیروز</time>
            </div>

            <div class="admin-activity-item">
                <span class="admin-activity-marker admin-activity-marker-orange"></span>

                <div class="admin-activity-content">
                    <strong>یک دسته‌بندی به فهرست نمونه اضافه شد</strong>
                    <small>رویداد نمایشی</small>
                </div>

                <time>۲ روز قبل</time>
            </div>

        </div>
    </section>

    {{-- Demo notice --}}
    <div class="admin-demo-notice">
        <span class="admin-demo-notice-icon" aria-hidden="true">i</span>

        <p>
            <strong>نسخه نمایشی:</strong>
            آمار، مطالب و فعالیت‌های این داشبورد ساختگی هستند و به پایگاه داده
            متصل نیستند. هیچ عملیات ثبت، ویرایش یا حذف واقعی انجام نمی‌شود.
        </p>
    </div>

@endsection
