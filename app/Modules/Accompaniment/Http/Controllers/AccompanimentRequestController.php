<?php

namespace App\Modules\Accompaniment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Accompaniment\Models\AccompanimentOffer;
use App\Modules\Accompaniment\Models\AccompanimentRequest;
use App\Modules\Accompaniment\Requests\AccompanimentRequestFormRequest;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AccompanimentRequestController extends Controller
{
    public function adminIndex(Request $request): View
    {
        abort_unless($request->user()->isAdmin(), 403);

        $query = AccompanimentRequest::query()
            ->with(['companionSeeker:id,name,mobile', 'acceptedOffer.helper:id,name,mobile,vehicle_model,vehicle_plate'])
            ->latest('departure_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.accompaniment-requests.index', [
            'requests' => $query->paginate(20)->withQueryString(),
            'status' => $request->input('status', ''),
        ]);
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->isCompanionSeeker(), 403);

        $requests = $request->user()
            ->accompanimentRequests()
            ->with(['offers.helper', 'acceptedOffer.helper'])
            ->latest('departure_at')
            ->paginate(10);

        return view('companion-seeker.requests.index', [
            'requests' => $requests,
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isCompanionSeeker(), 403);

        return view('companion-seeker.requests.create');
    }

    public function store(AccompanimentRequestFormRequest $request): RedirectResponse
    {
        $accompanimentRequest = new AccompanimentRequest();
        $accompanimentRequest->fill($request->validated());
        $accompanimentRequest->status = AccompanimentRequest::STATUS_OPEN;
        $request->user()->accompanimentRequests()->save($accompanimentRequest);

        return redirect()
            ->route('companion-seeker.requests.index')
            ->with('status', 'درخواست همراهی شما ثبت شد و برای همراه‌یاران واجد شرایط قابل مشاهده است.');
    }

    public function helperIndex(Request $request): View
    {
        $helper = $request->user();
        abort_unless($helper->isCompanionHelper(), 403);

        $minimumDepartureDate = CarbonImmutable::now('Asia/Tehran')
            ->startOfDay()
            ->addDay();

        if (CarbonImmutable::now('Asia/Tehran')->hour >= 8) {
            $minimumDepartureDate = $minimumDepartureDate->addDay();
        }

        $query = AccompanimentRequest::query()
            ->where('status', AccompanimentRequest::STATUS_OPEN)
            ->whereDate('departure_at', '>=', $minimumDepartureDate->toDateString())
            ->where('user_id', '!=', $helper->id)
            ->where('passenger_count', '<=', (int) $helper->vehicle_capacity)
            ->whereHas('companionSeeker', function ($userQuery): void {
                $userQuery->where('account_status', User::ACCOUNT_STATUS_APPROVED)
                    ->where('can_be_companion_seeker', true)
                    ->whereNull('blocked_at');
            })
            ->whereDoesntHave('offers', function ($offerQuery) use ($helper): void {
                $offerQuery->where('helper_id', $helper->id)
                    ->whereIn('status', [
                        AccompanimentOffer::STATUS_PENDING,
                        AccompanimentOffer::STATUS_ACCEPTED,
                    ]);
            })
            ->with('companionSeeker:id,name')
            ->orderBy('departure_at');

        $query->when($request->filled('origin_city'), fn ($builder) =>
            $builder->where('origin_city', 'like', '%'.$request->string('origin_city').'%')
        );
        $query->when($request->filled('destination_city'), fn ($builder) =>
            $builder->where('destination_city', 'like', '%'.$request->string('destination_city').'%')
        );
        $query->when($request->filled('departure_date'), fn ($builder) =>
            $builder->whereDate('departure_at', $request->input('departure_date'))
        );
        $query->when($request->filled('type'), fn ($builder) =>
            $builder->where('type', $request->input('type'))
        );
        $query->when($request->filled('passenger_count'), fn ($builder) =>
            $builder->where('passenger_count', '<=', (int) $request->input('passenger_count'))
        );

        return view('companion-seeker.helper.requests.index', [
            'requests' => $query->paginate(12)->withQueryString(),
            'filters' => $request->only(['origin_city', 'destination_city', 'departure_date', 'type', 'passenger_count']),
            'vehicleCapacity' => $helper->vehicle_capacity,
        ]);
    }

    public function helperOffers(Request $request): View
    {
        abort_unless($request->user()->isCompanionHelper(), 403);

        $offers = $request->user()
            ->accompanimentOffers()
            ->with(['accompanimentRequest.companionSeeker'])
            ->latest()
            ->paginate(12);

        return view('companion-seeker.helper.offers.index', [
            'offers' => $offers,
        ]);
    }

    public function makeOffer(Request $request, int $requestId): RedirectResponse
    {
        $helper = $request->user();
        abort_unless($helper->isCompanionHelper(), 403);

        DB::transaction(function () use ($helper, $requestId): void {
            $accompanimentRequest = AccompanimentRequest::query()
                ->lockForUpdate()
                ->findOrFail($requestId);

            if ($accompanimentRequest->user_id === $helper->id) {
                throw ValidationException::withMessages(['request' => 'نمی‌توانید برای درخواست خودتان اعلام آمادگی کنید.']);
            }

            if ($accompanimentRequest->status !== AccompanimentRequest::STATUS_OPEN
                || ! $this->isRequestStillEligible($accompanimentRequest)) {
                throw ValidationException::withMessages(['request' => 'این درخواست دیگر برای اعلام آمادگی فعال نیست.']);
            }

            $owner = $accompanimentRequest->companionSeeker()->first();
            if (! $owner || ! $owner->isCompanionSeeker()) {
                throw ValidationException::withMessages(['request' => 'حساب ثبت‌کنندهٔ درخواست در حال حاضر واجد شرایط نیست.']);
            }

            $lockedHelper = User::query()->lockForUpdate()->findOrFail($helper->id);
            if (! $lockedHelper->isCompanionHelper()) {
                throw ValidationException::withMessages(['request' => 'دسترسی همراه‌یار شما فعال نیست.']);
            }

            if (! $lockedHelper->vehicle_capacity
                || $accompanimentRequest->passenger_count > $lockedHelper->vehicle_capacity) {
                throw ValidationException::withMessages(['request' => 'ظرفیت ثبت‌شدهٔ خودروی شما برای این درخواست کافی نیست.']);
            }

            if ($this->hasConflictingReservation($lockedHelper, $accompanimentRequest)) {
                throw ValidationException::withMessages(['request' => 'برای این بازهٔ زمانی، اعلام آمادگی یا توافق فعال دیگری دارید.']);
            }

            $existing = AccompanimentOffer::query()
                ->where('accompaniment_request_id', $accompanimentRequest->id)
                ->where('helper_id', $lockedHelper->id)
                ->first();

            if ($existing && in_array($existing->status, [
                AccompanimentOffer::STATUS_PENDING,
                AccompanimentOffer::STATUS_ACCEPTED,
            ], true)) {
                throw ValidationException::withMessages(['request' => 'قبلاً برای این درخواست اعلام آمادگی کرده‌اید.']);
            }

            if ($existing) {
                $existing->update([
                    'reserved_passenger_count' => $accompanimentRequest->passenger_count,
                    'status' => AccompanimentOffer::STATUS_PENDING,
                    'responded_at' => null,
                ]);
            } else {
                $accompanimentRequest->offers()->create([
                    'helper_id' => $lockedHelper->id,
                    'reserved_passenger_count' => $accompanimentRequest->passenger_count,
                    'status' => AccompanimentOffer::STATUS_PENDING,
                ]);
            }
        });

        return back()->with('status', 'اعلام آمادگی شما ثبت شد. ظرفیت موردنیاز این درخواست تا تعیین نتیجه برای شما رزرو شده است.');
    }

    public function withdrawOffer(Request $request, int $offerId): RedirectResponse
    {
        $offer = AccompanimentOffer::query()->findOrFail($offerId);
        abort_unless($offer->helper_id === $request->user()->id, 403);

        if ($offer->status !== AccompanimentOffer::STATUS_PENDING) {
            throw ValidationException::withMessages(['offer' => 'فقط اعلام آمادگی‌های در انتظار را می‌توان پس گرفت.']);
        }

        $offer->update([
            'status' => AccompanimentOffer::STATUS_WITHDRAWN,
            'responded_at' => now(),
        ]);

        return back()->with('status', 'اعلام آمادگی شما پس گرفته شد و ظرفیت رزروشده آزاد شد.');
    }

    public function acceptOffer(Request $request, int $requestId, int $offerId): RedirectResponse
    {
        DB::transaction(function () use ($request, $requestId, $offerId): void {
            $accompanimentRequest = AccompanimentRequest::query()
                ->lockForUpdate()
                ->findOrFail($requestId);

            abort_unless($accompanimentRequest->user_id === $request->user()->id, 403);

            if ($accompanimentRequest->status !== AccompanimentRequest::STATUS_OPEN
                || ! $this->isRequestStillEligible($accompanimentRequest)) {
                throw ValidationException::withMessages(['request' => 'این درخواست دیگر قابل توافق نیست.']);
            }

            $offer = AccompanimentOffer::query()
                ->where('accompaniment_request_id', $accompanimentRequest->id)
                ->whereKey($offerId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($offer->status !== AccompanimentOffer::STATUS_PENDING) {
                throw ValidationException::withMessages(['offer' => 'این اعلام آمادگی قبلاً تعیین تکلیف شده است.']);
            }

            $helper = User::query()->lockForUpdate()->findOrFail($offer->helper_id);
            if (! $helper->isCompanionHelper()
                || ! $helper->vehicle_capacity
                || $helper->vehicle_capacity < $accompanimentRequest->passenger_count) {
                throw ValidationException::withMessages(['offer' => 'شرایط یا ظرفیت همراه‌یار تغییر کرده است؛ امکان توافق وجود ندارد.']);
            }

            if ($this->hasConflictingReservation($helper, $accompanimentRequest, $offer->id)) {
                throw ValidationException::withMessages(['offer' => 'همراه‌یار برای این بازهٔ زمانی توافق یا رزرو فعال دیگری دارد.']);
            }

            $offer->update([
                'status' => AccompanimentOffer::STATUS_ACCEPTED,
                'responded_at' => now(),
            ]);

            $accompanimentRequest->offers()
                ->where('id', '!=', $offer->id)
                ->where('status', AccompanimentOffer::STATUS_PENDING)
                ->update([
                    'status' => AccompanimentOffer::STATUS_REJECTED,
                    'responded_at' => now(),
                ]);

            $accompanimentRequest->update([
                'status' => AccompanimentRequest::STATUS_AGREED,
            ]);
        });

        return back()->with('status', 'توافق ثبت شد. اکنون اطلاعات تماس و جزئیات لازم برای هماهنگی در اختیار دو طرف قرار دارد.');
    }

    public function rejectOffer(Request $request, int $requestId, int $offerId): RedirectResponse
    {
        $accompanimentRequest = AccompanimentRequest::query()->findOrFail($requestId);
        abort_unless($accompanimentRequest->user_id === $request->user()->id, 403);

        $offer = $accompanimentRequest->offers()
            ->whereKey($offerId)
            ->where('status', AccompanimentOffer::STATUS_PENDING)
            ->firstOrFail();

        $offer->update([
            'status' => AccompanimentOffer::STATUS_REJECTED,
            'responded_at' => now(),
        ]);

        return back()->with('status', 'اعلام آمادگی موردنظر رد شد.');
    }

    public function cancelRequest(Request $request, int $requestId): RedirectResponse
    {
        DB::transaction(function () use ($request, $requestId): void {
            $accompanimentRequest = AccompanimentRequest::query()
                ->lockForUpdate()
                ->findOrFail($requestId);

            abort_unless($accompanimentRequest->user_id === $request->user()->id, 403);

            if ($accompanimentRequest->status !== AccompanimentRequest::STATUS_OPEN) {
                throw ValidationException::withMessages(['request' => 'فقط درخواست‌های باز و بدون توافق را می‌توان لغو کرد.']);
            }

            $accompanimentRequest->update([
                'status' => AccompanimentRequest::STATUS_CANCELLED,
            ]);

            $accompanimentRequest->offers()
                ->where('status', AccompanimentOffer::STATUS_PENDING)
                ->update([
                    'status' => AccompanimentOffer::STATUS_WITHDRAWN,
                    'responded_at' => now(),
                ]);
        });

        return back()->with('status', 'درخواست همراهی لغو شد.');
    }

    public function completeRequest(Request $request, int $requestId): RedirectResponse
    {
        DB::transaction(function () use ($request, $requestId): void {
            $accompanimentRequest = AccompanimentRequest::query()
                ->with('acceptedOffer')
                ->lockForUpdate()
                ->findOrFail($requestId);

            $acceptedOffer = $accompanimentRequest->acceptedOffer;
            $userId = $request->user()->id;

            abort_unless(
                $accompanimentRequest->user_id === $userId
                    || ($acceptedOffer && $acceptedOffer->helper_id === $userId),
                403
            );

            if ($accompanimentRequest->status !== AccompanimentRequest::STATUS_AGREED || ! $acceptedOffer) {
                throw ValidationException::withMessages(['request' => 'فقط همراهی توافق‌شده را می‌توان تکمیل کرد.']);
            }

            $completionValue = $accompanimentRequest->getRawOriginal('return_at')
                ?: $accompanimentRequest->getRawOriginal('departure_at');
            $completionTime = CarbonImmutable::parse($completionValue, 'Asia/Tehran');

            if ($completionTime->isFuture()) {
                throw ValidationException::withMessages(['request' => 'پس از زمان پایان برنامه‌ریزی‌شده می‌توانید همراهی را تکمیل کنید.']);
            }

            $accompanimentRequest->update([
                'status' => AccompanimentRequest::STATUS_COMPLETED,
            ]);
        });

        return back()->with('status', 'پایان همراهی ثبت شد.');
    }

    private function hasConflictingReservation(
        User $helper,
        AccompanimentRequest $candidate,
        ?int $excludeOfferId = null
    ): bool {
        $candidateStart = CarbonImmutable::parse(
            $candidate->getRawOriginal('departure_at'),
            'Asia/Tehran'
        );
        $candidateEndValue = $candidate->getRawOriginal('return_at');
        $candidateEnd = $candidateEndValue
            ? CarbonImmutable::parse($candidateEndValue, 'Asia/Tehran')
            : $candidateStart;

        $offers = AccompanimentOffer::query()
            ->with('accompanimentRequest')
            ->where('helper_id', $helper->id)
            ->whereIn('status', [
                AccompanimentOffer::STATUS_PENDING,
                AccompanimentOffer::STATUS_ACCEPTED,
            ])
            ->when($excludeOfferId, fn ($query) => $query->where('id', '!=', $excludeOfferId))
            ->get();

        foreach ($offers as $offer) {
            $reservedRequest = $offer->accompanimentRequest;
            if (! $reservedRequest) {
                continue;
            }

            $reservedStart = CarbonImmutable::parse(
                $reservedRequest->getRawOriginal('departure_at'),
                'Asia/Tehran'
            );
            $reservedEndValue = $reservedRequest->getRawOriginal('return_at');
            $reservedEnd = $reservedEndValue
                ? CarbonImmutable::parse($reservedEndValue, 'Asia/Tehran')
                : $reservedStart;

            if ($candidateStart->lessThanOrEqualTo($reservedEnd)
                && $reservedStart->lessThanOrEqualTo($candidateEnd)) {
                return true;
            }
        }

        return false;
    }

    private function isRequestStillEligible(AccompanimentRequest $request): bool
    {
        $departureValue = $request->getRawOriginal('departure_at');
        if (! $departureValue) {
            return false;
        }

        $departure = CarbonImmutable::parse($departureValue, 'Asia/Tehran');
        $expiresAt = $departure->startOfDay()->subDay()->setTime(8, 0);

        return $expiresAt->isFuture();
    }
}
