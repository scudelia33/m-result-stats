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
        Schema::table('player_affiliations', function (Blueprint $table) {
            $table->smallInteger('view_order', false, true)->nullable()->after('team_id')->comment('表示順');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_affiliations', function (Blueprint $table) {
            $table->dropColumn('view_order');
        });
    }
};
