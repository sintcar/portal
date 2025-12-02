<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request, Hotel $hotel): JsonResponse
    {
        $orders = Order::query()
            ->where('hotel_id', $hotel->id)
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->with(['user', 'orderable'])
            ->orderByDesc('placed_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($orders);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|string',
            'message' => 'nullable|string',
        ]);

        $order->update([
            'status' => $data['status'],
            'completed_at' => $data['status'] === 'completed' ? now() : $order->completed_at,
            'canceled_at' => $data['status'] === 'canceled' ? now() : $order->canceled_at,
        ]);

        OrderLog::query()->create([
            'order_id' => $order->id,
            'user_id' => $request->user()?->id,
            'status' => $data['status'],
            'message' => $data['message'] ?? 'Order status updated',
            'context' => ['actor' => 'admin'],
        ]);

        return response()->json($order);
    }
}
