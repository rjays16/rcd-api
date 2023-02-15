<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    //     $this->call('RoleSeeder');
    //     $this->call('UserStatusSeeder');
    //     $this->call('UserSeeder');
    //     $this->call('ConventionMemberTypeSeeder');
    //     $this->call('RegistrationTypeScopeSeeder');
    //     $this->call('RegistrationTypeSeeder');
    //     $this->call('WorkshopSeeder');
    //     $this->call('ConfigSeeder');
    //     $this->call('FeeTypeSeeder');
    //     $this->call('FeeSeeder');
    //     $this->call('OrderStatusSeeder');
    //     $this->call('IdeapayStatusSeeder');
    //     $this->call('PaymentMethodSeeder');
    //     $this->call('CategorySeeder');
    //     $this->call('StudyDesignSeeder');
    //     $this->call('ConventionMemberSeeder');
    //     $this->call('CountrySeeder');
    //     $this->call('TrainingInstitutionSeeder');
    //     $this->call('RegistrationSubTypeSeeder');
    //     $this->call('ApplicantInstitutionSeeder');
    //     $this->call('ForExRateSeeder');
    //     $this->call('RCDAdminUserSeeder');
    //     $this->call('SponsorTypeSeeder');
    //     $this->call('SponsorSeeder');
    //     $this->call('SponsorAssetTypeSeeder');
    //     $this->call('PlenaryEventSeeder');
    //     $this->call('SponsorExhibitorSeeder');
    //     $this->call('PlenaryAttendanceSeeder');
    //     $this->call('WorkshopScheduleSeeder');
    //     $this->call('PlenaryDaySeeder');
    //     $this->call('SymposiaCategorySeeder');
        $this->call('SymposiaSeeder');
    }
}
