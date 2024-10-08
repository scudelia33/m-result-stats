<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory;
    use SoftDeletes;

    // 主キー
    protected $primaryKey = 'player_id';

    // ====================
    // アクセサ
    // ====================
    /**
     * 選手名(名字+名前)を取得する
     */
    public function playerName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['player_last_name'] . ' ' . $attributes['player_first_name'],
        );
    }
}
