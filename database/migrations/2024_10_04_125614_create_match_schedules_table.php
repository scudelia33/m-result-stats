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
        Schema::create('match_schedules', function (Blueprint $table) {
            $table->date('match_date')->primary()->comment('試合日');
            $table->integer('season_id', false, true)->index()->comment('シーズンID');
            $table->integer('match_category_id', false, true)->index()->comment('試合区分ID');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE match_schedules COMMENT '試合日程';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_schedules');
    }
};
