<?php 
namespace futureactivities\rest\data;

use yii\base\InvalidConfigException;
use yii\db\QueryInterface;
use futureactivities\rest\Plugin;
use futureactivities\rest\models\Entry;

class EntryDataProvider extends \yii\data\ActiveDataProvider
{
    /**
     * {@inheritdoc}
     */
    protected function prepareModels()
    {
        $models = parent::prepareModels();
        
        $result = [];
        foreach ($models AS $model) {
            $result[] = new Entry([
                'model' => $model,
                'transform' => \Craft::$app->request->getParam('transforms')
            ]);
        }
        
        return $result;
    }
}