<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::with('author:id,name')
            ->orderByRaw('COALESCE(published_at, created_at) desc')
            ->get();

        return response()->json($posts);
    }

    public function show(int $id): JsonResponse
    {
        $post = Post::with('author:id,name')->findOrFail($id);

        return response()->json($post);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'featured_image' => $request->featured_image,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->published_at,
            'author_id' => $request->user()->id,
        ]);

        return response()->json($post, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'featured_image' => 'nullable|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'slug' => 'sometimes|string|unique:posts,slug,' . $id,
        ]);

        if ($request->has('title') && $request->title !== $post->title) {
            $post->slug = Str::slug($request->title);
        }

        $post->update($request->only(['title', 'content', 'featured_image', 'is_published', 'published_at', 'slug']));

        return response()->json($post);
    }

    public function destroy(int $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }

    public function togglePublish(int $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->is_published = ! $post->is_published;
        if ($post->is_published && ! $post->published_at) {
            $post->published_at = now();
        }
        $post->save();

        return response()->json($post);
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $path = $request->file('image')->store('uploads', 'public');

        return response()->json(['url' => Storage::url($path)]);
    }
}
