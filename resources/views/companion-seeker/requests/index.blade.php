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
        @if($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <div class="mt-7 space-y-4">
            @forelse($requests as $item)
                @php($statusLabels = ['open' => 'باز؛ در انتظار همراه‌یار', 'agreed' => 'توافق‌شده', 'completed' => 'تکمیل‌شده', 'cancelled' => 'لغوشده', 'expired' => 'منقضی‌شده'])
                <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-gray-800">{{ $statusLabels[$item->status] ?? $item->status }}</span>
                        <span class="text-xs text-gray-500">شماره درخواست: {{ $item->id }}</span>
                    </div>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div><p class="text-xs text-gray-500">مبدأ</p><p class="mt-1 font-black text-gray-900">{{ $item->origin_city }} - {{ $item->origin_location }}</p></div>
                        <div><p class="text-xs text-gray-500">مقصد</p><p class="mt-1 font-black text-gray-900">{{ $item->destination_city }} - {{ $item->destination_location }}</p></div>
                        <div><p class="text-xs text-gray-500">زمان حرکت</p><p class="mt-1 font-bold text-gray-800">{{ \App\Support\JalaliDate::format($item->departure_at, 'Y/m/d H:i') }}</p></div>
                        <div><p class="text-xs text-gray-500">نوع همراهی</p><p class="mt-1 font-bold text-gray-800">{{ $item->isRoundTrip() ? 'رفت‌وبرگشت' : 'یک‌طرفه' }}</p></div>
                        @if($item->isRoundTrip())
                            <div><p class="text-xs text-gray-500">زمان برگشت</p><p class="mt-1 font-bold text-gray-800">{{ \App\Support\JalaliDate::format($item->return_at, 'Y/m/d H:i') }}</p></div>
                        @endif
                        <div><p class="text-xs text-gray-500">تعداد افراد</p><p class="mt-1 font-bold text-gray-800">{{ $item->passenger_count }} نفر</p></div>
                    </div>

                    @if($item->status === 'open')
                        <section class="mt-5 border-t border-gray-100 pt-4">
                            <h2 class="font-black text-gray-900">اعلام آمادگی همراه‌یاران ({{ $item->offers->where('status', 'pending')->count() }})</h2>
                            @forelse($item->offers->where('status', 'pending') as $offer)
                                <div class="mt-3 flex flex-wrap items-center justify-between gap-3 rounded-xl bg-slate-50 p-4">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $offer->helper->name }}</p>
                                        <p class="mt-1 text-xs text-gray-600">{{ $offer->helper->vehicle_model ?: 'مدل خودرو ثبت نشده' }} — ظرفیت {{ $offer->helper->vehicle_capacity }} نفر</p>
                                        <p class="mt-1 text-xs text-gray-500">اطلاعات تماس تا زمان توافق نمایش داده نمی‌شود.</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <form method="POST" action="{{ route('companion-seeker.requests.offers.accept', [$item, $offer]) }}">
                                            @csrf
                                            <button class="rounded-full bg-green-900 px-4 py-2 text-xs font-bold text-white">پذیرش و توافق</button>
                                        </form>
                                        <form method="POST" action="{{ route('companion-seeker.requests.offers.reject', [$item, $offer]) }}">
                                            @csrf
                                            <button class="rounded-full border border-red-300 px-4 py-2 text-xs font-bold text-red-700">رد اعلام آمادگی</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="mt-2 text-sm text-gray-500">هنوز همراه‌یاری اعلام آمادگی نکرده است.</p>
                            @endforelse
                        </section>
                        <form method="POST" action="{{ route('companion-seeker.requests.cancel', $item) }}" class="mt-4" onsubmit="return confirm('درخواست همراهی لغو شود؟')">
                            @csrf
                            <button class="rounded-full border border-red-300 px-5 py-2 text-sm font-bold text-red-700">لغو درخواست</button>
                        </form>
                    @elseif($item->status === 'agreed' && $item->acceptedOffer)
                        <section class="mt-5 rounded-xl border border-green-200 bg-green-50 p-4">
                            <h2 class="font-black text-green-900">توافق نهایی شد؛ اطلاعات هماهنگی</h2>
                            <p class="mt-2 text-sm text-green-900">همراه‌یار: {{ $item->acceptedOffer->helper->name }}</p>
                            <p class="mt-1 text-sm text-green-900" dir="ltr">{{ $item->acceptedOffer->helper->mobile }}</p>
                            <p class="mt-1 text-sm text-green-900">خودرو: {{ $item->acceptedOffer->helper->vehicle_model }} — پلاک: {{ $item->acceptedOffer->helper->vehicle_plate }}</p>
                            @if($item->return_at)
                                <p class="mt-2 text-sm text-green-900">زمان برگشت: {{ \App\Support\JalaliDate::format($item->return_at, 'Y/m/d H:i') }}</p>
                            @endif
                        </section>
                        <form method="POST" action="{{ route('companion-seeker.requests.complete', $item) }}" class="mt-4">
                            @csrf
                            <button class="rounded-full border border-green-700 px-5 py-2 text-sm font-bold text-green-900">ثبت پایان همراهی</button>
                        </form>
                    @endif

                    @if($item->description && $item->status !== 'open')
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
