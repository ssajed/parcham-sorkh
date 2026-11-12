@extends('layouts.app')

@section('title', 'اعلام آمادگی‌های من')

@section('content')
<main id="main-content" class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6">
    <div class="mx-auto max-w-5xl">
        <a href="{{ route('companion-seeker.helper.requests.index') }}" class="text-sm font-bold text-green-800 hover:underline">بازگشت به درخواست‌های همراهی</a>
        <h1 class="mt-4 text-2xl font-black text-gray-900">اعلام آمادگی‌های من</h1>
        <p class="mt-2 text-sm text-gray-600">وضعیت درخواست‌هایی که برای آن‌ها اعلام آمادگی کرده‌اید.</p>

        @if(session('status'))
            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <div class="mt-6 space-y-4">
            @forelse($offers as $offer)
                @php($item = $offer->accompanimentRequest)
                <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="font-black text-gray-900">{{ $item->origin_city }} به {{ $item->destination_city }}</h2>
                        @php($statusLabels = ['pending' => 'در انتظار پاسخ همراه‌جو', 'accepted' => 'توافق‌شده', 'rejected' => 'پذیرفته‌نشده', 'withdrawn' => 'پس‌گرفته‌شده', 'expired' => 'منقضی‌شده', 'completed' => 'تکمیل‌شده'])
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-gray-700">{{ $statusLabels[$offer->status] ?? $offer->status }}</span>
                    </div>
                    <div class="mt-4 grid gap-3 sm:grid-cols-3">
                        <div><p class="text-xs text-gray-500">زمان حرکت</p><p class="mt-1 text-sm font-bold">{{ \App\Support\JalaliDate::format($item->departure_at, 'Y/m/d H:i') }}</p></div>
                        <div><p class="text-xs text-gray-500">تعداد افراد رزروشده</p><p class="mt-1 text-sm font-bold">{{ $offer->reserved_passenger_count }} نفر</p></div>
                        <div><p class="text-xs text-gray-500">نوع همراهی</p><p class="mt-1 text-sm font-bold">{{ $item->isRoundTrip() ? 'رفت‌وبرگشت' : 'یک‌طرفه' }}</p></div>
                    </div>

                    @if(in_array($offer->status, ['accepted', 'completed'], true))
                        <div class="mt-4 rounded-xl border border-green-200 bg-green-50 p-4">
                            <h3 class="font-black text-green-900">{{ $offer->status === 'completed' ? 'همراهی تکمیل‌شده' : 'توافق نهایی شد؛ هماهنگی مستقیم' }}</h3>
                            <p class="mt-2 text-sm text-green-900">همراه‌جو: {{ $item->companionSeeker->name }}</p>
                            <p class="mt-1 text-sm text-green-900" dir="ltr">{{ $item->companionSeeker->mobile }}</p>
                            <p class="mt-2 text-xs leading-6 text-green-800">مبدأ: {{ $item->origin_location }} — مقصد: {{ $item->destination_location }}</p>
                            @if($item->description)
                                <p class="mt-2 text-sm leading-7 text-green-900">{{ $item->description }}</p>
                            @endif
                        </div>
                        @if($offer->status === 'accepted')
                            <form method="POST" action="{{ route('companion-seeker.requests.complete', $item) }}" class="mt-4">
                                @csrf
                                <button class="rounded-full border border-green-700 px-5 py-2 text-sm font-bold text-green-900">ثبت پایان همراهی</button>
                            </form>
                        @endif
                    @elseif($offer->status === 'pending')
                        <p class="mt-4 text-xs leading-6 text-gray-500">تا زمان پذیرش از سوی همراه‌جو، اطلاعات تماس و محل دقیق نمایش داده نمی‌شود. ظرفیت این درخواست برای شما موقتاً رزرو است.</p>
                        <form method="POST" action="{{ route('companion-seeker.helper.offers.withdraw', $offer) }}" class="mt-4">
                            @csrf
                            <button class="rounded-full border border-red-300 px-5 py-2 text-sm font-bold text-red-700">پس‌گرفتن اعلام آمادگی و آزادسازی ظرفیت</button>
                        </form>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
                    <h2 class="font-black text-gray-900">هنوز اعلام آمادگی ثبت نکرده‌اید</h2>
                    <a href="{{ route('companion-seeker.helper.requests.index') }}" class="mt-4 inline-flex rounded-full bg-green-900 px-5 py-3 text-sm font-bold text-white">مشاهده درخواست‌ها</a>
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $offers->links() }}</div>
    </div>
</main>
@endsection
