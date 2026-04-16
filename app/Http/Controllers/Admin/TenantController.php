<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TenantEmailVerification;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function updateModules(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'modules' => 'nullable|array',
            'modules.*' => 'string|in:clients,fournisseurs,devis,factures,paiements,produits,entrepots,stocks,bons_commande,depenses,rapports,rh',
        ]);

        $settings = $tenant->settings ?? [];
        $settings['modules'] = $validated['modules'] ?? [];
        
        $tenant->update(['settings' => $settings]);

        return back()->with('success', 'Modules mis à jour avec succès.');
    }

    public function updateEmail(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:tenants,email,' . $tenant->id . '|unique:tenants,pending_email,' . $tenant->id,
        ]);

        $newEmail = $validated['email'];

        if ($newEmail === $tenant->email) {
            return back()->with('info', 'L\'email est identique à l\'actuel.');
        }

        $token = bin2hex(random_bytes(32));
        
        $tenant->update([
            'pending_email' => $newEmail,
            'email_verification_token' => $token,
            'email_verified_at' => null,
        ]);

        $verificationUrl = route('admin.tenants.verify-email', [
            'tenant' => $tenant->id,
            'token' => $token,
        ]);

        try {
            Mail::to($newEmail)->send(new TenantEmailVerification($tenant, $verificationUrl));
            
            return back()->with('success', 'Un email de vérification a été envoyé à ' . $newEmail);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }

    public function verifyEmail(Tenant $tenant, string $token)
    {
        if ($tenant->email_verification_token !== $token) {
            return redirect()->route('admin.tenants.show', $tenant)
                ->with('error', 'Lien de vérification invalide.');
        }

        if (!$tenant->pending_email) {
            return redirect()->route('admin.tenants.show', $tenant)
                ->with('error', 'Aucun email en attente de vérification.');
        }

        $oldEmail = $tenant->email;
        $newEmail = $tenant->pending_email;

        $tenant->update([
            'email' => $newEmail,
            'pending_email' => null,
            'email_verification_token' => null,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.tenants.show', $tenant)
            ->with('success', 'Email vérifié et mis à jour avec succès ! Ancien: ' . $oldEmail . ' → Nouveau: ' . $newEmail);
    }
}
