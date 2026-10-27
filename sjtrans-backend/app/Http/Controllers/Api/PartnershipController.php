<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnershipController extends Controller
{
    /**
     * Tampilkan daftar mitra kerja sama / sponsor
     */
    public function index(Request $request): JsonResponse
    {
        $query = Partnership::query();

        if (!$request->user() || !$request->user()->isAdmin()) {
            $query->where('status', 'active');
        }

        $partnerships = $query->orderBy('company_name')->get();

        return response()->json([
            'data' => $partnerships,
        ]);
    }

    /**
     * Pengajuan kemitraan baru (Public / Mitra)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:150',
            'contact_person' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:30',
            'logo_url' => 'nullable|string',
            'website_url' => 'nullable|url|max:200',
            'partnership_type' => 'required|in:corporate,travel_agent,hotel,fleet_partner,sponsor',
        ]);

        $isAdmin = $request->user() && $request->user()->isAdmin();
        $validated['status'] = $isAdmin ? 'active' : 'pending';

        $partner = Partnership::create($validated);

        return response()->json([
            'message' => 'Pengajuan kemitraan berhasil dikirim! Tim SJ Trans akan segera menghubungi Anda.',
            'data' => $partner,
        ], 201);
    }

    /**
     * Update status kemitraan (Admin & Super Admin)
     */
    public function update(Request $request, Partnership $partnership): JsonResponse
    {
        $validated = $request->validate([
            'company_name' => 'sometimes|required|string|max:150',
            'contact_person' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:30',
            'logo_url' => 'nullable|string',
            'website_url' => 'nullable|string|max:200',
            'partnership_type' => 'sometimes|required|in:corporate,travel_agent,hotel,fleet_partner,sponsor',
            'status' => 'sometimes|required|in:active,pending,inactive',
        ]);

        $partnership->update($validated);

        return response()->json([
            'message' => 'Data kemitraan berhasil diperbarui!',
            'data' => $partnership,
        ]);
    }

    /**
     * Hapus kemitraan (Super Admin only)
     */
    public function destroy(Partnership $partnership): JsonResponse
    {
        $partnership->delete();

        return response()->json([
            'message' => 'Data kemitraan berhasil dihapus.',
        ]);
    }
}
