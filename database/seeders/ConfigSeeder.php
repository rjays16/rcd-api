<?php
use Illuminate\Database\Seeder;
use App\Enum\ConfigTypeEnum;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config_types')->updateOrInsert([
            'id' => 1
        ], [
            'name' => 'Ideapay Fee (Fixed)',
        ]);

        DB::table('configs')->updateOrInsert([
            'id' => 1
        ], [
            'type' => ConfigTypeEnum::IDEAPAY_FEE_FIXED,
            'name' => 'Fixed',
            'value' => '87.5',
        ]);

        DB::table('config_types')->updateOrInsert([
            'id' => 2
        ], [
            'name' => 'Ideapay Fee (Percentage)',
        ]);

        DB::table('configs')->updateOrInsert([
            'id' => 2
        ], [
            'type' => ConfigTypeEnum::IDEAPAY_FEE_PERCENTAGE,
            'name' => 'Percentage',
            'value' => '0.05',
        ]);

        DB::table('config_types')->updateOrInsert([
            'id' => 3
        ], [
            'name' => 'PHP Rate for 1 USD',
        ]);

        DB::table('configs')->updateOrInsert([
            'id' => 3
        ], [
            'type' => ConfigTypeEnum::PHP_RATE_FOR_USD,
            'name' => 'Default Fixed PHP Rate for 1 USD',
            'value' => '50.00',
        ]);
        
        DB::table('config_types')->updateOrInsert([
            'id' => 4
        ], [
            'name' => 'Registration',
        ]);

        DB::table('configs')->updateOrInsert([
            'id' => 4
        ], [
            'type' => ConfigTypeEnum::REGISTRATION_SWITCH,
            'name' => 'Enabled',
            'value' => 'Yes',
        ]);

        DB::table('config_types')->updateOrInsert([
            'id' => 5
        ], [
            'name' => 'Abstract Submission',
        ]);

        DB::table('configs')->updateOrInsert([
            'id' => 5
        ], [
            'type' => ConfigTypeEnum::ABSTRACT_SWITCH,
            'name' => 'Enabled',
            'value' => 'Yes',
        ]);

        DB::table('config_types')->updateOrInsert([
            'id' => 6
        ], [
            'name' => 'VCC Opening Date',
        ]);

        DB::table('configs')->updateOrInsert([
            'id' => 6
        ], [
            'type' => ConfigTypeEnum::VCC_OPENING_DATE,
            'name' => 'Date',
            'value' => '2022-08-01',
        ]);

        DB::table('config_types')->updateOrInsert([
            'id' => 7
        ], [
            'name' => 'Workshop Payment Switch',
        ]);

        DB::table('configs')->updateOrInsert([
            'id' => 7
        ], [
            'type' => ConfigTypeEnum::WORKSHOP_PAYMENT_SWITCH,
            'name' => 'Enabled',
            'value' => 'Yes',
        ]);
    }
}
