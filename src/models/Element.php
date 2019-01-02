<?php
namespace futureactivities\rest\models;

use \craft\base\Model;
use futureactivities\rest\Plugin;
use futureactivities\rest\models\Category;

class Element extends Model
{
    public $model;
    
    public $id;
    public $title;
    public $slug;
    public $enabled = true;
    public $fields = [];
    
    public function init()
    {
        parent::init();
        $this->processModel();
    }
    
    public function fields()
    {
        return [
            'id',
            'title',
            'slug',
            'enabled',
            'fields'
        ];
    }
    
    /**
     * Fields that extend craft\elements\db\ElementQuery are expandable.
     * Adding ?expand=fieldname will return the formatted models instead of IDs.
     */
    public function extraFields()
    {
        $result = [];
        foreach($this->model->getFieldLayout()->getFields() AS $field) {
            $handle = $field->handle;
            $element = $this->model->$handle;
            
            if (get_parent_class($element) !== 'craft\elements\db\ElementQuery')
                continue;
            
            // Determine element class
            $class = 'futureactivities\rest\models\Element';
            if ($element instanceof \craft\elements\db\EntryQuery) {
                $class = 'futureactivities\rest\models\Entry';
            } else if ($element instanceof \craft\elements\db\CategoryQuery) {
                $class = 'futureactivities\rest\models\Category';
            }
            
            // Define expandable function
            $result[$handle] = function($model) use ($class, $element) {
                $data = [];
                foreach ($element->all() AS $item) {
                    $data[] = new $class([
                        'model' => $item
                    ]);
                }
                
                return $data;
            };
        }
        
        return $result;
    }
    
    /**
     * Process an element and convert the format.
     * We can't simply return the fields from the model as we need them in a format
     * that will work in an API. Here we process each field and convert the values.
     */
    protected function processModel()
    {
        if (!$this->model)
            return;
            
        // Process the core element fields
        $this->id = $this->model->id;
        $this->title = $this->model->title;
        $this->slug = $this->model->slug;
        $this->enabled = $this->model->enabled == 1;
        
        // Get the elements custom fields
        $excluded = Plugin::getInstance()->fields->getExcluded();
        $customFields = $this->model->getFieldLayout()->getFields();
        foreach($customFields AS $field) {
            $handle = $field->handle;
            if (in_array($handle, $excluded)) continue;
            $this->fields[$handle] = Plugin::getInstance()->fields->process($this->model->$handle);
        }
    }
}