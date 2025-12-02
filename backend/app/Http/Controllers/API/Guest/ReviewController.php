<?php

namespace App\Http\Controllers\API\Guest;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'service_id' => 'nullable|exists:services,id',
            'staff_id' => 'nullable|exists:users,id',
            'room_id' => 'nullable|exists:rooms,id',
            'hotel_id' => 'nullable|exists:hotels,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_anonymous' => 'boolean',
        ]);

        $order = null;
        if (! empty($data['order_id'])) {
            $order = Order::query()->find($data['order_id']);

            if ($order && $order->user_id && $request->user() && $order->user_id !== $request->user()->id) {
                throw new AccessDeniedHttpException('Order does not belong to the current user.');
            }
        }

        $service = null;
        if (! empty($data['service_id'])) {
            $service = Service::query()->find($data['service_id']);
        }

        $data['hotel_id'] = $data['hotel_id']
            ?? $order?->hotel_id
            ?? $service?->hotel_id
            ?? null;

        if (! $data['hotel_id']) {
            throw new AccessDeniedHttpException('Hotel context is required to submit a review.');
        }

        $existing = Review::query()
            ->when($order, fn ($query) => $query->where('order_id', $order->id))
            ->when(! $order && $request->user(), fn ($query) => $query->where('created_at', '>=', now()->subMinutes(1)))
            ->first();

        if ($existing && $order) {
            throw new AccessDeniedHttpException('Review for this order already exists.');
        }

        $review = Review::query()->create([
            'hotel_id' => $data['hotel_id'],
            'room_id' => $data['room_id'] ?? $order?->orderable_id,
            'order_id' => $data['order_id'],
            'service_id' => $data['service_id'] ?? $order?->orderable_id,
            'staff_id' => $data['staff_id'] ?? null,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'is_anonymous' => $data['is_anonymous'] ?? false,
            'reviewed_at' => now(),
        ]);

        return response()->json($review->load(['service', 'staff', 'hotel']), 201);
    }

    public function my(Request $request): JsonResponse
    {
        $user = $request->user();

        $reviews = Review::query()
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user?->id);
            })
            ->with(['service', 'staff'])
            ->orderByDesc('reviewed_at')
            ->paginate($request->integer('per_page', 10));

        return response()->json($reviews);
    }

    public function serviceReviews(Request $request, Service $service): JsonResponse
    {
        $reviews = Review::query()
            ->where('service_id', $service->id)
            ->orderByDesc('reviewed_at')
            ->paginate($request->integer('per_page', 10), ['id', 'rating', 'comment', 'is_anonymous', 'reviewed_at', 'staff_id']);

        return response()->json([
            'service' => $service,
            'reviews' => $reviews,
        ]);
    }
}
