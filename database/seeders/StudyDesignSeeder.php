<?php

use Illuminate\Database\Seeder;
use App\Models\StudyDesign;

class StudyDesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studies = ["Case report/Case series", "Case Control Study", "Cross Sectional Study", "Cohort Study", "Clinical trial", "Synthesis study (e.g. Systematic review, Meta-analysis)",
        "Others"];
        sort($studies);

        foreach ($studies as $study_val) {
            $study = StudyDesign::where('study_value', $study_val)->first();
            if (is_null($study)) {
                $study = new StudyDesign();
                $study->study_value = $study_val;
                $study->save();
            }
        }
    }
}
