<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_category_id')->constrained()->cascadeOnDelete();
            $table->string('number');
            $table->string('name')->nullable();
            $table->unsignedInteger('floor')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('capacity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['available', 'occupied', 'maintenance', 'cleaning'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->json('attributes')->nullable();
            $table->timestamps();

            $table->unique(['hotel_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
