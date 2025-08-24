<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * 持ち越しポイントを合算するチェックボックスを表示するコンポーネント
 */
class IsCombineCarriedOverPoint extends Component
{
    /**
     * Create a new component instance.
     *
     * @param bool $isCombineCarriedOverPoint 持ち越しポイントを合算するか否か
     */
    public function __construct(
        public ?bool $isCombineCarriedOverPoint = false,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.is-combine-carried-over-point');
    }
}
