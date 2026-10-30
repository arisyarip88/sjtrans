<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Tampilkan daftar post (artikel blog) atau page (halaman statis)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Post::with(['category', 'author:id,name']);

        // Filter tipe: 'post' (default artikel) atau 'page' (halaman statis)
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Jika bukan admin, hanya ambil yang published
        if (!$request->user() || !$request->user()->isAdmin()) {
            $query->where('status', 'published');
        } else {
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
        }

        if ($request->has('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('excerpt', 'like', "%{$s}%");
            });
        }

        $posts = $query->latest('published_at')->paginate(12);

        return response()->json($posts);
    }

    /**
     * Detail post / page berdasarkan slug atau ID
     */
    public function show(string $slugOrId): JsonResponse
    {
        $post = Post::with(['category', 'author:id,name', 'tags'])
                    ->where('slug', $slugOrId)
                    ->orWhere('id', $slugOrId)
                    ->firstOrFail();

        $post->increment('views_count');

        return response()->json([
            'data' => $post,
        ]);
    }

    /**
     * Buat artikel post atau halaman baru (Admin & Super Admin)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'nullable|in:post,page',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'status' => 'nullable|in:draft,published,archived',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $validated['author_id'] = $request->user()->id;
        $validated['type'] = $validated['type'] ?? 'post';
        $validated['status'] = $validated['status'] ?? 'published';
        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['published_at'] = $validated['status'] === 'published' ? Carbon::now() : null;
        $validated['excerpt'] = $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 140);
        $validated['featured_image'] = $validated['featured_image'] ?? 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80';

        $post = Post::create($validated);

        return response()->json([
            'message' => 'Konten berhasil disimpan!',
            'data' => $post->load('category'),
        ], 201);
    }

    /**
     * Update artikel post atau halaman (Admin & Super Admin)
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'sometimes|required|in:post,page',
            'excerpt' => 'nullable|string',
            'content' => 'sometimes|required|string',
            'featured_image' => 'nullable|string',
            'status' => 'sometimes|required|in:draft,published,archived',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = Carbon::now();
        }

        $post->update($validated);

        return response()->json([
            'message' => 'Konten berhasil diperbarui!',
            'data' => $post->fresh('category'),
        ]);
    }

    /**
     * Hapus artikel / halaman (Super Admin only)
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json([
            'message' => 'Konten berhasil dihapus.',
        ]);
    }
}
