<?php
namespace futureactivities\rest\events;

use craft\base\FieldInterface;
use yii\base\Event;

class ExtraFieldsEvent extends Event
{
    // Properties
    // =========================================================================

    /**
     * @var The model object associated with this event.
     */
    public $model;
    
    /**
     * @var array Additional fields to be included in the output
     */
    public $fields = null;
}
