@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500">Vue d'ensemble de votre activité SaaS</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">MRR</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['mrr'], 0, ',', ' ') }} €</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">ARR: {{ number_format($stats['arr'], 0, ',', ' ') }} €</p>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Tenants actifs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_subscriptions'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">{{ $stats['trialing_subscriptions'] }} en essai</p>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Nouveaux ce mois</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $newTenantsThisMonth }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Total: {{ $stats['total_tenants'] }} tenants</p>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Churn Rate</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $churnRate }}%</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Ce mois-ci</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenus mensuels</h3>
            <canvas id="revenueChart" height="100"></canvas>
        </div>

        <!-- Subscriptions by Plan -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Abonnements par plan</h3>
            <div class="space-y-3">
                @foreach($subscriptionsByPlan as $plan)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $plan->name }}</p>
                            <p class="text-sm text-gray-500">{{ $plan->price_monthly }} €/mois</p>
                        </div>
                        <span class="text-lg font-bold text-primary-600">{{ $plan->active_subscriptions_count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Expiring Soon -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Expirent bientôt</h3>
            @if($expiringSoon->count() > 0)
                <div class="space-y-3">
                    @foreach($expiringSoon as $sub)
                        <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $sub->tenant->name }}</p>
                                <p class="text-sm text-gray-500">{{ $sub->plan->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-amber-600">{{ $sub->current_period_end->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $sub->daysUntilExpiry() }} jours</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucun abonnement n'expire bientôt</p>
            @endif
        </div>

        <!-- Recent Invoices -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Dernières factures</h3>
            @if($recentInvoices->count() > 0)
                <div class="space-y-3">
                    @foreach($recentInvoices as $invoice)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                                <p class="text-sm text-gray-500">{{ $invoice->tenant->name ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">{{ number_format($invoice->total, 0, ',', ' ') }} €</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $invoice->status === 'paid' ? 'Payée' : 'En attente' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune facture récente</p>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json(array_column($monthlyRevenue, 'month')),
        datasets: [{
            label: 'Revenus (€)',
            data: @json(array_column($monthlyRevenue, 'revenue')),
            backgroundColor: 'rgba(22, 163, 74, 0.8)',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
