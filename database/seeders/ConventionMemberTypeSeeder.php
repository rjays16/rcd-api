<?php

use Illuminate\Database\Seeder;

class ConventionMemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('convention_member_types')->updateOrInsert([
            'id' => 1
        ], [
            'name' => 'Speaker/Session and Workshop Chair'
        ]);

        DB::table('convention_member_types')->updateOrInsert([
            'id' => 2
        ], [
            'name' => 'Delegate'
        ]);
    }
}
