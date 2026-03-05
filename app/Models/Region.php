<?php

namespace App\Models;

use App\Mixins\Geo\Geo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
    protected $table = 'regions';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $country = 'country';
    static $province = 'province';
    static $city = 'city';
    static $district = 'district';

    static $types = [
        'country',
        'province',
        'city',
        'district',
    ];

    public function getGeoCenterAttribute()
    {
        return Geo::get_geo_array($this->attributes['geo_center']);
    }

    public function country()
    {
        return $this->belongsTo($this, 'country_id', 'id')->where('type', self::$country);
    }

    public function countryProvinces()
    {
        return $this->hasMany($this, 'country_id', 'id')->where('type', self::$province);
    }

    public function countryCities()
    {
        return $this->hasMany($this, 'country_id', 'id')->where('type', self::$city);
    }

    public function provinceCities()
    {
        return $this->hasMany($this, 'province_id', 'id')->where('type', self::$city);
    }

    public function cityDistricts()
    {
        return $this->hasMany($this, 'city_id', 'id')->where('type', self::$district);
    }

    public function province()
    {
        return $this->belongsTo($this, 'province_id', 'id')->where('type', self::$province);
    }

    public function city()
    {
        return $this->belongsTo($this, 'city_id', 'id')->where('type', self::$city);
    }

    public function countryUsers()
    {
        return $this->hasMany('App\User', 'country_id', 'id');
    }

    public function provinceUsers()
    {
        return $this->hasMany('App\User', 'province_id', 'id');
    }

    public function cityUsers()
    {
        return $this->hasMany('App\User', 'city_id', 'id');
    }

    public function districtUsers()
    {
        return $this->hasMany('App\User', 'district_id', 'id');
    }


    static public function getRegionsByTypeAndColumn($type, $column = null, $columnId = null)
    {
        $query = self::query()
            ->select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
            ->where('type', $type);

        if (!empty($column) and !empty($columnId)) {
            $query->where($column, $columnId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

}
