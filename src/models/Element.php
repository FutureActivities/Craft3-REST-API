<?php
namespace futureactivities\rest\models;

use \craft\base\Model;
use futureactivities\rest\Plugin;
use futureactivities\rest\models\Category;
use futureactivities\rest\events\ExtraFieldsEvent;

class Element extends Model
{
    const EVENT_EXTRA_FIELDS = 'extraFields';
    
    public $model;
    public $transform;
    
    public $id;
    public $title;
    public $slug;
    public $url;
    public $enabled = true;
    public $parentId = null;
    public $descendants = [];
    public $fields = [];
    
    public function init(): void
    {
        parent::init();
        $this->processModel();
    }
    
    public function fields(): array
    {
        return [
            'id',
            'title',
            'slug',
            'url',
            'enabled',
            'parentId',
            'descendants',
            'fields'
        ];
    }
    
    /**
     * Fields that extend craft\elements\db\ElementQuery are expandable.
     * Adding ?expand=fieldname will return the formatted models instead of IDs.
     */
    public function extraFields(): array
    {
        $result = [];
        
        // Expand custom fields
        if ($fieldLayout = $this->model->getFieldLayout()) {
            foreach($fieldLayout->getCustomFields() AS $field) {
                $handle = $field->handle;
                $element = $this->model->$handle;
                
                if (!is_object($element) || get_parent_class($element) !== 'craft\elements\db\ElementQuery')
                    continue;
                
                // Determine element class
                $class = $this->getElementClass($element);
                
                $transform = null;
                if ($this->transform && isset($this->transform[$handle])) {
                    $transform = $this->transform[$handle];
                }
                
                // Define expandable function
                $result[$handle] = function($model) use ($class, $element, $transform) {
                    $data = [];
                    
                    foreach ($element->all() AS $item) {
                        $data[] = new $class([
                            'model' => $item,
                            'transform' => $transform
                        ]);
                    }
                    
                    return $data;
                };
            }
        }
        
        // Expand parent
        if ($this->parentId) {
            $result['parentId'] = function($model) {
                $parent = $model->model->parent;
                $class = $this->getElementClass($parent);
                return new $class([
                    'model' => $parent
                ]);
            };
        }
        
        // Expand descendants
        if (!empty($this->descendants)) {
            $result['descendants'] = function($model) {
                $class = $this->getElementClass($model->model);
                $data = [];
                foreach ($model->model->descendants->all() AS $item) {
                    $data[] = new $class([
                        'model' => $item,
                        'transform' => $this->transform
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
        $this->url = $this->model->uri;
        $this->enabled = $this->model->enabled == 1;
        $this->parentId = $this->model->parent ? $this->model->parent->id : false;
        $this->descendants = $this->model->hasDescendants ? $this->model->descendants->ids() : [];

        // Get the elements custom fields
        if ($fieldLayout = $this->model->getFieldLayout()) {
            $excluded = Plugin::getInstance()->fields->getExcluded();
            $customFields = $fieldLayout->getCustomFields();
            foreach($customFields AS $field) {
                $handle = $field->handle;
                if (in_array($handle, $excluded)) continue;
                $this->fields[$handle] = Plugin::getInstance()->fields->process($this->model->$handle);
            }
            
            $event = new ExtraFieldsEvent([
                'model' => $this->model
            ]);
            
            $this->trigger(self::EVENT_EXTRA_FIELDS, $event);
            
            if (!is_null($event->fields))
                $this->fields = array_merge($this->fields, $event->fields);
        }
    }
    
    /**
     * Check what type of element is being processed and return
     * the appropriate model
     */
    protected function getElementClass($element)
    {
        if ($element instanceof \craft\elements\db\MatrixBlockQuery)
            return 'futureactivities\rest\models\Matrix';
            
        if ($element instanceof \craft\elements\db\EntryQuery)
            return 'futureactivities\rest\models\Entry';
            
        if ($element instanceof \craft\elements\db\CategoryQuery)
            return 'futureactivities\rest\models\Category';
            
        if ($element instanceof \craft\elements\db\AssetQuery)
            return 'futureactivities\rest\models\Asset';
        
        return 'futureactivities\rest\models\Element';
    }
}