<?php

namespace App\Console\Commands;

use App\Modules\Accompaniment\Models\AccompanimentOffer;
use App\Modules\Accompaniment\Models\AccompanimentRequest;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class ExpireOpenAccompanimentRequests extends Command
{
    protected $signature = 'accompaniment-requests:expire';

    protected $description = 'Expire open accompaniment requests after their Tehran-time deadline';

    public function handle(): int
    {
        $now = CarbonImmutable::now('Asia/Tehran');
        $expiredCount = 0;

        AccompanimentRequest::query()
            ->where('status', AccompanimentRequest::STATUS_OPEN)
            ->orderBy('id')
            ->chunkById(100, function ($requests) use ($now, &$expiredCount): void {
                foreach ($requests as $request) {
                    $departureValue = $request->getRawOriginal('departure_at');
                    if (! $departureValue) {
                        continue;
                    }

                    $departure = CarbonImmutable::parse($departureValue, 'Asia/Tehran');
                    $expiresAt = $departure->startOfDay()->subDay()->setTime(8, 0);

                    if ($expiresAt->greaterThan($now)) {
                        continue;
                    }

                    $request->update(['status' => AccompanimentRequest::STATUS_EXPIRED]);
                    $request->offers()
                        ->where('status', AccompanimentOffer::STATUS_PENDING)
                        ->update([
                            'status' => AccompanimentOffer::STATUS_EXPIRED,
                            'responded_at' => $now,
                        ]);
                    $expiredCount++;
                }
            });

        $this->info("Expired {$expiredCount} accompaniment request(s).");

        return self::SUCCESS;
    }
}
