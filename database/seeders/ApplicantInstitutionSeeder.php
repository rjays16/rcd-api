<?php

use Illuminate\Database\Seeder;
use App\Models\ApplicantInstitution;

class ApplicantInstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applicant_institutions = [
            "Dr. Jose N. Rodriguez Memorial Hospital (DJNRMHS)",
            "Southern Isabela Medical Center (SIMC)",
            "Tondo Medical Center (TMC)",
            "Valenzuela Medical Center (VMC)",
        ];

        sort($applicant_institutions);
        foreach($applicant_institutions as $applicant_institution_name) {
            $institution = ApplicantInstitution::where('name', $applicant_institution_name)->first();
            if(is_null($institution)) {
                $institution = new ApplicantInstitution();
                $institution->name = $applicant_institution_name;
                $institution->save();
            }
        }
    }
}
