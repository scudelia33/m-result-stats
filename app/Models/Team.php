<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sortable;
    // 主キー
    protected $primaryKey = 'team_id';
}
