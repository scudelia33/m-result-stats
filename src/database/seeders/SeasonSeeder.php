<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Season;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Season::truncate();
        //
        $data = [
            ['season_id' => 1, 'season_name' => '2018-19',],
            ['season_id' => 2, 'season_name' => '2019-20',],
            ['season_id' => 3, 'season_name' => '2020-21',],
            ['season_id' => 4, 'season_name' => '2021-22',],
            ['season_id' => 5, 'season_name' => '2022-23',],
            ['season_id' => 6, 'season_name' => '2023-24',],
            ['season_id' => 7, 'season_name' => '2024-25',],
        ];
        Season::insert($data);
    }
}
