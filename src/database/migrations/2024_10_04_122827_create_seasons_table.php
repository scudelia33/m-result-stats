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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id('season_id')->comment('シーズンID');
            $table->string('season_name', 50)->comment('シーズン名');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE seasons COMMENT 'シーズン';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
