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
        Schema::table('leads', function (Blueprint $table) {

            $table->date('follow_up_date')
                ->nullable()
                ->after('status');

            $table->enum('priority', [
                'Low',
                'Medium',
                'High'
            ])
                ->default('Medium')
                ->after('follow_up_date');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {

            $table->dropColumn([
                'follow_up_date',
                'priority'
            ]);

        });
    }
};