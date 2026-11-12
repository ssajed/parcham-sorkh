
@extends('layouts.app')

@section('title', 'ثبت‌نام')

@section('meta_description', 'ثبت‌نام در سامانه همراهی رایگان و بشردوستانه پرچم سرخ.')

@section('content')

<style>
    .register-page {
        direction: rtl;
        font-family: inherit;
    }

    .register-card {
        border: 1px solid #e5eaf0;
        border-radius: 24px;
        background: #fff;
        box-shadow: 0 8px 30px rgba(15, 23, 42, .06);
    }

    .register-input {
        width: 100%;
        min-width: 0;
        border: 1px solid #cbd0d8;
        border-radius: 11px;
        background: #fff;
        padding: 11px 14px;
        color: #1f2937;
        font-size: 14px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .register-input:focus {
        border-color: #166534;
        box-shadow: 0 0 0 3px rgba(22, 101, 52, .10);
    }

    .register-input::placeholder {
        color: #9ca3af;
    }

    .register-label {
        display: block;
        margin-bottom: 7px;
        color: #263244;
        font-size: 13px;
        font-weight: 800;
    }

    .role-card {
        position: relative;
        display: flex;
        min-height: 140px;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border: 1px solid #d9e0e7;
        border-radius: 15px;
        cursor: pointer;
        transition: .2s;
    }

    .role-card.seeker:has(input:checked) {
        border-color: #166534;
        background: #f0f8f2;
    }

    .role-card.helper:has(input:checked) {
        border-color: #dc2626;
        background: #fff3f3;
    }

    .role-card input[type="radio"] {
        position: absolute;
        top: 17px;
        right: 17px;
        width: 19px;
        height: 19px;
        accent-color: #166534;
    }

    .role-icon {
        display: flex;
        width: 75px;
        height: 75px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
    }

    .role-card.seeker .role-icon {
        color: #166534;
        background: #e2f2e6;
    }

    .role-card.helper .role-icon {
        color: #b91c1c;
        background: #ffe8e8;
    }

    .role-chip {
        display: inline-block;
        margin-top: 9px;
        border-radius: 30px;
        padding: 5px 17px;
        color: #fff;
        font-size: 11px;
        font-weight: 800;
    }

    .seeker .role-chip {
        background: #27834b;
    }

    .helper .role-chip {
        background: #d62828;
    }

    .vehicle-panel {
        border: 1px solid #ffe0e0;
        border-radius: 17px;
        background: #fff8f8;
        padding: 17px;
    }

    /* پلاک خودرو */
    .iran-plate {
        direction: ltr;
        display: grid;
        grid-template-columns: 68px minmax(0, 1.25fr) minmax(45px, .55fr) minmax(0, 1fr) 82px;
        align-items: stretch;
        width: 100%;
        max-width: 850px;
        min-height: 94px;
        margin: 0 auto;
        overflow: hidden;
        border: 5px solid #151515;
        border-radius: 17px;
        background: #fff;
        box-shadow:
            0 0 0 2px #a8a8a8,
            inset 0 0 0 2px #555,
            0 5px 10px rgba(0,0,0,.15);
    }

    .plate-flag-side {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        background: #0752a5;
        color: #fff;
        padding: 5px 3px;
    }

    .plate-flag {
        width: 43px;
        height: 27px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,.7);
        background: linear-gradient(
            to bottom,
            #239b56 0 33.33%,
            #fff 33.33% 66.66%,
            #d62929 66.66% 100%
        );
        position: relative;
    }

    .plate-flag::after {
        content: "☼";
        position: absolute;
        top: 1px;
        left: 50%;
        transform: translateX(-50%);
        color: #d62929;
        font-size: 12px;
        line-height: 24px;
    }

    .plate-flag-text {
        font-family: Arial, sans-serif;
        font-size: 13px;
        font-weight: 800;
        line-height: 1;
    }

    .plate-section {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 0;
        padding: 5px;
        border-right: 2px solid #7b7b7b;
        background: #fff;
    }

    .plate-section input,
    .plate-section select {
        width: 100%;
        min-width: 0;
        height: 75%;
        border: 1.5px solid #8796a9;
        border-radius: 10px;
        background: #fff;
        color: #172033;
        text-align: center;
        font-size: clamp(20px, 4vw, 34px);
        font-weight: 900;
        outline: none;
        padding: 2px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,.06);
    }

    .plate-section input:focus,
    .plate-section select:focus {
        border-color: #176b3b;
        box-shadow: 0 0 0 2px rgba(22,101,52,.15);
    }

    .plate-section input::placeholder {
        color: #a4a8af;
        opacity: 1;
    }

    .plate-letter select {
        appearance: none;
        -webkit-appearance: none;
        padding: 0;
        cursor: pointer;
    }

    .plate-iran-side {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1px;
        background: #0752a5;
        color: white;
        font-weight: 900;
        padding: 4px;
        border-left: 2px solid #082f61;
    }

    .plate-iran-side span:first-child {
        font-size: 20px;
    }

    .plate-iran-side input {
        width: 100%;
        min-width: 0;
        border: 0;
        border-radius: 5px;
        background: transparent;
        color: white;
        text-align: center;
        font-size: 23px;
        font-weight: 900;
        outline: none;
        padding: 0;
    }

    .plate-iran-side input::placeholder {
        color: rgba(255,255,255,.7);
    }

    .plate-hint {
        margin-top: 12px;
        border-radius: 12px;
        background: #e9edf4;
        padding: 11px;
        text-align: center;
        color: #334155;
        font-size: 12px;
        line-height: 1.8;
    }

    .document-guide {
        border: 1px solid #ffc65e;
        border-radius: 13px;
        background: #fff7e7;
        color: #70420a;
        padding: 14px 17px;
        font-size: 12px;
        line-height: 2;
    }

    @media (max-width: 480px) {
        .register-card {
            padding: 16px !important;
            border-radius: 18px;
        }

        .role-card {
            min-height: 112px;
            gap: 10px;
            padding: 12px;
        }

        .role-icon {
            width: 55px;
            height: 55px;
        }

        .role-icon svg {
            width: 38px;
            height: 38px;
        }

        .role-card input[type="radio"] {
            top: 12px;
            right: 12px;
        }

        .iran-plate {
            grid-template-columns: 42px minmax(0, 1.2fr) minmax(34px, .5fr) minmax(0, 1fr) 57px;
            min-height: 66px;
            border-width: 3px;
            border-radius: 11px;
        }

        .plate-flag {
            width: 30px;
            height: 19px;
        }

        .plate-flag-text {
            font-size: 9px;
        }

        .plate-section {
            padding: 3px;
        }

        .plate-section input,
        .plate-section select {
            font-size: 19px;
            border-radius: 6px;
        }

        .plate-iran-side span:first-child {
            font-size: 14px;
        }

        .plate-iran-side input {
            font-size: 17px;
        }
    }
