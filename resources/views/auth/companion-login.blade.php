@extends('layouts.app')

@section('title', 'ورود همراه‌جو و همراه‌یار')
@section('meta_description', 'ورود امن همراه‌جویان و همراه‌یاران به حساب پرچم سرخ.')

@section('content')
<main id="main-content" class="min-h-screen bg-slate-50 px-4 py-12">
    <div class="mx-auto max-w-md">
        <div class="mb-7 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-2xl font-black text-green-900">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-900 text-2xl text-white">پ</span>
                پرچم سرخ
            </a>
            <h1 class="mt-6 text-2xl font-black text-gray-900">ورود به حساب همراهان</h1>
            <p class="mt-2 text-sm leading-7 text-gray-600">برای ادامه فعالیت به‌عنوان همراه‌جو یا همراه‌یار وارد حساب خود شوید.</p>
        </div>

        <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm leading-7 text-red-700" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('companion-seeker.login.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="mobile" class="mb-2 block text-sm font-bold text-gray-800">شماره موبایل</label>
                    <input id="mobile" name="mobile" type="tel" inputmode="numeric" autocomplete="username"
                        value="{{ old('mobile') }}" maxlength="11" required autofocus
                        placeholder="مثلاً ۰۹۱۲۳۴۵۶۷۸۹"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-700 focus:ring-4 focus:ring-green-100">
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-gray-800">رمز عبور</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        placeholder="رمز عبور حساب"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-green-700 focus:ring-4 focus:ring-green-100">
                </div>
                <button type="submit" class="w-full rounded-xl bg-green-900 px-5 py-3.5 text-sm font-black text-white transition hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-200">
                    ورود به حساب
                </button>
            </form>

            <div class="mt-6 border-t border-gray-100 pt-5 text-center text-sm leading-7">
                <p class="text-gray-600">هنوز حساب ندارید؟ <a href="{{ route('register') }}" class="font-black text-red-700 underline">ثبت‌نام کنید</a></p>
                <a href="{{ route('home') }}" class="mt-2 inline-block text-gray-500 underline">بازگشت به صفحه اصلی</a>
            </div>
        </section>
        <p class="mt-5 text-center text-xs leading-6 text-gray-500">ورود فقط برای حساب‌های تأییدشده همراه‌جو و همراه‌یار امکان‌پذیر است.</p>
    </div>
</main>
@endsection
