<?php

namespace App\Http\Controllers\API\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function inbox(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->with(['hotel', 'user'])
            ->orderBy('placed_at')
            ->paginate($request->integer('per_page', 20));

        return response()->json($orders);
    }

    public function progress(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|string|in:processing,in_progress,completed,canceled',
            'notes' => 'nullable|string',
        ]);

        $order->update([
            'status' => $data['status'],
        ]);

        OrderLog::query()->create([
            'order_id' => $order->id,
            'user_id' => $request->user()?->id,
            'status' => $data['status'],
            'message' => $data['notes'] ?? 'Status changed by staff',
            'context' => ['actor' => 'staff'],
        ]);

        return response()->json($order);
    }
}
