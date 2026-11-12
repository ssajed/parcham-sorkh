@extends('admin.layouts.app')

@section('title', 'درخواست‌های همراهی')

@section('breadcrumb', 'درخواست‌های همراهی')

@section('content')
<section class="admin-page-heading">
    <div>
        <span class="admin-eyebrow">مدیریت پرچم سرخ</span>
        <h1 class="admin-page-title">درخواست‌های همراهی</h1>
        <p class="admin-page-description">مشاهده درخواست‌ها و وضعیت توافق‌ها</p>
    </div>
</section>

<section class="admin-panel">
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <label for="status" class="text-sm font-bold">وضعیت</label>
        <select id="status" name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="">همه وضعیت‌ها</option>
            @foreach(['open' => 'باز', 'agreed' => 'توافق‌شده', 'completed' => 'تکمیل‌شده', 'cancelled' => 'لغوشده', 'expired' => 'منقضی‌شده'] as $value => $label)
                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="rounded-lg bg-green-900 px-4 py-2 text-sm font-bold text-white">فیلتر</button>
    </form>

    <div class="mt-6 space-y-4">
        @forelse($requests as $item)
            @php($labels = ['open' => 'باز', 'agreed' => 'توافق‌شده', 'completed' => 'تکمیل‌شده', 'cancelled' => 'لغوشده', 'expired' => 'منقضی‌شده'])
            <article class="rounded-xl border border-gray-200 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <strong>درخواست شماره {{ $item->id }}</strong>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold">{{ $labels[$item->status] ?? $item->status }}</span>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div><small class="text-gray-500">همراه‌جو</small><p class="font-bold">{{ $item->companionSeeker->name }}</p><p dir="ltr" class="text-sm">{{ $item->companionSeeker->mobile }}</p></div>
                    <div><small class="text-gray-500">مسیر</small><p class="font-bold">{{ $item->origin_city }} به {{ $item->destination_city }}</p></div>
                    <div><small class="text-gray-500">زمان حرکت</small><p class="font-bold">{{ \App\Support\JalaliDate::format($item->departure_at, 'Y/m/d H:i') }}</p></div>
                    <div><small class="text-gray-500">تعداد افراد</small><p class="font-bold">{{ $item->passenger_count }} نفر</p></div>
                </div>
                @if($item->acceptedOffer)
                    <div class="mt-4 rounded-lg bg-green-50 p-4 text-sm">
                        <strong>همراه‌یار توافق‌شده:</strong> {{ $item->acceptedOffer->helper->name }}
                        <span class="mx-2" dir="ltr">{{ $item->acceptedOffer->helper->mobile }}</span>
                    </div>
                @endif
            </article>
        @empty
            <p class="py-8 text-center text-sm text-gray-500">درخواستی با این وضعیت ثبت نشده است.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $requests->links() }}</div>
</section>
@endsection
