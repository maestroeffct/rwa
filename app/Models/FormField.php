<?php

namespace App\Models;

use App\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = "form_fields";
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $fieldTypes = ['input', 'number', 'upload', 'date_picker', 'toggle', 'textarea', 'dropdown', 'checkbox', 'radio'];
    public $translatedAttributes = ['title'];


    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }


    /********
     * Relations
     * ******/

    public function options()
    {
        return $this->hasMany(FormFieldOption::class, 'form_field_id', 'id');
    }


    /********
     * Helpers
     * ******/
    public function getFieldValueTitle($value)
    {
        $result = $value;

        switch ($this->type) {
            case 'checkbox':
                $result = null;

                if (!empty($value)) {
                    try {
                        $optionIds = json_decode($value, true);

                        if (!empty($optionIds)) {
                            $options = $this->options()->whereIn('id', $optionIds)->get();
                            $optionsTitle = [];

                            foreach ($options as $option) {
                                $optionsTitle[] = $option->title;
                            }

                            $result = implode(', ', $optionsTitle);
                        }
                    } catch (\Exception $exception) {
                    }
                }
                break;
            case 'radio':
                $result = null;

                if (!empty($value)) {
                    try {
                        $optionId = json_decode($value, true);

                        if (!empty($optionId)) {
                            $option = $this->options()->where('id', $optionId)->first();

                            if (!empty($option)) {
                                $result = $option->title;
                            }
                        }
                    } catch (\Exception $exception) {
                    }
                }
                break;
        }

        return $result;
    }

}
