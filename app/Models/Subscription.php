<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'billing_cycle',
        'status',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'canceled_at',
        'cancellation_reason',
        'payment_method',
        'stripe_subscription_id',
        'stripe_customer_id',
        'metadata',
    ];

    protected $casts = [
        'trial_ends_at' => 'date',
        'current_period_start' => 'date',
        'current_period_end' => 'date',
        'canceled_at' => 'date',
        'metadata' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(SaasInvoice::class);
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['active', 'trialing']);
    }

    public function isTrialing(): bool
    {
        return $this->status === 'trialing' && $this->trial_ends_at?->isFuture();
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->current_period_end->isPast();
    }

    public function daysUntilExpiry(): int
    {
        return max(0, now()->diffInDays($this->current_period_end, false));
    }

    public function trialDaysRemaining(): int
    {
        if (!$this->isTrialing()) return 0;
        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'canceled',
            'canceled_at' => now(),
            'cancellation_reason' => $reason,
        ]);
    }

    public function renew(): void
    {
        $months = $this->billing_cycle === 'yearly' ? 12 : 1;
        
        $this->update([
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonths($months),
            'canceled_at' => null,
            'cancellation_reason' => null,
        ]);
    }

    public function getCurrentPriceAttribute(): float
    {
        return $this->billing_cycle === 'yearly' 
            ? $this->plan->price_yearly 
            : $this->plan->price_monthly;
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'trialing']);
    }

    public function scopeTrialing($query)
    {
        return $query->where('status', 'trialing');
    }

    public function scopeExpiringSoon($query, int $days = 7)
    {
        return $query->active()
            ->where('current_period_end', '<=', now()->addDays($days));
    }

    public function scopePastDue($query)
    {
        return $query->where('status', 'past_due');
    }
}
