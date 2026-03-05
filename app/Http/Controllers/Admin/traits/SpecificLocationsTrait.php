<?php

namespace App\Http\Controllers\Admin\traits;


use App\Models\SpecificLocation;
use Illuminate\Support\Facades\DB;

trait SpecificLocationsTrait
{

    public function handleSpecificLocations($targetableId, $targetableType, $items, $type)
    {
        SpecificLocation::query()->where('targetable_id', $targetableId)
            ->where('targetable_type', $targetableType)
            ->delete();

        $insert = [];

        if (count($items)) {
            foreach ($items as $item) {
                $insert[] = [
                    'targetable_id' => $targetableId,
                    'targetable_type' => $targetableType,
                    'country_id' => !empty($item['country_id']) ? $item['country_id'] : null,
                    'province_id' => !empty($item['province_id']) ? $item['province_id'] : null,
                    'city_id' => !empty($item['city_id']) ? $item['city_id'] : null,
                    'district_id' => !empty($item['district_id']) ? $item['district_id'] : null,
                    'geo_center' => (!empty($item['latitude']) and !empty($item['longitude'])) ? DB::raw("point(" . $item['latitude'] . "," . $item['longitude'] . ")") : null,
                    'address' => !empty($item['address']) ? $item['address'] : null,
                    'zip_code' => !empty($item['zip_code']) ? $item['zip_code'] : null,
                ];
            }
        }

        if (!empty($insert)) {
            SpecificLocation::query()->insert($insert);
        }
    }

    public function updateOrCreateSpecificLocation($targetableId, $targetableType, $data)
    {
        SpecificLocation::query()
            ->where('targetable_id', $targetableId)
            ->where('targetable_type', $targetableType)
            ->delete();

        SpecificLocation::query()->create([
            'targetable_id' => $targetableId,
            'targetable_type' => $targetableType,
            'country_id' => !empty($data['country_id']) ? $data['country_id'] : null,
            'province_id' => !empty($data['province_id']) ? $data['province_id'] : null,
            'city_id' => !empty($data['city_id']) ? $data['city_id'] : null,
            'district_id' => !empty($data['district_id']) ? $data['district_id'] : null,
            'geo_center' => (!empty($data['latitude']) and !empty($data['longitude'])) ? DB::raw("point(" . $data['latitude'] . "," . $data['longitude'] . ")") : null,
            'address' => !empty($data['address']) ? $data['address'] : null,
            'zip_code' => !empty($data['zip_code']) ? $data['zip_code'] : null,
        ]);
    }

}
