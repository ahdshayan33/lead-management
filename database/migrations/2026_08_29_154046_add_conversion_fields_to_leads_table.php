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

            $table->timestamp('converted_at')->nullable()->after('status');

            $table->decimal('conversion_value', 12, 2)
                ->nullable()
                ->after('converted_at');

            $table->text('conversion_notes')
                ->nullable()
                ->after('conversion_value');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {

            $table->dropColumn([
                'converted_at',
                'conversion_value',
                'conversion_notes',
            ]);

        });
    }
};