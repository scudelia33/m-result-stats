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
        Schema::create('players', function (Blueprint $table) {
            $table->id('player_id')->comment('選手ID');
            $table->string('player_last_name', 50)->comment('選手名字');
            $table->string('player_first_name', 10)->comment('選手名前');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE players COMMENT '選手';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
