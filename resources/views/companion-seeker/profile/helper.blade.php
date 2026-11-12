@extends('layouts.app')

@section('title', 'پروفایل همراه‌یار')

@section('content')
<main id="main-content" class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6">
    <div class="mx-auto max-w-4xl">
        <a href="{{ route('companion-seeker.dashboard') }}" class="text-sm font-bold text-green-800 hover:underline">بازگشت به داشبورد</a>
        <header class="mt-6 rounded-3xl bg-gradient-to-l from-red-800 to-red-600 p-7 text-white shadow-sm sm:p-9">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 text-3xl">🚘</div>
                <div>
                    <p class="text-sm text-red-100">پروفایل کاربری</p>
                    <h1 class="mt-1 text-2xl font-black">همراه‌یار</h1>
                    <p class="mt-2 text-sm text-red-100">اطلاعات حساب و وسیله نقلیه</p>
                </div>
            </div>
        </header>

        <section class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-black text-gray-900">اطلاعات هویتی</h2>
                <span class="rounded-full bg-green-100 px-4 py-1.5 text-xs font-bold text-green-800">حساب تأییدشده</span>
            </div>
            <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                <div><dt class="text-xs text-gray-500">نام و نام خانوادگی</dt><dd class="mt-2 font-bold text-gray-900">{{ $user->name }}</dd></div>
                <div><dt class="text-xs text-gray-500">شماره همراه</dt><dd class="mt-2 font-bold text-gray-900" dir="ltr">{{ $user->mobile }}</dd></div>
                <div><dt class="text-xs text-gray-500">کد ملی</dt><dd class="mt-2 font-bold text-gray-900">••••••{{ substr($user->national_code, -4) }}</dd></div>
                <div><dt class="text-xs text-gray-500">تاریخ عضویت</dt><dd class="mt-2 font-bold text-gray-900">{{ $user->created_at?->format('Y/m/d') }}</dd></div>
            </dl>
        </section>

        <section class="mt-6 rounded-2xl border border-red-100 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-lg font-black text-gray-900">اطلاعات وسیله نقلیه</h2>
            <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                <div><dt class="text-xs text-gray-500">مدل خودرو</dt><dd class="mt-2 font-bold text-gray-900">{{ $user->vehicle_model ?: 'ثبت نشده' }}</dd></div>
                <div><dt class="text-xs text-gray-500">شماره پلاک ثبت‌شده</dt><dd class="mt-2 font-bold text-gray-900" dir="ltr">{{ $user->vehicle_plate ?: 'ثبت نشده' }}</dd></div>
            </dl>
            <p class="mt-5 rounded-xl bg-amber-50 p-4 text-xs leading-7 text-amber-900">برای تغییر اطلاعات خودرو، فعلاً از طریق مدیریت سامانه پیگیری کنید تا اطلاعات جدید بررسی و تأیید شود.</p>
        </section>

        <section class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-lg font-black text-gray-900">مسئولیت همراه‌یار</h2>
            <p class="mt-3 text-sm leading-8 text-gray-600">اعلام آمادگی برای همراهی داوطلبانه است. پیش از هر توافق، مسیر، زمان و ظرفیت واقعی خودرو را بررسی کنید و اطلاعات تماس دیگران را محرمانه نگه دارید.</p>
        </section>
    </div>
</main>
@endsection
