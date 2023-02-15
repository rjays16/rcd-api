<?php

use Illuminate\Database\Seeder;

class FeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fee_types')->updateOrInsert([
            'id' => 1
        ], [
            'name' => 'Registration'
        ]);

        DB::table('fee_types')->updateOrInsert([
            'id' => 2
        ], [
            'name' => 'Workshop'
        ]);
    }
}
