<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->index();
            $table->string('email')->nullable();
            $table->enum('source', [
                'call', 'whatsapp', 'instagram', 'facebook', 'referral', 'website', 'walk_in', 'other'
            ])->default('call');
            $table->enum('status', [
                'new', 'contacted', 'spoken', 'interested', 'not_interested',
                'visited_site', 'follow_up_required', 'loan_processing',
                'closed_won', 'closed_lost'
            ])->default('new');
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->string('preferred_property_type')->nullable();
            $table->string('location_preference')->nullable();
            $table->enum('urgency', ['low', 'medium', 'high', 'immediate'])->default('medium');
            $table->boolean('is_converted')->default(false);
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['phone', 'deleted_at'], 'leads_phone_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
