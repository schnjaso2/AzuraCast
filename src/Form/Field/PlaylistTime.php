<?php
namespace App\Form\Field;

use AzuraForms;
use AzuraForms\Field\Time;

class PlaylistTime extends Time
{
    public function __construct(AzuraForms\Form $form, $element_name, array $config = [], $group = null)
    {
        parent::__construct($form, $element_name, $config, $group);

        $this->attributes['pattern'] = '[0-9]{2}:[0-9]{2}';
        $this->attributes['placeholder'] = '13:45';

        // Handle the "time code" format used by the database entity,
        // which is just the regular 24-hour time minus the ":".
        $this->filters[] = function($new_value) {
            if (!empty($new_value) && false === strpos($new_value, ':')) {
                $time_code = str_pad($new_value, 4, '0', STR_PAD_LEFT);
                return substr($time_code, 0, 2).':'.substr($time_code, 2);
            }
            return $new_value;
        };
    }

    public function getValue()
    {
        if (empty($this->value)) {
            return null;
        }

        [$hours, $minutes] = explode(':', $this->value);
        return ((int)$hours * 100) + (int)$minutes;
    }

}
