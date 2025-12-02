<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Hotel $hotel): JsonResponse
    {
        $services = Service::query()
            ->where('hotel_id', $hotel->id)
            ->with('category')
            ->orderBy('name')
            ->get();

        return response()->json($services);
    }

    public function store(Request $request, Hotel $hotel): JsonResponse
    {
        $data = $request->validate([
            'service_category_id' => 'required|integer',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration_minutes' => 'nullable|integer',
            'is_active' => 'boolean',
            'metadata' => 'array',
        ]);

        $service = Service::query()->create(array_merge($data, [
            'hotel_id' => $hotel->id,
        ]));

        return response()->json($service, 201);
    }

    public function update(Request $request, Hotel $hotel, Service $service): JsonResponse
    {
        $data = $request->validate([
            'service_category_id' => 'sometimes|integer',
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'duration_minutes' => 'nullable|integer',
            'is_active' => 'boolean',
            'metadata' => 'array',
        ]);

        $service->update($data);

        return response()->json($service);
    }

    public function destroy(Service $service): JsonResponse
    {
        $service->delete();

        return response()->json(['status' => 'deleted']);
    }
}
