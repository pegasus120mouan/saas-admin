@extends('layouts.admin')

@section('title', 'Plans')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Plans d'abonnement</h1>
            <p class="text-gray-500">Gérez vos formules de pricing</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau plan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden {{ $plan->is_popular ? 'ring-2 ring-primary-500' : '' }}">
                @if($plan->is_popular)
                    <div class="bg-primary-500 text-white text-center py-1 text-sm font-medium">
                        Populaire
                    </div>
                @endif
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $plan->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <span class="text-3xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 0, ',', ' ') }}</span>
                        <span class="text-gray-500">{{ $plan->currency ?? 'XOF' }}/mois</span>
                        <p class="text-sm text-gray-500">ou {{ number_format($plan->price_yearly, 0, ',', ' ') }} {{ $plan->currency ?? 'XOF' }}/an</p>
                    </div>

                    <div class="space-y-2 mb-6 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $plan->max_users }} utilisateur(s)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $plan->max_customers }} clients</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $plan->max_invoices_per_month }} factures/mois</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-3">{{ $plan->active_subscriptions_count }} abonnement(s) actif(s)</p>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 text-sm">
                                Modifier
                            </a>
                            @if($plan->active_subscriptions_count === 0)
                                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Supprimer ce plan ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg text-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
