<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'national_code',
    'mobile',
    'password',
    'vehicle_model',
    'vehicle_plate',
])]
#[Hidden([
    'password',
    'remember_token',
    'national_code',
    'mobile',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_COMPANION_SEEKER = 'companion_seeker';

    public const ROLE_COMPANION_HELPER = 'companion_helper';

    public const ACCOUNT_STATUS_PENDING = 'pending';

    public const ACCOUNT_STATUS_APPROVED = 'approved';

    public const ACCOUNT_STATUS_REJECTED = 'rejected';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mobile_verified_at' => 'datetime',
            'account_approved_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN
            && $this->isAccountApproved();
    }

    public function isAccountApproved(): bool
    {
        return $this->account_status === self::ACCOUNT_STATUS_APPROVED;
    }

    public function isAccountPending(): bool
    {
        return $this->account_status === self::ACCOUNT_STATUS_PENDING;
    }

    public function isAccountRejected(): bool
    {
        return $this->account_status === self::ACCOUNT_STATUS_REJECTED;
    }

    public function isCompanionSeeker(): bool
    {
        return $this->isAccountApproved()
            && $this->role === self::ROLE_COMPANION_SEEKER;
    }

    public function isCompanionHelper(): bool
    {
        return $this->isAccountApproved()
            && $this->role === self::ROLE_COMPANION_HELPER;
    }

    public function hasVerifiedMobile(): bool
    {
        return $this->mobile_verified_at !== null;
    }
}
