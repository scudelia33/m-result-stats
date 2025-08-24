<?php

namespace App\Models;

use App\Traits\CommonFunctionsTrait;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchInformation extends Model
{
    use CommonFunctionsTrait;
    use HasFactory;
    use SoftDeletes;

    // 主キー
    protected $primaryKey = 'match_id';

    /**
     * 試合スケジュールテーブルとの結合
     */
    public function matchSchedule(): HasOne
    {
        return $this->HasOne(MatchSchedule::class, 'match_date', 'match_date');
    }

    /**
     * 試合成績テーブルとの結合
     */
    public function matchResult(): HasOne
    {
        return $this->hasOne(MatchResult::class, 'match_id', 'match_id');
    }
    public function matchResults(): HasMany
    {
        return $this->hasMany(MatchResult::class, 'match_id', 'match_id');
    }

    // ====================
    // アクセサ
    // ====================
    /**
     * 表示用の試合日を取得する
     */
    public function matchDateOrderDisplay(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                return "{$this->getWithWeekName($this->match_date)} #{$attributes['match_order']}";
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
