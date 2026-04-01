<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['plot', 'villa', 'flat'])->index();
            $table->enum('sub_type', ['orchard', 'settlement', 'sanad', 'na'])->nullable();
            $table->string('location');
            $table->string('area')->nullable();
            $table->decimal('price', 14, 2);
            $table->decimal('price_per_sqm', 10, 2)->nullable();
            $table->decimal('size_sqm', 10, 2)->nullable();
            $table->string('size_label')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');
            $table->json('tags')->nullable();
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();
            $table->string('map_link')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('owner_phone', 20)->nullable();
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
