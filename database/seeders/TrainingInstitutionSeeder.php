<?php

use Illuminate\Database\Seeder;
use App\Models\TrainingInstitution;

class TrainingInstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $training_institutions = [
            'East Avenue Medical Center',
            'Jose R. Reyes Memorial Medical Center',
            'Makati Medical Center',
            'Ospital ng Maynila Medical Center',
            'Region 1 Medical Center',
            'Research Institute for Tropical Medicine',
            'Rizal Medical Center',
            'Skin Cancer and Foundation Inc.',
            'Southern Philippines Medical Center',
            "St. Luke's Medical Center",
            'University of Santo Tomas Hospital',
            'University of the East Ramon Magsaysay Memorial Medical Center',
            'UP-Philippine General Hospital',
        ];

        sort($training_institutions);
        foreach($training_institutions as $training_institution_name) {
            $institution = TrainingInstitution::where('name', $training_institution_name)->first();
            if(is_null($institution)) {
                $institution = new TrainingInstitution();
                $institution->name = $training_institution_name;
                $institution->save();
            }
        }
    }
}
