<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * チーム一覧を表示するリストボックス
 */
class TeamList extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $teamId 選択されたチームID
     * @param Collection $teams DBから取得したチーム
     * @param boolean $isAddEmpty リストに空白を追加するか
     */
    public function __construct(
        public ?string $teamId,
        public Collection $teams,
        public bool $isAddEmpty = false,
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.team-list');
    }
}
