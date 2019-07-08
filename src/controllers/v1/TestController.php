<?php 
namespace futureactivities\rest\controllers\v1;

use Craft;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\data\ActiveDataFilter;
use yii\web\ForbiddenHttpException;

use futureactivities\api\Plugin;

class TestController extends ActiveController
{
    public $modelClass = 'craft\records\Entry';
    
    public function actions()
    {
        return [];
    }
    
    public function actionIndex()
    {
        die('YES');
    }
}