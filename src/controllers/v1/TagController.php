<?php 
namespace futureactivities\rest\controllers\v1;

use Craft;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\data\ActiveDataFilter;
use craft\elements\Tag;
use futureactivities\rest\traits\ActionRemovable;
use yii\data\DataFilter;
use futureactivities\rest\data\ElementDataProvider;

class TagController extends ActiveController
{
    use ActionRemovable;
    
    public $modelClass = 'craft\records\Tag';
    
    public function actionIndex()
    {
        $this->checkAccess(null);
        
        $query = Tag::find()->status(null)->orderBy(null);
        $perPage = Craft::$app->request->getParam('per-page') ?? 20;
        $page = Craft::$app->request->getParam('page') ?? 1;
        
        if ($search = Craft::$app->request->getParam('search'))
            $query->search($search);
        
        if ($filter = Craft::$app->request->getParam('filter')) {
            foreach ($filter AS $key => $value)
                $query->$key($value);
        }
        
        return new ElementDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $perPage,
                'page' => $page - 1
            ]
        ]);
    }
    
    public function actionView($id)
    {
        $this->checkAccess($id);
        
        $tag = Tag::find()->status(null)->id($id)->one();
        
        return new \futureactivities\rest\models\Element([
            'model' => $tag
        ]);
    }
    
    public function actionCreate()
    {
        die('Not yet supported!');
    }
    
    public function checkAccess($action, $model = null, $params = [])
    { 
        $enabled = \futureactivities\rest\Plugin::getInstance()->settings->tags;
        
        if ($enabled == 1)
            return true;
        
        throw new ForbiddenHttpException();
    }
}