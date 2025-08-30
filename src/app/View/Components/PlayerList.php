<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * 選手一覧を表示するリストボックス
 */
class PlayerList extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $playerId 選択された選手ID
     * @param Collection $players DBから取得した選手
     * @param boolean $isAddEmpty リストに空白を追加するか
     */
    public function __construct(
        public ?string $playerId,
        public Collection $players,
        public bool $isAddEmpty = false,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.player-list');
    }
}
