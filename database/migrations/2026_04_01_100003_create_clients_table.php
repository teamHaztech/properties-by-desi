<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone', 20)->index();
            $table->string('email')->nullable();
            $table->enum('buying_type', ['loan', 'cash', 'black_white_mix'])->nullable();
            $table->enum('purpose', ['investment', 'end_use'])->nullable();
            $table->enum('buyer_profile', ['family', 'individual', 'company'])->nullable();
            $table->enum('urgency', ['low', 'medium', 'high', 'immediate'])->default('medium');
            $table->text('address')->nullable();
            $table->string('pan_number', 10)->nullable();
            $table->string('aadhar_number', 12)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
