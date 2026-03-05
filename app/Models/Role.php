<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'roles';
    protected $guarded = ['id'];
    public $timestamps = false;


    static $admin = 'admin';
    static $user = 'user';
    static $teacher = 'teacher';
    static $organization = 'organization';

    public $translatedAttributes = ['caption'];

    public function getCaptionAttribute()
    {
        return getTranslateAttributeValue($this, 'caption');
    }


    public function canDelete()
    {
        return !in_array($this->name, [self::$admin, self::$user, self::$organization, self::$teacher]);
    }

    public function users()
    {
        return $this->hasMany('App\User', 'role_id', 'id');
    }

    public function isDefaultRole()
    {
        return in_array($this->name, [self::$admin, self::$user, self::$organization, self::$teacher]);
    }

    public function isMainAdminRole()
    {
        return $this->name == self::$admin;
    }

    public static function getUserRoleId()
    {
        $id = 1; // user role id

        $role = self::where('name', self::$user)->first();

        return !empty($role) ? $role->id : $id;
    }

    public static function getTeacherRoleId()
    {
        $id = 4; // teacher role id

        $role = self::where('name', self::$teacher)->first();

        return !empty($role) ? $role->id : $id;
    }

    public static function getOrganizationRoleId()
    {
        $id = 3; // teacher role id

        $role = self::where('name', self::$organization)->first();

        return !empty($role) ? $role->id : $id;
    }
}
