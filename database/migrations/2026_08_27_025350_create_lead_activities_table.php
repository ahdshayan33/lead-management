<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lead_activities', function (Blueprint $table) {

            $table->id();

            // The lead this activity belongs to
            $table->foreignId('lead_id')
                ->constrained('leads')
                ->cascadeOnDelete();

            // The staff/admin member who recorded the activity
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Type of communication
            $table->enum('communication_method', [
                'Call',
                'WhatsApp',
                'Email',
                'Meeting',
                'Other'
            ]);

            // Activity details
            $table->text('notes');

            // Optional follow-up date
            $table->date('follow_up_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_activities');
    }
};