<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewAnalyticsController extends Controller
{
    public function services(Request $request): JsonResponse
    {
        $services = Review::query()
            ->selectRaw('service_id, avg(rating) as avg_rating, count(*) as reviews_count')
            ->when($request->filled('hotel_id'), fn ($query) => $query->where('hotel_id', $request->integer('hotel_id')))
            ->when($request->filled('category_id'), fn ($query) => $query->whereHas('service', function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'));
            }))
            ->whereNotNull('service_id')
            ->groupBy('service_id')
            ->orderByDesc('avg_rating')
            ->with('service')
            ->paginate($request->integer('per_page', 20));

        return response()->json($services);
    }

    public function staff(Request $request): JsonResponse
    {
        $staffRatings = Review::query()
            ->selectRaw('staff_id, avg(rating) as avg_rating, count(*) as reviews_count, sum(case when rating <= 2 then 1 else 0 end) as negative_reviews')
            ->when($request->filled('department'), fn ($query) => $query->whereHas('staff.role', function ($query) use ($request) {
                $query->where('slug', $request->string('department'));
            }))
            ->when($request->filled('hotel_id'), fn ($query) => $query->where('hotel_id', $request->integer('hotel_id')))
            ->whereNotNull('staff_id')
            ->groupBy('staff_id')
            ->orderByDesc('avg_rating')
            ->with('staff')
            ->paginate($request->integer('per_page', 20));

        return response()->json($staffRatings);
    }

    public function overview(Request $request): JsonResponse
    {
        $query = Review::query()
            ->when($request->filled('hotel_id'), fn ($builder) => $builder->where('hotel_id', $request->integer('hotel_id')))
            ->when($request->filled('date_from'), fn ($builder) => $builder->whereDate('reviewed_at', '>=', $request->date('date_from')))
            ->when($request->filled('date_to'), fn ($builder) => $builder->whereDate('reviewed_at', '<=', $request->date('date_to')));

        $stats = [
            'average_rating' => round((float) $query->clone()->avg('rating'), 2),
            'total_reviews' => (int) $query->clone()->count(),
            'distribution' => $query->clone()
                ->selectRaw('rating, count(*) as total')
                ->groupBy('rating')
                ->orderBy('rating')
                ->pluck('total', 'rating'),
        ];

        return response()->json($stats);
    }
}
