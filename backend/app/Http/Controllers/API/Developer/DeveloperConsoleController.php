<?php

namespace App\Http\Controllers\API\Developer;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\UpdateLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeveloperConsoleController extends Controller
{
    public function modules(): JsonResponse
    {
        $modules = Module::query()->with(['versions' => fn ($query) => $query->orderByDesc('released_at')])->get();

        return response()->json($modules);
    }

    public function publishVersion(Request $request, Module $module): JsonResponse
    {
        $data = $request->validate([
            'version' => 'required|string',
            'changelog' => 'required|string',
            'released_at' => 'nullable|date',
            'is_stable' => 'boolean',
        ]);

        $version = ModuleVersion::query()->create([
            'module_id' => $module->id,
            'version' => $data['version'],
            'changelog' => $data['changelog'],
            'released_at' => $data['released_at'] ?? now(),
            'is_stable' => $data['is_stable'] ?? false,
        ]);

        UpdateLog::query()->create([
            'module_version_id' => $version->id,
            'status' => 'released',
            'message' => 'Module version published',
            'context' => ['actor' => 'developer'],
        ]);

        return response()->json($version, 201);
    }

    public function updateLog(Request $request, ModuleVersion $moduleVersion): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|string',
            'message' => 'nullable|string',
            'context' => 'array',
        ]);

        $log = UpdateLog::query()->create([
            'module_version_id' => $moduleVersion->id,
            'status' => $data['status'],
            'message' => $data['message'] ?? 'Status updated',
            'context' => $data['context'] ?? [],
        ]);

        return response()->json($log, 201);
    }
}
