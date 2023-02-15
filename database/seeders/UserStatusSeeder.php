<?php
use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_status')->updateOrInsert([
            'id' => 1
        ], [
            'name' => 'Imported (Pending)'
        ]);

        DB::table('user_status')->updateOrInsert([
            'id' => 2
        ], [
            'name' => 'Successfully Registered'
        ]);

        DB::table('user_status')->updateOrInsert([
            'id' => 3
        ], [
            'name' => 'Declined'
        ]);

    }
}
