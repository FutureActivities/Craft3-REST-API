<?php
namespace futureactivities\rest\events;

use craft\base\FieldInterface;
use yii\base\Event;

class FieldEvent extends Event
{
    // Properties
    // =========================================================================

    /**
     * @var FieldInterface|null The field associated with this event.
     */
    public $field;

    /**
     * @var array The parsed field data
     */
    public $data = null;
}
