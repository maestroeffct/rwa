<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\Traits\SequenceContent;

class File extends Model implements TranslatableContract
{
    use Translatable;
    use SequenceContent;

    public $timestamps = false;
    protected $table = 'files';
    protected $guarded = ['id'];

    static $accessibility = [
        'free', 'paid'
    ];

    static $videoTypes = ['mp4', 'mkv', 'avi', 'mov', 'wmv', 'avchd', 'flv', 'f4v', 'swf', 'mpeg-2', 'webm', 'video'];
    static $fileTypes = [
        'pdf', 'powerpoint', 'sound', 'video', 'image', 'archive', 'document', 'project'
    ];

    static $fileSources = [
        'upload', 'youtube', 'vimeo', 'external_link', 'google_drive', 'iframe', 's3', 'secure_host'
    ];

    static $urlInputSources = ['youtube', 'vimeo', 'external_link', 'google_drive', 'iframe'];

    static $Active = 'active';
    static $Inactive = 'inactive';
    static $fileStatus = ['active', 'inactive'];

    static $ignoreVolumeFileSources = ['youtube', 'vimeo', 'iframe'];

    public $translatedAttributes = ['title', 'description'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }


    public function chapter()
    {
        return $this->belongsTo('App\Models\WebinarChapter', 'chapter_id', 'id');
    }

    public function learningStatus()
    {
        return $this->hasOne('App\Models\CourseLearning', 'file_id', 'id');
    }

    public function personalNote()
    {
        return $this->morphOne('App\Models\CoursePersonalNote', 'targetable');
    }

    public function isVideo()
    {
        return (in_array($this->file_type, self::$videoTypes));
    }

    public function getFileDuration()
    {
        $duration = 0;

        if ($this->storage == 'upload') {
            $file_path = public_path($this->file);

            $getID3 = new \getID3;
            $file = $getID3->analyze($file_path);

            if (!empty($file) and !empty($file['playtime_seconds'])) {
                $duration = $file['playtime_seconds'];
            }
        }

        return convertMinutesToHourAndMinute($duration);
    }

    public function getIconByType($type = null)
    {
        $icon = 'file';

        if (empty($type)) {
            $type = $this->file_type;
        }

        if (!empty($type)) {
            if (in_array($type, ['pdf', 'powerpoint', 'document'])) {
                $icon = 'file-text';
            } else if (in_array($type, ['sound'])) {
                $icon = 'volume-2';
            } else if (in_array($type, ['video'])) {
                $icon = 'film';
            } else if (in_array($type, ['image'])) {
                $icon = 'image';
            } else if (in_array($type, ['archive'])) {
                $icon = 'archive';
            }
        }

        return $icon;
    }

    public function getIconXByType($type = null)
    {
        $icon = 'document';

        if (empty($type)) {
            $type = $this->file_type;
        }

        if (!empty($type)) {
            if (in_array($type, ['pdf', 'powerpoint', 'document'])) {
                $icon = 'document-text';
            } else if (in_array($type, ['sound'])) {
                $icon = 'music-play';
            } else if (in_array($type, ['video'])) {
                $icon = 'video-vertical';
            } else if (in_array($type, ['image'])) {
                $icon = 'image';
            } else if (in_array($type, ['archive'])) {
                $icon = 'archive-book';
            }
        }

        return $icon;
    }

    public function getVolume()
    {
        $volume = str_replace('MB', '', $this->volume);
        $volume = trim($volume);

        return $volume . ' MB';
    }

    public function checkPassedItem()
    {
        $result = false;

        if (auth()->check()) {
            $check = $this->learningStatus()->where('user_id', auth()->id())->count();

            $result = ($check > 0);
        }

        return $result;
    }

    public function getVimeoPath()
    {
        $path = $this->file;
        $path = trim($path);

        if (strpos($path, 'player.vimeo.com/video') !== false) {
            return $path;
        }

        if (!preg_match('/^https?:\/\//i', $path)) {
            $path = 'https://' . $path;
        }

        $parsed = parse_url($path);

        if (!$parsed || !isset($parsed['host'])) {
            return $path;
        }

        $hostname = preg_replace('/^www\./', '', strtolower($parsed['host']));

        if ($hostname === 'vimeo.com') {
            if (isset($parsed['path'])) {
                $parts = explode('/', trim($parsed['path'], '/'));
                $id = end($parts);

                if (preg_match('/^\d+$/', $id)) {
                    return 'https://player.vimeo.com/video/' . $id;
                }
            }
        }

        return $path;
    }
}
