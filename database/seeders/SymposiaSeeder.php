<?php

use Illuminate\Database\Seeder;

use App\Models\SymposiaCategory;
use App\Models\Symposia;

class SymposiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['title' => 'ACNE AND SEBACEOUS GLAND DISORDERS',
                'events' => [
                    ['title' => 'Microbiome Interaction and Manipulation in Acne Management', 'author' => 'Eileen Liesl Cubillan, MD', 'card_title' => 'Introduction'],
                    ['title' => 'Acne-triggering medications: Supplements for or against acne', 'author' => 'Socouer Oblepias, MD', 'card_title' => 'Lecture 01'],
                    ['title' => "Hidradenitis Suppurativa: What works and What's new", 'author' => 'Hazel Oon, MD', 'card_title' => 'Lecture 02'],
                    ['title' => "Emerging therapies for Rosacea: What's in the pipeline", 'author' => 'Jean S. McGee, MD, PhD', 'card_title' => 'Lecture 03'],
                ]
            ],

            ['title' => 'AESTHETIC DERMATOLOGY',
                'events' => [
                    ['title' => 'Functional Anatomy and Aging relevant to NM & STF (Basic)', 'author' => 'Krishan Mohan Kapoor, MD', 'card_title' => 'Lecture 01'],
                    ['title' => 'Myomodulation with soft tissue fillers: mechanism and results', 'author' => 'Ma. Christina Tantiangco-Javier, MD', 'card_title' => 'Lecture 02'],
                    ['title' => 'Innovative treatment for Acne Scars, my experience: mesomix with monofilament thread-lift', 'author' => 'Allen Aguinaldo-Cabrera, MD', 'card_title' => 'Lecture 03'],
                    ['title' => 'Autologous Fibroblasts for Facial Rejuvenation', 'author' => 'Rungsima Wanitphakdeedecha, MD', 'card_title' => 'Lecture 04'],
                ]
            ],

            ['title' => 'COSMECEUTICAL/NUTRACEUTICAL COMPASS',
                'events' => [
                    ['title' => 'GPS on Cosmeceuticals', 'author' => 'Bernadette Arcilla, MD', 'card_title' => 'Lecture 01'],
                    ['title' => 'Nutraceuticals: An Evidence Based Guide for Dermatologists', 'author' => 'Hester Gail Lim Bueser, MD', 'card_title' => 'Lecture 02'],
                    ['title' => 'Tips to treat recalcitrant age spots with topical therapies', 'author' => 'Voraphol Vejjabhinanta, MD', 'card_title' => 'Lecture 03'],
                    ['title' => 'Sunscreens', 'author' => 'Rungsima Wanitphakdeedecha, MD', 'card_title' => 'Lecture 04'],
                ]
            ],

            ['title' => 'CUTANEOUS TUMORS',
                'events' => [
                    ['title' => 'Dermoscopy of cutaneous tumors and histological correlation: an interactive session', 'author' => 'Arunee Siripunvarapon, MD and Claudine Yap Silva, MD', 'card_title' => 'Lecture 01'],
                    ['title' => 'Handling the Ineffable: Management of Advanced Non-Melanoma Skin Cancer', 'author' => 'Cynthia Ciriaco-Tan, MD', 'card_title' => 'Lecture 02'],
                    ['title' => 'Going Beyond Surgery: Radiotherapy of Skin Cancer', 'author' => 'Henri Cartier Co, MD', 'card_title' => 'Lecture 03'],
                    ['title' => 'Current Standard of care in Cutaneous T-Cell Lymphoma', 'author' => 'Prof. Suat Hoon Tan, MD', 'card_title' => 'Lecture 04'],
                ]
            ],

            ['title' => 'DERMATOPATHOLOGY',
                'events' => [
                    ['title' => '2 cases for presentation from Indonesia', 'author' => 'Sondang Aemilia Pandjaitan Sirait, MD', 'card_title' => 'Lecture 01'],
                    ['title' => 'Alopecia - detangling the knots in 2 interesting cases', 'author' => 'Joyce Lee Siong See, MD', 'card_title' => 'Lecture 02'],
                    ['title' => 'The novel fungal infection: a new frontier', 'author' => 'Nopadon Noppakun, MD', 'card_title' => 'Lecture 03'],
                    ['title' => 'Intriguing Dermpath Cases from the Philippines', 'author' => 'Adolfo B. Bormate,Jr., MD', 'card_title' => 'Lecture 04'],
                ]
            ],

            ['title' => 'DERMATOTHERAPEUTICS',
                'events' => [
                    ['title' => 'Discovering Horizons: Drug Repurposing and Repositioning turning hard work to smart work', 'author' => 'Wilsie M. Salas-Walinsundin, MD', 'card_title' => 'Lecture 01'],
                    ['title' => 'Novel Therapeutics in Dermatology', 'author' => 'Cybill Dianne Uy, MD', 'card_title' => 'Lecture 02'],
                    ['title' => 'Please be gentle to my mantle', 'author' => 'Blossom Chan, MD', 'card_title' => 'Lecture 03'],
                    ['title' => "Probiotics: what we know and don't know", 'author' => 'Prof. Elma Baron, MD', 'card_title' => 'Lecture 04'],
                ]
            ],

            ['title' => 'DERMOSCOPY',
                'events' => [
                    ['title' => 'Approach to Dermoscopy for Asian Skin', 'author' => 'Steven Thng Tien Guan, MD', 'card_title' => 'Lecture 01'],
                    ['title' => 'Dermoscopy of Challenging Tumors: A huge and rare variant of a keratinizing tumor on the scalp', 'author' => 'Elsie Floreza, MD and Maria Jasmin Jamora, MD', 'card_title' => 'Lecture 02'],
                    ['title' => 'Assessment of Nail Disorders by Onychoscopy', 'author' => 'Carolyn Jean Chua- Aguilera, MD', 'card_title' => 'Lecture 03'],
                    ['title' => 'Practical Trichoscopy in the Evaluation of Hair Loss', 'author' => 'Adolfo B. Bormate,Jr., MD', 'card_title' => 'Lecture 04'],
                ]
            ],

            ['title' => 'ECZEMA',
                'events' => [
                    ['title' => 'Eczema Guidelines of Care: Review and comparison of recommendations in the Asia Pacific Region', 'author' => 'Maria Lourdes H. Palmero, MD', 'card_title' => 'Lecture 01'],
                    ['title' => 'Comparison of patch test series in the Asia Pacific Region', 'author' => 'Sarah Grace Tan-Desierto, MD', 'card_title' => 'Lecture 02'],
                    ['title' => 'Eczema Treatment Updates', 'author' => 'Mark Tang, MD', 'card_title' => 'Lecture 03'],
                    ['title' => 'Eczema Support - beyond therapeutics', 'author' => 'Mark Koh Jean Aan, MD', 'card_title' => 'Lecture 04'],
                ]
            ],

            ['title' => 'GENDER DERMATOLOGY/ VENEREOLOGY',
                'events' => [
                    ['title' => 'Importance of Monitoring Using Gender Indicators in HIV/AIDS', 'author' => 'Mr. Zimbodilion Mosendez', 'card_title' => 'Lecture 01'],
                    ['title' => 'Looking at STIs more clearly: applying a gender and rights lens', 'author' => 'Junice Lirza Melgar, MD', 'card_title' => 'Lecture 02'],
                    ['title' => 'Barriers and Challenges of Physicians in Sexually Transmitted Infections', 'author' => 'Roberto Antonio Pascual, MD', 'card_title' => 'Lecture 03'],
                    ['title' => 'WHO Global Policies and guidelines in STI management', 'author' => 'Teodora Elvira Wi, MD', 'card_title' => 'Lecture 04'],
                ]
            ],

            ['title' => 'HAIR AND NAILS',
                'events' => [
                    ['title' => "What's New in androgenetic alopecia", 'author' => 'Mr. Zimbodilion Mosendez', 'card_title' => 'Lecture 01'],
                    ['title' => 'Hair Transplantation: the Philippine Experience', 'author' => 'Theresa Marie Cacas, MD', 'card_title' => 'Lecture 02'],
                    ['title' => "What's new in Nail Psoriasis", 'author' => 'Mae Ramirez-Quizon, MD', 'card_title' => 'Lecture 03'],
                    ['title' => 'Management of Acral Lentiginous Melanoma in Asians', 'author' => 'Krisinda Clare Dim-Jamora, MD', 'card_title' => 'Lecture 04'],
                ]
            ],
        ];

        foreach($categories as $category) {
            try {
                $symposia_category = SymposiaCategory::where('title', $category['title'])->first();

                if(!is_null($symposia_category)) {
                    foreach($category["events"] as $event) {
                        $category_event = Symposia::where('title', $event['title'])->where('author', $event['author'])->first();
                        if(is_null($category_event)) {
                            $event["category_id"] = $symposia_category->id;
                            Symposia::create($event);
                        } else {
                            $category_event->update($event);
                        }
                    }
                }

                DB::commit();
            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}