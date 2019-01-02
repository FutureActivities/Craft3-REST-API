<?php

namespace futureactivities\rest\models;

use craft\base\Model;

class Settings extends Model
{
    public $disabled = false;
    public $assets = true;
    public $tags = true;
    public $excludedFields;
    public $excludedSections;

    public function rules()
    {
        return [];
    }
}