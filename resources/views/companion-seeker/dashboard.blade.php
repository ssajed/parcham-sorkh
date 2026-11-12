@extends('layouts.app')

@section('title', 'حساب کاربری')

@section('meta_description', 'بخش کاربران سامانه پرچم سرخ')

@section('content')
<main id="main-content" class="min-h-screen bg-gray-50 px-4 py-10 sm:px-6">
    <div class="mx-auto max-w-5xl">
        <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="mb-2 text-sm font-bold text-green-800">پرچم سرخ</p>
                <h1 class="text-2xl font-black text-gray-900">حساب کاربری شما</h1>
                <p class="mt-2 text-sm leading-7 text-gray-500">
                    از این بخش به قابلیت‌های فعال حساب خود دسترسی دارید.
                </p>
            </div>

            <form method="POST" action="{{ route('companion-seeker.logout') }}">
                @csrf
                <button type="submit"
                    class="rounded-full border border-gray-300 bg-white px-5 py-2.5 text-sm font-bold text-gray-700 transition hover:border-red-300 hover:text-red-700">
                    خروج از حساب
                </button>
            </form>
        </header>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-2xl font-black text-green-900">✓</div>
            <h2 class="text-xl font-black text-gray-900">
                خوش آمدید، {{ auth()->user()->name }}
            </h2>
            <p class="mt-3 max-w-2xl text-sm leading-8 text-gray-600">
                ورود شما با موفقیت انجام شد. دسترسی‌های فعال حساب شما در ادامه نمایش داده شده‌اند.
            </p>

            <div class="mt-7 grid gap-4 sm:grid-cols-2">
                @if (auth()->user()->can_be_companion_seeker)
                    <div class="rounded-xl border border-green-200 bg-green-50 p-5">
                        <h3 class="font-black text-green-900">دسترسی همراه‌جو</h3>
                        <p class="mt-2 text-sm leading-7 text-green-800">
                            امکان ثبت و پیگیری درخواست‌های همراهی برای این حساب فعال است.
                        </p>
                        <p class="mt-3 text-xs text-green-700">بخش ثبت درخواست در مرحله توسعه است.</p>
                    </div>
                @endif

                @if (auth()->user()->can_be_companion_helper)
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                        <h3 class="font-black text-gray-900">دسترسی همراه‌یار</h3>
                        <p class="mt-2 text-sm leading-7 text-gray-600">
                            امکان مشاهده درخواست‌ها و اعلام آمادگی برای همراهی برای این حساب فعال است.
                        </p>
                        <p class="mt-3 text-xs text-gray-500">بخش همراه‌یار در مرحله توسعه است.</p>
                    </div>
                @endif
            </div>
        </section>

        <footer class="mt-8 text-center text-xs leading-7 text-gray-400">
            پرچم سرخ؛ بستری برای همراهی داوطلبانه و انسانی
        </footer>
    </div>
</main>
@endsection
