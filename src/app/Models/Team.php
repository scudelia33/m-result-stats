<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;
    // Removed Sortable trait (kyslik/column-sortable) to avoid dependency on external package.
    // 主キー
    protected $primaryKey = 'team_id';

    /**
     * Mass assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'team_id',
        'team_name',
        'team_name_shortened',
        'team_color',
        'background_color',
        'graph_color',
    ];

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
