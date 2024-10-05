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
        Schema::create('teams', function (Blueprint $table) {
            $table->id('team_id')->comment('チームID');
            $table->string('team_name', 50)->comment('チーム名');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE teams COMMENT 'チーム';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
