<?php
namespace futureactivities\rest\models;

use futureactivities\rest\models\Element;
use futureactivities\rest\Plugin;

class User extends Element
{
    public $username;
    public $firstName;
    public $lastName;
    public $email;
    public $status;
    public $dateCreated;
    public $lastLoginDate;
    public $groups;
    
    public function fields(): array
    {
        return [
            'id',
            'username',
            'firstName',
            'lastName',
            'email',
            'status',
            'dateCreated',
            'lastLoginDate',
            'fields',
            'groups'
        ];
    }
    
    protected function processModel()
    {
        if (!$this->model)
            return;
        
        parent::processModel();
        
        $this->username = $this->model->username;
        $this->firstName = $this->model->firstName;
        $this->lastName = $this->model->lastName;
        $this->email = $this->model->email;
        $this->status = $this->model->status;
        $this->dateCreated = $this->model->dateCreated;
        $this->dateCreated->string = $this->model->dateCreated->format('Y-m-d\TH:i:s.\0\0\0\Z');
        $this->lastLoginDate = $this->model->lastLoginDate;
        $this->lastLoginDate->string = $this->model->lastLoginDate->format('Y-m-d\TH:i:s.\0\0\0\Z');
        $this->groups = $this->model->getGroups();
    }
}