<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('min_rate_sqm', 10, 2)->nullable()->after('price_per_sqm');
            $table->decimal('max_rate_sqm', 10, 2)->nullable()->after('min_rate_sqm');
            $table->decimal('final_selling_price', 14, 2)->nullable()->after('quoted_price');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['min_rate_sqm', 'max_rate_sqm', 'final_selling_price']);
        });
    }
};
