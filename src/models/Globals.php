<?php
namespace futureactivities\rest\models;

use futureactivities\rest\models\Element;
use futureactivities\rest\Plugin;

class Globals extends Element
{
    public $handle;
    public $expiryDate;
    public $elementType;
    
    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'handle';
        
        return $fields;
    }
    
    protected function processModel()
    {
        if (!$this->model)
            return;
            
        parent::processModel();
        
        $this->handle = $this->model->handle;
    }
}