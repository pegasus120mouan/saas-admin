<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'email_verification_token',
        'email_verified_at',
        'pending_email',
        'phone',
        'address',
        'logo',
        'currency',
        'timezone',
        'locale',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'email_verified_at' => 'datetime',
    ];

    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function generateEmailVerificationToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->update(['email_verification_token' => $token]);
        return $token;
    }

    public function verifyEmail(): void
    {
        $this->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'tenant_id', 'id')->latest();
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'tenant_id', 'id')
            ->whereIn('status', ['active', 'trialing'])
            ->latest();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'tenant_id', 'id');
    }

    public function saasInvoices(): HasMany
    {
        return $this->hasMany(SaasInvoice::class, 'tenant_id', 'id');
    }

    public function getPlanAttribute(): ?Plan
    {
        return $this->activeSubscription?->plan;
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription !== null;
    }

    public function isOnTrial(): bool
    {
        return $this->activeSubscription?->isTrialing() ?? false;
    }

    public function hasFeature(string $feature): bool
    {
        return $this->plan?->hasFeature($feature) ?? false;
    }
}
