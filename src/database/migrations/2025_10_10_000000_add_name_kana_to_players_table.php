<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // 1) player_last_name_kana を追加
        if (Schema::hasColumn('players', 'player_first_name')) {
            Schema::table('players', function (Blueprint $table) {
                $table->string('player_last_name_kana')->nullable()->after('player_first_name')->comment('選手名字かな');
            });
        } else {
            Schema::table('players', function (Blueprint $table) {
                $table->string('player_last_name_kana')->nullable()->comment('選手名字かな');
            });
        }

        // 2) player_first_name_kana を追加
        if (Schema::hasColumn('players', 'player_last_name_kana')) {
            Schema::table('players', function (Blueprint $table) {
                $table->string('player_first_name_kana')->nullable()->after('player_last_name_kana')->comment('選手名前かな');
            });
        } else {
            Schema::table('players', function (Blueprint $table) {
                $table->string('player_first_name_kana')->nullable()->comment('選手名前かな');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // drop は存在チェックして安全に行う
        if (Schema::hasColumn('players', 'player_first_name_kana')) {
            Schema::table('players', function (Blueprint $table) {
                $table->dropColumn('player_first_name_kana');
            });
        }

        if (Schema::hasColumn('players', 'player_last_name_kana')) {
            Schema::table('players', function (Blueprint $table) {
                $table->dropColumn('player_last_name_kana');
            });
        }
    }
};
