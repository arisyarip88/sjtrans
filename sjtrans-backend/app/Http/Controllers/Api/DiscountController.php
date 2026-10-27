<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Tampilkan daftar promo / diskon
     */
    public function index(Request $request): JsonResponse
    {
        $query = Discount::query();

        // Jika bukan admin, hanya tampilkan yang aktif dan belum kadaluarsa
        if (!$request->user() || !$request->user()->isAdmin()) {
            $today = Carbon::today();
            $query->where('is_active', true)
                  ->where('start_date', '<=', $today)
                  ->where('end_date', '>=', $today);
        }

        $discounts = $query->latest()->get();

        return response()->json([
            'data' => $discounts,
        ]);
    }

    /**
     * Validasi kode kupon promo dan hitung potongan harga
     */
    public function validatePromo(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = strtoupper(trim($request->code));
        $subtotal = (float) $request->subtotal;

        $discount = Discount::where('code', $code)->first();

        if (!$discount) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode kupon tidak ditemukan.',
            ], 404);
        }

        if (!$discount->isValid($subtotal)) {
            $msg = 'Kupon tidak berlaku atau sudah kadaluarsa.';
            if ($discount->min_transaction && $subtotal < $discount->min_transaction) {
                $msg = 'Minimal nilai transaksi untuk promo ini adalah Rp ' . number_format($discount->min_transaction, 0, ',', '.');
            } elseif ($discount->quota && $discount->used_count >= $discount->quota) {
                $msg = 'Kuota pemakaian kupon ini telah habis.';
            }

            return response()->json([
                'valid' => false,
                'message' => $msg,
            ], 422);
        }

        $discountAmount = $discount->calculateDiscount($subtotal);
        $finalTotal = max(0, $subtotal - $discountAmount);

        return response()->json([
            'valid' => true,
            'message' => 'Kupon berhasil diterapkan!',
            'discount' => [
                'id' => $discount->id,
                'code' => $discount->code,
                'title' => $discount->title,
                'discount_type' => $discount->discount_type,
                'discount_value' => (float) $discount->discount_value,
                'discount_amount' => $discountAmount,
                'subtotal' => $subtotal,
                'total_price' => $finalTotal,
            ],
        ]);
    }

    /**
     * Buat promo diskon baru (Admin & Super Admin)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discounts,code',
            'title' => 'required|string|max:150',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_transaction' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'quota' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $discount = Discount::create($validated);

        return response()->json([
            'message' => 'Diskon promo berhasil dibuat!',
            'data' => $discount,
        ], 201);
    }

    /**
     * Update diskon (Admin & Super Admin)
     */
    public function update(Request $request, Discount $discount): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:150',
            'discount_type' => 'sometimes|required|in:percentage,fixed',
            'discount_value' => 'sometimes|required|numeric|min:1',
            'min_transaction' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'quota' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $discount->update($validated);

        return response()->json([
            'message' => 'Data diskon berhasil diperbarui!',
            'data' => $discount,
        ]);
    }

    /**
     * Hapus diskon (Super Admin)
     */
    public function destroy(Discount $discount): JsonResponse
    {
        $discount->delete();

        return response()->json([
            'message' => 'Diskon promo berhasil dihapus.',
        ]);
    }
}
