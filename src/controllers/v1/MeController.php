<?php 
namespace futureactivities\rest\controllers\v1;

use Craft;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\data\ActiveDataFilter;

use futureactivities\rest\traits\ActionRemovable;
use futureactivities\rest\Plugin;

class MeController extends ActiveController
{
    use ActionRemovable;
    
    public $modelClass = 'craft\records\User';
    
    /**
     * Get logged in user account details
     */
    public function actionIndex()
    {
        return Plugin::getInstance()->user->auth();
    }
}