<?php

namespace App\Modules\Accompaniment\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AccompanimentRequest extends Model
{
    public const TYPE_ONE_WAY = 'one_way';
    public const TYPE_ROUND_TRIP = 'round_trip';

    public const STATUS_OPEN = 'open';
    public const STATUS_AGREED = 'agreed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'origin_city',
        'origin_location',
        'destination_city',
        'destination_location',
        'type',
        'departure_at',
        'return_at',
        'passenger_count',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_at' => 'datetime',
            'return_at' => 'datetime',
            'passenger_count' => 'integer',
        ];
    }

    public function companionSeeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(AccompanimentOffer::class);
    }

    public function acceptedOffer(): HasOne
    {
        return $this->hasOne(AccompanimentOffer::class)->where('status', AccompanimentOffer::STATUS_ACCEPTED);
    }

    public function isRoundTrip(): bool
    {
        return $this->type === self::TYPE_ROUND_TRIP;
    }
}
