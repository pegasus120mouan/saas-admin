<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\SaasInvoice;
use App\Models\Subscription;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques globales
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_subscriptions' => Subscription::active()->count(),
            'trialing_subscriptions' => Subscription::trialing()->count(),
            'mrr' => $this->calculateMRR(),
            'arr' => $this->calculateMRR() * 12,
            'total_revenue' => SaasInvoice::paid()->sum('total'),
            'pending_revenue' => SaasInvoice::unpaid()->sum('total'),
        ];

        // Abonnements par plan
        $subscriptionsByPlan = Plan::withCount(['activeSubscriptions'])
            ->active()
            ->ordered()
            ->get();

        // Revenus des 12 derniers mois
        $monthlyRevenue = $this->getMonthlyRevenue();

        // Nouveaux clients ce mois
        $newTenantsThisMonth = Tenant::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Abonnements expirant bientôt
        $expiringSoon = Subscription::with(['tenant', 'plan'])
            ->expiringSoon(7)
            ->orderBy('current_period_end')
            ->limit(5)
            ->get();

        // Dernières factures
        $recentInvoices = SaasInvoice::with('tenant')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Churn rate (annulations ce mois / actifs début de mois)
        $churnRate = $this->calculateChurnRate();

        return view('admin.dashboard', compact(
            'stats',
            'subscriptionsByPlan',
            'monthlyRevenue',
            'newTenantsThisMonth',
            'expiringSoon',
            'recentInvoices',
            'churnRate'
        ));
    }

    private function calculateMRR(): float
    {
        $monthlyRevenue = Subscription::active()
            ->where('billing_cycle', 'monthly')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->sum('plans.price_monthly');

        $yearlyRevenue = Subscription::active()
            ->where('billing_cycle', 'yearly')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->sum('plans.price_yearly');

        return $monthlyRevenue + ($yearlyRevenue / 12);
    }

    private function getMonthlyRevenue(): array
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = SaasInvoice::paid()
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('total');

            $months[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ];
        }
        return $months;
    }

    private function calculateChurnRate(): float
    {
        $startOfMonth = now()->startOfMonth();
        
        $activeAtStart = Subscription::where('created_at', '<', $startOfMonth)
            ->whereIn('status', ['active', 'trialing'])
            ->count();

        if ($activeAtStart === 0) return 0;

        $canceledThisMonth = Subscription::whereMonth('canceled_at', now()->month)
            ->whereYear('canceled_at', now()->year)
            ->count();

        return round(($canceledThisMonth / $activeAtStart) * 100, 2);
    }
}
