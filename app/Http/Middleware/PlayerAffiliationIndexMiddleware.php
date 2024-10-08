<?php

namespace App\Http\Middleware;

use App\Models\PlayerAffiliation;
use App\Models\Season;
use App\Models\Team;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerAffiliationIndexMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ここに前処理を記述

        // マスタの取得
        $request->merge([
            'seasons' => Season::get(),
            'teams' => Team::get(),
        ]);

        $season_id = $request->season_id;
        $team_id = $request->team_id;
        // 選手所属一覧の取得
        $player_affiliations = PlayerAffiliation::with([
            'player',
            'season',
            'team',
        ])
        ->when($season_id, function (Builder $query, int $season_id) {
            $query->equalSeasonId($season_id);
        })
        ->when($team_id, function (Builder $query, int $team_id) {
            $query->equalTeamId($team_id);
        })
        ->orderBy('team_id')
        ->orderBy('season_id')
        ->orderBy('player_id')
        ->get()
        ;

        $request->merge([
            'player_affiliations' => $player_affiliations,
        ]);

        return $next($request);
        // ここに後処理を記述
    }
}
