<?php 
namespace futureactivities\rest\data;

use yii\base\InvalidConfigException;
use yii\db\QueryInterface;
use futureactivities\rest\Plugin;
use futureactivities\rest\models\Globals;

class GlobalDataProvider extends \yii\data\ActiveDataProvider
{
    /**
     * {@inheritdoc}
     */
    protected function prepareModels()
    {
        $models = parent::prepareModels();
        
        $result = [];
        foreach ($models AS $model) {
            $result[] = new Globals([
                'model' => $model
            ]);
        }
        
        return $result;
    }
}