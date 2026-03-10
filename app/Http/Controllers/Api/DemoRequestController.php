<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DemoRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DemoRequestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'employees' => 'nullable|string|max:50',
            'modules' => 'nullable|array',
            'modules.*' => 'string',
        ]);

        $demoRequest = DemoRequest::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Demo request created successfully',
            'data' => $demoRequest,
        ], 201);
    }

    public function index(): JsonResponse
    {
        $demoRequests = DemoRequest::latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $demoRequests,
        ]);
    }

    public function show(DemoRequest $demoRequest): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $demoRequest,
        ]);
    }

    public function update(Request $request, DemoRequest $demoRequest): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,contacted,scheduled,completed,cancelled',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'contacted' && $demoRequest->status === 'pending') {
            $validated['contacted_at'] = now();
        }

        $demoRequest->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Demo request updated successfully',
            'data' => $demoRequest,
        ]);
    }
}
