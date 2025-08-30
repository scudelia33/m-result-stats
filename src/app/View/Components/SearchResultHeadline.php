<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * 検索結果に対する見出しを表示するコンポーネント
 */
class SearchResultHeadline extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $textStart 見出しの左側
     * @param string $textCenter 見出しの真ん中
     * @param string $textEnd 見出しの右側
     */
    public function __construct(
        public ?string $textStart,
        public ?string $textCenter,
        public ?string $textEnd,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.search-result-headline');
    }
}
