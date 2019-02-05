<?php
namespace futureactivities\rest\services;

use yii\base\Component;
use futureactivities\rest\events\FieldEvent;

class Fields extends Component
{
    const EVENT_PROCESS_FIELD = 'processField';
    /**
     * Get fields to be excluded from results
     */
    public function getExcluded()
    {
        $excludedFields = \futureactivities\rest\Plugin::getInstance()->settings->excludedFields;
        
        if (!$excludedFields)
            return [];
        
        $excludes = array_filter($excludedFields, function ($e) {
                return $e['visible'] == 1;
            }
        );
        
        return array_keys($excludes);
    }
    
    /**
     * Processes a craft field and returns a usable value
     */
    public function process($field)
    {
        if (is_null($field) || is_string($field) || is_bool($field) || is_numeric($field) || is_array($field))
            return $field;
        
        if (is_a($field, 'DateTime'))
            return $field->format('Y-m-d H:i:s');
        
        if (get_parent_class($field) === 'craft\elements\db\ElementQuery')
            return $field->ids();
        
        if (is_a($field, 'craft\fields\data\SingleOptionFieldData'))
            return $this->singleOption($field);
        
        if (is_a($field, 'craft\fields\data\MultiOptionsFieldData'))
            return $this->multiOption($field);
        
        if (is_a($field, 'craft\fields\data\ColorData'))
            return $field->getHex();
        
        if (is_a($field, 'craft\redactor\FieldData'))
            return $field->getParsedContent();
        
        $event = new FieldEvent([
            'field' => $field
        ]);
        
        $this->trigger(self::EVENT_PROCESS_FIELD, $event);
        
        if (!is_null($event->data))
            return $event->data;
    
        if ($event->field) // Added to support other fields like Colour Swatches
            return $event->field;
        
        return 'Field not yet supported.';
    }
    
    /**
     * SingleOptionFieldData
     */
    protected function singleOption($field)
    {
        $options = $field->getOptions();
        foreach ($options AS $option) {
            if ($option->selected)
                return $option;
        }
    }
    
    /**
     * MultiOptionsFieldData
     */
    protected function multiOption($field)
    {
        $options = $field->getOptions();
        $selected = [];
        foreach ($options AS $option) {
            if (!$option->selected) continue;
            $selected[] = $option;
        }
        
        return $selected;
    }
}