<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Ambil semua setting situs untuk Footer, Kontak, Sosmed, Lokasi & Tema
     */
    public function index(): JsonResponse
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        $faqs = Faq::where('is_active', true)->orderBy('order_position')->get();

        return response()->json([
            'settings' => $settings,
            'faqs' => $faqs,
        ]);
    }

    /**
     * Update warna tema (Dapat diubah-ubah sendiri oleh Super Admin)
     */
    public function updateTheme(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme_primary_color' => 'required|string|regex:/^#([a-fA-F0-9]{6})$/',
            'theme_primary_hover' => 'nullable|string|regex:/^#([a-fA-F0-9]{6})$/',
            'theme_accent_color' => 'nullable|string|regex:/^#([a-fA-F0-9]{6})$/',
            'theme_active_preset' => 'nullable|string|max:50',
        ]);

        foreach ($validated as $key => $val) {
            if ($val !== null) {
                SiteSetting::set($key, $val, 'theme');
            }
        }

        return response()->json([
            'message' => 'Warna tema website berhasil diperbarui secara instan!',
            'settings' => SiteSetting::where('group', 'theme')->pluck('value', 'key'),
        ]);
    }

    /**
     * Update informasi situs & footer (Super Admin only)
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $data = $request->input('settings', []);

        foreach ($data as $key => $val) {
            SiteSetting::set($key, $val);
        }

        return response()->json([
            'message' => 'Pengaturan situs berhasil diperbarui!',
            'settings' => SiteSetting::all()->pluck('value', 'key'),
        ]);
    }

    /**
     * Form kirim pesan / kontak
     */
    public function submitContact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:30',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        $contact = ContactMessage::create($validated);

        return response()->json([
            'message' => 'Terima kasih telah menghubungi SJ Trans. Pesan Anda telah kami terima.',
            'data' => $contact,
        ], 201);
    }

    /**
     * Daftar pesan kontak masuk (Admin & Super Admin)
     */
    public function contactMessages(): JsonResponse
    {
        $messages = ContactMessage::latest()->paginate(20);

        return response()->json($messages);
    }
}
