<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123d32">
    <title>مسدودسازی حساب | پرچم سرخ</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-50 px-4 py-12 text-gray-900">
    <main class="mx-auto flex min-h-[75vh] max-w-xl items-center">
        <section class="w-full rounded-3xl border border-red-200 bg-white p-7 text-center shadow-sm sm:p-10">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-red-50 text-3xl font-black text-red-700" aria-hidden="true">!</div>
            <p class="mt-6 text-sm font-black text-green-900">پرچم سرخ</p>
            <h1 class="mt-3 text-2xl font-black text-red-800">حساب کاربری شما مسدود شده است</h1>
            <p class="mt-4 text-sm leading-8 text-gray-700">
                به دلیل نقض قوانین پرچم سرخ، دسترسی حساب شما موقتاً مسدود شده است.
                در حال حاضر امکان استفاده از امکانات سامانه را ندارید.
            </p>

            @if (auth()->user()->blocked_reason)
                <div class="mt-6 rounded-2xl border border-gray-200 bg-gray-50 p-5 text-right">
                    <h2 class="text-sm font-black text-gray-800">دلیل اعلام‌شده</h2>
                    <p class="mt-2 whitespace-pre-line text-sm leading-8 text-gray-700">{{ auth()->user()->blocked_reason }}</p>
                </div>
            @endif

            <p class="mt-5 text-xs leading-7 text-gray-500">
                برای پیگیری یا درخواست بررسی مجدد، با مدیریت پرچم سرخ تماس بگیرید.
            </p>

            <form method="POST" action="{{ route('companion-seeker.logout') }}" class="mt-7">
                @csrf
                <button type="submit" class="w-full rounded-xl bg-green-900 px-5 py-3.5 text-sm font-black text-white transition hover:bg-green-800">
                    خروج از حساب
                </button>
            </form>
        </section>
    </main>
</body>
</html>
