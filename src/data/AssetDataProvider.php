<?php 
namespace futureactivities\rest\data;

use yii\base\InvalidConfigException;
use yii\db\QueryInterface;

use futureactivities\rest\models\Asset;

class AssetDataProvider extends \yii\data\ActiveDataProvider
{
    /**
     * {@inheritdoc}
     */
    protected function prepareModels()
    {
        $models = parent::prepareModels();
        
        $result = [];
        foreach ($models AS $model) {
            $result[] = new Asset([
                'model' => $model,
                'transform' => \Craft::$app->request->getParam('transforms')
            ]);
        }
        
        return $result;
    }
}