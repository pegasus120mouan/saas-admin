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
    ];

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
