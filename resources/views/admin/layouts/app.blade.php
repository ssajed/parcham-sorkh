
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#123d32">
    <meta name="description" content="پنل مدیریت پرچم سرخ">

    <title>
        @hasSection('title')
            @yield('title') |
        @endif
        پنل مدیریت پرچم سرخ
    </title>

    @vite([
        'resources/css/admin.css',
        'resources/js/admin.js',
    ])
</head>

<body class="admin-body">

    <div class="admin-shell">

        {{-- Mobile sidebar overlay --}}
        <div class="admin-sidebar-overlay" data-sidebar-overlay></div>

        {{-- Sidebar --}}
        <aside class="admin-sidebar" id="admin-sidebar">

            <div class="admin-sidebar-header">

                <a href="{{ url('/admin') }}" class="admin-brand">
                    <span class="admin-brand-mark" aria-hidden="true">
                        پ
                    </span>

                    <span class="admin-brand-text">
                        <strong>پرچم سرخ</strong>
                        <small>سامانه مدیریت</small>
                    </span>
                </a>

                <button
                    type="button"
                    class="admin-sidebar-close"
                    data-sidebar-close
                    aria-label="بستن منو"
                >
                    &times;
                </button>

            </div>

            <div class="admin-sidebar-content">

                <div class="admin-sidebar-caption">
                    منوی اصلی
                </div>

                <nav class="admin-navigation" aria-label="منوی مدیریت">

                    <a
                        href="{{ url('/admin') }}"
                        class="admin-nav-link {{ request()->is('admin') ? 'is-active' : '' }}"
                    >
                        <span class="admin-nav-icon" aria-hidden="true">⌂</span>
                        <span>داشبورد</span>
                    </a>

                    <div class="admin-sidebar-caption">
                        مدیریت محتوا
                    </div>

                    <a
                        href="{{ url('/admin/pages') }}"
                        class="admin-nav-link {{ request()->is('admin/pages*') ? 'is-active' : '' }}"
                    >
                        <span class="admin-nav-icon" aria-hidden="true">▤</span>
                        <span>صفحات</span>
                    </a>

                    <a
                        href="{{ url('/admin/posts') }}"
                        class="admin-nav-link {{ request()->is('admin/posts*') ? 'is-active' : '' }}"
                    >
                        <span class="admin-nav-icon" aria-hidden="true">▧</span>
                        <span>اخبار و مطالب</span>
                    </a>

                    <a
                        href="{{ url('/admin/categories') }}"
                        class="admin-nav-link {{ request()->is('admin/categories*') ? 'is-active' : '' }}"
                    >
                        <span class="admin-nav-icon" aria-hidden="true">▦</span>
                        <span>دسته‌بندی‌ها</span>
                    </a>

                    <a
                        href="{{ url('/admin/media') }}"
                        class="admin-nav-link {{ request()->is('admin/media*') ? 'is-active' : '' }}"
                    >
                        <span class="admin-nav-icon" aria-hidden="true">▣</span>
                        <span>رسانه‌ها</span>
                    </a>

                    <div class="admin-sidebar-caption">
                        مدیریت سامانه
                    </div>

                    <a
                        href="{{ url('/admin/administrators') }}"
                        class="admin-nav-link {{ request()->is('admin/administrators*') ? 'is-active' : '' }}"
                    >
                        <span class="admin-nav-icon" aria-hidden="true">♙</span>
                        <span>مدیران</span>
                    </a>

                    <a
                        href="{{ url('/admin/roles') }}"
                        class="admin-nav-link {{ request()->is('admin/roles*') ? 'is-active' : '' }}"
                    >
                        <span class="admin-nav-icon" aria-hidden="true">⚙</span>
                        <span>نقش‌ها و دسترسی‌ها</span>
                    </a>

                    <a
                        href="{{ url('/admin/settings') }}"
                        class="admin-nav-link {{ request()->is('admin/settings*') ? 'is-active' : '' }}"
                    >
                        <span class="admin-nav-icon" aria-hidden="true">⚙</span>
                        <span>تنظیمات عمومی</span>
                    </a>

                </nav>

            </div>

            <div class="admin-sidebar-footer">

                <div class="admin-sidebar-footer-mark">
                    <span class="admin-status-dot"></span>
                    محیط نمایشی پنل
                </div>

                <small>
                    پرچم سرخ
                    <span dir="ltr">v1.0</span>
                </small>

            </div>

        </aside>

        {{-- Main area --}}
        <div class="admin-main">

            {{-- Topbar --}}
            <header class="admin-topbar">

                <div class="admin-topbar-start">

                    <button
                        type="button"
                        class="admin-menu-toggle"
                        data-sidebar-toggle
                        aria-label="باز کردن منو"
                        aria-controls="admin-sidebar"
                        aria-expanded="false"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <div class="admin-breadcrumb">
                        <span>پنل مدیریت</span>

                        @hasSection('breadcrumb')
                            <span class="admin-breadcrumb-separator">/</span>
                            <strong>@yield('breadcrumb')</strong>
                        @endif
                    </div>

                </div>

                <div class="admin-topbar-end">

                    <div class="admin-topbar-date" data-tehran-date>
                        پنل مدیریت پرچم سرخ
                    </div>

                    <div class="admin-user">

                        <div class="admin-user-avatar" aria-hidden="true">
                            م
                        </div>

                        <div class="admin-user-info">
                            <strong>مدیر سامانه</strong>
                            <small>مدیر کل</small>
                        </div>

                    </div>

                </div>

            </header>

            {{-- Page content --}}
            <main class="admin-content" id="admin-main-content">

                @if (session('success'))
                    <div class="admin-alert admin-alert-success" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="admin-alert admin-alert-error" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </main>

            {{-- Footer --}}
            <footer class="admin-footer">
                <span>
                    سامانه مدیریت پرچم سرخ
                </span>

                <span>
                    تمامی حقوق محفوظ است.
                </span>
            </footer>

        </div>

    </div>

</body>
</html>
