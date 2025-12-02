<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request, Hotel $hotel): JsonResponse
    {
        $data = $request->validate([
            'orderable_type' => 'required|string|in:service,spa,restaurant',
            'orderable_id' => 'required|integer',
            'total_amount' => 'required|numeric',
            'notes' => 'nullable|string',
            'user_id' => 'nullable|integer',
        ]);

        $orderableTypeMap = [
            'service' => Service::class,
            'spa' => Service::class,
            'restaurant' => Service::class,
        ];

        $orderableClass = $orderableTypeMap[$data['orderable_type']];

        $order = Order::query()->create([
            'user_id' => $data['user_id'] ?? null,
            'hotel_id' => $hotel->id,
            'orderable_type' => $orderableClass,
            'orderable_id' => $data['orderable_id'],
            'total_amount' => $data['total_amount'],
            'status' => 'pending',
            'reference' => Str::uuid()->toString(),
            'notes' => $data['notes'] ?? null,
            'placed_at' => now(),
        ]);

        OrderLog::query()->create([
            'order_id' => $order->id,
            'user_id' => $data['user_id'] ?? null,
            'status' => 'pending',
            'message' => 'Order created from public endpoint',
            'context' => ['source' => 'guest'],
        ]);

        return response()->json($order, 201);
    }
}
