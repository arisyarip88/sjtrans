<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Memulai atau mengambil sesi obrolan live chat berdasarkan session_id
     */
    public function getOrCreateRoom(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|string|max:100',
            'visitor_name' => 'nullable|string|max:100',
            'visitor_phone' => 'nullable|string|max:30',
            'visitor_email' => 'nullable|email|max:100',
        ]);

        $sessionId = $validated['session_id'];
        $user = $request->user();

        $room = ChatRoom::where('session_id', $sessionId)->first();

        if (!$room) {
            $name = $user ? $user->name : ($validated['visitor_name'] ?? 'Tamu SJ Trans');
            $phone = $user ? $user->phone_number : ($validated['visitor_phone'] ?? null);
            $email = $user ? $user->email : ($validated['visitor_email'] ?? null);

            $room = ChatRoom::create([
                'session_id' => $sessionId,
                'user_id' => $user ? $user->id : null,
                'visitor_name' => $name,
                'visitor_phone' => $phone,
                'visitor_email' => $email,
                'status' => 'open',
                'last_message_at' => Carbon::now(),
            ]);

            // Pesan selamat datang otomatis dari bot SJ Trans
            ChatMessage::create([
                'room_id' => $room->id,
                'sender_type' => 'bot',
                'message' => 'Halo! Selamat datang di Layanan Pelanggan SJ Trans. Ada yang bisa kami bantu seputar reservasi bus pariwisata hari ini?',
            ]);
        }

        $messages = $room->messages()->latest()->take(50)->get()->reverse()->values();

        return response()->json([
            'room' => $room,
            'messages' => $messages,
        ]);
    }

    /**
     * Kirim pesan baru (Customer / Visitor)
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:chat_rooms,id',
            'message' => 'required|string|max:1500',
        ]);

        $room = ChatRoom::findOrFail($validated['room_id']);
        $user = $request->user();

        $chatMessage = ChatMessage::create([
            'room_id' => $room->id,
            'sender_type' => 'customer',
            'sender_id' => $user ? $user->id : null,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        $room->update([
            'last_message_at' => Carbon::now(),
            'status' => 'waiting', // Menunggu balasan admin
        ]);

        return response()->json([
            'message' => 'Pesan terkirim!',
            'data' => $chatMessage,
        ], 201);
    }

    /**
     * Balas pesan dari Admin / Super Admin
     */
    public function adminReply(Request $request, ChatRoom $chatRoom): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1500',
        ]);

        $admin = $request->user();

        $chatMessage = ChatMessage::create([
            'room_id' => $chatRoom->id,
            'sender_type' => 'admin',
            'sender_id' => $admin->id,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        $chatRoom->update([
            'assigned_admin_id' => $admin->id,
            'last_message_at' => Carbon::now(),
            'status' => 'open',
        ]);

        return response()->json([
            'message' => 'Balasan berhasil dikirim!',
            'data' => $chatMessage,
        ], 201);
    }

    /**
     * Daftar room chat masuk (Admin & Super Admin)
     */
    public function listRooms(Request $request): JsonResponse
    {
        $rooms = ChatRoom::with(['messages' => function ($q) {
            $q->latest()->take(1);
        }])->latest('last_message_at')->paginate(20);

        return response()->json($rooms);
    }
}
