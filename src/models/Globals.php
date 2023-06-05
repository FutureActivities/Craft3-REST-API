<?php
namespace futureactivities\rest\models;

use futureactivities\rest\models\Element;
use futureactivities\rest\Plugin;

class Globals extends Element
{
    public $handle;
    public $expiryDate;
    public $elementType;
    
    public function fields(): array
    {
        $fields = parent::fields();
        $fields[] = 'handle';
        
        if (($key = array_search('slug', $fields)) !== false)
            unset($fields[$key]);
            
        if (($key = array_search('url', $fields)) !== false)
            unset($fields[$key]);
            
         if (($key = array_search('parentId', $fields)) !== false)
            unset($fields[$key]);
        
        return $fields;
    }
    
    protected function processModel()
    {
        if (!$this->model)
            return;
            
        parent::processModel();
        
        $this->title = $this->model->name;
        $this->handle = $this->model->handle;
    }
}