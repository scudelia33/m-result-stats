<?php

namespace App\Traits;

use App\Models\MatchSchedule;
use App\Models\PlayerAffiliation;
use Illuminate\Database\Eloquent\Builder;

/**
 * M統計に関する関数をまとめたトレイト
 */
trait MstatsFunctionsTrait
{
    /**
     * 順位1-4を取得するSQLを生成
     *
     * @param Builder $query
     */
    public function generationSqlOfRank(Builder $query)
    {
        // 順位1-4を取得するSQLを生成
        for ($i = 1; $i < 5; $i++) {
            $column = "COUNT(CASE WHEN `rank` = {$i} THEN `rank` ELSE null END) AS rank{$i}";
            $query->selectRaw($column);
        }
    }

    /**
     * 表示用の最終試合日を取得
     *
     * @param int $seasonId シーズンID
     * @param int $matchCategoryId 試合カテゴリID
     */
    public function getMatchLastDateDisplay(int $seasonId, int $matchCategoryId)
    {
        $date = MatchSchedule::selectRaw('MAX(match_date) as last_date')
            ->equalSeasonId($seasonId) // シーズンでの絞り込み
            ->equalMatchCategoryId($matchCategoryId) // 試合カテゴリーでの絞り込み
            ->whereHas('matchInformation.matchResult', function (Builder $query) {
                // 試合が開催されている試合日を取得
                // 試合が開催されていない場合は選手IDが0となる
                $query->where('player_id', '<>', 0);
            })
            ->first()
            ->last_date
            ;
        if ($date == null)
        {
            return null;
        }
        $converted = date_create_from_format('Y-m-d', $date)->format('Y/m/d');
        return " {$converted}時点";
    }

    /**
     * 結合用の成績所属テーブルの定義を返す
     *
     * @param int $seasonId シーズンID
     * @return 結合用の成績所属テーブルの定義
     */
    public function getDefinitionOfPlayerAffiliation(int $seasonId)
    {
        // チームIDでグルーピングするために、結合用の成績所属テーブルの定義
        return PlayerAffiliation::select(
            'player_id as player_id_pa',
            'team_id',
        )
        ->equalSeasonId($seasonId)
        ;
    }
}
