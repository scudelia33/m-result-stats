<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * 通過までのポイント差を表示するコンポーネント
 */
class QualifyingDifferencePoint extends Component
{

    /**
     * Create a new component instance.
     *
     * @param int $teamRank チーム順位
     * @param float $sumPoint 合計ポイント
     * @param int $qualifyingLineTeamRank 通過ラインチーム順位
     * @param ?float $qualifyingLineTeamPoint 通過ラインチームポイント
     */
    public function __construct(
        public int $teamRank,
        public float $sumPoint,
        public int $qualifyingLineTeamRank,
        public ?float $qualifyingLineTeamPoint,
    )
    {
    }

    /**
     * 通過ラインチームポイントとの差を取得する
     *
     * @return string チームポイントとの差 ※通過ラインより上の場合は'-'を返す
     */
    public function getDifferencePoint(): string
    {
        // 予選通過ラインの順位チーム順位 <= 予選通過ラインの場合
        if ($this->teamRank <= $this->qualifyingLineTeamRank)
        {
            return '-';
        }
        return bcsub($this->qualifyingLineTeamPoint, $this->sumPoint, 1);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.qualifying-difference-point');
    }
}
