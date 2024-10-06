<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Player::truncate();
        //
        $data = [
            ['player_id' => 1, 'player_last_name' => '園田', 'player_first_name' => '賢',],
            ['player_id' => 2, 'player_last_name' => '鈴木', 'player_first_name' => 'たろう',],
            ['player_id' => 3, 'player_last_name' => '浅見', 'player_first_name' => '真紀',],
            ['player_id' => 4, 'player_last_name' => '渡辺', 'player_first_name' => '太',],
            ['player_id' => 5, 'player_last_name' => '二階堂', 'player_first_name' => '亜樹',],
            ['player_id' => 6, 'player_last_name' => '勝又', 'player_first_name' => '健志',],
            ['player_id' => 7, 'player_last_name' => '松ヶ瀬', 'player_first_name' => '隆弥',],
            ['player_id' => 8, 'player_last_name' => '二階堂', 'player_first_name' => '留美',],
            ['player_id' => 9, 'player_last_name' => '内川', 'player_first_name' => '幸太郎',],
            ['player_id' => 10, 'player_last_name' => '岡田', 'player_first_name' => '紗佳',],
            ['player_id' => 11, 'player_last_name' => '堀', 'player_first_name' => '慎吾',],
            ['player_id' => 12, 'player_last_name' => '渋川', 'player_first_name' => '難波',],
            ['player_id' => 13, 'player_last_name' => '佐々木', 'player_first_name' => '寿人',],
            ['player_id' => 14, 'player_last_name' => '高宮', 'player_first_name' => 'まり',],
            ['player_id' => 15, 'player_last_name' => '伊達', 'player_first_name' => '朱里紗',],
            ['player_id' => 16, 'player_last_name' => '滝沢', 'player_first_name' => '和典',],
            ['player_id' => 17, 'player_last_name' => '多井', 'player_first_name' => '隆晴',],
            ['player_id' => 18, 'player_last_name' => '白鳥', 'player_first_name' => '翔',],
            ['player_id' => 19, 'player_last_name' => '松本', 'player_first_name' => '吉弘',],
            ['player_id' => 20, 'player_last_name' => '日向', 'player_first_name' => '藍子',],
            ['player_id' => 21, 'player_last_name' => '茅森', 'player_first_name' => '早香',],
            ['player_id' => 22, 'player_last_name' => '醍醐', 'player_first_name' => '大',],
            ['player_id' => 23, 'player_last_name' => '竹内', 'player_first_name' => '元太',],
            ['player_id' => 24, 'player_last_name' => '浅井', 'player_first_name' => '堂岐',],
            ['player_id' => 25, 'player_last_name' => '萩原', 'player_first_name' => '聖人',],
            ['player_id' => 26, 'player_last_name' => '瀬戸熊', 'player_first_name' => '直樹',],
            ['player_id' => 27, 'player_last_name' => '黒沢', 'player_first_name' => '咲',],
            ['player_id' => 28, 'player_last_name' => '本田', 'player_first_name' => '朋広',],
            ['player_id' => 29, 'player_last_name' => '猿川', 'player_first_name' => '真寿',],
            ['player_id' => 30, 'player_last_name' => '菅原', 'player_first_name' => '千瑛',],
            ['player_id' => 31, 'player_last_name' => '鈴木', 'player_first_name' => '大介',],
            ['player_id' => 32, 'player_last_name' => '中田', 'player_first_name' => '花奈',],
            ['player_id' => 33, 'player_last_name' => '小林', 'player_first_name' => '剛',],
            ['player_id' => 34, 'player_last_name' => '瑞原', 'player_first_name' => '明奈',],
            ['player_id' => 35, 'player_last_name' => '鈴木', 'player_first_name' => '優',],
            ['player_id' => 36, 'player_last_name' => '仲林', 'player_first_name' => '圭',],
            ['player_id' => 37, 'player_last_name' => '前原', 'player_first_name' => '雄大',],
            ['player_id' => 38, 'player_last_name' => '藤崎', 'player_first_name' => '智',],
            ['player_id' => 39, 'player_last_name' => '和久津', 'player_first_name' => '晶',],
            ['player_id' => 40, 'player_last_name' => '朝倉', 'player_first_name' => '庚心',],
            ['player_id' => 41, 'player_last_name' => '石橋', 'player_first_name' => '伸洋',],
            ['player_id' => 42, 'player_last_name' => '沢崎', 'player_first_name' => '誠',],
            ['player_id' => 43, 'player_last_name' => '近藤', 'player_first_name' => '誠一',],
            ['player_id' => 44, 'player_last_name' => '村上', 'player_first_name' => '淳',],
            ['player_id' => 45, 'player_last_name' => '丸山', 'player_first_name' => '奏子',],
            ['player_id' => 46, 'player_last_name' => '魚谷', 'player_first_name' => '侑未',],
            ['player_id' => 47, 'player_last_name' => '東城', 'player_first_name' => 'りお',],
        ];
        Player::insert($data);
    }
}
