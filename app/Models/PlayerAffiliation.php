<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerAffiliation extends Model
{
    use HasFactory;
    use SoftDeletes;

    // 主キー
    protected $primaryKey = 'player_affiliation_id';

    /**
     * 選手テーブルとの結合
     */
    public function player(): HasOne
    {
        return $this->HasOne(Player::class, 'player_id', 'player_id');
    }

    /**
     * シーズンテーブルとの結合
     */
    public function season(): HasOne
    {
        return $this->HasOne(Season::class, 'season_id', 'season_id');
    }

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
     * チームでの絞り込み
     */
    public function scopeEqualTeamId(Builder $query, int $value): void
    {
        $query->where('team_id', $value);
    }
}
