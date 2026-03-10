@extends('layouts.admin')

@section('title', 'Abonnements')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Abonnements</h1>
        <p class="text-gray-500">Gérez les abonnements de vos clients</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Actifs</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-500">En essai</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['trialing'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Impayés</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['past_due'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Annulés</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['canceled'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                <option value="trialing" {{ request('status') == 'trialing' ? 'selected' : '' }}>En essai</option>
                <option value="past_due" {{ request('status') == 'past_due' ? 'selected' : '' }}>Impayé</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Annulé</option>
            </select>
            <select name="plan_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                <option value="">Tous les plans</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                @endforeach
            </select>
            <select name="billing_cycle" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                <option value="">Tous les cycles</option>
                <option value="monthly" {{ request('billing_cycle') == 'monthly' ? 'selected' : '' }}>Mensuel</option>
                <option value="yearly" {{ request('billing_cycle') == 'yearly' ? 'selected' : '' }}>Annuel</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">Filtrer</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cycle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php
                    $statusColors = [
                        'active' => 'bg-green-100 text-green-800',
                        'trialing' => 'bg-blue-100 text-blue-800',
                        'past_due' => 'bg-amber-100 text-amber-800',
                        'canceled' => 'bg-red-100 text-red-800',
                        'expired' => 'bg-gray-100 text-gray-800',
                    ];
                    $statusLabels = [
                        'active' => 'Actif',
                        'trialing' => 'Essai',
                        'past_due' => 'Impayé',
                        'canceled' => 'Annulé',
                        'expired' => 'Expiré',
                    ];
                @endphp
                @forelse($subscriptions as $subscription)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-primary-700">{{ strtoupper(substr($subscription->tenant->name ?? 'N/A', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $subscription->tenant->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $subscription->tenant->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $subscription->plan->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ number_format($subscription->current_price, 0) }}€</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $subscription->billing_cycle === 'yearly' ? 'Annuel' : 'Mensuel' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$subscription->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$subscription->status] ?? $subscription->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <p>{{ $subscription->current_period_start->format('d/m/Y') }}</p>
                            <p class="text-xs">→ {{ $subscription->current_period_end->format('d/m/Y') }}</p>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if($subscription->status === 'active' || $subscription->status === 'trialing')
                                <form method="POST" action="{{ route('admin.subscriptions.cancel', $subscription) }}" class="inline" onsubmit="return confirm('Annuler cet abonnement ?')">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-500 text-sm font-medium">Annuler</button>
                                </form>
                            @endif
                            @if($subscription->status === 'canceled')
                                <form method="POST" action="{{ route('admin.subscriptions.renew', $subscription) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-500 text-sm font-medium">Réactiver</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Aucun abonnement trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($subscriptions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
