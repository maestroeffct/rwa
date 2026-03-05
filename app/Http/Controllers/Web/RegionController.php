<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{

    public function allCountries(Request $request)
    {
        $countries = Region::getRegionsByTypeAndColumn(Region::$country, null, null);

        return response()->json([
            'code' => 200,
            'countries' => $countries,
        ]);
    }

    public function provincesByCountry($countryId)
    {
        $provinces = Region::getRegionsByTypeAndColumn(Region::$province, 'country_id', $countryId);

        return response()->json([
            'code' => 200,
            'provinces' => $provinces
        ]);
    }

    public function citiesByProvince($provinceId)
    {
        $cities = Region::getRegionsByTypeAndColumn(Region::$city, 'province_id', $provinceId);

        return response()->json([
            'code' => 200,
            'cities' => $cities
        ]);
    }

    public function districtsByCity($cityId)
    {
        $districts = Region::getRegionsByTypeAndColumn(Region::$district, 'city_id', $cityId);

        return response()->json([
            'code' => 200,
            'districts' => $districts
        ]);
    }
}
