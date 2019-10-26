<?php 
namespace futureactivities\rest\controllers\v1;

use Craft;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\data\ActiveDataFilter;
use craft\elements\Asset;
use futureactivities\rest\traits\ActionRemovable;
use yii\data\DataFilter;
use futureactivities\rest\data\AssetDataProvider;

class AssetController extends ActiveController
{
    use ActionRemovable;
    
    public $modelClass = 'craft\records\Asset';
    
    public function actionIndex()
    {
        $this->checkAccess(null);
        
        $query = Asset::find()->status(null);
        
        if ($search = Craft::$app->request->getParam('search'))
            $query->search($search);
        
        if ($filter = Craft::$app->request->getParam('filter')) {
            foreach ($filter AS $key => $value)
                $query->$key($value);
        }
        
        return new AssetDataProvider([
            'query' => $query
        ]);
    }
    
    public function actionView($id)
    {
        $this->checkAccess($id);
        
        $imageTransforms = Craft::$app->request->getParam('transforms');
        
        $tag = Asset::find()->status(null)->id($id)->one();
        $asset = new \futureactivities\rest\models\Asset([
            'model' => $tag,
            'transform' => $imageTransforms
        ]);
        
        return $asset;
    }
    
    public function actionCreate()
    {
        die('Not yet supported!');
    }
    
    public function checkAccess($action, $model = null, $params = [])
    { 
        $enabled = \futureactivities\rest\Plugin::getInstance()->settings->assets;
        
        if ($enabled == 1)
            return true;
        
        throw new ForbiddenHttpException();
    }
}