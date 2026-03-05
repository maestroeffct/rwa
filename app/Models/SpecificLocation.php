<?php

namespace App\Models;


use App\Mixins\Geo\Geo;
use Illuminate\Database\Eloquent\Model;

class SpecificLocation extends Model
{
    protected $table = 'specific_locations';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function targetable()
    {
        return $this->morphTo();
    }

    public function getGeoCenterAttribute()
    {
        if (!empty($this->attributes['geo_center'])) {
            return Geo::get_geo_array($this->attributes['geo_center']);
        }

        return null;
    }

    /********
     * Relations
     * ******/
    public function country()
    {
        return $this->belongsTo(Region::class, 'country_id', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Region::class, 'province_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(Region::class, 'city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(Region::class, 'district_id', 'id');
    }


    /********
     * Helpers
     * ******/
    public function getFullAddress($country = true, $province = true, $city = true, $district = true, $address = true)
    {
        $titles = [];

        if ($country and !empty($this->country)) {
            $titles[] = $this->country->title;
        }

        if ($province and !empty($this->province)) {
            $titles[] = $this->province->title;
        }

        if ($city and !empty($this->city)) {
            $titles[] = $this->city->title;
        }

        if ($district and !empty($this->district)) {
            $titles[] = $this->district->title;
        }

        if ($address and !empty($this->address)) {
            $titles[] = $this->address;
        }

        return (count($titles) > 0) ? implode(", ", $titles) : null;
    }

}
