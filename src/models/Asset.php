<?php
namespace futureactivities\rest\models;

use futureactivities\rest\models\Element;
use futureactivities\rest\Plugin;

class Asset extends Element
{
    public $transform = null;
    public $focalPoint = [];
    
    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'url';
        $fields[] = 'focalPoint';
        
        return $fields;
    }
    
    protected function processModel()
    {
        if (!$this->model)
            return;
            
        parent::processModel();
        
        $this->focalPoint = $this->model->focalPoint;
        
        if ($this->transform)
            $this->url = $this->model->getUrl($this->transform);
        else
            $this->url = $this->model->getUrl();
    }
}