<?php

use Illuminate\Database\Seeder;

class SponsorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sponsor_types')->updateOrInsert([
            'id' => 1
        ], [
            'name' => 'Platinum',
            'stamp_timer' => 120, # 2 minutes, originally 12 Minutes (720)
            'max_brochures' => 8,
            'max_catalog' => 1,
            'max_videos' => 4,
            'has_360_view' => true,
            'has_ticker_text' => true,
            'max_exhibitors' => 8,
            'max_industry_lecture_account' => 0,
        ]);

        DB::table('sponsor_types')->updateOrInsert([
            'id' => 2
        ], [
            'name' => 'Gold',
            'stamp_timer' => 120, # 2 minutes, originally 10 Minutes (600)
            'max_brochures' => 8,
            'max_catalog' => 1,
            'max_videos' => 3,
            'has_360_view' => true,
            'has_ticker_text' => true,
            'max_exhibitors' => 3,
            'max_industry_lecture_account' => 1,
        ]);

        DB::table('sponsor_types')->updateOrInsert([
            'id' => 3
        ], [
            'name' => 'Silver',
            'stamp_timer' => 120, # 2 minutes, originally 7 Minutes (420)
            'max_brochures' => 8,
            'max_catalog' => 1,
            'max_videos' => 2,
            'has_360_view' => false,
            'has_ticker_text' => true,
            'max_exhibitors' => 12,
            'max_industry_lecture_account' => 1,
        ]);

        DB::table('sponsor_types')->updateOrInsert([
            'id' => 4
        ], [
            'name' => 'Bronze',
            'stamp_timer' => 120, # 2 minutes, originally 5 Minutes (300)
            'max_brochures' => 4,
            'max_catalog' => 0,
            'max_videos' => 0,
            'has_360_view' => false,
            'has_ticker_text' => true,
            'max_exhibitors' => 14,
            'max_industry_lecture_account' => 0,
        ]);
    }
}