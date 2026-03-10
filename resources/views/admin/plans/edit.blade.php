@extends('layouts.admin')

@section('title', 'Modifier le plan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.plans.index') }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Modifier : {{ $plan->name }}</h1>
    </div>

    <form method="POST" action="{{ route('admin.plans.update', $plan) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Infos générales -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations générales</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du plan *</label>
                            <input type="text" name="name" value="{{ old('name', $plan->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">{{ old('description', $plan->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tarification -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tarification</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix mensuel (€) *</label>
                            <input type="number" step="0.01" name="price_monthly" value="{{ old('price_monthly', $plan->price_monthly) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix annuel (€) *</label>
                            <input type="number" step="0.01" name="price_yearly" value="{{ old('price_yearly', $plan->price_yearly) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Devise</label>
                            <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                <option value="EUR" {{ $plan->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ $plan->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="XOF" {{ $plan->currency == 'XOF' ? 'selected' : '' }}>XOF</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jours d'essai</label>
                            <input type="number" name="trial_days" value="{{ old('trial_days', $plan->trial_days) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                </div>

                <!-- Limites -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Limites</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Utilisateurs max</label>
                            <input type="number" name="max_users" value="{{ old('max_users', $plan->max_users) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Clients max</label>
                            <input type="number" name="max_customers" value="{{ old('max_customers', $plan->max_customers) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Factures/mois</label>
                            <input type="number" name="max_invoices_per_month" value="{{ old('max_invoices_per_month', $plan->max_invoices_per_month) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produits max</label>
                            <input type="number" name="max_products" value="{{ old('max_products', $plan->max_products) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Devis/mois</label>
                            <input type="number" name="max_quotes_per_month" value="{{ old('max_quotes_per_month', $plan->max_quotes_per_month) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stockage (MB)</label>
                            <input type="number" name="max_storage_mb" value="{{ old('max_storage_mb', $plan->max_storage_mb) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Fonctionnalités</h2>
                    <div class="space-y-3">
                        @foreach([
                            'feature_quotes' => 'Devis',
                            'feature_expenses' => 'Dépenses',
                            'feature_reports' => 'Rapports avancés',
                            'feature_api_access' => 'Accès API',
                            'feature_multi_currency' => 'Multi-devises',
                            'feature_custom_templates' => 'Templates personnalisés',
                            'feature_reminders' => 'Relances automatiques',
                            'feature_stock_management' => 'Gestion de stock',
                            'feature_purchase_orders' => 'Bons de commande',
                            'feature_priority_support' => 'Support prioritaire',
                            'feature_white_label' => 'White label',
                        ] as $key => $label)
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="{{ $key }}" value="1" {{ old($key, $plan->$key) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Options</h2>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $plan->is_popular) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">Marquer comme populaire</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">Plan actif</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.plans.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
