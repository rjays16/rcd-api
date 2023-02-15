<?php

use Illuminate\Database\Seeder;
use App\Enum\RegistrationTypeScopeEnum;
use App\Enum\ConventionMemberTypeEnum;

class RegistrationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('registration_types')->updateOrInsert([
            'id' => 1
        ], [
            'name' => 'Speaker/Session, Workshop Chairs',
            'scope' => RegistrationTypeScopeEnum::EXISTING,
            'member_type' => ConventionMemberTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR
        ]);

        DB::table('registration_types')->updateOrInsert([
            'id' => 2
        ], [
            'name' => 'International LADS',
            'scope' => RegistrationTypeScopeEnum::WALK_IN,
            'member_type' => ConventionMemberTypeEnum::DELEGATE
        ]);

        DB::table('registration_types')->updateOrInsert([
            'id' => 3
        ], [
            'name' => 'International Non-LADS',
            'scope' => RegistrationTypeScopeEnum::WALK_IN,
            'member_type' => ConventionMemberTypeEnum::DELEGATE
        ]);

        DB::table('registration_types')->updateOrInsert([
            'id' => 4
        ], [
            'name' => 'International Resident',
            'scope' => RegistrationTypeScopeEnum::EXISTING,
            'member_type' => ConventionMemberTypeEnum::DELEGATE
        ]);

        DB::table('registration_types')->updateOrInsert([
            'id' => 5
        ], [
            'name' => 'Local PDS Member',
            'scope' => RegistrationTypeScopeEnum::EXISTING,
            'member_type' => ConventionMemberTypeEnum::DELEGATE
        ]);

        DB::table('registration_types')->updateOrInsert([
            'id' => 6
        ], [
            'name' => 'Local PDS Resident',
            'scope' => RegistrationTypeScopeEnum::EXISTING,
            'member_type' => ConventionMemberTypeEnum::DELEGATE
        ]);

        DB::table('registration_types')->updateOrInsert([
            'id' => 7
        ], [
            'name' => 'Local Non-PDS MD',
            'scope' => RegistrationTypeScopeEnum::WALK_IN,
            'member_type' => ConventionMemberTypeEnum::DELEGATE
        ]);

        DB::table('registration_types')->updateOrInsert([
            'id' => 8
        ], [
            'name' => 'Local Non-PDS Residents of Applicants Institutions',
            'scope' => RegistrationTypeScopeEnum::WALK_IN,
            'member_type' => ConventionMemberTypeEnum::DELEGATE
        ]);

        // DB::table('registration_types')->updateOrInsert([
        //     'id' => 9
        // ], [
        //     'name' => 'Residents New Graduates',
        //     'scope' => RegistrationTypeScopeEnum::EXISTING,
        //     'member_type' => ConventionMemberTypeEnum::DELEGATE
        // ]);

        // DB::table('registration_types')->updateOrInsert([
        //     'id' => 10
        // ], [
        //     'name' => 'Board of Directors',
        //     'scope' => RegistrationTypeScopeEnum::EXISTING,
        //     'member_type' => ConventionMemberTypeEnum::DELEGATE
        // ]);

        // DB::table('registration_types')->updateOrInsert([
        //     'id' => 11
        // ], [
        //     'name' => 'Council of Advisers',
        //     'scope' => RegistrationTypeScopeEnum::EXISTING,
        //     'member_type' => ConventionMemberTypeEnum::DELEGATE
        // ]);

        DB::table('registration_types')->updateOrInsert([
            'id' => 9
        ], [
            'name' => 'International Officers of LAD Member Countries',
            'scope' => RegistrationTypeScopeEnum::EXISTING,
            'member_type' => ConventionMemberTypeEnum::DELEGATE
        ]);

        DB::table('registration_types')->updateOrInsert([
            'id' => 10
        ], [
            'name' => 'Local PDS - COA, Board of Directors, Organizing Committee',
            'scope' => RegistrationTypeScopeEnum::EXISTING,
            'member_type' => ConventionMemberTypeEnum::DELEGATE
        ]);
    }
}