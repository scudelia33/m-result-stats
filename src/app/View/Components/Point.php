<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * ポイントを表示するコンポーネント
 */
class Point extends Component
{
    /**
     * Create a new component instance.
     *
     * @param float $point ポイント
     */
    public function __construct(
        public float $point,
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.point');
    }
}
