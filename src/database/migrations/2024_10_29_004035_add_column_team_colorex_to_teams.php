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
            $table->string('team_color_to_text', 10)->after('team_color')->comment('チームカラー(テキスト用)');
            $table->string('team_color_to_graph', 10)->after('team_color_to_text')->comment('チームカラー(グラフ用)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn([
                'team_color_to_text',
                'team_color_to_graph'
            ]);
        });
    }
};
