<?php

use Illuminate\Database\Seeder;

class SponsorAssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sponsor_asset_types')->updateOrInsert([
            'id' => 1
        ], [
            'name' => 'Video',
        ]);

        DB::table('sponsor_asset_types')->updateOrInsert([
            'id' => 2
        ], [
            'name' => 'Brochure',
        ]);

        DB::table('sponsor_asset_types')->updateOrInsert([
            'id' => 3
        ], [
            'name' => 'Product Catalogue',
        ]);
    }
}