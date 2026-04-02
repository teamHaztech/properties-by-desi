<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('state')->default('Goa');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot: lead can be interested in multiple cities
        Schema::create('city_lead', function (Blueprint $table) {
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->primary(['city_id', 'lead_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('city_lead');
        Schema::dropIfExists('cities');
    }
};
