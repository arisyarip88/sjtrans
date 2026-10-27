<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Tampilkan daftar testimoni
     */
    public function index(Request $request): JsonResponse
    {
        $query = Testimonial::with('fleet:id,name');

        if (!$request->user() || !$request->user()->isAdmin()) {
            $query->where('is_approved', true);
            if ($request->has('featured')) {
                $query->where('is_featured', true);
            }
        }

        $testimonials = $query->latest()->get();

        return response()->json([
            'data' => $testimonials,
        ]);
    }

    /**
     * Kirim ulasan baru
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fleet_id' => 'nullable|exists:bus_fleets,id',
            'customer_name' => 'required|string|max:100',
            'customer_role_or_company' => 'nullable|string|max:150',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'photo_url' => 'nullable|string',
        ]);

        $userId = $request->user() ? $request->user()->id : null;

        $testimonial = Testimonial::create([
            'user_id' => $userId,
            'fleet_id' => $validated['fleet_id'] ?? null,
            'customer_name' => $validated['customer_name'],
            'customer_role_or_company' => $validated['customer_role_or_company'] ?? null,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'photo_url' => $validated['photo_url'] ?? null,
            'is_approved' => false, // Perlu persetujuan admin
            'is_featured' => false,
        ]);

        return response()->json([
            'message' => 'Terima kasih atas ulasan Anda! Testimoni akan ditayangkan setelah dimoderasi tim admin.',
            'data' => $testimonial,
        ], 201);
    }

    /**
     * Moderasi / Approve testimoni (Admin & Super Admin)
     */
    public function updateStatus(Request $request, Testimonial $testimonial): JsonResponse
    {
        $validated = $request->validate([
            'is_approved' => 'required|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $testimonial->update($validated);

        return response()->json([
            'message' => 'Status testimoni berhasil diperbarui!',
            'data' => $testimonial,
        ]);
    }

    /**
     * Hapus ulasan (Admin & Super Admin)
     */
    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();

        return response()->json([
            'message' => 'Ulasan berhasil dihapus.',
        ]);
    }
}
