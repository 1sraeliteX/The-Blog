<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class AdminCommentController extends Controller
{
    public function index(): JsonResponse
    {
        $comments = Comment::with('post:id,title')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comments);
    }

    public function approve(int $id): JsonResponse
    {
        $comment = Comment::findOrFail($id);
        $comment->is_approved = ! $comment->is_approved;
        $comment->save();

        return response()->json($comment);
    }

    public function destroy(int $id): JsonResponse
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }
}
