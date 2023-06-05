<?php
namespace futureactivities\rest\models;

use futureactivities\rest\models\Element;
use futureactivities\rest\Plugin;

class Matrix extends Element
{
    public $handle;
    
    public function fields(): array
    {
        return [
            'id',
            'handle',
            'fields'
        ];
    }
    
    protected function processModel()
    {
        if (!$this->model)
            return;
            
        parent::processModel();
        
        if (isset($this->model->type) && isset($this->model->type->handle))
            $this->handle = $this->model->type->handle;
    }
}