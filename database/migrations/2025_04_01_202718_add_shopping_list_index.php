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
        Schema::table('shopping_list_entries', function (Blueprint $table) {
            $table->index(['shopping_list_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shopping_list_entries', function (Blueprint $table) {
            if (Schema::hasColumn('shopping_list_entries', 'shopping_list_id')) {
                $table->dropIndex('shopping_list_entries_shopping_list_id_index');
            }
        });
    }
};
