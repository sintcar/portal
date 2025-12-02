<?php

use App\Http\Controllers\API\Admin\FileUploadController;
use App\Http\Controllers\API\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\API\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\API\Admin\RoleController;
use App\Http\Controllers\API\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\API\Common\OrderController;
use App\Http\Controllers\API\InstallController;
use App\Http\Controllers\API\Developer\DeveloperConsoleController;
use App\Http\Controllers\API\Guest\GuestController;
use App\Http\Controllers\API\Network\NetworkAdminController;
use App\Http\Controllers\API\Staff\RequestController as StaffRequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('guest')->group(function () {
    Route::get('hotels', [GuestController::class, 'hotels']);
    Route::get('hotels/{hotel}/rooms', [GuestController::class, 'rooms']);
    Route::get('hotels/{hotel}/services', [GuestController::class, 'services']);
    Route::get('hotels/{hotel}/spa', [GuestController::class, 'spa']);
    Route::get('hotels/{hotel}/restaurants', [GuestController::class, 'restaurants']);
    Route::get('restaurants/{restaurant}/menu', [GuestController::class, 'menu']);
    Route::get('map', [GuestController::class, 'map']);
    Route::get('hotels/{hotel}/guide', [GuestController::class, 'guide']);
    Route::get('hotels/{hotel}/news', [GuestController::class, 'news']);
    Route::post('hotels/{hotel}/orders', [OrderController::class, 'store']);
});

Route::prefix('install')->group(function () {
    Route::get('status', [InstallController::class, 'status']);
    Route::post('env', [InstallController::class, 'createEnv']);
    Route::post('database', [InstallController::class, 'configureDatabase']);
    Route::post('migrate', [InstallController::class, 'runMigrations']);
    Route::post('key', [InstallController::class, 'generateKey']);
    Route::post('admin', [InstallController::class, 'createAdmin']);
    Route::post('seed', [InstallController::class, 'runSeeder']);
});

Route::prefix('admin')
    ->middleware(['auth:sanctum', 'role:admin'])
    ->group(function () {
        Route::get('hotels/{hotel}/services', [AdminServiceController::class, 'index']);
        Route::post('hotels/{hotel}/services', [AdminServiceController::class, 'store']);
        Route::put('hotels/{hotel}/services/{service}', [AdminServiceController::class, 'update']);
        Route::delete('services/{service}', [AdminServiceController::class, 'destroy']);

        Route::get('hotels/{hotel}/orders', [AdminOrderController::class, 'index']);
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus']);

        Route::get('hotels/{hotel}/news', [AdminNewsController::class, 'index']);
        Route::post('hotels/{hotel}/news', [AdminNewsController::class, 'store']);
        Route::put('news/{news}', [AdminNewsController::class, 'update']);

        Route::get('roles', [RoleController::class, 'index']);
        Route::post('roles', [RoleController::class, 'store']);
        Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions']);
        Route::patch('users/{user}/role', [RoleController::class, 'assignUser']);

        Route::post('uploads', [FileUploadController::class, 'store']);
    });

Route::prefix('staff')
    ->middleware(['auth:sanctum', 'role:staff'])
    ->group(function () {
        Route::get('requests', [StaffRequestController::class, 'inbox']);
        Route::patch('requests/{order}', [StaffRequestController::class, 'progress']);
    });

Route::prefix('network')
    ->middleware(['auth:sanctum', 'role:network-admin'])
    ->group(function () {
        Route::get('hotels', [NetworkAdminController::class, 'hotels']);
        Route::put('hotels/{hotel}', [NetworkAdminController::class, 'updateHotel']);
        Route::get('modules', [NetworkAdminController::class, 'modules']);
        Route::patch('modules/{module}/toggle', [NetworkAdminController::class, 'toggleModule']);
        Route::post('modules/{module}/licenses', [NetworkAdminController::class, 'issueLicense']);
    });

Route::prefix('dev-console')
    ->middleware(['auth:sanctum', 'role:developer'])
    ->group(function () {
        Route::get('modules', [DeveloperConsoleController::class, 'modules']);
        Route::post('modules/{module}/versions', [DeveloperConsoleController::class, 'publishVersion']);
        Route::post('versions/{moduleVersion}/logs', [DeveloperConsoleController::class, 'updateLog']);
    });
