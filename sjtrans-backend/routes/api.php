<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\BusFleetController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\PartnershipController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // 1. Auth Public
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    // 2. Armada Bus (Public)
    Route::get('/fleets', [BusFleetController::class, 'index']);
    Route::get('/fleets/{slugOrId}', [BusFleetController::class, 'show']);

    // 3. Diskon & Kupon (Public)
    Route::get('/discounts', [DiscountController::class, 'index']);
    Route::post('/discounts/validate', [DiscountController::class, 'validatePromo']);

    // 4. CMS Posts & Pages (Public)
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{slugOrId}', [PostController::class, 'show']);

    // 5. Testimoni (Public)
    Route::get('/testimonials', [TestimonialController::class, 'index']);
    Route::post('/testimonials', [TestimonialController::class, 'store']);

    // 6. Live Chat (Public & Visitor)
    Route::post('/chats/session', [ChatController::class, 'getOrCreateRoom']);
    Route::post('/chats/message', [ChatController::class, 'sendMessage']);

    // 7. Pengaturan Situs, Footer & Kontak (Public)
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/contact', [SettingController::class, 'submitContact']);

    // 8. Kemitraan / Partnership (Public)
    Route::get('/partnerships', [PartnershipController::class, 'index']);
    Route::post('/partnerships', [PartnershipController::class, 'store']);

    // 9. Reservasi Pemesanan (Public & Guest Booking)
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{codeOrId}', [BookingController::class, 'show']);

    // 10. Authenticated Routes (Semua User Login: Customer, Admin, Super Admin)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Riwayat pemesanan & transaksi user
        Route::get('/my-bookings', [BookingController::class, 'index']);
        Route::get('/my-transactions', [TransactionController::class, 'index']);
        Route::post('/transactions', [TransactionController::class, 'store']);

        // 11. Admin & Super Admin Panel (Role: admin, super_admin)
        Route::middleware('role:admin,super_admin')->group(function () {
            // Dashboard & Metrik
            Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

            // CRUD Armada Bus
            Route::post('/fleets', [BusFleetController::class, 'store']);
            Route::put('/fleets/{busFleet}', [BusFleetController::class, 'update']);

            // CRUD Pemesanan
            Route::get('/admin/bookings', [BookingController::class, 'index']);
            Route::put('/bookings/{booking}/status', [BookingController::class, 'updateStatus']);

            // CRUD Transaksi & Verifikasi
            Route::get('/admin/transactions', [TransactionController::class, 'index']);
            Route::put('/transactions/{transaction}/verify', [TransactionController::class, 'verify']);

            // CRUD Diskon Promo
            Route::post('/discounts', [DiscountController::class, 'store']);
            Route::put('/discounts/{discount}', [DiscountController::class, 'update']);

            // CRUD CMS Artikel & Page
            Route::post('/posts', [PostController::class, 'store']);
            Route::put('/posts/{post}', [PostController::class, 'update']);

            // Moderasi Testimoni
            Route::put('/testimonials/{testimonial}/status', [TestimonialController::class, 'updateStatus']);

            // Live Chat Admin Inbox & Balas Chat
            Route::get('/admin/chats/rooms', [ChatController::class, 'listRooms']);
            Route::post('/admin/chats/{chatRoom}/reply', [ChatController::class, 'adminReply']);

            // Pesan Kontak Masuk & Kemitraan
            Route::get('/admin/contacts', [SettingController::class, 'contactMessages']);
            Route::put('/partnerships/{partnership}', [PartnershipController::class, 'update']);
        });

        // 12. Super Admin Exclusive (Role: super_admin)
        Route::middleware('role:super_admin')->group(function () {
            // Manajemen Akun Pengguna
            Route::get('/admin/users', [AuthController::class, 'users']);
            Route::put('/admin/users/{user}/toggle-status', [AuthController::class, 'toggleUserStatus']);
            Route::put('/admin/users/{user}/role', [AuthController::class, 'updateUserRole']);

            // Hapus Permanen
            Route::delete('/fleets/{busFleet}', [BusFleetController::class, 'destroy']);
            Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);
            Route::delete('/discounts/{discount}', [DiscountController::class, 'destroy']);
            Route::delete('/posts/{post}', [PostController::class, 'destroy']);
            Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy']);
            Route::delete('/partnerships/{partnership}', [PartnershipController::class, 'destroy']);

            // Kontrol Warna Tema & Pengaturan Global
            Route::post('/settings/theme', [SettingController::class, 'updateTheme']);
            Route::post('/settings/general', [SettingController::class, 'updateSettings']);
        });
    });
});
