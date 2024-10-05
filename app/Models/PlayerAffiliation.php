<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerAffiliation extends Model
{
    use HasFactory;
    use SoftDeletes;

    // 主キー
    protected $primaryKey = 'player_affiliation_id';
}
