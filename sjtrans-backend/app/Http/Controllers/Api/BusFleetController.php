<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusFleet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusFleetController extends Controller
{
    /**
     * Tampilkan katalog armada bus (Public & Admin)
     */
    public function index(Request $request): JsonResponse
    {
        $query = BusFleet::with('category');

        // Filter berdasarkan kategori armada
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter kapasitas minimum
        if ($request->has('min_capacity')) {
            $query->where('capacity', '>=', (int) $request->min_capacity);
        }

        // Filter kapasitas maksimum
        if ($request->has('max_capacity')) {
            $query->where('capacity', '<=', (int) $request->max_capacity);
        }

        // Filter status armada
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian nama bus
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $fleets = $query->orderBy('price_per_day', 'asc')->get();

        return response()->json([
            'data' => $fleets,
            'total' => $fleets->count(),
        ]);
    }

    /**
     * Detail armada bus berdasarkan slug atau ID
     */
    public function show(string $slugOrId): JsonResponse
    {
        $fleet = BusFleet::with(['category', 'testimonials' => function ($q) {
            $q->where('is_approved', true)->latest()->take(5);
        }])->where('slug', $slugOrId)->orWhere('id', $slugOrId)->firstOrFail();

        return response()->json([
            'data' => $fleet,
        ]);
    }

    /**
     * Tambah armada bus baru (Admin & Super Admin)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|exists:categories,id',
            'capacity' => 'required|integer|min:5|max:100',
            'seat_layout' => 'required|string|max:30',
            'price_per_day' => 'required|numeric|min:0',
            'facilities' => 'nullable|array',
            'specifications' => 'nullable|array',
            'thumbnail_url' => 'nullable|string',
            'gallery_urls' => 'nullable|array',
            'description' => 'nullable|string',
            'status' => 'required|in:available,booked,maintenance',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . rand(100, 999);

        $fleet = BusFleet::create($validated);

        return response()->json([
            'message' => 'Armada bus berhasil ditambahkan!',
            'data' => $fleet->load('category'),
        ], 201);
    }

    /**
     * Ubah data armada bus (Admin & Super Admin)
     */
    public function update(Request $request, BusFleet $busFleet): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:150',
            'category_id' => 'nullable|exists:categories,id',
            'capacity' => 'sometimes|required|integer|min:5|max:100',
            'seat_layout' => 'sometimes|required|string|max:30',
            'price_per_day' => 'sometimes|required|numeric|min:0',
            'facilities' => 'nullable|array',
            'specifications' => 'nullable|array',
            'thumbnail_url' => 'nullable|string',
            'gallery_urls' => 'nullable|array',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:available,booked,maintenance',
        ]);

        if (isset($validated['name']) && $validated['name'] !== $busFleet->name) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . $busFleet->id;
        }

        $busFleet->update($validated);

        return response()->json([
            'message' => 'Data armada bus berhasil diperbarui!',
            'data' => $busFleet->fresh('category'),
        ]);
    }

    /**
     * Hapus armada bus (Super Admin)
     */
    public function destroy(BusFleet $busFleet): JsonResponse
    {
        $busFleet->delete();

        return response()->json([
            'message' => 'Armada bus berhasil dihapus.',
        ]);
    }
}
