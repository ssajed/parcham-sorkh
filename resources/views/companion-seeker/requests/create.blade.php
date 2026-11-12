@extends('layouts.app')

@section('title', 'ثبت درخواست همراهی')

@section('content')
<main id="main-content" class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6">
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('companion-seeker.dashboard') }}" class="text-sm font-bold text-green-800 hover:underline">بازگشت به داشبورد</a>
        <header class="mt-6">
            <p class="text-sm font-bold text-red-700">پرچم سرخ</p>
            <h1 class="mt-2 text-2xl font-black text-gray-900">ثبت درخواست همراهی</h1>
            <p class="mt-3 text-sm leading-8 text-gray-600">اطلاعات مسیر و زمان موردنیاز را وارد کنید. ثبت درخواست به‌تنهایی به معنی توافق با همراه‌یار نیست.</p>
        </header>

        @if ($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm leading-7 text-red-700">
                <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('companion-seeker.requests.store') }}" class="mt-7 space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            <fieldset>
                <legend class="mb-3 font-black text-gray-900">نوع همراهی</legend>
                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4">
                        <input type="radio" name="type" value="one_way" @checked(old('type', 'one_way') === 'one_way') required>
                        <span class="text-sm font-bold">یک‌طرفه</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4">
                        <input type="radio" name="type" value="round_trip" @checked(old('type') === 'round_trip') required>
                        <span class="text-sm font-bold">رفت‌وبرگشت</span>
                    </label>
                </div>
            </fieldset>

            <div class="grid gap-4 sm:grid-cols-2">
                <div><label for="origin_city" class="mb-2 block text-sm font-bold">شهر مبدأ</label><input id="origin_city" name="origin_city" value="{{ old('origin_city') }}" maxlength="100" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm" placeholder="مثلاً تهران"></div>
                <div><label for="destination_city" class="mb-2 block text-sm font-bold">شهر مقصد</label><input id="destination_city" name="destination_city" value="{{ old('destination_city') }}" maxlength="100" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm" placeholder="مثلاً کرج"></div>
                <div><label for="origin_location" class="mb-2 block text-sm font-bold">محل مبدأ</label><input id="origin_location" name="origin_location" value="{{ old('origin_location') }}" maxlength="255" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm" placeholder="محله یا نشانی تقریبی"></div>
                <div><label for="destination_location" class="mb-2 block text-sm font-bold">محل مقصد</label><input id="destination_location" name="destination_location" value="{{ old('destination_location') }}" maxlength="255" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm" placeholder="محله یا نشانی تقریبی"></div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div><label for="departure_at" class="mb-2 block text-sm font-bold">زمان حرکت</label><input id="departure_at" name="departure_at" type="datetime-local" value="{{ old('departure_at') }}" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm" dir="ltr"></div>
                <div id="return-field" @if(old('type') !== 'round_trip') hidden @endif><label for="return_at" class="mb-2 block text-sm font-bold">زمان برگشت</label><input id="return_at" name="return_at" type="datetime-local" value="{{ old('return_at') }}" @required(old('type') === 'round_trip') class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm" dir="ltr"></div>
            </div>

            <div>
                <label for="passenger_count" class="mb-2 block text-sm font-bold">تعداد افراد (با احتساب خود همراه‌جو)</label>
                <select id="passenger_count" name="passenger_count" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm">
                    @foreach([1,2,3,4] as $count)
                        <option value="{{ $count }}" @selected((string) old('passenger_count', '1') === (string) $count)>{{ $count }} نفر</option>
                    @endforeach
                </select>
            </div>

            <div><label for="description" class="mb-2 block text-sm font-bold">توضیحات تکمیلی (اختیاری)</label><textarea id="description" name="description" rows="4" maxlength="2000" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm" placeholder="در صورت نیاز توضیح کوتاهی درباره همراهی بنویسید.">{{ old('description') }}</textarea></div>

            <div class="rounded-xl bg-amber-50 p-4 text-xs leading-7 text-amber-900">نشانی دقیق و اطلاعات تماس را در توضیحات وارد نکنید. تا پیش از توافق، اطلاعات حساس باید محرمانه بماند. ظرفیت نهایی باید با ظرفیت واقعی خودرو هماهنگ باشد.</div>
            <button type="submit" class="w-full rounded-full bg-green-900 px-6 py-3.5 text-sm font-black text-white hover:bg-green-800">ثبت درخواست همراهی</button>
        </form>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const returnField = document.getElementById('return-field');
    const returnInput = document.getElementById('return_at');
    const typeInputs = document.querySelectorAll('input[name="type"]');
    function updateReturnField() {
        const roundTrip = document.querySelector('input[name="type"]:checked')?.value === 'round_trip';
        returnField.hidden = !roundTrip;
        returnInput.required = roundTrip;
        if (!roundTrip) returnInput.value = '';
    }
    typeInputs.forEach(input => input.addEventListener('change', updateReturnField));
    updateReturnField();
});
</script>
@endsection
