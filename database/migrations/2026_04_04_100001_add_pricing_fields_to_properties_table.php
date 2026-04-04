<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // City from database
            $table->foreignId('city_id')->nullable()->after('location')->constrained('cities')->nullOnDelete();

            // Pricing breakdown
            $table->decimal('owner_expected_price', 14, 2)->nullable()->after('price');
            $table->decimal('quoted_price', 14, 2)->nullable()->after('owner_expected_price');
            $table->decimal('commission_percent', 5, 2)->default(2.00)->after('quoted_price');
            $table->decimal('commission_amount', 14, 2)->nullable()->after('commission_percent');

            // Negotiation
            $table->boolean('is_negotiable')->default(false)->after('commission_amount');
            $table->decimal('negotiable_price', 14, 2)->nullable()->after('is_negotiable');

            // Plot total price auto-calc
            $table->decimal('total_plot_price', 14, 2)->nullable()->after('negotiable_price');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn([
                'city_id', 'owner_expected_price', 'quoted_price',
                'commission_percent', 'commission_amount',
                'is_negotiable', 'negotiable_price', 'total_plot_price',
            ]);
        });
    }
};
