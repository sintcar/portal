<?php

namespace App\Http\Controllers\API\Network;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\License;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NetworkAdminController extends Controller
{
    public function hotels(): JsonResponse
    {
        $hotels = Hotel::query()->with(['services', 'news'])->get();

        return response()->json($hotels);
    }

    public function updateHotel(Request $request, Hotel $hotel): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $hotel->update($data);

        return response()->json($hotel);
    }

    public function modules(): JsonResponse
    {
        $modules = Module::query()->with(['versions', 'licenses'])->get();

        return response()->json($modules);
    }

    public function toggleModule(Module $module): JsonResponse
    {
        $module->update(['is_active' => ! $module->is_active]);

        return response()->json($module);
    }

    public function issueLicense(Request $request, Module $module): JsonResponse
    {
        $data = $request->validate([
            'issued_to' => 'required|string',
            'expires_at' => 'nullable|date',
            'metadata' => 'array',
        ]);

        $license = License::query()->create([
            'module_id' => $module->id,
            'license_key' => Str::uuid()->toString(),
            'issued_to' => $data['issued_to'],
            'issued_at' => now(),
            'expires_at' => $data['expires_at'] ?? null,
            'status' => 'active',
            'metadata' => $data['metadata'] ?? [],
        ]);

        return response()->json($license, 201);
    }
}
