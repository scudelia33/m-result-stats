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
        Schema::create('match_categories', function (Blueprint $table) {
            $table->id('match_category_id')->comment('試合カテゴリーID');
            $table->string('match_category_name', 50)->comment('試合カテゴリー名');
            $table->string('match_category_name_abbreviation', 10)->comment('試合カテゴリー略称');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE match_categories COMMENT '試合カテゴリー';");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_categories');
    }
};
