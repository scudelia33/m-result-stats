<?php

namespace App\Traits;

use DateTime;
use Illuminate\Http\Request;

/**
 * 共通関数をまとめたトレイト
 */
trait CommonFunctionsTrait
{
    /**
     * リクエスト内に存在しないクエリパラメータをリクエストに追加する
     *
     * @param Request $request
     * @param array $addQueryParameters 追加クエリパラメータ
     */
    public function addQueryParameter(Request $request, array $addQueryParameters)
    {
        foreach ($addQueryParameters as $key => $value) {
            $request->mergeIfMissing([
                $key => $value,
            ]);
        }
    }

    /**
     * 日付から曜日を取得する
     *
     * @param DateTime $date 日付
     * @return string 曜日
     */
    public function getWeekName(DateTime $date): string
    {
        $weeks = ['日','月','火','水','木','金','土',];
        return $weeks[$date->format('w')];
    }

    /**
     * 日付と曜日を同時に表示する
     *
     * @param DateTime $date 日付
     * @return string "yyyy-mm-dd(曜日)"の形式
     */
    public function getWithWeekName(DateTime $date): string
    {
        $weekName = $this->getWeekName($date);
        return "{$date->format('Y-m-d')}({$weekName})";
    }
}
