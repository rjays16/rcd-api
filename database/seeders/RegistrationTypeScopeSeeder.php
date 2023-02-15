<?php

use Illuminate\Database\Seeder;

class RegistrationTypeScopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('registration_type_scopes')->updateOrInsert([
            'id' => 1
        ], [
            'name' => 'Existing'
        ]);

        DB::table('registration_type_scopes')->updateOrInsert([
            'id' => 2
        ], [
            'name' => 'Walk-in'
        ]);
    }
}
