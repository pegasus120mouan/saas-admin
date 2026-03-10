@extends('layouts.admin')

@section('title', 'Tenants')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tenants</h1>
            <p class="text-gray-500">Gérez vos clients et leurs abonnements</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 w-64">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                <option value="trialing" {{ request('status') == 'trialing' ? 'selected' : '' }}>En essai</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expiré</option>
            </select>
            <select name="plan_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                <option value="">Tous les plans</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                @endforeach
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscrit le</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($tenants as $tenant)
                    @php
                        $sub = $tenant->activeSubscription;
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
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-primary-700">{{ strtoupper(substr($tenant->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $tenant->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $tenant->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $sub?->plan?->name ?? 'Aucun' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($sub)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$sub->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$sub->status] ?? $sub->status }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Sans abonnement
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $sub?->current_period_end?->format('d/m/Y') ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $tenant->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.tenants.show', $tenant) }}" class="text-primary-600 hover:text-primary-500 font-medium text-sm">
                                Voir détails
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Aucun tenant trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($tenants->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
