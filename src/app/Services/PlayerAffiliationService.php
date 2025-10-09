<?php

namespace App\Services;

use App\Enums\BlankInList;
use App\Models\PlayerAffiliation;
use App\Models\Season;
use App\Models\Team;
use App\Traits\CommonFunctionsTrait;
use Illuminate\Http\Request;

class PlayerAffiliationService
{
    use CommonFunctionsTrait;

    /**
     * 選手所属一覧ビューで使用するデータを準備します。
     *
     * 以前のミドルウェアの処理を再現します：
     * - クエリパラメータのデフォルト値を確保
     * - マスタデータ（seasons, teams）を追加
     * - playerAffiliations コレクションを取得してリクエストにマージ
     *
     * @param Request $request
     * @return Request 追加データをマージした同じ Request インスタンス
     */
    public function prepareIndexData(Request $request): Request
    {
        // クエリパラメータのデフォルトを確保
        $this->addQueryParameter($request, [
            'season_id' => BlankInList::NON->value,
            'team_id' => BlankInList::NON->value,
        ]);

        // マスタデータ（seasons, teams）を追加
        $request->merge([
            'seasons' => Season::get(),
            'teams' => Team::get(),
        ]);

        // 選手所属一覧を取得
        $playerAffiliations = PlayerAffiliation::with([
            'player',
            'season',
            'team',
        ])
        ->when($request->season_id, function ($query) use ($request) {
            $query->equalSeasonId($request->season_id);
        })
        ->when($request->team_id, function ($query) use ($request) {
            $query->equalTeamId($request->team_id);
        })
        ->orderBy([
            'team_id',
            'season_id',
            'view_order',
        ])
        ->get();

        $request->merge(['playerAffiliations' => $playerAffiliations]);

        return $request;
    }
}
