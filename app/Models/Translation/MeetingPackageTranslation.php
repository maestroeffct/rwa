<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;

class MeetingPackageTranslation extends Model
{
    protected $table = 'meeting_package_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
