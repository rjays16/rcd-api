<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ["Acne, hidradenitis suppurativa, rosacea", "Aesthetic/cosmetic dermatology", "Aging", "Autoimmune disorders and connective tissue diseases",
            "Basic science", "Contact Dermatitis:  Allergic & Irritant", "COVID 19-related dermatosis/vaccine reactions", "Carcinoma",
            "Dermatology in the elderly", "Dermatology in women and during pregnancy", "Dermatology in women and during pregnancy", "Dermatopathology",
            "Dermatologic Surgery", "Diagnostic techniques/immunostaining", "Digital/electronic technology (ie dermoscopy, digital imaging technology)",
            "Diseases/ Disorders of the apocrine, eccrine and sweat glands", "Disorders of the subcutaneous tissue", "Eczemas",
            "Education and community service", "Epidemiology & health services administration", "Genetics and genodermatoses", "Hair & nail disorders",
            "Immunodermatology & blistering disorders", "Infections", "Internal medicine dermatology", "Papulosquamous disorders", "Pediatric dermatology",
            "Pharmacology", "Photobiology, phototherapy & photosensitivity diseases", "Pigmented disorders & pigmentary changes",
            "Psychodermatology and neurocutaneous diseases", "Sexually transmitted infections", "Surgery (laser, cosmetic, dermatologic)", "Telemedicine",
            "Tumors", "Vascular anomalies/disorders", "Wound healing, keloids & ulcers"];
        sort($categories);

        foreach ($categories as $category_name) {
            $category = Category::where('category_value', $category_name)->first();
            if (is_null($category)) {
                $category = new Category();
                $category->category_value = $category_name;
                $category->save();
            }
        }
    }
}
