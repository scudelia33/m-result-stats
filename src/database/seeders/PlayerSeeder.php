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
            ['player_id' => 1, 'player_last_name' => '園田', 'player_first_name' => '賢', 'player_last_name_kana' => 'そのだ', 'player_first_name_kana' => 'けん',],
            ['player_id' => 2, 'player_last_name' => '鈴木', 'player_first_name' => 'たろう', 'player_last_name_kana' => 'すずき', 'player_first_name_kana' => 'たろう',],
            ['player_id' => 3, 'player_last_name' => '浅見', 'player_first_name' => '真紀', 'player_last_name_kana' => 'あさみ', 'player_first_name_kana' => 'まき',],
            ['player_id' => 4, 'player_last_name' => '渡辺', 'player_first_name' => '太', 'player_last_name_kana' => 'わたなべ', 'player_first_name_kana' => 'ふとし',],
            ['player_id' => 5, 'player_last_name' => '二階堂', 'player_first_name' => '亜樹', 'player_last_name_kana' => 'にかいどう', 'player_first_name_kana' => 'あき',],
            ['player_id' => 6, 'player_last_name' => '勝又', 'player_first_name' => '健志', 'player_last_name_kana' => 'かつまた', 'player_first_name_kana' => 'けんじ',],
            ['player_id' => 7, 'player_last_name' => '松ヶ瀬', 'player_first_name' => '隆弥', 'player_last_name_kana' => 'まつがせ', 'player_first_name_kana' => 'たかや',],
            ['player_id' => 8, 'player_last_name' => '二階堂', 'player_first_name' => '留美', 'player_last_name_kana' => 'にかいどう', 'player_first_name_kana' => 'るみ',],
            ['player_id' => 9, 'player_last_name' => '内川', 'player_first_name' => '幸太郎', 'player_last_name_kana' => 'うちかわ', 'player_first_name_kana' => 'こうたろう',],
            ['player_id' => 10, 'player_last_name' => '岡田', 'player_first_name' => '紗佳', 'player_last_name_kana' => 'おかだ', 'player_first_name_kana' => 'さやか',],
            ['player_id' => 11, 'player_last_name' => '堀', 'player_first_name' => '慎吾', 'player_last_name_kana' => 'ほり', 'player_first_name_kana' => 'しんご',],
            ['player_id' => 12, 'player_last_name' => '渋川', 'player_first_name' => '難波', 'player_last_name_kana' => 'しぶかわ', 'player_first_name_kana' => 'なんば',],
            ['player_id' => 13, 'player_last_name' => '佐々木', 'player_first_name' => '寿人', 'player_last_name_kana' => 'ささき', 'player_first_name_kana' => 'ひさと',],
            ['player_id' => 14, 'player_last_name' => '高宮', 'player_first_name' => 'まり', 'player_last_name_kana' => 'たかみや', 'player_first_name_kana' => 'まり',],
            ['player_id' => 15, 'player_last_name' => '伊達', 'player_first_name' => '朱里紗', 'player_last_name_kana' => 'だて', 'player_first_name_kana' => 'ありさ',],
            ['player_id' => 16, 'player_last_name' => '滝沢', 'player_first_name' => '和典', 'player_last_name_kana' => 'たきざわ', 'player_first_name_kana' => 'かずのり',],
            ['player_id' => 17, 'player_last_name' => '多井', 'player_first_name' => '隆晴', 'player_last_name_kana' => 'おおい', 'player_first_name_kana' => 'たかはる',],
            ['player_id' => 18, 'player_last_name' => '白鳥', 'player_first_name' => '翔', 'player_last_name_kana' => 'しらとり', 'player_first_name_kana' => 'しょう',],
            ['player_id' => 19, 'player_last_name' => '松本', 'player_first_name' => '吉弘', 'player_last_name_kana' => 'まつもと', 'player_first_name_kana' => 'よしひろ',],
            ['player_id' => 20, 'player_last_name' => '日向', 'player_first_name' => '藍子', 'player_last_name_kana' => 'ひなた', 'player_first_name_kana' => 'あいこ',],
            ['player_id' => 21, 'player_last_name' => '茅森', 'player_first_name' => '早香', 'player_last_name_kana' => 'かやもり', 'player_first_name_kana' => 'さやか',],
            ['player_id' => 22, 'player_last_name' => '醍醐', 'player_first_name' => '大', 'player_last_name_kana' => 'だいご', 'player_first_name_kana' => 'ひろし',],
            ['player_id' => 23, 'player_last_name' => '竹内', 'player_first_name' => '元太', 'player_last_name_kana' => 'たけうち', 'player_first_name_kana' => 'げんた',],
            ['player_id' => 24, 'player_last_name' => '浅井', 'player_first_name' => '堂岐', 'player_last_name_kana' => 'あさい', 'player_first_name_kana' => 'たかき',],
            ['player_id' => 25, 'player_last_name' => '萩原', 'player_first_name' => '聖人', 'player_last_name_kana' => 'はぎわら', 'player_first_name_kana' => 'まさと',],
            ['player_id' => 26, 'player_last_name' => '瀬戸熊', 'player_first_name' => '直樹', 'player_last_name_kana' => 'せとくま', 'player_first_name_kana' => 'なおき',],
            ['player_id' => 27, 'player_last_name' => '黒沢', 'player_first_name' => '咲', 'player_last_name_kana' => 'くろさわ', 'player_first_name_kana' => 'さき',],
            ['player_id' => 28, 'player_last_name' => '本田', 'player_first_name' => '朋広', 'player_last_name_kana' => 'ほんだ', 'player_first_name_kana' => 'ともひろ',],
            ['player_id' => 29, 'player_last_name' => '猿川', 'player_first_name' => '真寿', 'player_last_name_kana' => 'さるかわ', 'player_first_name_kana' => 'まさとし',],
            ['player_id' => 30, 'player_last_name' => '菅原', 'player_first_name' => '千瑛', 'player_last_name_kana' => 'すがわら', 'player_first_name_kana' => 'ひろえ',],
            ['player_id' => 31, 'player_last_name' => '鈴木', 'player_first_name' => '大介', 'player_last_name_kana' => 'すずき', 'player_first_name_kana' => 'だいすけ',],
            ['player_id' => 32, 'player_last_name' => '中田', 'player_first_name' => '花奈', 'player_last_name_kana' => 'なかだ', 'player_first_name_kana' => 'かな',],
            ['player_id' => 33, 'player_last_name' => '小林', 'player_first_name' => '剛', 'player_last_name_kana' => 'こばやし', 'player_first_name_kana' => 'ごう',],
            ['player_id' => 34, 'player_last_name' => '瑞原', 'player_first_name' => '明奈', 'player_last_name_kana' => 'みずはら', 'player_first_name_kana' => 'あきな',],
            ['player_id' => 35, 'player_last_name' => '鈴木', 'player_first_name' => '優', 'player_last_name_kana' => 'すずき', 'player_first_name_kana' => 'ゆう',],
            ['player_id' => 36, 'player_last_name' => '仲林', 'player_first_name' => '圭', 'player_last_name_kana' => 'なかばやし', 'player_first_name_kana' => 'けい',],
            ['player_id' => 37, 'player_last_name' => '前原', 'player_first_name' => '雄大', 'player_last_name_kana' => 'まえはら', 'player_first_name_kana' => 'ゆうだい',],
            ['player_id' => 38, 'player_last_name' => '藤崎', 'player_first_name' => '智', 'player_last_name_kana' => 'ふじさき', 'player_first_name_kana' => 'さとし',],
            ['player_id' => 39, 'player_last_name' => '和久津', 'player_first_name' => '晶', 'player_last_name_kana' => 'わくつ', 'player_first_name_kana' => 'あきら',],
            ['player_id' => 40, 'player_last_name' => '朝倉', 'player_first_name' => '庚心', 'player_last_name_kana' => 'あさくら', 'player_first_name_kana' => 'こうしん',],
            ['player_id' => 41, 'player_last_name' => '石橋', 'player_first_name' => '伸洋', 'player_last_name_kana' => 'いしばし', 'player_first_name_kana' => 'のぶひろ',],
            ['player_id' => 42, 'player_last_name' => '沢崎', 'player_first_name' => '誠', 'player_last_name_kana' => 'さわざき', 'player_first_name_kana' => 'まこと',],
            ['player_id' => 43, 'player_last_name' => '近藤', 'player_first_name' => '誠一', 'player_last_name_kana' => 'こんどう', 'player_first_name_kana' => 'せいいち',],
            ['player_id' => 44, 'player_last_name' => '村上', 'player_first_name' => '淳', 'player_last_name_kana' => 'むらかみ', 'player_first_name_kana' => 'じゅん',],
            ['player_id' => 45, 'player_last_name' => '丸山', 'player_first_name' => '奏子', 'player_last_name_kana' => 'まるやま', 'player_first_name_kana' => 'かなこ',],
            ['player_id' => 46, 'player_last_name' => '魚谷', 'player_first_name' => '侑未', 'player_last_name_kana' => 'うおたに', 'player_first_name_kana' => 'ゆうみ',],
            ['player_id' => 47, 'player_last_name' => '東城', 'player_first_name' => 'りお', 'player_last_name_kana' => 'とうじょう', 'player_first_name_kana' => 'りお',],
            ['player_id' => 48, 'player_last_name' => '永井', 'player_first_name' => '孝典', 'player_last_name_kana' => 'ながい', 'player_first_name_kana' => 'たかのり',],
            ['player_id' => 49, 'player_last_name' => '阿久津', 'player_first_name' => '翔太', 'player_last_name_kana' => 'あくつ', 'player_first_name_kana' => 'しょうた',],
            ['player_id' => 50, 'player_last_name' => '下石', 'player_first_name' => '戟', 'player_last_name_kana' => 'しもいし', 'player_first_name_kana' => 'げき',],
            ['player_id' => 51, 'player_last_name' => '石井', 'player_first_name' => '一馬', 'player_last_name_kana' => 'いしい', 'player_first_name_kana' => 'かずま',],
            ['player_id' => 52, 'player_last_name' => '三浦', 'player_first_name' => '智博', 'player_last_name_kana' => 'みうら', 'player_first_name_kana' => 'ともひろ',],
            ['player_id' => 53, 'player_last_name' => '逢川', 'player_first_name' => '恵夢', 'player_last_name_kana' => 'あいかわ', 'player_first_name_kana' => 'めぐむ',],
            ['player_id' => 54, 'player_last_name' => 'HIRO', 'player_first_name' => '柴田', 'player_last_name_kana' => 'ひろ', 'player_first_name_kana' => 'しばた',],
        ];
        Player::insert($data);
    }
}
