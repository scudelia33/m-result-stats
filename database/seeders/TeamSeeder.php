<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::truncate();
        //
        $data = [
            ['team_id' => 1, 'team_name' => '赤坂ドリブンズ',],
            ['team_id' => 2, 'team_name' => 'EX風林火山',],
            ['team_id' => 3, 'team_name' => 'KADOKAWAサクラナイツ',],
            ['team_id' => 4, 'team_name' => 'KOMAMI麻雀格闘倶楽部',],
            ['team_id' => 5, 'team_name' => '渋谷ABEMAS',],
            ['team_id' => 6, 'team_name' => 'セガサミーフェニックス',],
            ['team_id' => 7, 'team_name' => 'TEAM雷電',],
            ['team_id' => 8, 'team_name' => 'BEASTX',],
            ['team_id' => 9, 'team_name' => 'U-NEXT Pirates',],
        ];
        Team::insert($data);
    }
}
