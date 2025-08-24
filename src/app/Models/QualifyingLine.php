<?php

namespace App\Models;

use Illuminate\database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QualifyingLine extends Model
{
    use HasFactory;
    use SoftDeletes;

    // 主キー
    protected $primaryKey = 'qualifying_line_id';

    /**
     * シーズンでの絞り込み
     */
    public function scopeEqualSeasonId(Builder $query, int $value): void
    {
        $query->where('season_id', $value);
    }

    /**
     * 試合区分での絞り込み
     */
    public function scopeEqualMatchCategoryId(Builder $query, int $value): void
    {
        $query->where('match_category_id', $value);
    }
}
