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
        Schema::table('lead_activities', function (Blueprint $table) {

            // Identify what happened during the activity
            $table->string('activity_type')->after('user_id');

            // Automated system activities may not have a user
            $table->foreignId('user_id')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_activities', function (Blueprint $table) {

            $table->dropColumn('activity_type');

            $table->foreignId('user_id')
                ->nullable(false)
                ->change();
        });
    }
};