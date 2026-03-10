<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['tenant', 'plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        if ($request->filled('billing_cycle')) {
            $query->where('billing_cycle', $request->billing_cycle);
        }

        $subscriptions = $query->orderByDesc('created_at')->paginate(20);
        $plans = Plan::active()->ordered()->get();

        $stats = [
            'active' => Subscription::where('status', 'active')->count(),
            'trialing' => Subscription::where('status', 'trialing')->count(),
            'past_due' => Subscription::where('status', 'past_due')->count(),
            'canceled' => Subscription::where('status', 'canceled')->count(),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'plans', 'stats'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['tenant', 'plan', 'invoices']);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function cancel(Subscription $subscription, Request $request)
    {
        $subscription->cancel($request->input('reason'));

        return back()->with('success', 'Abonnement annulé avec succès.');
    }

    public function renew(Subscription $subscription)
    {
        $subscription->renew();

        return back()->with('success', 'Abonnement renouvelé avec succès.');
    }

    public function changePlan(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $subscription->update(['plan_id' => $validated['plan_id']]);

        return back()->with('success', 'Plan modifié avec succès.');
    }
}
