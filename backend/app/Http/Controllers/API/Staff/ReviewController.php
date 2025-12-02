<?php

namespace App\Http\Controllers\API\Staff;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function my(Request $request): JsonResponse
    {
        $staffId = $request->user()?->id;

        $reviews = Review::query()
            ->where('staff_id', $staffId)
            ->orderByDesc('reviewed_at')
            ->paginate($request->integer('per_page', 10));

        return response()->json($reviews);
    }

    public function stats(Request $request): JsonResponse
    {
        $staffId = $request->user()?->id;

        $query = Review::query()->where('staff_id', $staffId);

        return response()->json([
            'average_rating' => round((float) $query->clone()->avg('rating'), 2),
            'reviews_count' => (int) $query->clone()->count(),
            'negative_reviews' => (int) $query->clone()->where('rating', '<=', 2)->count(),
        ]);
    }
}
