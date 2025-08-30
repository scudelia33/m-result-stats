<?php

namespace App\Traits;

use App\Models\MatchSchedule;
use Illuminate\Database\Eloquent\Builder;

/**
 * M統計に関する関数をまとめたトレイト
 */
trait MstatsFunctionsTrait
{
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
}
