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
        Schema::create('match_information', function (Blueprint $table) {
            $table->id('match_id')->comment('試合ID');
            $table->date('match_date')->index()->comment('試合日');
            $table->integer('match_order', false, true)->comment('試合順序');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE match_information COMMENT '試合情報';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_information');
    }
};
