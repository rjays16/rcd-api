<?php

use Illuminate\Database\Seeder;

use App\Models\SymposiaCategory;

class SymposiaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['chair' => 'Eileen Liesl Cubillan, MD', 'title' => 'ACNE AND SEBACEOUS GLAND DISORDERS',  'subtitle' => 'Breakout Concepts and Management of Acne, Rosacea and Hidradentis Suppurativa'],
            ['chair' => 'Allen Aguinaldo-Cabrera, MD', 'title' => 'AESTHETIC DERMATOLOGY',  'subtitle' => 'The Basic, the Past, and the Future in Aesthetic Dermatology'],
            ['chair' => 'Bernadette Arcilla, MD', 'title' => 'COSMECEUTICAL/NUTRACEUTICAL COMPASS',  'subtitle' => null],
            ['chair' => 'Claudine Yap Silva, MD', 'title' => 'CUTANEOUS TUMORS',  'subtitle' => 'Diagnosis and comprehensive management of skin tumors'],
            ['chair' => 'Prof. Arnelfa Paliza, MD', 'title' => 'DERMATOPATHOLOGY',  'subtitle' => 'Consultations in dermatopathology: challenging cases from the region'],

            ['chair' => 'Blossom Tian Chan, MD', 'title' => 'DERMATOTHERAPEUTICS',  'subtitle' => 'The past, present and future of dermatotherapeutics'],
            ['chair' => 'Maria Jasmin Jamora, MD & Co-Chair: Prof Steven Thng MD', 'title' => 'DERMOSCOPY',  'subtitle' => 'Best Practices in Dermoscopy'],
            ['chair' => 'Maria Lourdes H. Palmero, MD', 'title' => 'ECZEMA',  'subtitle' => 'Eczema: a regional perspective'],
            ['chair' => 'Roberto Antonio Pascual, MD', 'title' => 'GENDER DERMATOLOGY/ VENEREOLOGY',  'subtitle' => 'Eliminating gender bias: towards a holistic approach in STI management'],
            ['chair' => 'Mae Ramirez-Quizon, MD', 'title' => 'HAIR AND NAILS',  'subtitle' => 'Snips and Clips: current and future perspectives in hair and nail disorders'],
        ];

        foreach($categories as $category) {
            try {
                $symposia_category = SymposiaCategory::where([
                        ['chair', $category['chair']],
                        ['title', $category['title']],
                    ])
                    ->first();

                if(is_null($symposia_category)) {
                    SymposiaCategory::create($category);
                } else {
                    $symposia_category->update($category);
                }

                DB::commit();
            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}