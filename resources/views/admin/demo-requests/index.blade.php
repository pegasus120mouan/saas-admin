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
                        <div class="text-sm text-gray-500">{{ $request->email }}</div>
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
                        <a href="{{ route('admin.demo-requests.show', $request) }}" class="text-primary-600 hover:text-primary-800 font-medium text-sm">
                            Voir
                        </a>
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
