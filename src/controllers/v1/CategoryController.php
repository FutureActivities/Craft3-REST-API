<?php 
namespace futureactivities\rest\controllers\v1;

use Craft;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\data\ActiveDataFilter;
use craft\elements\Category;
use futureactivities\rest\traits\ActionRemovable;
use yii\data\DataFilter;
use futureactivities\rest\Plugin;
use futureactivities\rest\data\CategoryDataProvider;

class CategoryController extends ActiveController
{
    use ActionRemovable;
    
    public $modelClass = 'craft\records\Category';
    
    public function actionIndex()
    {
        $query = Category::find();
        $perPage = Craft::$app->request->getParam('per-page');
        $page = Craft::$app->request->getParam('page') ?? 1;
        
        if (Plugin::getInstance()->settings->disabled == 1)
            $query->status(null);
        
        if ($search = Craft::$app->request->getParam('search'))
            $query->search($search);
        
        if ($filter = Craft::$app->request->getParam('filter')) {
            foreach ($filter AS $key => $value)
                $query->$key($value);
        }
        
        $pagination = is_null($perPage) || $perPage > 0 ? [
            'defaultPageSize' => 50,
            'page' => $page - 1
        ] : false;

        return new CategoryDataProvider([
            'query' => $query,
            'pagination' => $pagination
        ]);
    }
    
    public function actionView($id)
    {
        $category = Category::find()->status(null)->id($id)->one();
        
        return new \futureactivities\rest\models\Category([
            'model' => $category,
            'transform' => \Craft::$app->request->getParam('transforms')
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