<?php

namespace Database\Seeders;

use App\Models\QualifyingLine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QualifyingLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QualifyingLine::truncate();
        //
        $data = [
            ['season_id' => 1, 'match_category_id' => 1, 'qualifying_line_team_rank' => 4,],
            ['season_id' => 2, 'match_category_id' => 1, 'qualifying_line_team_rank' => 6,],
            ['season_id' => 2, 'match_category_id' => 2, 'qualifying_line_team_rank' => 4,],
            ['season_id' => 3, 'match_category_id' => 1, 'qualifying_line_team_rank' => 6,],
            ['season_id' => 3, 'match_category_id' => 2, 'qualifying_line_team_rank' => 4,],
            ['season_id' => 4, 'match_category_id' => 1, 'qualifying_line_team_rank' => 6,],
            ['season_id' => 4, 'match_category_id' => 2, 'qualifying_line_team_rank' => 4,],
            ['season_id' => 5, 'match_category_id' => 1, 'qualifying_line_team_rank' => 6,],
            ['season_id' => 5, 'match_category_id' => 2, 'qualifying_line_team_rank' => 4,],
            ['season_id' => 6, 'match_category_id' => 1, 'qualifying_line_team_rank' => 6,],
            ['season_id' => 6, 'match_category_id' => 2, 'qualifying_line_team_rank' => 4,],
            ['season_id' => 7, 'match_category_id' => 1, 'qualifying_line_team_rank' => 6,],
            ['season_id' => 7, 'match_category_id' => 2, 'qualifying_line_team_rank' => 4,],
        ];
        QualifyingLine::insert($data);
    }
}
