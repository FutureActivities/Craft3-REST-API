<?php
namespace futureactivities\rest\models;

use futureactivities\rest\models\Element;
use futureactivities\rest\Plugin;

class Asset extends Element
{
    public $url;
    public $transform = null;
    
    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'url';
        
        return $fields;
    }
    
    protected function processModel()
    {
        if (!$this->model)
            return;
            
        parent::processModel();
        
        if ($this->transform)
            $this->url = $this->model->getUrl($this->transform);
        else
            $this->url = $this->model->getUrl();
    }
}