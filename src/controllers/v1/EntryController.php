<?php 
namespace futureactivities\rest\controllers\v1;

use Craft;
use craft\elements\Entry;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\data\ActiveDataFilter;
use yii\data\DataFilter;
use futureactivities\rest\errors\BadRequestException;
use futureactivities\rest\traits\ActionRemovable;
use futureactivities\rest\Plugin;
use futureactivities\rest\data\EntryDataProvider;

class EntryController extends ActiveController
{
    use ActionRemovable;
    
    public $modelClass = 'craft\records\Entry';
    
    public function actionIndex()
    {
        $query = Entry::find();
        $perPage = Craft::$app->request->getParam('per-page');
        
        if (Plugin::getInstance()->settings->disabled == 1)
            $query->status(null);
    
        if ($search = Craft::$app->request->getParam('search'))
            $query->search($search);
        
        if ($filter = Craft::$app->request->getParam('filter')) {
            foreach ($filter AS $key => $value)
                $query->$key($value);
        }
        
        // Filter excluded sections
        $excludedSections = Plugin::getInstance()->sections->getExcluded();
        if (!empty($excludedSections)) {
            $sectionQuery = 'and, not ' . implode(', not ', $excludedSections);
            if (($filter = Craft::$app->request->getParam('filter')) && isset($filter['section']))
                $sectionQuery .= ', '.$filter['section'];
            
            $query->section = $sectionQuery;
        }
        
        $pagination = is_null($perPage) || $perPage > 0 ? [
            'defaultPageSize' => 50,
        ] : false;
        
        return new EntryDataProvider([
            'query' => $query,
            'pagination' => $pagination
        ]);
    }
    
    public function actionView($id)
    {
        $entry = Entry::find()->status(null)->id($id)->one();
        
        if (!$entry)
            throw new BadRequestException('Could not find entry');
            
        if (Plugin::getInstance()->sections->isExcluded($entry->section->handle))
            throw new BadRequestException('Could not find entry');
        
        return new \futureactivities\rest\models\Entry([
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