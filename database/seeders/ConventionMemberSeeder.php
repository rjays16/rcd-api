<?php

use Illuminate\Database\Seeder;
use App\Enum\RegistrationTypeEnum;

class ConventionMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::INTERNATIONAL_LADS
        //     'id' => 1
        // ], [
        //     'user_id' => 5,
        //     'pma_number' => 'cm1_testDLGT_PMA123',
        //     'prc_license_number' => 'cm1_testDLGT_PRC123',
        //     'is_interested_for_ws' => false,
        //     'ws_to_attend' => null,
        //     'type' => RegistrationTypeEnum::INTERNATIONAL_LADS,
        //     'is_good_standing' => false
        // ]);

        // DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::INTERNATIONAL_NON_LADS
        //     'id' => 2
        // ], [
        //     'user_id' => 6,
        //     'pma_number' => 'cm2_testDLGT_PMA123',
        //     'prc_license_number' => 'cm2_testDLGT_PRC123',
        //     'is_interested_for_ws' => false,
        //     'ws_to_attend' => null,
        //     'type' => RegistrationTypeEnum::INTERNATIONAL_NON_LADS,
        //     'is_good_standing' => false
        // ]);

        // DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::INTERNATIONAL_RESIDENT
        //     'id' => 3
        // ], [
        //     'user_id' => 7,
        //     'pma_number' => 'cm3_testDLGT_PMA123',
        //     'prc_license_number' => 'cm3_testDLGT_PRC123',
        //     'is_interested_for_ws' => false,
        //     'ws_to_attend' => null,
        //     'type' => RegistrationTypeEnum::INTERNATIONAL_RESIDENT,
        //     'is_good_standing' => false
        // ]);

        // DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::LOCAL_PDS_MEMBER, with good standing
        //     'id' => 4
        // ], [
        //     'user_id' => 8,
        //     'pma_number' => 'cm4_testDLGT_PMA123',
        //     'prc_license_number' => 'cm4_testDLGT_PRC123',
        //     'is_interested_for_ws' => false,
        //     'ws_to_attend' => null,
        //     'type' => RegistrationTypeEnum::LOCAL_PDS_MEMBER,
        //     'is_good_standing' => true
        // ]);

        // DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::LOCAL_PDS_MEMBER, not in good standing
        //     'id' => 5
        // ], [
        //     'user_id' => 9,
        //     'pma_number' => 'cm5_testDLGT_PMA123',
        //     'prc_license_number' => 'cm5_testDLGT_PRC123',
        //     'is_interested_for_ws' => false,
        //     'ws_to_attend' => null,
        //     'type' => RegistrationTypeEnum::LOCAL_PDS_MEMBER,
        //     'is_good_standing' => false
        // ]);

        // DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::LOCAL_PDS_RESIDENT
        //     'id' => 6
        // ], [
        //     'user_id' => 10,
        //     'pma_number' => 'cm6_testDLGT_PMA123',
        //     'prc_license_number' => 'cm6_testDLGT_PRC123',
        //     'is_interested_for_ws' => false,
        //     'ws_to_attend' => null,
        //     'type' => RegistrationTypeEnum::LOCAL_PDS_RESIDENT,
        //     'is_good_standing' => false
        // ]);

        // DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::LOCAL_NON_PDS_MD
        //     'id' => 7
        // ], [
        //     'user_id' => 11,
        //     'pma_number' => 'cm7_testDLGT_PMA123',
        //     'prc_license_number' => 'cm7_testDLGT_PRC123',
        //     'is_interested_for_ws' => false,
        //     'ws_to_attend' => null,
        //     'type' => RegistrationTypeEnum::LOCAL_NON_PDS_MD,
        //     'is_good_standing' => false
        // ]);

        // DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS
        //     'id' => 8
        // ], [
        //     'user_id' => 12,
        //     'pma_number' => 'cm8_testDLGT_PMA123',
        //     'prc_license_number' => 'cm8_testDLGT_PRC123',
        //     'is_interested_for_ws' => false,
        //     'ws_to_attend' => null,
        //     'type' => RegistrationTypeEnum::LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS,
        //     'is_good_standing' => false
        // ]);

        // DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR
        //     'id' => 9
        // ], [
        //     'user_id' => 13,
        //     'pma_number' => 'cm9_testSP_PMA123',
        //     'prc_license_number' => 'cm9_testSP_PRC123',
        //     'is_interested_for_ws' => false,
        //     'ws_to_attend' => null,
        //     'type' => RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR,
        //     'is_good_standing' => false
        // ]);

        DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR
            'id' => 10
        ], [
            'user_id' => 14,
            'pma_number' => 'cm10_testSP_PMA123',
            'prc_license_number' => 'cm10_testSP_PRC123',
            'is_interested_for_ws' => false,
            'ws_to_attend' => null,
            'type' => RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR,
            'is_good_standing' => false
        ]);

        DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::LOCAL_PDS_MEMBER, with good standing
            'id' => 11
        ], [
            'user_id' => 15,
            'pma_number' => 'cm11_testDLGT_PMA123',
            'prc_license_number' => 'cm11_testDLGT_PRC123',
            'is_interested_for_ws' => false,
            'ws_to_attend' => null,
            'type' => RegistrationTypeEnum::LOCAL_PDS_MEMBER,
            'is_good_standing' => true
        ]);

        DB::table('convention_members')->updateOrInsert([ # RegistrationTypeEnum::LOCAL_PDS_MEMBER, with good standing
            'id' => 12
        ], [
            'user_id' => 13,
            'pma_number' => 'cm12_testSP_PMA123',
            'prc_license_number' => 'cm12_testSP_PRC123',
            'is_interested_for_ws' => false,
            'ws_to_attend' => null,
            'type' => RegistrationTypeEnum::INTERNATIONAL_LADS_OFFICER,
            'is_good_standing' => true
        ]);
    }
}