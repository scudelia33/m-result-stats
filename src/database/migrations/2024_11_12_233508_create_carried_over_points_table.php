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
        Schema::create('carried_over_points', function (Blueprint $table) {
            $table->id('carried_over_point_id')->comment('開始ポイントID');
            $table->integer('season_id', false, true)->index()->comment('シーズンID');
            $table->integer('match_category_id', false, true)->index()->comment('試合区分ID');
            $table->integer('team_id', false, true)->index()->comment('チームID');
            $table->decimal('carried_over_point', total: 4, places: 1)->comment('持ち越し済ポイント');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE carried_over_points COMMENT '持ち越し済ポイント';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carried_over_points');
    }
};
