<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(string $slug): JsonResponse
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $comments = Comment::where('post_id', $post->id)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comments);
    }

    public function store(Request $request, string $slug): JsonResponse
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'name' => $request->name,
            'content' => $request->content,
            'is_approved' => false,
        ]);

        return response()->json($comment, 201);
    }
}
