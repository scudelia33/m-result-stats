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
        Schema::create('match_results', function (Blueprint $table) {
            $table->id('match_result_id')->comment('試合成績ID');
            $table->integer('match_id', false, true)->index()->comment('試合ID');
            $table->integer('rank', false, true)->comment('順位');
            $table->integer('player_id', false, true)->index()->comment('選手ID');
            $table->decimal('point', total: 4, places: 1)->comment('ポイント');
            $table->decimal('penalty', total: 4, places: 1)->nullable()->comment('ペナルティ');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE match_results COMMENT '試合成績';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_results');
    }
};
