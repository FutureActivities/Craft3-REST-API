<?php 
namespace futureactivities\rest\controllers\v1;

use Craft;
use craft\elements\GlobalSet;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\data\ActiveDataFilter;
use yii\data\DataFilter;
use futureactivities\rest\errors\BadRequestException;
use futureactivities\rest\traits\ActionRemovable;
use futureactivities\rest\Plugin;
use futureactivities\rest\data\ElementDataProvider;

class GlobalController extends ActiveController
{
    use ActionRemovable;
    
    public $modelClass = 'craft\records\GlobalSet';
    
    public function actionIndex()
    {
        $query = GlobalSet::find();
        
        if (Plugin::getInstance()->settings->disabled == 1)
            $query->status(null);
    
        if ($search = Craft::$app->request->getParam('search'))
            $query->search($search);
        
        if ($filter = Craft::$app->request->getParam('filter')) {
            foreach ($filter AS $key => $value)
                $query->$key($value);
        }
        
        return new ElementDataProvider([
            'query' => $query
        ]);
    }
    
    public function actionView($id)
    {
        $entry = GlobalSet::find()->status(null)->id($id)->one();
        
        if (!$entry)
            throw new BadRequestException('Could not find global set');
        
        return new \futureactivities\rest\models\Element([
            'model' => $entry
        ]);
    }
    
    public function actionCreate()
    {
        die('Not yet supported!');
    }
    
    public function checkAccess($action, $model = null, $params = [])
    { 
    }
}