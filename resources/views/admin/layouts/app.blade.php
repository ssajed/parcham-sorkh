<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#123d32">
    <meta name="description" content="پنل مدیریت سامانه پرچم سرخ">

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
        <div
            class="admin-sidebar-overlay"
            data-sidebar-overlay
        ></div>

        {{-- Sidebar --}}
        <aside
            class="admin-sidebar"
            id="admin-sidebar"
            aria-label="منوی اصلی مدیریت"
        >

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
                    میز کار
                </div>

                <nav
                    class="admin-navigation"
                    aria-label="منوی مدیریت"
                >

                    {{-- Dashboard --}}
                    <a
                        href="{{ url('/admin') }}"
                        class="admin-nav-link {{ request()->is('admin') ? 'is-active' : '' }}"
                        @if(request()->is('admin')) aria-current="page" @endif
                    >
                        <span class="admin-nav-icon" aria-hidden="true">
                            ◫
                        </span>
                        <span>داشبورد</span>
                    </a>

                    <div class="admin-sidebar-caption">
                        مدیریت کاربران
                    </div>

                    {{-- Users --}}
                    <a
                        href="{{ url('/admin/users') }}"
                        class="admin-nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'is-active' : '' }}"
                        @if(request()->is('admin/users') || request()->is('admin/users/*')) aria-current="page" @endif
                    >
                        <span class="admin-nav-icon" aria-hidden="true">
                            ♙
                        </span>
                        <span>همه کاربران</span>
                    </a>

                    {{-- Pending accounts --}}
                    <a
                        href="{{ url('/admin/accounts/pending') }}"
                        class="admin-nav-link {{ request()->is('admin/accounts/pending*') ? 'is-active' : '' }}"
                        @if(request()->is('admin/accounts/pending*')) aria-current="page" @endif
                    >
                        <span class="admin-nav-icon" aria-hidden="true">
                            ◷
                        </span>
                        <span>بررسی و تأیید حساب‌ها</span>
                    </a>

                    {{-- Companion seekers --}}
                    <a
                        href="{{ url('/admin/users?role=companion_seeker') }}"
                        class="admin-nav-link {{ request()->fullUrlIs(url('/admin/users') . '?role=companion_seeker') ? 'is-active' : '' }}"
                    >
                        <span class="admin-nav-icon" aria-hidden="true">
                            ◉
                        </span>
                        <span>همراه‌جویان</span>
                    </a>

                    {{-- Companion helpers --}}
                    <a
                        href="{{ url('/admin/users?role=companion_helper') }}"
                        class="admin-nav-link {{ request()->fullUrlIs(url('/admin/users') . '?role=companion_helper') ? 'is-active' : '' }}"
                    >
                        <span class="admin-nav-icon" aria-hidden="true">
                            ◈
                        </span>
                        <span>همراه‌یاران</span>
                    </a>

                    <div class="admin-sidebar-caption">
                        مدیریت دسترسی‌ها
                    </div>

                    {{-- Administrators --}}
                    <a
                        href="{{ url('/admin/administrators') }}"
                        class="admin-nav-link {{ request()->is('admin/administrators*') ? 'is-active' : '' }}"
                        @if(request()->is('admin/administrators*')) aria-current="page" @endif
                    >
                        <span class="admin-nav-icon" aria-hidden="true">
                            ♟
                        </span>
                        <span>مدیران سامانه</span>
                    </a>

                    {{-- Roles --}}
                    <a
                        href="{{ url('/admin/roles') }}"
                        class="admin-nav-link {{ request()->is('admin/roles*') ? 'is-active' : '' }}"
                        @if(request()->is('admin/roles*')) aria-current="page" @endif
                    >
                        <span class="admin-nav-icon" aria-hidden="true">
                            ⚙
                        </span>
                        <span>نقش‌ها و دسترسی‌ها</span>
                    </a>

                </nav>

            </div>

            {{-- Sidebar footer --}}
            <div class="admin-sidebar-footer">

                <div class="admin-sidebar-footer-mark">
                    <span class="admin-status-dot"></span>
                    سامانه پرچم سرخ
                </div>

                <small>
                    نسخه ۱.۰
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
                            <span class="admin-breadcrumb-separator">
                                /
                            </span>

                            <strong>
                                @yield('breadcrumb')
                            </strong>
                        @endif

                    </div>

                </div>

                <div class="admin-topbar-end">

                    <div
                        class="admin-topbar-date"
                        data-tehran-date
                    >
                        سامانه مدیریت پرچم سرخ
                    </div>

                    <div class="admin-user">

                        <div
                            class="admin-user-avatar"
                            aria-hidden="true"
                        >
                            پ
                        </div>

                        <div class="admin-user-info">
                            <strong>بخش مدیریت</strong>
                            <small>سامانه پرچم سرخ</small>
                        </div>

                    </div>

                </div>

            </header>

            {{-- Page content --}}
            <main
                class="admin-content"
                id="admin-main-content"
            >

                @if (session('success'))
                    <div
                        class="admin-alert admin-alert-success"
                        role="status"
                    >
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="admin-alert admin-alert-error"
                        role="alert"
                    >
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="admin-alert admin-alert-error"
                        role="alert"
                    >
                        <strong>لطفاً موارد زیر را بررسی کنید:</strong>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
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
