<?php
namespace futureactivities\rest;

use yii\base\Event;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

class Plugin extends \craft\base\Plugin
{
    public bool $hasCpSettings = true;
    
    public function init()
    {
        // Set the controllerNamespace based on whether this is a console or web request
        if (\Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'futureactivities\\rest\\console\\controllers';
        } else {
            $this->controllerNamespace = 'futureactivities\\rest\\controllers';
        }
        
        parent::init();
        
        // Register our site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                // Entries
                $event->rules[] = [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'rest/v1/entry'
                ];
                
                // Categories
                $event->rules[] = [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'rest/v1/category'
                ];
                
                // Globals
                $event->rules[] = [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'rest/v1/global'
                ];
                
                // Tags
                $event->rules[] = [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'rest/v1/tag'
                ];
                
                // Assets
                $event->rules[] = [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'rest/v1/asset'
                ];
                
                // Users
                $event->rules[] = [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'rest/v1/user',
                    'extraPatterns' => [
                        'POST auth' => 'auth',
                        'POST verify' => 'verify',
                        'POST activate' => 'activate',
                        'POST password/reset' => 'send-password-reset',
                        'PUT password/reset' => 'do-password-reset',
                        'OPTIONS auth' => 'options',
                        'OPTIONS verify' => 'options',
                        'OPTIONS activate' => 'activate',
                        'OPTIONS password/reset' => 'options'
                    ]
                ];
                $event->rules[] = [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'rest/v1/me'
                ];
                $event->rules[] = [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'rest/v2/me'
                ];
                
                // General
                $event->rules['rest/v1/uri/<uri:.*>'] = 'rest/v1/general/uri';
            }
        );
        
        $this->setComponents([
            'user' => \futureactivities\rest\services\User::class,
            'fields' => \futureactivities\rest\services\Fields::class,
            'sections' => \futureactivities\rest\services\Sections::class
        ]);
    }
    
    protected function createSettingsModel(): \craft\base\Model
    {
        return new \futureactivities\rest\models\Settings();
    }
    
    public function getSettingsResponse(): mixed
    {
        return \Craft::$app->controller->renderTemplate('rest/settings', [
            'settings' => $this->getSettings(),
            'plugin' => $this
        ]);
    }
}