</style>

<main id="main-content" class="register-page min-h-screen bg-slate-50 px-3 py-8 sm:px-5 sm:py-12">

    <div class="mx-auto max-w-2xl">

        {{-- سربرگ --}}
        <header class="mb-7 text-center">

            <a href="{{ route('home') }}"
               class="inline-flex items-center justify-center gap-3 text-3xl font-black text-green-900">

                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-600 text-3xl text-white shadow-sm">
                    پ
                </span>

                پرچم سرخ
            </a>

            <h1 class="mt-5 text-2xl font-black text-gray-900 sm:text-3xl">
                ایجاد حساب کاربری
            </h1>

            <p class="mt-3 text-sm leading-7 text-gray-600">
                برای پیوستن به جمع همراه‌جویان و همراه‌یاران، اطلاعات خود را وارد کنید.
            </p>

        </header>

        <div class="register-card p-5 sm:p-8">

            {{-- پیام موفقیت --}}
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm leading-7 text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- خطاهای اعتبارسنجی --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="mb-2 font-bold">لطفاً خطاهای زیر را اصلاح کنید:</p>

                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
                @csrf

                {{-- انتخاب نوع حساب --}}
                <fieldset>
                    <legend class="mb-3 text-sm font-bold text-gray-800">
                        نوع حساب کاربری
                    </legend>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        {{-- همراه‌جو --}}
                        <label class="role-card seeker">

                            <input
                                type="radio"
                                name="role"
                                value="companion_seeker"
                                @checked(old('role', 'companion_seeker') === 'companion_seeker')
                            >

                            <span class="role-icon">
                                <svg viewBox="0 0 64 64" class="h-12 w-12" fill="currentColor" aria-hidden="true">
                                    <circle cx="32" cy="17" r="11"/>
                                    <path d="M12 56c0-11 9-20 20-20s20 9 20 20v2H12z"/>
                                </svg>
                            </span>

                            <span class="min-w-0">
                                <span class="block text-lg font-black text-green-900">
                                    همراه‌جو
                                </span>

                                <span class="mt-1 block text-xs leading-6 text-gray-600">
                                    برای درخواست همراهی و دریافت کمک داوطلبانه
                                </span>

                                <span class="role-chip">درخواست همراهی</span>
                            </span>

                        </label>

                        {{-- همراه‌یار --}}
                        <label class="role-card helper">

                            <input
                                type="radio"
                                name="role"
                                value="companion_helper"
                                @checked(old('role') === 'companion_helper')
                            >

                            <span class="role-icon">
                                <svg viewBox="0 0 64 64" class="h-12 w-12" fill="none" aria-hidden="true">
                                    <path
                                        fill="currentColor"
                                        d="M60 28c0-8.3-5.016-24-24-24h-8C9.016 4 4 19.7 4 28c-2.211 0-4 1.789-4 4v16c0 2.211 1.789 4 4 4h4v4c0 2.211 1.789 4 4 4h4c2.211 0 4-1.789 4-4v-4h24v4c0 2.211 1.789 4 4 4h4c2.211 0 4-1.789 4-4v-4h4c2.211 0 4-1.789 4-4V32c0-2.211-1.789-4-4-4ZM16 44c-2.211 0-4-1.789-4-4s1.789-4 4-4 4 1.789 4 4-1.789 4-4 4ZM12 28c0-.652.184-16 16-16h8c15.41 0 15.984 14.379 16 16H12Zm36 16c-2.211 0-4-1.789-4-4s1.789-4 4-4 4 1.789 4 4-1.789 4-4 4Z"
                                    />
                                </svg>
                            </span>

                            <span class="min-w-0">
                                <span class="block text-lg font-black text-red-900">
                                    همراه‌یار
                                </span>

                                <span class="mt-1 block text-xs leading-6 text-gray-600">
                                    برای ارائه همراهی و کمک داوطلبانه به دیگران
                                </span>

                                <span class="role-chip">ارائه همراهی</span>
                            </span>

                        </label>

                    </div>

                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- اطلاعات خودرو --}}
                <section
                    id="vehicle-fields"
                    class="vehicle-panel space-y-5"
                    @if (old('role', 'companion_seeker') !== 'companion_helper') hidden @endif
                >

                    <div class="flex items-center justify-between rounded-xl bg-red-100/70 px-4 py-3">
                        <div>
                            <h2 class="font-black text-gray-900">
                                اطلاعات وسیله نقلیه
                            </h2>

                            <p class="mt-1 text-xs text-gray-600">
                                تکمیل این اطلاعات برای ثبت‌نام همراه‌یار الزامی است.
                            </p>
                        </div>

                        <span class="text-2xl" aria-hidden="true">🚘</span>
                    </div>

                    {{-- ظرفیت مجاز خودرو --}}
                    <div>
                        <label for="vehicle_capacity" class="register-label">ظرفیت مجاز همراهی (۱ تا ۴ نفر)</label>
                        <select
                            id="vehicle_capacity"
                            name="vehicle_capacity"
                            class="register-input"
                            @required(old('role', 'companion_seeker') === 'companion_helper')
                            @disabled(old('role', 'companion_seeker') !== 'companion_helper')
                        >
                            <option value="">ظرفیت را انتخاب کنید</option>
                            @foreach ([1, 2, 3, 4] as $capacity)
                                <option value="{{ $capacity }}" @selected((string) old('vehicle_capacity') === (string) $capacity)>{{ $capacity }} نفر</option>
                            @endforeach
                        </select>
                        @error('vehicle_capacity')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- مدل خودرو --}}
                    <div>
                        <label for="vehicle_model" class="register-label">
                            مدل خودرو
                        </label>

                        <input
                            id="vehicle_model"
                            name="vehicle_model"
                            type="text"
                            value="{{ old('vehicle_model') }}"
                            maxlength="100"
                            placeholder="مثلاً پژو ۲۰۶ سفید"
                            autocomplete="off"
                            @required(old('role', 'companion_seeker') === 'companion_helper')
                            @disabled(old('role', 'companion_seeker') !== 'companion_helper')
                            class="register-input"
                        >

                        @error('vehicle_model')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- پلاک واقعی با ورودی روی پلاک --}}
                    <div>

                        <label class="register-label">
                            شماره پلاک خودرو
                        </label>

                        <div class="iran-plate" aria-label="پلاک خودرو">

                            {{-- بخش پرچم --}}
                            <div class="plate-flag-side">
                                <div class="plate-flag" aria-label="پرچم ایران"></div>

                                <span class="plate-flag-text">I.R.</span>
                                <span class="plate-flag-text">IRAN</span>
                            </div>

                            {{-- سه رقم سمت چپ حرف --}}
                            <div class="plate-section">
                                <input
                                    id="vehicle_plate_three"
                                    name="vehicle_plate_three"
                                    type="text"
                                    value="{{ old('vehicle_plate_three') }}"
                                    inputmode="numeric"
                                    maxlength="3"
                                    autocomplete="off"
                                    dir="ltr"
                                    placeholder="۳۴۵"
                                    aria-label="سه رقم پلاک"
                                    @required(old('role', 'companion_seeker') === 'companion_helper')
                                    @disabled(old('role', 'companion_seeker') !== 'companion_helper')
                                >
                            </div>

                            {{-- حرف پلاک --}}
                            <div class="plate-section plate-letter">
                                <select
                                    id="vehicle_plate_letter"
                                    name="vehicle_plate_letter"
                                    aria-label="حرف پلاک"
                                    @required(old('role', 'companion_seeker') === 'companion_helper')
                                    @disabled(old('role', 'companion_seeker') !== 'companion_helper')
                                >
                                    <option value="">حرف</option>

                                    @foreach ([
                                        'ب', 'ج', 'د', 'س', 'ص', 'ط', 'ع',
                                        'ق', 'ل', 'م', 'ن', 'و', 'ه', 'ی',
                                    ] as $letter)
                                        <option
                                            value="{{ $letter }}"
                                            @selected(old('vehicle_plate_letter') === $letter)
                                        >
                                            {{ $letter }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- دو رقم انتهایی --}}
                            <div class="plate-section">
                                <input
                                    id="vehicle_plate_last"
                                    name="vehicle_plate_last"
                                    type="text"
                                    value="{{ old('vehicle_plate_last') }}"
                                    inputmode="numeric"
                                    maxlength="2"
                                    autocomplete="off"
                                    dir="ltr"
                                    placeholder="۶۷"
                                    aria-label="دو رقم انتهایی پلاک"
                                    @required(old('role', 'companion_seeker') === 'companion_helper')
                                    @disabled(old('role', 'companion_seeker') !== 'companion_helper')
                                >
                            </div>

                            {{-- بخش آبی ایران و دو رقم --}}
                            <div class="plate-iran-side">
                                <span>ایران</span>

                                <input
                                    id="vehicle_plate_region"
                                    name="vehicle_plate_region"
                                    type="text"
                                    value="{{ old('vehicle_plate_region') }}"
                                    inputmode="numeric"
                                    maxlength="2"
                                    autocomplete="off"
                                    dir="ltr"
                                    placeholder="۱۲"
                                    aria-label="کد دو رقمی ایران"
                                    @required(old('role', 'companion_seeker') === 'companion_helper')
                                    @disabled(old('role', 'companion_seeker') !== 'companion_helper')
                                >
                            </div>

                        </div>

                        @foreach ([
                            'vehicle_plate_region',
                            'vehicle_plate_three',
                            'vehicle_plate_letter',
                            'vehicle_plate_last',
                        ] as $plateField)
                            @error($plateField)
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        @endforeach

                        <div class="plate-hint">
                            <span class="font-bold">ⓘ</span>
                            شماره پلاک را مستقیماً روی قسمت‌های سفید پلاک وارد کنید.
                        </div>

                    </div>
                </section>

                {{-- نام و نام خانوادگی --}}
                <div>
                    <label for="name" class="register-label">
                        نام و نام خانوادگی
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        required
                        maxlength="150"
                        placeholder="نام و نام خانوادگی خود را وارد کنید"
                        class="register-input"
                    >

                    @error('name')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- کد ملی --}}
                <div>
                    <label for="national_code" class="register-label">
                        کد ملی
                    </label>

                    <input
                        id="national_code"
                        name="national_code"
                        type="text"
                        value="{{ old('national_code') }}"
                        inputmode="numeric"
                        autocomplete="off"
                        dir="ltr"
                        required
                        maxlength="10"
                        placeholder="مثلاً 0012345679"
                        class="register-input text-left tracking-wider"
                    >

                    @error('national_code')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- شماره موبایل --}}
                <div>
                    <label for="mobile" class="register-label">
                        شماره تلفن همراه
                    </label>

                    <input
                        id="mobile"
                        name="mobile"
                        type="tel"
                        value="{{ old('mobile') }}"
                        inputmode="numeric"
                        autocomplete="tel"
                        dir="ltr"
                        required
                        maxlength="11"
                        placeholder="09123456789"
                        class="register-input text-left tracking-wider"
                    >

                    @error('mobile')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- رمز عبور و تکرار --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <label for="password" class="register-label">
                            رمز عبور
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            minlength="8"
                            placeholder="حداقل ۸ نویسه"
                            class="register-input"
                        >

                        @error('password')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="register-label">
                            تکرار رمز عبور
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            minlength="8"
                            placeholder="رمز عبور را دوباره وارد کنید"
                            class="register-input"
                        >
                    </div>

                </div>

                {{-- راهنمای ارسال مدارک --}}
                <div class="document-guide">

                    <p class="mb-1 font-black">
                        ⚠ راهنمای ارسال مدارک برای تأیید حساب
                    </p>

                    <ul class="list-inside list-disc space-y-1">
                        <li>
                            همراه‌جو باید تصویر کارت ملی خود را از طریق پیام‌رسان بله برای مدیریت ارسال کند.
                        </li>

                        <li>
                            همراه‌یار باید تصویر کارت ملی و کارت خودرو را از طریق پیام‌رسان بله برای مدیریت ارسال کند.
                        </li>

                        <li>
                            تصاویر مدارک در وب‌سایت بارگذاری یا ذخیره نمی‌شوند.
                        </li>
                    </ul>

                    <p class="mt-2">
                        حساب کاربری پس از بررسی مدارک توسط مدیریت تأیید خواهد شد.
                    </p>

                </div>

                {{-- توضیح وضعیت حساب --}}
                <div class="rounded-xl bg-slate-100 px-4 py-3 text-center text-xs leading-7 text-slate-700">
                    اطلاعات ثبت‌شده برای بررسی و مدیریت حساب کاربری استفاده می‌شود.
                    ثبت‌نام به‌تنهایی به معنای تأیید یا فعال‌شدن حساب نیست.
                </div>

                {{-- پذیرش قوانین --}}
                <section class="rounded-xl border border-gray-200 bg-slate-50 p-4">
                    <label for="accepted_terms" class="flex cursor-pointer items-start gap-3">
                        <input
                            id="accepted_terms"
                            name="accepted_terms"
                            type="checkbox"
                            value="1"
                            @checked(old('accepted_terms'))
                            required
                            class="mt-1 h-5 w-5 shrink-0 rounded border-gray-300 text-green-800 focus:ring-green-700"
                        >
                        <span class="text-sm leading-7 text-gray-700">
                            قوانین و شرایط استفاده از پرچم سرخ را
                            <a href="{{ route('terms') }}" target="_blank" rel="noopener noreferrer"
                               class="font-black text-green-800 underline underline-offset-4">
                                مطالعه کرده‌ام
                            </a>
                            و می‌پذیرم.
                        </span>
                    </label>
                    @error('accepted_terms')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </section>

                {{-- ثبت‌نام --}}
                <button
                    type="submit"
                    class="w-full rounded-full bg-green-900 px-5 py-3.5 text-sm font-black text-white shadow-sm transition hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-200"
                >
                    ثبت اطلاعات و ادامه
                </button>

            </form>

        </div>

        <p class="mt-5 text-center text-xs leading-7 text-gray-500">
            پرچم سرخ؛ سامانه همراهی رایگان و بشردوستانه
        </p>

    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const roleInputs = document.querySelectorAll('input[name="role"]');
    const vehicleFields = document.getElementById('vehicle-fields');

    const vehicleModel = document.getElementById('vehicle_model');
    const vehicleCapacity = document.getElementById('vehicle_capacity');

    const plateRegion = document.getElementById('vehicle_plate_region');
    const plateThree = document.getElementById('vehicle_plate_three');
    const plateLetter = document.getElementById('vehicle_plate_letter');
    const plateLast = document.getElementById('vehicle_plate_last');

    const vehicleInputs = [
        vehicleModel,
        vehicleCapacity,
        plateRegion,
        plateThree,
        plateLetter,
        plateLast
    ];

    function updateVehicleFields() {

        const selectedRole = document.querySelector(
            'input[name="role"]:checked'
        )?.value;

        const isHelper = selectedRole === 'companion_helper';

        vehicleFields.hidden = !isHelper;

        vehicleInputs.forEach(function (input) {
            input.disabled = !isHelper;
            input.required = isHelper;
        });
    }

    function normalizeDigits(value) {

        const persianDigits = '۰۱۲۳۴۵۶۷۸۹';
        const arabicDigits = '٠١٢٣٤٥٦٧٨٩';

        return value.replace(/[۰-۹٠-٩]/g, function (digit) {

            let index = persianDigits.indexOf(digit);

            if (index !== -1) {
                return String(index);
            }

            index = arabicDigits.indexOf(digit);

            if (index !== -1) {
                return String(index);
            }

            return digit;
        });
    }

    const numericPlateInputs = [
        plateRegion,
        plateThree,
        plateLast
    ];

    numericPlateInputs.forEach(function (input) {

        input.addEventListener('input', function () {

            const maxLength = Number(input.maxLength);

            input.value = normalizeDigits(input.value)
                .replace(/[^0-9]/g, '')
                .slice(0, maxLength);

            if (input.value.length === maxLength) {

                if (input === plateRegion) {
                    plateLast.focus();
                } else if (input === plateLast) {
                    plateLetter.focus();
                } else if (input === plateThree) {
                    input.blur();
                }
            }
        });
    });

    plateLetter.addEventListener('change', function () {
        if (this.value !== '') {
            plateThree.focus();
        }
    });

    const plateInputs = [
        plateRegion,
        plateLast,
        plateLetter,
        plateThree
    ];

    plateInputs.forEach(function (input, index) {

        input.addEventListener('keydown', function (event) {

            if (
                event.key === 'Backspace' &&
                input.value === '' &&
                index > 0
            ) {
                plateInputs[index - 1].focus();
            }
        });
    });

    roleInputs.forEach(function (input) {
        input.addEventListener('change', updateVehicleFields);
    });

    updateVehicleFields();

});
</script>

@endsection
