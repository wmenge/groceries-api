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
        Schema::table('groceries', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->default('');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('shopping_lists', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->default('');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('shopping_list_entries', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->default('');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('change_events', function (Blueprint $table) {
            $table->id();
            $table->string('table');
            $table->integer('object_id');
            //$table->id('type');
            $table->string('changed');
            $table->timestamps();
            //$table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groceries', function (Blueprint $table) {
            if (Schema::hasColumn('groceries', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('shopping_lists', function (Blueprint $table) {
            if (Schema::hasColumn('shopping_lists', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('shopping_list_entries', function (Blueprint $table) {
            if (Schema::hasColumn('shopping_list_entries', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::dropIfExists('change_events');
    }
};
