<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mixins\Geo\Geo;
use App\Mixins\Regions\RegionsFromLocalJson;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{

    public function index($pageType)
    {
        $this->authorize('admin_regions_' . $pageType);

        $pageTypes = [
            'countries' => Region::$country,
            'provinces' => Region::$province,
            'cities' => Region::$city,
            'districts' => Region::$district
        ];

        $type = $pageTypes[$pageType];

        $regions = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->with([
                'countryProvinces',
                'provinceCities',
                'province',
                'city',
            ])
            ->paginate(20);

        $data = [
            'pageTitle' => trans('update.' . $pageType),
            'regions' => $regions,
            'type' => $type
        ];

        return view('admin.regions.index', $data);
    }

    public function create(Request $request)
    {
        $this->authorize('admin_regions_create');

        $type = $request->get('type');
        $countries = null;
        $apiCountries = [];

        if ($type !== Region::$country) {
            $countries = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                ->where('type', Region::$country)
                ->get();
        }

        // load countries list from local JSON datasets when creating a country
        if ($type === Region::$country) {
            try {
                $regionsFromLocal = new RegionsFromLocalJson();
                $apiCountries = $regionsFromLocal->getCountries();
            } catch (\Throwable $e) {
                $apiCountries = [];
            }
        }


        $data = [
            'pageTitle' => trans('update.new_' . $type),
            'countries' => $countries,
            'latitude' => 42.67,
            'longitude' => 12.65,
            'apiCountries' => $apiCountries,
        ];

        return view('admin.regions.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_regions_create');

        $this->validate($request, [
            'type' => 'required|in:' . implode(',', Region::$types),
        ]);

        $data = $request->all();

        // Creating a country from JSON dropdown
        if ($data['type'] == Region::$country && !empty($data['api_country_id'])) {
            $regionsFromLocal = new RegionsFromLocalJson();
            $countries = $regionsFromLocal->getCountries();

            $apiCountry = null;
            foreach ($countries as $c) {
                if ((string)$c['id'] === (string)$data['api_country_id']) { $apiCountry = $c; break; }
            }

            if (!$apiCountry) {
                return back()->withErrors(['api_country_id' => 'Invalid country selected']);
            }

            DB::transaction(function () use ($apiCountry, $regionsFromLocal) {
                $lat = $apiCountry['latitude'] ?? null; $lng = $apiCountry['longitude'] ?? null;
                $country = Region::create([
                    'country_id' => null,
                    'province_id' => null,
                    'city_id' => null,
                    'type' => Region::$country,
                    'title' => $apiCountry['name'],
                    'geo_center' => ($lat !== null && $lng !== null) ? DB::raw('point(' . $lat . ',' . $lng . ')') : null,
                    'created_at' => time()
                ]);

                $states = $regionsFromLocal->getStates($apiCountry['id']);
                foreach ($states as $state) {
                    $slat = $state['latitude'] ?? null; $slng = $state['longitude'] ?? null;
                    $province = Region::create([
                        'country_id' => $country->id,
                        'province_id' => null,
                        'city_id' => null,
                        'type' => Region::$province,
                        'title' => $state['name'],
                        'geo_center' => ($slat !== null && $slng !== null) ? DB::raw('point(' . $slat . ',' . $slng . ')') : null,
                        'created_at' => time()
                    ]);

                    $cities = $regionsFromLocal->getCities($state['id']);
                    foreach ($cities as $city) {
                        $clat = $city['latitude'] ?? null; $clng = $city['longitude'] ?? null;
                        Region::create([
                            'country_id' => $country->id,
                            'province_id' => $province->id,
                            'city_id' => null,
                            'type' => Region::$city,
                            'title' => $city['name'],
                            'geo_center' => ($clat !== null && $clng !== null) ? DB::raw('point(' . $clat . ',' . $clng . ')') : null,
                            'created_at' => time()
                        ]);
                    }
                }
            });
        } else {
            // Legacy single region create (province/city/district or manual country)
            $this->validate($request, [
                'title' => 'required|string',
                'latitude' => 'required',
                'longitude' => 'required',
                'country_id' => 'required_if:type,province,city,district',
                'province_id' => 'required_if:type,city,district',
                'city_id' => 'required_if:type,district',
            ]);

            Region::create([
                'country_id' => $data['country_id'] ?? null,
                'province_id' => $data['province_id'] ?? null,
                'city_id' => $data['city_id'] ?? null,
                'type' => $data['type'],
                'title' => $data['title'],
                'geo_center' => DB::raw('point(' . $data['latitude'] . ',' . $data['longitude'] . ')'),
                'created_at' => time()
            ]);
        }

        $url = getAdminPanelUrl('/regions/');
        if ($data['type'] == Region::$country) {
            $url .= 'countries';
        } else if ($data['type'] == Region::$province) {
            $url .= 'provinces';
        } else if ($data['type'] == Region::$city) {
            $url .= 'cities';
        } else {
            $url .= 'districts';
        }

        return redirect($url);
    }

    public function edit($id)
    {
        $this->authorize('admin_regions_edit');

        $region = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
            ->where('id', $id)
            ->first();

        if ($region) {
            $latitude = $region->geo_center[0];
            $longitude = $region->geo_center[1];
            $countries = null;
            $provinces = null;
            $cities = null;

            if ($region->type !== Region::$country) {
                $countries = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                    ->where('type', Region::$country)
                    ->get();
            }

            if ($region->type !== Region::$country and $region->type !== Region::$province) {
                $provinces = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                    ->where('type', Region::$province)
                    ->where('country_id', $region->country_id)
                    ->get();
            }

            if ($region->type == Region::$district) {
                $cities = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                    ->where('type', Region::$city)
                    ->where('country_id', $region->country_id)
                    ->get();
            }


            $data = [
                'pageTitle' => trans('update.new_country'),
                'region' => $region,
                'countries' => $countries,
                'provinces' => $provinces,
                'cities' => $cities,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];

            return view('admin.regions.create', $data);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_regions_edit');

        $this->validate($request, [
            'type' => 'required|in:' . implode(',', Region::$types),
            'title' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'country_id' => 'required_if:type,province,city,district',
            'province_id' => 'required_if:type,city,district',
            'city_id' => 'required_if:type,district',
        ]);

        $data = $request->all();
        $region = Region::findOrFail($id);

        $region->update([
            'country_id' => $data['country_id'] ?? null,
            'province_id' => $data['province_id'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'type' => $data['type'],
            'title' => $data['title'],
            'geo_center' => DB::raw("point(" . $data['latitude'] . "," . $data['longitude'] . ")"),
            'created_at' => time()
        ]);

        $url = getAdminPanelUrl('/regions/');
        if ($data['type'] == Region::$country) {
            $url .= 'countries';
        } else if ($data['type'] == Region::$province) {
            $url .= 'provinces';
        } else if ($data['type'] == Region::$city) {
            $url .= 'cities';
        } else {
            $url .= 'districts';
        }

        return redirect($url);
    }

    public function delete($id)
    {
        $this->authorize('admin_regions_delete');

        $region = Region::findOrFail($id);

        $region->delete();

        return back();
    }

    public function provincesByCountry($countryId)
    {
        $this->authorize('admin_regions_create');

        $provinces = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
            ->where('type', Region::$province)
            ->where('country_id', $countryId)
            ->get();

        return response()->json([
            'code' => 200,
            'provinces' => $provinces
        ]);
    }

    public function citiesByProvince($provinceId)
    {
        $this->authorize('admin_regions_create');

        $cities = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
            ->where('type', Region::$city)
            ->where('province_id', $provinceId)
            ->get();

        return response()->json([
            'code' => 200,
            'cities' => $cities
        ]);
    }
}
