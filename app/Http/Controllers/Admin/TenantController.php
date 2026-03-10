<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with(['activeSubscription.plan']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereHas('activeSubscription', function ($q) {
                    $q->where('status', 'active');
                });
            } elseif ($request->status === 'trialing') {
                $query->whereHas('activeSubscription', function ($q) {
                    $q->where('status', 'trialing');
                });
            } elseif ($request->status === 'expired') {
                $query->whereDoesntHave('activeSubscription');
            }
        }

        if ($request->filled('plan_id')) {
            $query->whereHas('activeSubscription', function ($q) use ($request) {
                $q->where('plan_id', $request->plan_id);
            });
        }

        $tenants = $query->orderByDesc('created_at')->paginate(20);
        $plans = Plan::active()->ordered()->get();

        return view('admin.tenants.index', compact('tenants', 'plans'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['subscriptions.plan', 'saasInvoices']);

        return view('admin.tenants.show', compact('tenant'));
    }

    public function updateSubscription(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'status' => 'required|in:trialing,active,past_due,canceled,expired',
        ]);

        $subscription = $tenant->activeSubscription;

        if ($subscription) {
            $subscription->update($validated);
        } else {
            $plan = Plan::findOrFail($validated['plan_id']);
            
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $validated['plan_id'],
                'billing_cycle' => $validated['billing_cycle'],
                'status' => $validated['status'],
                'trial_ends_at' => $validated['status'] === 'trialing' ? now()->addDays($plan->trial_days) : null,
                'current_period_start' => now(),
                'current_period_end' => $validated['billing_cycle'] === 'yearly' ? now()->addYear() : now()->addMonth(),
            ]);
        }

        return back()->with('success', 'Abonnement mis à jour avec succès.');
    }

    public function suspend(Tenant $tenant)
    {
        $subscription = $tenant->activeSubscription;
        
        if ($subscription) {
            $subscription->update(['status' => 'canceled']);
        }

        return back()->with('success', 'Tenant suspendu avec succès.');
    }

    public function reactivate(Tenant $tenant)
    {
        $subscription = $tenant->subscription;
        
        if ($subscription && $subscription->status === 'canceled') {
            $subscription->renew();
        }

        return back()->with('success', 'Tenant réactivé avec succès.');
    }
}
