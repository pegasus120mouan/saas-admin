@extends('layouts.admin')

@section('title', 'Détails du tenant')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.tenants.index') }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $tenant->name }}</h1>
            <p class="text-gray-500">{{ $tenant->email }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Infos tenant -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations</h2>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Nom</dt>
                        <dd class="font-medium text-gray-900">{{ $tenant->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Email</dt>
                        <dd class="font-medium text-gray-900">{{ $tenant->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Téléphone</dt>
                        <dd class="font-medium text-gray-900">{{ $tenant->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Inscrit le</dt>
                        <dd class="font-medium text-gray-900">{{ $tenant->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-sm text-gray-500">Adresse</dt>
                        <dd class="font-medium text-gray-900">{{ $tenant->address ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Historique des abonnements -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Historique des abonnements</h2>
                @if($tenant->subscriptions->count() > 0)
                    <div class="space-y-3">
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
                        @foreach($tenant->subscriptions as $subscription)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $subscription->plan->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $subscription->current_period_start->format('d/m/Y') }} - {{ $subscription->current_period_end->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$subscription->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$subscription->status] ?? $subscription->status }}
                                    </span>
                                    <p class="text-sm text-gray-500 mt-1">{{ $subscription->billing_cycle === 'yearly' ? 'Annuel' : 'Mensuel' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun abonnement</p>
                @endif
            </div>

            <!-- Factures SaaS -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Factures</h2>
                @if($tenant->saasInvoices->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase py-2">N°</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase py-2">Date</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase py-2">Montant</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase py-2">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($tenant->saasInvoices as $invoice)
                                <tr>
                                    <td class="py-3 text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</td>
                                    <td class="py-3 text-sm text-gray-500">{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                    <td class="py-3 text-sm text-gray-900">{{ number_format($invoice->total, 2) }} €</td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $invoice->status === 'paid' ? 'Payée' : 'En attente' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-center py-4">Aucune facture</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Abonnement actuel -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Abonnement actuel</h2>
                @php $activeSub = $tenant->activeSubscription; @endphp
                @if($activeSub)
                    <div class="space-y-4">
                        <div class="p-4 bg-primary-50 rounded-lg">
                            <p class="font-bold text-primary-900 text-lg">{{ $activeSub->plan->name ?? 'N/A' }}</p>
                            <p class="text-primary-700">{{ number_format($activeSub->current_price, 0) }} €/{{ $activeSub->billing_cycle === 'yearly' ? 'an' : 'mois' }}</p>
                        </div>
                        <div class="text-sm space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Statut</span>
                                <span class="font-medium">{{ $statusLabels[$activeSub->status] ?? $activeSub->status }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Expire le</span>
                                <span class="font-medium">{{ $activeSub->current_period_end->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Jours restants</span>
                                <span class="font-medium">{{ $activeSub->daysUntilExpiry() }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200 space-y-2">
                            @if($activeSub->isActive())
                                <form method="POST" action="{{ route('admin.tenants.suspend', $tenant) }}" onsubmit="return confirm('Suspendre ce tenant ?')">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-red-100 text-red-700 font-medium rounded-lg hover:bg-red-200">
                                        Suspendre
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.tenants.reactivate', $tenant) }}">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-100 text-green-700 font-medium rounded-lg hover:bg-green-200">
                                        Réactiver
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun abonnement actif</p>
                @endif
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-2">
                    <a href="#" class="block w-full px-4 py-2 text-center bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">
                        Envoyer un email
                    </a>
                    <a href="#" class="block w-full px-4 py-2 text-center bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">
                        Créer une facture
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
