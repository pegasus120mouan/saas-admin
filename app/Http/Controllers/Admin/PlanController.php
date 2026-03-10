<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('activeSubscriptions')
            ->ordered()
            ->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'trial_days' => 'required|integer|min:0',
            'max_users' => 'required|integer|min:1',
            'max_customers' => 'required|integer|min:1',
            'max_invoices_per_month' => 'required|integer|min:1',
            'max_products' => 'required|integer|min:1',
            'max_quotes_per_month' => 'required|integer|min:1',
            'max_storage_mb' => 'required|integer|min:1',
            'features_list' => 'nullable|array',
            'sort_order' => 'required|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['feature_quotes'] = $request->boolean('feature_quotes');
        $validated['feature_expenses'] = $request->boolean('feature_expenses');
        $validated['feature_reports'] = $request->boolean('feature_reports');
        $validated['feature_api_access'] = $request->boolean('feature_api_access');
        $validated['feature_multi_currency'] = $request->boolean('feature_multi_currency');
        $validated['feature_custom_templates'] = $request->boolean('feature_custom_templates');
        $validated['feature_reminders'] = $request->boolean('feature_reminders');
        $validated['feature_stock_management'] = $request->boolean('feature_stock_management');
        $validated['feature_purchase_orders'] = $request->boolean('feature_purchase_orders');
        $validated['feature_priority_support'] = $request->boolean('feature_priority_support');
        $validated['feature_white_label'] = $request->boolean('feature_white_label');
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active', true);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan créé avec succès.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'trial_days' => 'required|integer|min:0',
            'max_users' => 'required|integer|min:1',
            'max_customers' => 'required|integer|min:1',
            'max_invoices_per_month' => 'required|integer|min:1',
            'max_products' => 'required|integer|min:1',
            'max_quotes_per_month' => 'required|integer|min:1',
            'max_storage_mb' => 'required|integer|min:1',
            'features_list' => 'nullable|array',
            'sort_order' => 'required|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['feature_quotes'] = $request->boolean('feature_quotes');
        $validated['feature_expenses'] = $request->boolean('feature_expenses');
        $validated['feature_reports'] = $request->boolean('feature_reports');
        $validated['feature_api_access'] = $request->boolean('feature_api_access');
        $validated['feature_multi_currency'] = $request->boolean('feature_multi_currency');
        $validated['feature_custom_templates'] = $request->boolean('feature_custom_templates');
        $validated['feature_reminders'] = $request->boolean('feature_reminders');
        $validated['feature_stock_management'] = $request->boolean('feature_stock_management');
        $validated['feature_purchase_orders'] = $request->boolean('feature_purchase_orders');
        $validated['feature_priority_support'] = $request->boolean('feature_priority_support');
        $validated['feature_white_label'] = $request->boolean('feature_white_label');
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active');

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan mis à jour avec succès.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->activeSubscriptions()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un plan avec des abonnements actifs.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan supprimé avec succès.');
    }
}
