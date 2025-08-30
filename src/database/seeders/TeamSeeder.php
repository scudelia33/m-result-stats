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
            ['team_id' => 1, 'team_name' => '赤坂ドリブンズ', 'team_name_shortened' => 'ドリブンズ', 'team_color' => 'b2d235', 'team_color_to_text' => '189116', 'team_color_to_graph' => 'b2d235',],
            ['team_id' => 2, 'team_name' => 'EX風林火山', 'team_name_shortened' => '風林火山', 'team_color' => 'e60012', 'team_color_to_text' => 'e60012', 'team_color_to_graph' => 'e60012',],
            ['team_id' => 3, 'team_name' => 'KADOKAWAサクラナイツ', 'team_name_shortened' => 'サクラナイツ', 'team_color' => 'ef92ae', 'team_color_to_text' => 'ef92ae', 'team_color_to_graph' => 'ef92ae',],
            ['team_id' => 4, 'team_name' => 'KOMAMI麻雀格闘倶楽部', 'team_name_shortened' => '麻雀格闘倶楽部', 'team_color' => 'b60014', 'team_color_to_text' => 'b60014', 'team_color_to_graph' => 'b60014',],
            ['team_id' => 5, 'team_name' => '渋谷ABEMAS', 'team_name_shortened' => 'ABEMAS', 'team_color' => 'bfa566', 'team_color_to_text' => 'bfa566', 'team_color_to_graph' => 'f49100',],
            ['team_id' => 6, 'team_name' => 'セガサミーフェニックス', 'team_name_shortened' => 'フェニックス', 'team_color' => 'e14909', 'team_color_to_text' => 'e14909', 'team_color_to_graph' => 'e14909',],
            ['team_id' => 7, 'team_name' => 'TEAM雷電', 'team_name_shortened' => '雷電', 'team_color' => 'fedf00', 'team_color_to_text' => 'cbb30f', 'team_color_to_graph' => 'fedf00',],
            ['team_id' => 8, 'team_name' => 'BEAST X', 'team_name_shortened' => 'BEAST', 'team_color' => '144324', 'team_color_to_text' => '144324', 'team_color_to_graph' => '189116',],
            ['team_id' => 9, 'team_name' => 'U-NEXT Pirates', 'team_name_shortened' => 'Pirates', 'team_color' => '008fd0', 'team_color_to_text' => '008fd0', 'team_color_to_graph' => '008fd0',],
        ];
        Team::insert($data);
    }
}
