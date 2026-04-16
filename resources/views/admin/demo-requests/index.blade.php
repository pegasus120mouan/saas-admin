@extends('layouts.admin')

@section('title', 'Demandes de démo')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Demandes de démo</h1>
            <p class="text-gray-500">Gérez les demandes de démonstration reçues</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-sm text-gray-500">En attente</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-sm text-gray-500">Contactés</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['contacted'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-sm text-gray-500">Planifiés</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['scheduled'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-sm text-gray-500">Terminés</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entreprise</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modules</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($demoRequests as $request)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $request->name }}</div>
                        <div class="text-sm text-gray-500 flex items-center gap-1">
                            {{ $request->email }}
                            @if($request->email_verified_at)
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20" title="Email vérifié">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20" title="Email non vérifié">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">{{ $request->phone }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $request->company }}</div>
                        <div class="text-sm text-gray-500">{{ $request->employees }} employés</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($request->modules)
                            <div class="flex flex-wrap gap-1">
                                @foreach($request->modules as $module)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ ucfirst($module) }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @switch($request->status)
                            @case('pending')
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">En attente</span>
                                @break
                            @case('contacted')
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Contacté</span>
                                @break
                            @case('scheduled')
                                <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">Planifié</span>
                                @break
                            @case('completed')
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Terminé</span>
                                @break
                            @case('cancelled')
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Annulé</span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $request->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.demo-requests.show', $request) }}" class="text-primary-600 hover:text-primary-800" title="Voir">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.demo-requests.destroy', $request) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        Aucune demande de démo pour le moment
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $demoRequests->links() }}
    </div>
</div>
@endsection
