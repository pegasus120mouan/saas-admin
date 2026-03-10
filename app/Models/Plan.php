<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'currency',
        'trial_days',
        'max_users',
        'max_customers',
        'max_invoices_per_month',
        'max_products',
        'max_quotes_per_month',
        'max_storage_mb',
        'feature_quotes',
        'feature_expenses',
        'feature_reports',
        'feature_api_access',
        'feature_multi_currency',
        'feature_custom_templates',
        'feature_reminders',
        'feature_stock_management',
        'feature_purchase_orders',
        'feature_priority_support',
        'feature_white_label',
        'features_list',
        'sort_order',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'features_list' => 'array',
        'feature_quotes' => 'boolean',
        'feature_expenses' => 'boolean',
        'feature_reports' => 'boolean',
        'feature_api_access' => 'boolean',
        'feature_multi_currency' => 'boolean',
        'feature_custom_templates' => 'boolean',
        'feature_reminders' => 'boolean',
        'feature_stock_management' => 'boolean',
        'feature_purchase_orders' => 'boolean',
        'feature_priority_support' => 'boolean',
        'feature_white_label' => 'boolean',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)->whereIn('status', ['active', 'trialing']);
    }

    public function hasFeature(string $feature): bool
    {
        $column = "feature_{$feature}";
        return $this->{$column} ?? false;
    }

    public function getYearlySavingsAttribute(): float
    {
        return ($this->price_monthly * 12) - $this->price_yearly;
    }

    public function getYearlySavingsPercentAttribute(): int
    {
        if ($this->price_monthly <= 0) return 0;
        $yearlyIfMonthly = $this->price_monthly * 12;
        return round((($yearlyIfMonthly - $this->price_yearly) / $yearlyIfMonthly) * 100);
    }

    public function getMonthlyEquivalentAttribute(): float
    {
        return round($this->price_yearly / 12, 2);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price_monthly');
    }

    public static function getFree(): ?self
    {
        return static::where('price_monthly', 0)->where('is_active', true)->first();
    }
}
