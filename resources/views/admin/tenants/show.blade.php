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
                        <dd class="font-medium text-gray-900">
                            <div class="flex items-center gap-2">
                                {{ $tenant->email }}
                                @if($tenant->email_verified_at)
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20" title="Email vérifié">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                <button type="button" onclick="document.getElementById('emailModal').classList.remove('hidden')" class="text-primary-600 hover:text-primary-700" title="Modifier l'email">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                            </div>
                            @if($tenant->pending_email)
                                <div class="mt-1 text-sm text-amber-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    En attente de vérification : {{ $tenant->pending_email }}
                                </div>
                            @endif
                        </dd>
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

            <!-- Modules activés -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Modules activés</h2>
                    <button type="button" onclick="document.getElementById('modulesModal').classList.remove('hidden')" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Modifier
                    </button>
                </div>
                @php
                    $allModules = [
                        'clients' => ['name' => 'Clients', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                        'fournisseurs' => ['name' => 'Fournisseurs', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                        'devis' => ['name' => 'Devis', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        'factures' => ['name' => 'Factures', 'icon' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
                        'paiements' => ['name' => 'Paiements', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                        'produits' => ['name' => 'Produits', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        'entrepots' => ['name' => 'Entrepôts', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
                        'stocks' => ['name' => 'Stocks', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                        'bons_commande' => ['name' => 'Bons de commande', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                        'depenses' => ['name' => 'Dépenses', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                        'rapports' => ['name' => 'Rapports', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        'rh' => ['name' => 'Ressources Humaines', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ];
                    $tenantModules = $tenant->settings['modules'] ?? [];
                @endphp
                
                @if(count($tenantModules) > 0)
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($tenantModules as $moduleKey)
                            @if(isset($allModules[$moduleKey]))
                                <div class="flex items-center gap-2 p-2 bg-green-50 rounded-lg">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $allModules[$moduleKey]['icon'] }}"/>
                                    </svg>
                                    <span class="text-sm font-medium text-green-800">{{ $allModules[$moduleKey]['name'] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Tous les modules sont activés</p>
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
<!-- Modal Email -->
<div id="emailModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="document.getElementById('emailModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <form action="{{ route('admin.tenants.update-email', $tenant) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Modifier l'email</h3>
                            <p class="text-sm text-gray-500">Changer l'adresse email de {{ $tenant->name }}</p>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Nouvel email</label>
                        <input type="email" name="email" id="email" value="{{ $tenant->email }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('emailModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modules -->
<div id="modulesModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="document.getElementById('modulesModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.tenants.update-modules', $tenant) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Gérer les modules</h3>
                            <p class="text-sm text-gray-500">Sélectionnez les modules accessibles pour {{ $tenant->name }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @php
                            $modulesList = [
                                'clients' => 'Clients',
                                'fournisseurs' => 'Fournisseurs',
                                'devis' => 'Devis',
                                'factures' => 'Factures',
                                'paiements' => 'Paiements',
                                'produits' => 'Produits',
                                'entrepots' => 'Entrepôts',
                                'stocks' => 'Stocks',
                                'bons_commande' => 'Bons de commande',
                                'depenses' => 'Dépenses',
                                'rapports' => 'Rapports',
                                'rh' => 'Ressources Humaines',
                            ];
                            $currentModules = $tenant->settings['modules'] ?? [];
                        @endphp
                        
                        @foreach($modulesList as $key => $label)
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="modules[]" value="{{ $key }}" 
                                    {{ in_array($key, $currentModules) || empty($currentModules) ? 'checked' : '' }}
                                    class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="font-medium text-gray-900">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4 p-3 bg-amber-50 rounded-lg">
                        <p class="text-sm text-amber-800">
                            <strong>Note :</strong> Si aucun module n'est sélectionné, tous les modules seront accessibles.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modulesModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
