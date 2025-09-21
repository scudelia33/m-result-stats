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
        if (Schema::hasColumn('teams', 'team_color_to_text')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->renameColumn('team_color_to_text', 'background_color');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('teams', 'background_color')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->renameColumn('background_color', 'team_color_to_text');
            });
        }
    }
};
