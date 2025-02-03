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
        Schema::create('shopping_list_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('shopping_list_id')->unsigned();
            $table->integer('grocery_id')->unsigned();
            $table->string('status');
            $table->foreign('shopping_list_id')->references('id')->on('shopping_lists');
            $table->foreign('grocery_id')->references('id')->on('groceries');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_list_entries');
    }
};
