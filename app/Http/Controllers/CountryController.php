<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Country;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function getCountries() {
        $country = Country::all()->where('show_country', 1);

        if(!is_null($country)) {
            return response()->json($country);
        } else {
            return response()->json(['message' => 'The data for the countries have not been set yet'], 404);
        }
    }

    public function getCountries_specific()
    {
        $country = Country::where('show_country', 1)->whereIn('name', ['Philippines',  'Thailand',  'Malaysia', 'Singapore', 'Vietnam', 'Cambodia', 'Indonesia'])->get();
        
        if(!is_null($country)) {
            return response()->json($country);
        } else {
            return response()->json(['message' => 'The data for the countries have not been set yet'], 404);
        }
    }

    public function getCountries_Customed()
    {
        $country = Country::where('show_country', 1)->whereNotIn('name', ['Philippines', 'Malaysia', 'Thailand', 'Singapore', 'Vietnam', 'Cambodia', 'Indonesia'])->get();
        
        if(!is_null($country)) {
            return response()->json($country);
        } else {
            return response()->json(['message' => 'The data for the countries have not been set yet'], 404);
        }
    }
}
