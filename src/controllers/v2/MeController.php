<?php 
namespace futureactivities\rest\controllers\v2;

use Craft;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\data\ActiveDataFilter;

use futureactivities\rest\traits\ActionRemovable;
use futureactivities\rest\Plugin;
use futureactivities\rest\models\User as RestUser;

class MeController extends ActiveController
{
    use ActionRemovable;
    
    public $modelClass = 'craft\records\User';
    
    /**
     * Get logged in user account details
     */
    public function actionIndex()
    {
        $user = Plugin::getInstance()->user->auth();
        
        return new RestUser([
            'model' => $user
        ]);
    }
    
    /**
     * Options request
     */
    public function actionOptions()
    {
        return true;
    }
}