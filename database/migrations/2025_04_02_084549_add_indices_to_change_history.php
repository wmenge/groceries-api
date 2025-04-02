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
        Schema::table('change_events', function (Blueprint $table) {
            $table->index(['table', 'object_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_events', function (Blueprint $table) {
            if (Schema::hasColumn('change_events', 'table') && Schema::hasColumn('change_events', 'object_id')) {
                $table->dropIndex('change_events_table_object_id_index');
            }
        });
    }
};
