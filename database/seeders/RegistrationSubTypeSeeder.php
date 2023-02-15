<?php

// namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RegistrationSubTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('registration_sub_types')->updateOrInsert([
            'id' => 1
        ], [
            'name' => 'Speaker/Session',
        ]);

        DB::table('registration_sub_types')->updateOrInsert([
            'id' => 2
        ], [
            'name' => 'Workshop Chairs',
        ]);

        DB::table('registration_sub_types')->updateOrInsert([
            'id' => 3
        ], [
            'name' => 'Council of Advisers',
        ]);

        DB::table('registration_sub_types')->updateOrInsert([
            'id' => 4
        ], [
            'name' => 'Board of Directors',
        ]);

        DB::table('registration_sub_types')->updateOrInsert([
            'id' => 5
        ], [
            'name' => 'Organizing Committee',
        ]);
    }
}
