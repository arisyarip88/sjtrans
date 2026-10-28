<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusFleet;
use App\Models\Discount;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Daftar pemesanan / reservasi
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Booking::with(['fleet', 'discount', 'transactions']);

        // Jika bukan admin, ambil pesanan milik user (berdasarkan user_id ATAU customer_email)
        if (!$user || !$user->isAdmin()) {
            if ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('customer_email', $user->email);
                });
            } else {
                return response()->json(['data' => []]);
            }
        } else {
            // Filter admin
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('payment_status') && $request->payment_status !== 'all') {
                $query->where('payment_status', $request->payment_status);
            }

            if ($request->has('search')) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('booking_code', 'like', "%{$s}%")
                      ->orWhere('customer_name', 'like', "%{$s}%")
                      ->orWhere('customer_email', 'like', "%{$s}%")
                      ->orWhere('customer_phone', 'like', "%{$s}%")
                      ->orWhere('destination', 'like', "%{$s}%");
                });
            }
        }

        $bookings = $query->latest()->paginate(15);

        return response()->json($bookings);
    }

    /**
     * Detail pemesanan berdasarkan booking code atau ID
     */
    public function show(string $codeOrId, Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Booking::with(['fleet', 'discount', 'transactions.verifier']);

        if (!$user || !$user->isAdmin()) {
            $query->where(function ($q) use ($codeOrId, $user) {
                $q->where('booking_code', $codeOrId)->orWhere('id', $codeOrId);
            });
            if ($user) {
                $query->where('user_id', $user->id);
            }
        } else {
            $query->where('booking_code', $codeOrId)->orWhere('id', $codeOrId);
        }

        $booking = $query->firstOrFail();

        return response()->json([
            'data' => $booking,
        ]);
    }

    /**
     * Buat reservasi sewa bus baru
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fleet_id' => 'required|exists:bus_fleets,id',
            'customer_name' => 'required|string|max:150',
            'customer_email' => 'required|email|max:150',
            'customer_phone' => 'required|string|max:30',
            'pickup_location' => 'required|string',
            'destination' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_passengers' => 'required|integer|min:1',
            'total_units' => 'nullable|integer|min:1',
            'discount_code' => 'nullable|string',
            'special_notes' => 'nullable|string',
        ]);

        $fleet = BusFleet::findOrFail($validated['fleet_id']);
        $totalUnits = $validated['total_units'] ?? 1;

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $totalDays = max(1, $startDate->diffInDays($endDate) + 1);

        $subtotal = (float) $fleet->price_per_day * $totalDays * $totalUnits;
        $discountAmount = 0.0;
        $discountId = null;
        $discountCode = null;

        // Cek diskon jika ada
        if (!empty($validated['discount_code'])) {
            $disc = Discount::where('code', strtoupper(trim($validated['discount_code'])))->first();
            if ($disc && $disc->isValid($subtotal)) {
                $discountAmount = $disc->calculateDiscount($subtotal);
                $discountId = $disc->id;
                $discountCode = $disc->code;
                $disc->increment('used_count');
            }
        }

        $totalPrice = max(0, $subtotal - $discountAmount);

        // Generate unik booking code
        $bookingCode = 'SJT-' . date('Ym') . '-' . strtoupper(Str::random(5));

        $userId = $request->user() ? $request->user()->id : null;
        if (!$userId && !empty($validated['customer_email'])) {
            $matchedUser = User::where('email', $validated['customer_email'])->first();
            if ($matchedUser) {
                $userId = $matchedUser->id;
            }
        }

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => $userId,
            'fleet_id' => $fleet->id,
            'discount_id' => $discountId,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'pickup_location' => $validated['pickup_location'],
            'destination' => $validated['destination'],
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_days' => $totalDays,
            'total_passengers' => $validated['total_passengers'],
            'total_units' => $totalUnits,
            'subtotal' => $subtotal,
            'discount_code' => $discountCode,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice,
            'special_notes' => $validated['special_notes'] ?? null,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Buat faktur tagihan awal
        $invoiceNumber = 'INV-' . $bookingCode;
        Transaction::create([
            'booking_id' => $booking->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $totalPrice,
            'payment_type' => 'full_payment',
            'payment_method' => 'bank_transfer',
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Pemesanan sewa bus berhasil dibuat!',
            'data' => $booking->load(['fleet', 'transactions']),
        ], 201);
    }

    /**
     * Update status pemesanan (Admin / Super Admin)
     */
    public function updateStatus(Request $request, Booking $booking): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,in_trip,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,down_payment,paid,refunded',
        ]);

        $booking->update($validated);

        return response()->json([
            'message' => 'Status pemesanan berhasil diperbarui!',
            'data' => $booking->fresh(),
        ]);
    }

    /**
     * Hapus pemesanan (Super Admin only)
     */
    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();

        return response()->json([
            'message' => 'Data pemesanan berhasil dihapus.',
        ]);
    }
}
