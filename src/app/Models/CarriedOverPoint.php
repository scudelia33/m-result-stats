<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarriedOverPoint extends Model
{
    use HasFactory;
    use SoftDeletes;
    // 主キー
    protected $primaryKey = 'carried_over_point_id';

    /**
     * チームテーブルとの結合
     */
    public function team(): HasOne
    {
        return $this->HasOne(Team::class, 'team_id', 'team_id');
    }

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

    /**
     * チームでの絞り込み
     */
    public function scopeEqualTeamId(Builder $query, int $value): void
    {
        $query->where('team_id', $value);
    }

    // ====================
    // アクセサ
    // ====================
    /**
     * 順位の内訳を取得する ※4-3-2-1の表示形式
     *
     * rank1というカラムは存在しないので、取得時に生成する必要がある
     */
    public function rankDetail(): Attribute
    {
        return Attribute::make(
            get: function(mixed $value, array $attributes) {
                $ranks = [];
                for ($i=1; $i < 5; $i++) {
                    $column = "rank{$i}";
                    $ranks[] = $attributes[$column];
                }
                return implode('-', $ranks);
            }
        );
    }
}
