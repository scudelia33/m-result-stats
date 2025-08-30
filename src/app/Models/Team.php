<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sortable;
    // 主キー
    protected $primaryKey = 'team_id';

    /**
     * 持ち越しポイントポイントテーブルとの結合
     */
    public function carriedOverPoint(): HasOne
    {
        return $this->hasOne(CarriedOverPoint::class, 'team_id', 'team_id');
    }

    /**
     * チームでの絞り込み
     */
    public function scopeEqualTeamId(Builder $query, int $value): void
    {
        $query->where('team_id', $value);
    }
}
