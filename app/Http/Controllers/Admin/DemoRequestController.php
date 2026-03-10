<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DemoRequestController extends Controller
{
    public function index()
    {
        $demoRequests = DemoRequest::latest()->paginate(20);
        
        $stats = [
            'total' => DemoRequest::count(),
            'pending' => DemoRequest::where('status', 'pending')->count(),
            'contacted' => DemoRequest::where('status', 'contacted')->count(),
            'scheduled' => DemoRequest::where('status', 'scheduled')->count(),
            'completed' => DemoRequest::where('status', 'completed')->count(),
        ];

        return view('admin.demo-requests.index', compact('demoRequests', 'stats'));
    }

    public function show(DemoRequest $demoRequest)
    {
        return view('admin.demo-requests.show', compact('demoRequest'));
    }

    public function update(Request $request, DemoRequest $demoRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,contacted,scheduled,completed,cancelled',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
        ]);

        if ($validated['status'] === 'contacted' && $demoRequest->status === 'pending') {
            $validated['contacted_at'] = now();
        }

        $demoRequest->update($validated);

        return redirect()->route('admin.demo-requests.index')
            ->with('success', 'Demande de démo mise à jour avec succès.');
    }

    public function destroy(DemoRequest $demoRequest)
    {
        $demoRequest->delete();

        return redirect()->route('admin.demo-requests.index')
            ->with('success', 'Demande de démo supprimée.');
    }

    public function provision(DemoRequest $demoRequest)
    {
        if ($demoRequest->isProvisioned()) {
            return redirect()->route('admin.demo-requests.show', $demoRequest)
                ->with('error', 'Un compte a déjà été créé pour cette demande.');
        }

        try {
            $response = Http::post(config('services.business_suite.url') . '/api/provisioning/tenants', [
                'name' => $demoRequest->name,
                'email' => $demoRequest->email,
                'phone' => $demoRequest->phone,
                'company' => $demoRequest->company,
                'plan' => 'trial',
                'modules' => $demoRequest->modules,
            ]);

            if ($response->successful()) {
                $data = $response->json('data');
                
                $demoRequest->update([
                    'tenant_id' => $data['tenant_id'],
                    'provisioned_at' => now(),
                    'status' => 'completed',
                ]);

                return redirect()->route('admin.demo-requests.show', $demoRequest)
                    ->with('success', 'Compte créé avec succès ! Email: ' . $data['email'] . ' | Mot de passe: ' . $data['password']);
            }

            return redirect()->route('admin.demo-requests.show', $demoRequest)
                ->with('error', 'Erreur lors de la création du compte: ' . $response->body());

        } catch (\Exception $e) {
            return redirect()->route('admin.demo-requests.show', $demoRequest)
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
