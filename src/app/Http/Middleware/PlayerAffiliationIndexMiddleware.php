<?php

namespace App\Http\Middleware;

use App\Enums\BlankInList;
use App\Models\PlayerAffiliation;
use App\Models\Season;
use App\Models\Team;
use App\Traits\CommonFunctionsTrait;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerAffiliationIndexMiddleware
{
    use CommonFunctionsTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ここに前処理を記述
        // クエリパラメータが存在しない場合を考慮して、クエリパラメータの追加
        $this->addQueryParameter($request, [
            'season_id' => BlankInList::NON->value,
            'team_id' => BlankInList::NON->value,
        ]);

        // マスタの取得
        $request->merge([
            'seasons' => Season::get(),
            'teams' => Team::get(),
        ]);

        // 選手所属一覧の取得
        $playerAffiliations = PlayerAffiliation::with([
            'player',
            'season',
            'team',
        ])
        ->when($request->season_id, function (Builder $query) use ($request) {
            $query->equalSeasonId($request->season_id);
        })
        ->when($request->team_id, function (Builder $query) use ($request) {
            $query->equalTeamId($request->team_id);
        })
        ->orderBy('team_id')
        ->orderBy('season_id')
        ->orderBy('view_order')
        ->get()
        ;

        $request->merge([
            'playerAffiliations' => $playerAffiliations,
        ]);

        return $next($request);
        // ここに後処理を記述
    }
}
