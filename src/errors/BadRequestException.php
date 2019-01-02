<?php
namespace futureactivities\rest\errors;

use yii\web\BadRequestHttpException;

class BadRequestException extends BadRequestHttpException implements \JsonSerializable
{
    private $data = [];

    public function __construct($message, $data = []) 
    {
        parent::__construct($message);
        
        $this->data = $data;
    }

    /**
     * @return array Additional data
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Format exception for JSON
     */
    public function jsonSerialize() 
    {
        return [
            'message' => $this->getMessage(),
            'data' => $this->getData()
        ];
    }
}
