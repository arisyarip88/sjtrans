<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Daftar transaksi / pembayaran
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Transaction::with(['booking.fleet', 'verifier']);

        if (!$user || !$user->isAdmin()) {
            if ($user) {
                $query->whereHas('booking', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            } else {
                return response()->json(['data' => []]);
            }
        } else {
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
        }

        $transactions = $query->latest()->paginate(15);

        return response()->json($transactions);
    }

    /**
     * Upload bukti pembayaran / submit pembayaran
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:1000',
            'payment_type' => 'required|in:down_payment,full_payment,remaining',
            'payment_method' => 'required|string|max:50',
            'payment_proof_url' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        $invoiceNumber = 'INV-' . $booking->booking_code . '-' . rand(10, 99);

        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $validated['amount'],
            'payment_type' => $validated['payment_type'],
            'payment_method' => $validated['payment_method'],
            'payment_proof_url' => $validated['payment_proof_url'] ?? null,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Bukti pembayaran berhasil dikirim! Tim SJ Trans akan segera melakukan verifikasi.',
            'data' => $transaction,
        ], 201);
    }

    /**
     * Verifikasi pembayaran oleh Admin / Super Admin
     */
    public function verify(Request $request, Transaction $transaction): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:verified,rejected',
            'notes' => 'nullable|string',
        ]);

        $transaction->update([
            'status' => $validated['status'],
            'verified_by' => $request->user()->id,
            'verified_at' => Carbon::now(),
            'notes' => $validated['notes'] ?? $transaction->notes,
        ]);

        // Update payment_status pada booking
        $booking = $transaction->booking;
        if ($validated['status'] === 'verified') {
            if ($transaction->payment_type === 'down_payment') {
                $booking->update([
                    'payment_status' => 'down_payment',
                    'status' => 'confirmed',
                ]);
            } else {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                ]);
            }
        }

        return response()->json([
            'message' => 'Transaksi pembayaran berhasil diperbarui!',
            'data' => $transaction->fresh(['booking', 'verifier']),
        ]);
    }
}
