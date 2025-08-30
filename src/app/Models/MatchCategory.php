<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    // 主キー
    protected $primaryKey = 'match_category_id';
}
