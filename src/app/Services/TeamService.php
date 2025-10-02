<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Collection;

class TeamService
{
    /**
     * team_name 昇順で全てのチームを返します。
     *
     * @return Collection<int, Team>
     */
    public function getAllOrderedTeams(): Collection
    {
        // トレイトに依存しないよう、コントローラと同様に明示的に orderBy を使用
        return Team::orderBy('team_name', 'asc')->get();
    }
}
