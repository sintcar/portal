<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'file' => 'required|file|max:20480',
        ]);

        $path = $data['file']->store('uploads');

        return response()->json([
            'path' => $path,
            'url' => Storage::url($path),
        ], 201);
    }
}
