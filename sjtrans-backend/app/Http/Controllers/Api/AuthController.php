<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login via Username ATAU Email
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $request->input('login');
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);

        $field = $isEmail ? 'email' : 'username';

        $user = User::with('role')->where($field, $loginInput)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Username/Email atau password salah.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Akun Anda dinonaktifkan. Silakan hubungi Customer Service SJ Trans.',
            ], 403);
        }

        // Buat token Sanctum
        $token = $user->createToken('auth-token', [$user->role->name ?? 'customer'])->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'avatar' => $user->avatar,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'display_name' => $user->role->display_name,
                ] : null,
            ],
        ]);
    }

    /**
     * Sign Up / Registrasi Akun Customer
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'username' => 'required|string|min:3|max:50|alpha_dash|unique:users,username',
            'email' => 'required|string|email|max:100|unique:users,email',
            'phone_number' => 'required|string|min:9|max:20|unique:users,phone_number',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customerRole = Role::where('name', 'customer')->first();

        $user = User::create([
            'role_id' => $customerRole ? $customerRole->id : 3,
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        $user->load('role');
        $token = $user->createToken('auth-token', ['customer'])->plainTextToken;

        return response()->json([
            'message' => 'Pendaftaran akun SJ Trans berhasil!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'avatar' => $user->avatar,
                'role' => [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'display_name' => $user->role->display_name,
                ],
            ],
        ], 201);
    }

    /**
     * Ambil profil akun aktif
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('role');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'avatar' => $user->avatar,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'display_name' => $user->role->display_name,
                ] : null,
            ],
        ]);
    }

    /**
     * Logout & Cabut Token
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil. Sesi telah diakhiri.',
        ]);
    }

    /**
     * Daftar semua pengguna (Super Admin only)
     */
    public function users(Request $request): JsonResponse
    {
        $query = User::with('role');

        if ($request->has('role')) {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return response()->json($users);
    }

    /**
     * Toggle status aktif pengguna (Super Admin only)
     */
    public function toggleUserStatus(User $user): JsonResponse
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'message' => 'Status pengguna berhasil diperbarui.',
            'is_active' => $user->is_active,
        ]);
    }

    /**
     * Ubah hak akses role pengguna (Super Admin only)
     */
    public function updateUserRole(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role_name' => 'required|in:admin,customer',
        ]);

        $role = Role::where('name', $validated['role_name'])->firstOrFail();
        $user->update(['role_id' => $role->id]);

        return response()->json([
            'message' => "Hak akses {$user->name} berhasil diubah menjadi {$role->display_name}.",
            'user' => $user->fresh('role'),
        ]);
    }
}
