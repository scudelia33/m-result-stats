<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qualifying_lines', function (Blueprint $table) {
            $table->id('qualifying_line_id')->comment('予選通過ラインID');
            $table->integer('season_id', false, true)->index()->comment('シーズンID');
            $table->integer('match_category_id', false, true)->index()->comment('試合区分ID');
            $table->integer('qualifying_line_team_rank', false, true)->comment('予選通過ラインチーム順位');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE qualifying_lines COMMENT '予選通過ライン';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualifying_lines');
    }
};
