<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $reviews = Review::query()
            ->when($request->filled('hotel_id'), fn ($query) => $query->where('hotel_id', $request->integer('hotel_id')))
            ->when($request->filled('service_id'), fn ($query) => $query->where('service_id', $request->integer('service_id')))
            ->when($request->filled('staff_id'), fn ($query) => $query->where('staff_id', $request->integer('staff_id')))
            ->when($request->filled('rating_from'), fn ($query) => $query->where('rating', '>=', $request->integer('rating_from')))
            ->when($request->filled('rating_to'), fn ($query) => $query->where('rating', '<=', $request->integer('rating_to')))
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('reviewed_at', '>=', $request->date('date_from')))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('reviewed_at', '<=', $request->date('date_to')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->with(['service', 'staff', 'hotel'])
            ->orderByDesc('reviewed_at')
            ->paginate($request->integer('per_page', 20));

        return response()->json($reviews);
    }

    public function show(Review $review): JsonResponse
    {
        return response()->json($review->load(['service', 'staff', 'hotel', 'room', 'order']));
    }

    public function updateStatus(Request $request, Review $review): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|in:new,viewed,processed',
        ]);

        $review->update([
            'status' => $data['status'],
        ]);

        return response()->json($review);
    }

    public function reply(Request $request, Review $review): JsonResponse
    {
        $data = $request->validate([
            'admin_reply' => 'required|string',
        ]);

        $review->update([
            'admin_reply' => $data['admin_reply'],
            'status' => $review->status === 'new' ? 'viewed' : $review->status,
        ]);

        return response()->json($review);
    }
}
