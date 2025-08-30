<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * チーム名を表示するコンポーネント
 */
class TeamName extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $teamName チーム名
     * @param string $teamColor チームカラー
     */
    public function __construct(
        public string $teamName,
        public string $teamColor,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.team-name');
    }
}
