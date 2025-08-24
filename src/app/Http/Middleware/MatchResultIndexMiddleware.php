<?php

namespace App\Http\Middleware;

use App\Enums\BlankInList;
use App\Models\MatchCategory;
use App\Models\MatchResult;
use App\Models\Player;
use App\Models\Season;
use App\Models\Team;
use App\Traits\CommonFunctionsTrait;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchResultIndexMiddleware
{
    use CommonFunctionsTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ====================
        // ここに前処理を記述
        // ====================
        // クエリパラメータが存在しない場合を考慮して、クエリパラメータの追加
        // 検索結果が0件になるように-1を指定している
        $this->addQueryParameter($request, [
            'match_category_id' => BlankInList::EXIST->value,
            'player_id' => BlankInList::EXIST->value,
            'season_id' => BlankInList::NON->value,
            'team_id' => BlankInList::EXIST->value,
        ]);

        // マスタの取得
        $request->merge([
            'matchCategories' => MatchCategory::get(),
            'players' => Player::get(),
            'seasons' => Season::get(),
            'teams' => Team::get(),
        ]);

        // 試合結果の取得
        $matchResults = MatchResult::with([
            'playerAffiliation' => function (HasOne $query) use ($request) {
                $query->equalSeasonId($request->season_id);
            },
            'playerAffiliation.player',
            'playerAffiliation.team:team_id,team_name,team_color_to_text',
            'matchInformation.matchSchedule',
            'matchInformation.matchSchedule.season',
            'matchInformation.matchSchedule.matchCategory',
        ])
        ->whereHas('matchInformation.matchSchedule', function (Builder $query) use ($request) { // 試合日テーブルとの結合
            $query->when($request->season_id, function (Builder $query) use ($request) {
                $query->equalSeasonId($request->season_id);
            })
            ->when($request->match_category_id, function (Builder $query) use ($request) {
                $query->equalMatchCategoryId($request->match_category_id);
            })
            ;
        })
        ->whereHas('playerAffiliation', function (Builder $query) use ($request) { // 選手所属テーブルとの結合
            $query->when($request->team_id, function (Builder $query) use ($request) {
                $query->equalTeamId($request->team_id);
            })
            ->when($request->player_id, function (Builder $query) use ($request) {
                $query->equalPlayerId($request->player_id);
            })
            ->when($request->season_id, function (Builder $query) use ($request) {
                $query->equalSeasonId($request->season_id);
            })
            ;
        })
        ->oldest('match_result_id')
        ->get()
        ;

        $request->merge([
            'matchResults' => $matchResults,
        ]);

        return $next($request);
        // ====================
        // ここに後処理を記述
        // ====================
    }
}
