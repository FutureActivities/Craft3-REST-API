<?php
namespace futureactivities\rest\models;

use futureactivities\rest\models\Element;
use futureactivities\rest\Plugin;

class Entry extends Element
{
    public $author;
    public $postDate;
    public $expiryDate;
    public $elementType;
    
    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'author';
        $fields[] = 'postDate';
        $fields[] = 'expiryDate';
        $fields[] = 'elementType';
        
        return $fields;
    }
    
    protected function processModel()
    {
        if (!$this->model)
            return;
            
        parent::processModel();
        
        $this->author = $this->formatAuthor($this->model->author);
        $this->postDate = $this->model->postDate;
        $this->expiryDate = $this->model->expiryDate;
        $this->elementType = $this->model->type->handle;
    }
    
    private function formatAuthor($author)
    {
        if (!$author)
            return [];
        
        return [
            'id' => $author->id,
            'firstName' => $author->firstName,
            'lastName' => $author->lastName,
            'email' => $author->email
        ];
    }
}