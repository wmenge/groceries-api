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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'github_id')) {
                $table->dropIndex('users_github_id_unique');
                $table->dropColumn('github_id');
            }

            if (Schema::hasColumn('users', 'github_token')) {
                $table->dropColumn('github_token');
            }

            if (Schema::hasColumn('users', 'github_refresh_token')) {
                $table->dropColumn('github_refresh_token');
            }

            $table->string('socialite_provider')->nullable();
            $table->string('socialite_id')->nullable();
            $table->string('socialite_token')->nullable();
            $table->string('socialite_refresh_token')->nullable();            

            $table->unique(['socialite_provider', 'socialiate_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('github_id')->nullable();
            $table->string('github_token')->nullable();
            $table->string('github_refresh_token')->nullable();            
            $table->string('password')->nullable()->change();
            $table->unique(['github_id']);

            if (Schema::hasColumn('users', 'socialite_provider')) {
                $table->dropColumn('socialite_provider');
                $table->dropIndex('users_socialite_provide_socialiate_id_unique');
            }

            if (Schema::hasColumn('users', 'socialite_id')) {
                $table->dropColumn('socialite_id');
            }

            if (Schema::hasColumn('users', 'socialite_token')) {
                $table->dropColumn('socialite_token');
            }

            if (Schema::hasColumn('users', 'socialite_refresh_token')) {
                $table->dropColumn('socialite_refresh_token');
            }
        });
    }
};
