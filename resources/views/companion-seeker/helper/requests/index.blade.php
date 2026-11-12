@extends('layouts.app')

@section('title', 'درخواست‌های همراهی')

@section('content')
<main id="main-content" class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6">
    <div class="mx-auto max-w-6xl">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <a href="{{ route('companion-seeker.dashboard') }}" class="text-sm font-bold text-green-800 hover:underline">بازگشت به داشبورد</a>
                <h1 class="mt-4 text-2xl font-black text-gray-900">درخواست‌های همراهی</h1>
                <p class="mt-2 text-sm text-gray-600">درخواست‌های باز متناسب با ظرفیت خودروی شما</p>
            </div>
            <a href="{{ route('companion-seeker.helper.offers.index') }}" class="rounded-full border border-green-800 px-5 py-3 text-sm font-bold text-green-900">اعلام آمادگی‌های من</a>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        @if(!$vehicleCapacity)
            <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm leading-7 text-amber-900">ظرفیت خودروی شما هنوز ثبت نشده است؛ تا زمان ثبت ظرفیت تأییدشده، درخواست متناسبی نمایش داده نمی‌شود.</div>
        @endif

        <form method="GET" class="mt-6 grid gap-3 rounded-2xl border border-gray-200 bg-white p-4 sm:grid-cols-2 lg:grid-cols-5">
            <input name="origin_city" value="{{ $filters['origin_city'] ?? '' }}" placeholder="شهر مبدأ" class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
            <input name="destination_city" value="{{ $filters['destination_city'] ?? '' }}" placeholder="شهر مقصد" class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
            <input name="departure_date" type="date" value="{{ $filters['departure_date'] ?? '' }}" class="rounded-xl border border-gray-300 px-3 py-2 text-sm" aria-label="تاریخ حرکت">
            <select name="type" class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
                <option value="">همه انواع همراهی</option>
                <option value="one_way" @selected(($filters['type'] ?? '') === 'one_way')>یک‌طرفه</option>
                <option value="round_trip" @selected(($filters['type'] ?? '') === 'round_trip')>رفت‌وبرگشت</option>
            </select>
            <select name="passenger_count" class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
                <option value="">همه تعدادها</option>
                @foreach([1,2,3,4] as $count)
                    <option value="{{ $count }}" @selected((string)($filters['passenger_count'] ?? '') === (string)$count)>{{ $count }} نفر یا کمتر</option>
                @endforeach
            </select>
            <div class="flex gap-2 sm:col-span-2 lg:col-span-5">
                <button class="rounded-full bg-green-900 px-5 py-2 text-sm font-bold text-white">اعمال فیلتر</button>
                <a href="{{ route('companion-seeker.helper.requests.index') }}" class="rounded-full border border-gray-300 px-5 py-2 text-sm font-bold text-gray-700">پاک‌کردن</a>
            </div>
        </form>

        <div class="mt-6 space-y-4">
            @forelse($requests as $item)
                <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-800">درخواست باز</span>
                        <span class="text-xs text-gray-500">درخواست شماره {{ $item->id }}</span>
                    </div>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div><p class="text-xs text-gray-500">شهر مبدأ</p><p class="mt-1 font-black text-gray-900">{{ $item->origin_city }}</p></div>
                        <div><p class="text-xs text-gray-500">شهر مقصد</p><p class="mt-1 font-black text-gray-900">{{ $item->destination_city }}</p></div>
                        <div><p class="text-xs text-gray-500">زمان حرکت</p><p class="mt-1 font-bold text-gray-800">{{ \App\Support\JalaliDate::format($item->departure_at, 'Y/m/d H:i') }}</p></div>
                        <div><p class="text-xs text-gray-500">نوع همراهی</p><p class="mt-1 font-bold text-gray-800">{{ $item->isRoundTrip() ? 'رفت‌وبرگشت' : 'یک‌طرفه' }}</p></div>
                        <div><p class="text-xs text-gray-500">تعداد افراد</p><p class="mt-1 font-bold text-gray-800">{{ $item->passenger_count }} نفر</p></div>
                        @if($item->isRoundTrip())
                            <div><p class="text-xs text-gray-500">زمان برگشت</p><p class="mt-1 font-bold text-gray-800">{{ \App\Support\JalaliDate::format($item->return_at, 'Y/m/d H:i') }}</p></div>
                        @endif
                    </div>
                    <p class="mt-4 rounded-xl bg-slate-50 p-3 text-xs leading-6 text-gray-600">محل دقیق مبدأ و مقصد، توضیحات خصوصی و اطلاعات تماس پس از توافق دوطرفه نمایش داده می‌شود.</p>
                    <form method="POST" action="{{ route('companion-seeker.helper.requests.offer', $item) }}" class="mt-4">
                        @csrf
                        <button class="w-full rounded-full bg-green-900 px-5 py-3 text-sm font-black text-white hover:bg-green-800">اعلام آمادگی برای همراهی</button>
                    </form>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
                    <div class="text-4xl">🧭</div>
                    <h2 class="mt-4 font-black text-gray-900">درخواست واجد شرایطی پیدا نشد</h2>
                    <p class="mt-2 text-sm text-gray-500">می‌توانید فیلترها را تغییر دهید یا بعداً دوباره بررسی کنید.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $requests->links() }}</div>
    </div>
</main>
@endsection
