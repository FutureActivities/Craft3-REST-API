<?php 
namespace futureactivities\rest\controllers\v1;

use Craft;
use craft\web\Controller;
use craft\events\ElementEvent;
use futureactivities\rest\errors\BadRequestException;

class GeneralController extends Controller
{
    const EVENT_URI_PROCESS = 'processUri';
    
    protected $allowAnonymous = true;
    
    public function actionUri($uri)
    {
        $element = \Craft::$app->elements->getElementByUri($uri);
        
        if (!$element)
            throw new BadRequestException('Unable to find element.');
        
        $reflect = new \ReflectionClass($element);
        
        $result = [
            'id' => $element->id,
            'type' => strtolower($reflect->getShortName()),
        ];
        
        if (isset($element->type))
            $result['handle'] = $element->type->handle;
        
        $this->trigger(self::EVENT_URI_PROCESS, new ElementEvent([
            'element' => $element
        ]));
        
        return $this->asJson($result);
    }
}