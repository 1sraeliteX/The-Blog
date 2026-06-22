<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminBannerController extends Controller
{
    public function index(): JsonResponse
    {
        $banners = Banner::orderBy('created_at', 'desc')->get();

        return response()->json($banners);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $banner = Banner::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_url' => $request->image_url,
            'is_active' => $request->boolean('is_active'),
        ]);

        return response()->json($banner, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'image_url' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $banner->update($request->only(['title', 'content', 'image_url', 'is_active']));

        return response()->json($banner);
    }

    public function destroy(int $id): JsonResponse
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return response()->json(['message' => 'Banner deleted']);
    }
}
