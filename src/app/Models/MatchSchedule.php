<?php

namespace App\Models;

use App\Traits\CommonFunctionsTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchSchedule extends Model
{
    use CommonFunctionsTrait;
    use HasFactory;
    use SoftDeletes;

    // 主キー
    protected $primaryKey = 'match_date';
    /**
     * 主キーのデータ型
     */
    protected $keyType = 'date';

    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    /**
     * シーズンテーブルとの結合
     */
    public function season(): HasOne
    {
        return $this->HasOne(Season::class, 'season_id', 'season_id');
    }

    /**
     * 試合区分テーブルとの結合
     */
    public function matchCategory(): HasOne
    {
        return $this->HasOne(MatchCategory::class, 'match_category_id', 'match_category_id');
    }

    /**
     * 試合情報テーブルとの結合
     */
    public function matchInformation(): HasOne
    {
        return $this->HasOne(MatchInformation::class, 'match_date', 'match_date');
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

    // ====================
    // アクセサ
    // ====================
    /**
     * 表示用の試合日を取得する
     */
    public function matchDateDisplay(): Attribute
    {
        return Attribute::make(
            get: function() {
                return "{$this->getWithWeekName($this->match_date)}";
            }
        );
    }

    /**
     * キャストする属性の取得
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'match_date' => 'datetime',
        ];
    }
}
