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
        Schema::table('teams', function (Blueprint $table) {
            // Doctrine DBAL may be required to rename columns on some platforms.
            if (Schema::hasColumn('teams', 'team_color_to_graph') && ! Schema::hasColumn('teams', 'graph_color')) {
                $table->renameColumn('team_color_to_graph', 'graph_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            if (Schema::hasColumn('teams', 'graph_color') && ! Schema::hasColumn('teams', 'team_color_to_graph')) {
                $table->renameColumn('graph_color', 'team_color_to_graph');
            }
        });
    }
};
