<?php

namespace App\Modules\Accompaniment\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccompanimentOffer extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_WITHDRAWN = 'withdrawn';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'accompaniment_request_id',
        'helper_id',
        'reserved_passenger_count',
        'status',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'reserved_passenger_count' => 'integer',
            'responded_at' => 'datetime',
        ];
    }

    public function accompanimentRequest(): BelongsTo
    {
        return $this->belongsTo(AccompanimentRequest::class);
    }

    public function helper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'helper_id');
    }
}
