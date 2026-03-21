@extends('layouts.admin')

@section('title', 'Demande de démo - ' . $demoRequest->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.demo-requests.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux demandes
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Demande de {{ $demoRequest->name }}</h1>
            <p class="text-gray-500">Reçue le {{ $demoRequest->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div>
            @switch($demoRequest->status)
                @case('pending')
                    <span class="px-3 py-1.5 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full">En attente</span>
                    @break
                @case('contacted')
                    <span class="px-3 py-1.5 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">Contacté</span>
                    @break
                @case('scheduled')
                    <span class="px-3 py-1.5 text-sm font-medium bg-purple-100 text-purple-800 rounded-full">Planifié</span>
                    @break
                @case('completed')
                    <span class="px-3 py-1.5 text-sm font-medium bg-green-100 text-green-800 rounded-full">Terminé</span>
                    @break
                @case('cancelled')
                    <span class="px-3 py-1.5 text-sm font-medium bg-red-100 text-red-800 rounded-full">Annulé</span>
                    @break
            @endswitch
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations du contact -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations du contact</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Nom complet</p>
                        <p class="font-medium text-gray-900">{{ $demoRequest->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900 flex items-center gap-2">
                            <a href="mailto:{{ $demoRequest->email }}" class="text-primary-600 hover:underline">{{ $demoRequest->email }}</a>
                            @if($demoRequest->email_verified_at)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Vérifié
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Non vérifié
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Téléphone</p>
                        <p class="font-medium text-gray-900">
                            <a href="tel:{{ $demoRequest->phone }}" class="text-primary-600 hover:underline">{{ $demoRequest->phone }}</a>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Entreprise</p>
                        <p class="font-medium text-gray-900">{{ $demoRequest->company }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nombre d'employés</p>
                        <p class="font-medium text-gray-900">{{ $demoRequest->employees ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Modules demandés</h2>
                @if($demoRequest->modules && count($demoRequest->modules) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($demoRequest->modules as $module)
                            <span class="px-3 py-1.5 bg-primary-100 text-primary-800 rounded-lg font-medium">{{ ucfirst($module) }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Aucun module spécifié</p>
                @endif
            </div>

            @if($demoRequest->notes)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $demoRequest->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="space-y-6">
            <!-- Créer un compte -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Créer un compte</h2>
                @if($demoRequest->isProvisioned())
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-2 text-green-800 font-medium mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Compte créé
                        </div>
                        <p class="text-sm text-green-700">
                            Tenant ID: {{ $demoRequest->tenant_id }}<br>
                            Créé le: {{ $demoRequest->provisioned_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>
                @else
                    <p class="text-sm text-gray-600 mb-4">
                        Créer un compte Business Suite pour ce prospect avec un essai gratuit de 14 jours.
                    </p>
                    <div x-data="{ showModal: false }">
                        <button @click="showModal = true" type="button" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Créer le compte
                        </button>

                        <!-- Modal de confirmation -->
                        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <!-- Overlay -->
                                <div x-show="showModal" 
                                     x-transition:enter="ease-out duration-300" 
                                     x-transition:enter-start="opacity-0" 
                                     x-transition:enter-end="opacity-100" 
                                     x-transition:leave="ease-in duration-200" 
                                     x-transition:leave-start="opacity-100" 
                                     x-transition:leave-end="opacity-0" 
                                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                                     @click="showModal = false"></div>

                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                <!-- Modal panel -->
                                <div x-show="showModal" 
                                     x-transition:enter="ease-out duration-300" 
                                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                     x-transition:leave="ease-in duration-200" 
                                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                     class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                    
                                    <div class="bg-white px-6 pt-6 pb-4">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
                                                    Créer un compte Business Suite
                                                </h3>
                                                <p class="mt-2 text-sm text-gray-500">
                                                    Vous êtes sur le point de créer un compte pour ce prospect. Cette action va :
                                                </p>
                                                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                                                    <li class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Créer un nouveau tenant dans Business Suite
                                                    </li>
                                                    <li class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Activer un essai gratuit de 14 jours
                                                    </li>
                                                    <li class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Envoyer les identifiants par email
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Informations du prospect -->
                                        <div class="mt-5 p-4 bg-gray-50 rounded-xl">
                                            <div class="grid grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <p class="text-gray-500">Nom</p>
                                                    <p class="font-medium text-gray-900">{{ $demoRequest->name }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">Email</p>
                                                    <p class="font-medium text-gray-900">{{ $demoRequest->email }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">Entreprise</p>
                                                    <p class="font-medium text-gray-900">{{ $demoRequest->company }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">Téléphone</p>
                                                    <p class="font-medium text-gray-900">{{ $demoRequest->phone }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                        <button @click="showModal = false" type="button" class="w-full sm:w-auto px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                            Annuler
                                        </button>
                                        <form action="{{ route('admin.demo-requests.provision', $demoRequest) }}" method="POST" class="w-full sm:w-auto">
                                            @csrf
                                            <button type="submit" class="w-full px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Confirmer la création
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Mettre à jour</h2>
                <form action="{{ route('admin.demo-requests.update', $demoRequest) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="pending" {{ $demoRequest->status === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="contacted" {{ $demoRequest->status === 'contacted' ? 'selected' : '' }}>Contacté</option>
                            <option value="scheduled" {{ $demoRequest->status === 'scheduled' ? 'selected' : '' }}>Planifié</option>
                            <option value="completed" {{ $demoRequest->status === 'completed' ? 'selected' : '' }}>Terminé</option>
                            <option value="cancelled" {{ $demoRequest->status === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de démo planifiée</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ $demoRequest->scheduled_at?->format('Y-m-d\TH:i') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigné à</label>
                        <input type="text" name="assigned_to" value="{{ $demoRequest->assigned_to }}" placeholder="Nom du commercial" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="4" placeholder="Ajouter des notes..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ $demoRequest->notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        Mettre à jour
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Historique</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Créée le</span>
                        <span class="text-gray-900">{{ $demoRequest->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($demoRequest->email_verified_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email vérifié le</span>
                        <span class="text-gray-900 text-green-600">{{ $demoRequest->email_verified_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if($demoRequest->contacted_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Contacté le</span>
                        <span class="text-gray-900">{{ $demoRequest->contacted_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if($demoRequest->scheduled_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Démo prévue le</span>
                        <span class="text-gray-900">{{ $demoRequest->scheduled_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <form action="{{ route('admin.demo-requests.destroy', $demoRequest) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-red-50 text-red-600 font-medium rounded-lg hover:bg-red-100 transition-colors">
                    Supprimer cette demande
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
