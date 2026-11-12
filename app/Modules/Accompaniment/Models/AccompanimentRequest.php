<?php

namespace App\Modules\Accompaniment\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccompanimentRequest extends Model
{
    public const TYPE_ONE_WAY = 'one_way';
    public const TYPE_ROUND_TRIP = 'round_trip';

    public const STATUS_OPEN = 'open';
    public const STATUS_AGREED = 'agreed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

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

    public function isRoundTrip(): bool
    {
        return $this->type === self::TYPE_ROUND_TRIP;
    }
}
