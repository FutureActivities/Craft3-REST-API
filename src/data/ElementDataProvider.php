<?php 
namespace futureactivities\rest\data;

use yii\base\InvalidConfigException;
use yii\db\QueryInterface;

use futureactivities\rest\models\Element;

class ElementDataProvider extends \yii\data\ActiveDataProvider
{
    /**
     * {@inheritdoc}
     */
    protected function prepareModels()
    {
        $models = parent::prepareModels();
        
        $result = [];
        foreach ($models AS $model) {
            $result[] = new Element([
                'model' => $model
            ]);
        }
        
        return $result;
    }
}