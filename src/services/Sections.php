<?php
namespace futureactivities\rest\services;

use yii\base\Component;

class Sections extends Component
{
    /**
     * Get sections to be excluded from results
     */
    public function getExcluded()
    {
        $excludedSections = \futureactivities\rest\Plugin::getInstance()->settings->excludedSections;
        
        if (!$excludedSections)
            return [];
            
        $excludes = array_filter($excludedSections, function ($e) {
                return $e['visible'] == 1;
            }
        );
        
        return array_keys($excludes);
    }
    
    /**
     * Check if a section handle has been excluded
     */
    public function isExcluded($handle)
    {
        $excluded = $this->getExcluded();
       
        if (in_array($handle, $excluded))
            return true;
            
        return false;
    }
}