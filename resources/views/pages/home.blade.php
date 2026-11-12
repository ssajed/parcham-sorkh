@extends('layouts.app')

@section('title', 'پرچم سرخ؛ با هم برای همراهی')

@section('meta_description', 'پرچم سرخ؛ بستری برای همراهی داوطلبانه و انسانی، پیوند همراه‌جویان و همراه‌یاران و کمک به یکدیگر.')

@section('content')

<div class="site-wrapper">

    {{-- Header --}}
    <header class="site-header">
        <div class="container header-container">

            <a href="#home" class="brand" aria-label="پرچم سرخ، صفحه اصلی">
                <span class="brand-symbol" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>

                <span class="brand-name">پرچم سرخ</span>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="main-nav" aria-label="منوی اصلی">
                <a href="#about">درباره ما</a>
                <a href="#roles">همراه‌جو و همراه‌یار</a>
                <a href="#how-it-works">نحوه کار</a>
                <a href="#principles">اصول ما</a>
            </nav>

            <div class="header-action header-account-actions">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->can_be_companion_seeker ? route('companion-seeker.profile.seeker') : (auth()->user()->can_be_companion_helper ? route('companion-seeker.profile.helper') : route('companion-seeker.dashboard'))) }}" class="header-user-name" aria-label="مشاهده پروفایل {{ auth()->user()->name }}">{{ auth()->user()->name }}</a>

                    <form action="{{ auth()->user()->isAdmin() ? route('admin.logout') : route('companion-seeker.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="button button-outline">خروج</button>
                    </form>
                @else
                    <a href="{{ route('companion-seeker.login') }}" class="button button-outline">ورود</a>
                    <a href="{{ route('register') }}" class="button button-primary">ثبت‌نام</a>
                @endauth
            </div>

            {{-- Mobile Navigation --}}
            <details class="mobile-menu">
                <summary aria-label="باز کردن منوی موبایل">
                    <span class="menu-icon" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <span>منو</span>
                </summary>

                <nav class="mobile-nav" aria-label="منوی موبایل">
                    <a href="#home">صفحه اصلی</a>
                    <a href="#about">درباره ما</a>
                    <a href="#roles">همراه‌جو و همراه‌یار</a>
                    <a href="#how-it-works">نحوه کار</a>
                    <a href="#principles">اصول ما</a>
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->can_be_companion_seeker ? route('companion-seeker.profile.seeker') : (auth()->user()->can_be_companion_helper ? route('companion-seeker.profile.helper') : route('companion-seeker.dashboard'))) }}" class="mobile-user-name" aria-label="مشاهده پروفایل {{ auth()->user()->name }}">{{ auth()->user()->name }}</a>
                        <form action="{{ auth()->user()->isAdmin() ? route('admin.logout') : route('companion-seeker.logout') }}" method="POST">
                            @csrf
                            <button type="submit">خروج</button>
                        </form>
                    @else
                        <a href="{{ route('companion-seeker.login') }}">ورود همراهان</a>
                        <a href="{{ route('register') }}">ثبت‌نام</a>
                        <a href="{{ route('admin.login') }}">ورود مدیریت</a>
                    @endauth
                </nav>
            </details>

        </div>
    </header>


    <main id="main-content">

        {{-- Hero --}}
        <section class="hero-section" id="home">
            <div class="container hero-grid">

                <div class="hero-content">

                    <span class="eyebrow">
                        <span class="eyebrow-mark"></span>
                        همراهی داوطلبانه؛ با هم، برای هم
                    </span>

                    <h1>
                        پرچم سرخ
                        <span>با پرچم سرخ به خیابان بیایید</span>
                    </h1>

                    <p class="hero-description">
                        پرچم سرخ بستری برای همراهی داوطلبانه و انسانی است؛
                        جایی برای پیوند افرادی که به همراهی نیاز دارند
                        با کسانی که می‌خواهند وقت و توان خود را
                        بی‌چشم‌داشت در اختیار دیگران قرار دهند.
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('companion-seeker.requests.create') }}" class="button button-primary">ثبت درخواست همراهی</a>
                        <a href="{{ route('register') }}" class="button button-light">ثبت‌نام در پرچم سرخ</a>
                    </div>

                    <div class="hero-note">
                        <span class="note-symbol" aria-hidden="true">✳</span>
                        <span>
                            همراهی با رضایت، اعتماد و احترام متقابل شکل می‌گیرد.
                        </span>
                    </div>

                </div>


                {{-- Human-centered illustration --}}
                <div class="hero-art" aria-hidden="true">

                    <div class="art-background-circle"></div>
                    <div class="art-background-ring"></div>

                    <svg class="people-illustration"
                         viewBox="0 0 620 510"
                         xmlns="http://www.w3.org/2000/svg">

                        <defs>
                            <linearGradient id="greenShape" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#397A61"/>
                                <stop offset="100%" stop-color="#123C32"/>
                            </linearGradient>

                            <linearGradient id="redShape" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#E28A80"/>
                                <stop offset="100%" stop-color="#BD514D"/>
                            </linearGradient>
                        </defs>

                        {{-- Abstract city architecture --}}
                        <path d="M65 350 V205 Q65 190 80 190 H160 V350"
                              fill="#D9E7DC"/>

                        <path d="M95 190 V145 Q95 130 110 130 H165 Q180 130 180 145 V350"
                              fill="#C6DCCB"/>

                        <path d="M430 350 V160 Q430 145 445 145 H515 Q530 145 530 160 V350"
                              fill="#D9E7DC"/>

                        <path d="M465 145 V105 Q465 90 480 90 H520 Q535 90 535 105 V350"
                              fill="#C6DCCB"/>

                        <g fill="#F8FBF8">
                            <rect x="92" y="218" width="18" height="27" rx="4"/>
                            <rect x="128" y="218" width="18" height="27" rx="4"/>
                            <rect x="92" y="264" width="18" height="27" rx="4"/>
                            <rect x="128" y="264" width="18" height="27" rx="4"/>

                            <rect x="454" y="185" width="18" height="27" rx="4"/>
                            <rect x="490" y="185" width="18" height="27" rx="4"/>
                            <rect x="454" y="231" width="18" height="27" rx="4"/>
                            <rect x="490" y="231" width="18" height="27" rx="4"/>
                        </g>

                        {{-- Ground --}}
                        <path d="M30 365 Q180 340 300 365 Q450 390 590 350"
                              stroke="#B7D0BE"
                              stroke-width="4"
                              fill="none"
                              stroke-linecap="round"/>

                        {{-- Connection lines --}}
                        <path d="M170 280 Q310 150 450 280"
                              stroke="#D16A64"
                              stroke-width="3"
                              stroke-dasharray="7 11"
                              fill="none"
                              stroke-linecap="round"/>

                        <circle cx="170" cy="280" r="7" fill="#D16A64"/>
                        <circle cx="450" cy="280" r="7" fill="#D16A64"/>

                        {{-- Left person --}}
                        <g transform="translate(120 175)">
                            <circle cx="75" cy="52" r="37" fill="#DDAF91"/>

                            <path d="M39 50 Q37 5 78 8 Q115 10 112 52
                                     Q96 28 75 29 Q56 28 39 50"
                                  fill="#263D34"/>

                            <path d="M28 180 Q30 100 75 100
                                     Q120 100 125 180 Z"
                                  fill="url(#greenShape)"/>

                            <path d="M43 115 Q12 135 7 176"
                                  stroke="#DDAF91"
                                  stroke-width="17"
                                  stroke-linecap="round"
                                  fill="none"/>

                            <path d="M108 115 Q133 128 148 151"
                                  stroke="#DDAF91"
                                  stroke-width="17"
                                  stroke-linecap="round"
                                  fill="none"/>

                            <path d="M42 180 L36 230"
                                  stroke="#30483D"
                                  stroke-width="22"
                                  stroke-linecap="round"/>

                            <path d="M108 180 L115 230"
                                  stroke="#30483D"
                                  stroke-width="22"
                                  stroke-linecap="round"/>
                        </g>

                        {{-- Right person --}}
                        <g transform="translate(340 175)">
                            <circle cx="75" cy="52" r="37" fill="#C98F70"/>

                            <path d="M38 53 Q35 8 77 8 Q115 8 113 52
                                     Q98 32 76 30 Q55 30 38 53"
                                  fill="#263D34"/>

                            <path d="M28 180 Q30 100 75 100
                                     Q120 100 125 180 Z"
                                  fill="url(#redShape)"/>

                            <path d="M43 115 Q20 130 8 153"
                                  stroke="#C98F70"
                                  stroke-width="17"
                                  stroke-linecap="round"
                                  fill="none"/>

                            <path d="M108 115 Q137 133 145 174"
                                  stroke="#C98F70"
                                  stroke-width="17"
                                  stroke-linecap="round"
                                  fill="none"/>

                            <path d="M42 180 L36 230"
                                  stroke="#30483D"
                                  stroke-width="22"
                                  stroke-linecap="round"/>

                            <path d="M108 180 L115 230"
                                  stroke="#30483D"
                                  stroke-width="22"
                                  stroke-linecap="round"/>
                        </g>

                        {{-- Shared hands --}}
                        <path d="M268 310 Q290 285 312 310"
                              stroke="#DDAF91"
                              stroke-width="16"
                              stroke-linecap="round"
                              fill="none"/>

                        <path d="M305 310 Q325 285 345 310"
                              stroke="#C98F70"
                              stroke-width="16"
                              stroke-linecap="round"
                              fill="none"/>

                        {{-- Red flag --}}
                        <g transform="translate(270 55)">
                            <path d="M0 0 V95"
                                  stroke="#456B55"
                                  stroke-width="7"
                                  stroke-linecap="round"/>

                            <path d="M4 5 Q50 -8 92 12
                                     Q72 36 92 58
                                     Q45 45 4 64 Z"
                                  fill="#C85D59"/>

                            <path d="M24 18 Q45 12 64 20"
                                  stroke="#F8E9E7"
                                  stroke-width="4"
                                  stroke-linecap="round"
                                  fill="none"/>
                        </g>

                        {{-- Decorative shapes --}}
                        <circle cx="95" cy="95" r="7" fill="#C85D59"/>
                        <circle cx="530" cy="75" r="8" fill="#C85D59"/>
                        <circle cx="540" cy="315" r="5" fill="#8EAF99"/>
                        <path d="M85 390 l10 -10 l10 10 l-10 10 Z" fill="#D16A64"/>
                        <path d="M520 400 l9 -9 l9 9 l-9 9 Z" fill="#8EAF99"/>

                    </svg>

                    <div class="art-caption art-caption-top">
                        <span class="caption-icon">✳</span>
                        <span>
                            <strong>حمل‌ونقل داوطلبانه</strong>
                            <small>بدون دریافت هزینه</small>
                        </span>
                    </div>

                    <div class="art-caption art-caption-bottom">
                        <span class="caption-icon caption-icon-red">♥</span>
                        <span>
                            <strong>همیاری انسانی</strong>
                            <small>برای کمک به یکدیگر</small>
                        </span>
                    </div>

                </div>

            </div>
        </section>


        {{-- About --}}
        <section class="about-section section-padding" id="about">
            <div class="container about-container">

                <div class="section-heading">
                    <span class="section-label">پرچم سرخ چیست؟</span>

                    <h2>
                        سامانه‌ای برای
                        <span>همراهی داوطلبانه</span>
                    </h2>
                </div>

                <p class="about-description">
                    پرچم سرخ با هدف پیوند افرادی شکل گرفته است که به همراهی
                    نیاز دارند با کسانی که داوطلبانه برای همراهی اعلام آمادگی
                    می‌کنند. هدف، ایجاد بستری امن، شفاف و غیرتجاری برای
                    مشارکت انسانی، مسئولیت‌پذیری و کمک متقابل است.
                </p>

                <div class="about-highlight">
                    <span class="highlight-mark">✳</span>
                    <p>
                        پرچم سرخ بستری غیرتجاری و داوطلبانه است؛
                        همراهی با رضایت طرفین انجام می‌شود و هیچ پرداختی
                        شرط استفاده از این بستر نیست.
                    </p>
                </div>

            </div>
        </section>


        {{-- Roles --}}
        <section class="roles-section section-padding" id="roles">
            <div class="container">

                <div class="section-heading">
                    <span class="section-label">دو نقش، یک هدف</span>

                    <h2>
                        در پرچم سرخ
                        <span>چه نقشی دارید؟</span>
                    </h2>

                    <p>
                        چه به همراهی نیاز داشته باشید و چه بخواهید
                        داوطلبانه همراه دیگران باشید، می‌توانید
                        با انتخاب نقش خود به این حرکت بپیوندید.
                    </p>
                </div>


                <div class="roles-grid">

                    <article class="role-card role-seeker">

                        <div class="role-card-top">
                            <span class="role-number">۰۱</span>
                            <span class="role-icon" aria-hidden="true">؟</span>
                        </div>

                        <span class="role-label">درخواست‌کننده همراهی</span>

                        <h3>همراه‌جو</h3>

                        <p>
                            همراه‌جو فردی است که برای همراهی در مسیر
                            به مقصد نیاز دارد و درخواست همراهی خود را
                            در سامانه ثبت می‌کند.
                        </p>

                        <ul class="role-points">
                            <li>ثبت درخواست همراهی</li>
                            <li>مشخص کردن مسیر و زمان موردنیاز</li>
                            <li>بررسی پیشنهادها و انتخاب آزادانه</li>
                        </ul>

                        <a href="{{ route('companion-seeker.requests.create') }}" class="role-action">ثبت درخواست همراهی</a>

                    </article>


                    <article class="role-card role-helper">

                        <div class="role-card-top">
                            <span class="role-number">۰۲</span>
                            <span class="role-icon" aria-hidden="true">✳</span>
                        </div>

                        <span class="role-label">همراه داوطلب</span>

                        <h3>همراه‌یار</h3>

                        <p>
                            همراه‌یار فردی است که با اختیار و تمایل شخصی،
                            برای جابه‌جایی رایگان دیگران اعلام آمادگی
                            می‌کند و در صورت توافق، مسئولیت همراهی
                            و انتقال همراه‌جو را می‌پذیرد.
                        </p>

                        <ul class="role-points">
                            <li>اعلام آمادگی برای همراهی داوطلبانه</li>
                            <li>بررسی درخواست‌های همراهی</li>
                            <li>پذیرش یا نپذیرفتن آزادانه درخواست</li>
                        </ul>

                        <a href="{{ route('register') }}" class="role-action">ثبت‌نام به‌عنوان همراه‌یار</a>

                    </article>

                </div>

                <p class="roles-note">
                    تمام همراهی‌ها رایگان و داوطلبانه هستند.
                    هیچ فردی ملزم به پذیرش درخواست نیست و هر همراهی
                    با توافق و رضایت طرفین انجام می‌شود.
                </p>

            </div>
        </section>


        {{-- How it works --}}
        <section class="process-section section-padding" id="how-it-works">
            <div class="container">

                <div class="section-heading">
                    <span class="section-label">فرآیند همیاری</span>

                    <h2>
                        فرآیند همراهی چگونه
                        <span>انجام می‌شود؟</span>
                    </h2>

                    <p>
                        فرآیند پیش‌بینی‌شده برای ارتباط میان همراه‌جو
                        و همراه‌یار در چند گام ساده خلاصه می‌شود.
                    </p>
                </div>


                <div class="process-grid">

                    <article class="process-card">
                        <span class="process-number">۱</span>

                        <div class="process-icon">＋</div>

                        <h3>ثبت درخواست</h3>

                        <p>
                            همراه‌جو مبدأ، مقصد، زمان و اطلاعات ضروری
                            جابه‌جایی موردنیاز خود را ثبت می‌کند.
                        </p>
                    </article>


                    <article class="process-card">
                        <span class="process-number">۲</span>

                        <div class="process-icon">⌕</div>

                        <h3>بررسی درخواست</h3>

                        <p>
                            همراه‌یاران می‌توانند درخواست‌های موجود
                            را بررسی و برای کمک اعلام آمادگی کنند.
                        </p>
                    </article>


                    <article class="process-card">
                        <span class="process-number">۳</span>

                        <div class="process-icon">↔</div>

                        <h3>هماهنگی دوطرفه</h3>

                        <p>
                            همراه‌جو و همراه‌یار جزئیات لازم را بررسی
                            و درباره زمان و شرایط جابه‌جایی توافق می‌کنند.
                        </p>
                    </article>


                    <article class="process-card">
                        <span class="process-number">۴</span>

                        <div class="process-icon">♥</div>

                        <h3>انجام همراهی</h3>

                        <p>
                            در صورت توافق، همراهی به‌صورت داوطلبانه
                            و داوطلبانه انجام می‌شود.
                        </p>
                    </article>

                </div>

                <p class="section-disclaimer">
                    این فرآیند، طرح مفهومی سامانه است و به معنی فعال بودن
                    قابلیت‌های ثبت درخواست یا هماهنگی در نسخه فعلی نیست.
                </p>

            </div>
        </section>


        {{-- Principles and Privacy --}}
        <section class="principles-section section-padding" id="principles">
            <div class="container principles-grid">

                <div class="principles-content">

                    <span class="section-label">پایه‌های اعتماد</span>

                    <h2>
                        همراهی با
                        <span>احترام و مسئولیت</span>
                    </h2>

                    <p class="principles-intro">
                        همراهی داوطلبانه زمانی ارزشمند است که بر پایه
                        رضایت، مسئولیت‌پذیری، اعتماد و احترام به حریم
                        شخصی افراد شکل بگیرد.
                    </p>

                    <div class="principle-item">
                        <span class="principle-check">✓</span>
                        <div>
                            <h3>رایگان و داوطلبانه</h3>
                            <p>
                                پرداخت هزینه شرط همراهی نیست و مشارکت
                                همراه‌یار بر اساس تمایل شخصی و توافق
                                آگاهانه طرفین انجام می‌شود.
                            </p>
                        </div>
                    </div>

                    <div class="principle-item">
                        <span class="principle-check">✓</span>
                        <div>
                            <h3>حفظ حریم خصوصی</h3>
                            <p>
                                اطلاعات شخصی نباید بدون رضایت افراد
                                در معرض نمایش عمومی قرار بگیرد.
                            </p>
                        </div>
                    </div>

                    <div class="principle-item">
                        <span class="principle-check">✓</span>
                        <div>
                            <h3>حداقل اطلاعات ضروری</h3>
                            <p>
                                اطلاعات باید متناسب با نیاز هماهنگی
                                جابه‌جایی باشد و از جمع‌آوری غیرضروری
                                پرهیز شود.
                            </p>
                        </div>
                    </div>

                    <div class="principle-item">
                        <span class="principle-check">✓</span>
                        <div>
                            <h3>مسئولیت و احترام متقابل</h3>
                            <p>
                                تصمیم‌گیری درباره پذیرش درخواست و انجام
                                همراهی باید با آگاهی، رضایت و احترام
                                طرفین صورت بگیرد.
                            </p>
                        </div>
                    </div>

                </div>


                <div class="principles-art" aria-hidden="true">

                    <div class="principles-art-circle">
                        <div class="principles-shield">
                            <span>✓</span>
                        </div>

                        <span class="principles-orbit orbit-a"></span>
                        <span class="principles-orbit orbit-b"></span>

                        <span class="principles-decoration decoration-a">✳</span>
                        <span class="principles-decoration decoration-b">♥</span>
                    </div>

                    <p>
                        اعتماد، با احترام به انتخاب و مسئولیت‌پذیری آغاز می‌شود.
                    </p>

                </div>

            </div>
        </section>


        {{-- Final CTA --}}
        <section class="final-cta-section">
            <div class="container final-cta-container">

                <span class="final-cta-symbol">✳</span>

                <h2>
                    پرچم سرخ
                    <span>بستر همراهی داوطلبانه</span>
                </h2>

                <p>
                    همراه‌جو باشید یا همراه‌یار؛
                    با مشارکت داوطلبانه می‌توانیم به جابه‌جایی
                    رایگان و کمک به یکدیگر کمک کنیم.
                </p>

                <div class="hero-actions final-actions">
                    <a href="{{ route('register') }}" class="button button-light">ثبت‌نام و پیوستن</a>
                    <a href="{{ route('companion-seeker.login') }}" class="button button-ghost">ورود به حساب</a>
                </div>

            </div>
        </section>

    </main>


    {{-- Footer --}}
    <footer class="site-footer">

        <div class="container footer-main">

            <div class="footer-brand">

                <a href="#home" class="brand footer-logo">
                    <span class="brand-symbol" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>

                    <span class="brand-name">پرچم سرخ</span>
                </a>

                <p>
                    سامانه همراهی داوطلبانه و انسانی؛
                    بستری برای همیاری داوطلبانه و کمک به یکدیگر.
                </p>

            </div>


            <div class="footer-links">
                <h3>دسترسی سریع</h3>

                <a href="#about">درباره پرچم سرخ</a>
                <a href="#roles">همراه‌جو و همراه‌یار</a>
                <a href="#how-it-works">نحوه کار</a>
                <a href="#principles">اصول ما</a>
                <a href="{{ route('register') }}">ثبت‌نام</a>
                <a href="{{ route('companion-seeker.login') }}">ورود همراهان</a>
                <a href="{{ route('admin.login') }}">ورود مدیریت</a>
            </div>


            <div class="footer-description">
                <h3>یک اصل ساده</h3>

                <p>
                    همراهی بدون اجبار و با رضایت است؛
                    مشارکت داوطلبانه، با رضایت و احترام متقابل.
                </p>
            </div>

        </div>


        <div class="footer-bottom">
            <div class="container footer-bottom-content">
                <span>
                    © تمامی حقوق برای پرچم سرخ محفوظ است.
                </span>

                <span>
                    همراهی داوطلبانه؛ همیاری انسانی
                </span>
            </div>
        </div>

    </footer>

</div>

@endsection