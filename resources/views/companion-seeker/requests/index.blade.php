@extends('layouts.app')

@section('title', 'درخواست‌های همراهی من')

@section('content')
<main id="main-content" class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6">
    <div class="mx-auto max-w-5xl">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <a href="{{ route('companion-seeker.dashboard') }}" class="text-sm font-bold text-green-800 hover:underline">بازگشت به داشبورد</a>
                <h1 class="mt-4 text-2xl font-black text-gray-900">درخواست‌های همراهی من</h1>
                <p class="mt-2 text-sm text-gray-600">درخواست‌هایی که با حساب همراه‌جوی شما ثبت شده‌اند.</p>
            </div>
            <a href="{{ route('companion-seeker.requests.create') }}" class="rounded-full bg-green-900 px-5 py-3 text-sm font-bold text-white hover:bg-green-800">درخواست جدید</a>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
        @endif

        <div class="mt-7 space-y-4">
            @forelse($requests as $item)
                <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">باز و در انتظار همراه‌یار</span>
                        <span class="text-xs text-gray-500">شماره درخواست: {{ $item->id }}</span>
                    </div>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div><p class="text-xs text-gray-500">مبدأ</p><p class="mt-1 font-black text-gray-900">{{ $item->origin_city }} - {{ $item->origin_location }}</p></div>
                        <div><p class="text-xs text-gray-500">مقصد</p><p class="mt-1 font-black text-gray-900">{{ $item->destination_city }} - {{ $item->destination_location }}</p></div>
                        <div><p class="text-xs text-gray-500">زمان حرکت</p><p class="mt-1 font-bold text-gray-800">{{ $item->departure_at?->format('Y/m/d H:i') }}</p></div>
                        <div><p class="text-xs text-gray-500">نوع همراهی</p><p class="mt-1 font-bold text-gray-800">{{ $item->isRoundTrip() ? 'رفت‌وبرگشت' : 'یک‌طرفه' }}</p></div>
                        @if($item->isRoundTrip())
                            <div><p class="text-xs text-gray-500">زمان برگشت</p><p class="mt-1 font-bold text-gray-800">{{ $item->return_at?->format('Y/m/d H:i') }}</p></div>
                        @endif
                        <div><p class="text-xs text-gray-500">تعداد افراد (با احتساب همراه‌جو)</p><p class="mt-1 font-bold text-gray-800">{{ $item->passenger_count }} نفر</p></div>
                    </div>
                    @if($item->description)
                        <p class="mt-4 border-t border-gray-100 pt-4 text-sm leading-7 text-gray-600">{{ $item->description }}</p>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
                    <div class="text-4xl">🧭</div>
                    <h2 class="mt-4 font-black text-gray-900">هنوز درخواستی ثبت نکرده‌اید</h2>
                    <p class="mt-2 text-sm text-gray-500">برای شروع، درخواست همراهی خود را ثبت کنید.</p>
                    <a href="{{ route('companion-seeker.requests.create') }}" class="mt-5 inline-flex rounded-full bg-green-900 px-5 py-3 text-sm font-bold text-white">ثبت درخواست همراهی</a>
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $requests->links() }}</div>
    </div>
</main>
@endsection
