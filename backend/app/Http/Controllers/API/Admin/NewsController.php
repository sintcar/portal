<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Hotel $hotel): JsonResponse
    {
        $news = News::query()
            ->where('hotel_id', $hotel->id)
            ->orderByDesc('published_at')
            ->get();

        return response()->json($news);
    }

    public function store(Request $request, Hotel $hotel): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string',
            'summary' => 'nullable|string',
            'body' => 'required|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $news = News::query()->create([
            'hotel_id' => $hotel->id,
            'author_id' => $request->user()?->id,
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'summary' => $data['summary'] ?? null,
            'body' => $data['body'],
            'is_published' => $data['is_published'] ?? false,
            'published_at' => $data['published_at'] ?? null,
        ]);

        return response()->json($news, 201);
    }

    public function update(Request $request, News $news): JsonResponse
    {
        $data = $request->validate([
            'title' => 'sometimes|string',
            'summary' => 'nullable|string',
            'body' => 'sometimes|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $news->update($data);

        return response()->json($news);
    }
}
