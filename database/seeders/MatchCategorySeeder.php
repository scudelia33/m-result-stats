<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MatchCategory;

class MatchCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MatchCategory::truncate();
        //
        $data = [
            ['match_category_id' => 1, 'match_category_name' => 'レギュラー', 'match_category_name_abbreviation' => 'R',],
            ['match_category_id' => 2, 'match_category_name' => 'セミファイナル', 'match_category_name_abbreviation' => 'S',],
            ['match_category_id' => 3, 'match_category_name' => 'ファイナル', 'match_category_name_abbreviation' => 'F',],
        ];
        MatchCategory::insert($data);
    }
}
