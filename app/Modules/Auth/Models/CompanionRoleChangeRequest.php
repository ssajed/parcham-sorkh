<?php

namespace App\Modules\Auth\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanionRoleChangeRequest extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    // Review state and reviewer identity are intentionally not mass assignable.
    protected $fillable = [
        'requested_seeker_access',
        'requested_helper_access',
    ];

    protected function casts(): array
    {
        return [
            'requested_seeker_access' => 'boolean',
            'requested_helper_access' => 'boolean',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
