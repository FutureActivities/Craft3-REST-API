<?php
namespace futureactivities\rest\models;

use futureactivities\rest\models\Element;
use futureactivities\rest\Plugin;

class Asset extends Element
{
    public $transform = null;
    public $transforms = [];
    public $focalPoint = [];
    public $kind = null;
    
    public function fields(): array
    {
        $fields = parent::fields();
        $fields[] = 'url';
        $fields[] = 'transforms';
        $fields[] = 'focalPoint';
        $fields[] = 'kind';
        
        return $fields;
    }
    
    protected function processModel()
    {
        if (!$this->model)
            return;
            
        parent::processModel();
        
        $this->kind = $this->model->kind;
        $this->focalPoint = $this->model->focalPoint;
        
        // Process any transforms
        if ($this->transform) {
            $transforms = explode(',', $this->transform);
            foreach($transforms AS $transform)
                $this->transforms[$transform] = $this->model->getUrl($transform);
        }
        
        $this->url = $this->model->getUrl();
    }
}