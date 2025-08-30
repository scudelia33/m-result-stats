<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchResult extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * 主キー
     */
    protected $primaryKey = 'match_result_id';

    /**
     * 試合情報テーブルとの結合
     */
    public function matchInformation(): HasOne
    {
        return $this->HasOne(MatchInformation::class, 'match_id', 'match_id');
    }

    /**
     * 選手テーブルとの結合
     */
    public function player(): HasOne
    {
        return $this->HasOne(Player::class, 'player_id', 'player_id');
    }

    /**
     * チームテーブルとの結合
     *
     * 当モデルにはteam_idは存在しないので、使用する場合は外部結合などで参照する必要がある
     */
    public function team(): HasOne
    {
        return $this->HasOne(Team::class, 'team_id', 'team_id');
    }

    /**
     * 選手所属テーブルとの結合
     */
    public function playerAffiliation(): HasOne
    {
        return $this->HasOne(PlayerAffiliation::class, 'player_id', 'player_id');
    }

    /**
     * 選手での絞り込み
     */
    public function scopeEqualPlayerId(Builder $query, int $value): void
    {
        $query->where('player_id', $value);
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

    /**
     * トップ率を取得する ※順位1 / 試合数
     *
     * rank1というカラムは存在しないので、取得時に生成する必要がある
     */
    public function topRatio(): Attribute
    {
        return Attribute::make(
            get: function(mixed $value, array $attributes) {
                $topRatio = $attributes['rank1'] / $attributes['match_count'] * 100;
                return sprintf("%.1f%%", $topRatio);
            }
        );
    }

    /**
     * ラス回避率を取得する ※順位1+順位2+順位3 / 試合数
     *
     * rank1というカラムは存在しないので、取得時に生成する必要がある
     */
    public function avoidBottomRatio(): Attribute
    {
        return Attribute::make(
            get: function(mixed $value, array $attributes) {
                $notBottom = (function () use ($attributes) {
                    $rank = 0;
                    for ($i=1; $i < 4; $i++) {
                        $column = "rank{$i}";
                        $rank += $attributes[$column];
                    }
                    return $rank;
                })();
                $topRatio = $notBottom / $attributes['match_count'] * 100;
                return sprintf("%.1f%%", $topRatio);
            }
        );
    }
}
