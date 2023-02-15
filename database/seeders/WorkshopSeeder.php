<?php

use Illuminate\Database\Seeder;

class WorkshopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('workshops')->updateOrInsert([
            'id' => 1
        ], [
            'name' => 'Aesthetic'
        ]);

        DB::table('workshops')->updateOrInsert([
            'id' => 2
        ], [
            'name' => 'Laser'
        ]);

        DB::table('workshops')->updateOrInsert([
            'id' => 3
        ], [
            'name' => 'Both Aesthetic and Laser'
        ]);
    }
}
