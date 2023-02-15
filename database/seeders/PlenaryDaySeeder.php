<?php

use Illuminate\Database\Seeder;

class PlenaryDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plenary_days')->updateOrInsert([
            'id' => 1
        ], [
            'date' => '2022-10-26',
            'title' => 'Reconaissance',
            'starts_at' => '9:00',
            'ends_at' => '12:00'
        ]);

        DB::table('plenary_days')->updateOrInsert([
            'id' => 2
        ], [
            'date' => '2022-10-27',
            'title' => 'Controversies',
            'starts_at' => '9:00',
            'ends_at' => '12:00'
        ]);

        DB::table('plenary_days')->updateOrInsert([
            'id' => 3
        ], [
            'date' => '2022-10-28',
            'title' => 'Dialogues in Dermatology',
            'starts_at' => '9:00',
            'ends_at' => '12:00'
        ]);
    }
}
