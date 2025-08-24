<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // カッコ内でソートしてからコミットする
        $this->call([
            CarriedOverPointSeeder::class,
            MatchCategorySeeder::class,
            MatchInformationSeeder::class,
            MatchResultSeeder::class,
            MatchScheduleSeeder::class,
            PlayerAffiliationSeeder::class,
            PlayerSeeder::class,
            QualifyingLineSeeder::class,
            SeasonSeeder::class,
            TeamSeeder::class,
        ]);
    }
}
