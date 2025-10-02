<?php

namespace App\Services;

use App\Enums\BlankInList;
use App\Models\MatchCategory;
use App\Models\MatchResult;
use App\Models\Player;
use App\Models\Season;
use App\Models\Team;
use App\Traits\CommonFunctionsTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;

class MatchResultService
{
    use CommonFunctionsTrait;

    /**
     * 試合成績一覧表示に必要なデータを準備します。
     *
     * - クエリパラメータのデフォルト値を確保
     * - マスタデータ（matchCategories, players, seasons, teams）を追加
     * - 該当する MatchResult を取得してリクエストにマージ
     *
     * @param Request $request
     * @return Request
     */
    public function prepareIndexData(Request $request): Request
    {
        // クエリパラメータのデフォルトを確保（検索が0件になるような値を使う想定）
        $this->addQueryParameter($request, [
            'match_category_id' => BlankInList::EXIST->value,
            'player_id' => BlankInList::EXIST->value,
            'season_id' => BlankInList::NON->value,
            'team_id' => BlankInList::EXIST->value,
        ]);

        // マスタデータを取得
        $request->merge([
            'matchCategories' => MatchCategory::get(),
            'players' => Player::get(),
            'seasons' => Season::get(),
            'teams' => Team::get(),
        ]);

        // 試合成績を取得
        $matchResults = MatchResult::with([
            'playerAffiliation' => function (HasOne $query) use ($request) {
                $query->equalSeasonId($request->season_id);
            },
            'playerAffiliation.player',
            'playerAffiliation.team:team_id,team_name,background_color',
            'matchInformation.matchSchedule',
            'matchInformation.matchSchedule.season',
            'matchInformation.matchSchedule.matchCategory',
        ])
        ->whereHas('matchInformation.matchSchedule', function ($query) use ($request) {
            $query->when($request->season_id, function ($query) use ($request) {
                $query->equalSeasonId($request->season_id);
            })
            ->when($request->match_category_id, function ($query) use ($request) {
                $query->equalMatchCategoryId($request->match_category_id);
            });
        })
        ->whereHas('playerAffiliation', function ($query) use ($request) {
            $query->when($request->team_id, function ($query) use ($request) {
                $query->equalTeamId($request->team_id);
            })
            ->when($request->player_id, function ($query) use ($request) {
                $query->equalPlayerId($request->player_id);
            })
            ->when($request->season_id, function ($query) use ($request) {
                $query->equalSeasonId($request->season_id);
            });
        })
        ->oldest('match_result_id')
        ->get();

        $request->merge([
            'matchResults' => $matchResults,
        ]);

        return $request;
    }
}
