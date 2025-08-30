<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * 試合カテゴリ一覧を表示するリストボックス
 */
class MatchCategoryList extends Component
{
    /**
     * Create a new component instance.
     *
     * @param string $matchCategoryId 選択された試合カテゴリID
     * @param Collection $matchCategories DBから取得した試合カテゴリ
     * @param boolean $isAddEmpty リストに空白を追加するか
     */
    public function __construct(
        public ?string $matchCategoryId,
        public Collection $matchCategories,
        public bool $isAddEmpty = false,
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.match-category-list');
    }
}
