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
        Schema::create('player_affiliations', function (Blueprint $table) {
            $table->id('player_affiliation_id')->comment('選手所属ID');
            $table->integer('player_id', false, true)->index()->comment('選手ID');
            $table->integer('season_id', false, true)->index()->comment('シーズンID');
            $table->integer('team_id', false, true)->index()->comment('チームID');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE player_affiliations COMMENT '選手所属';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_affiliations');
    }
};
