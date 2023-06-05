# Craft REST API

The REST API plugin by FutureActivities provides easy to use API endpoints for any Craft 3 or 4 installation. 
No configuration required, just enable the plugin and the endpoints are immediately available.

You can optionally exclude certain fields and sections from the API to avoid exposing data.

Use v1 for Craft 3, v2+ for Craft 4.

## Features

- Get all Entries, Categories, Globals, Tags, and Assets.
- Authenticate and manage users.
- Build on top of Yii 2 RESTful Web Services - supports expands, fields, pagination.
- Exclude specific fields or sections from being included in the response.

## Installation

Install via composer:

    composer require futureactivities/craft3-rest-api
    

## Usage

Further documentation of available endpoints can be found at:

[https://craftrest.docs.apiary.io/](https://craftrest.docs.apiary.io/)

## Examples

Get all categories:

    GET /rest/v1/categories
    
Get all categories with the related field expanded - this will return data objects instead of IDs.

    GET /rest/v1/categories?expand=related

Get a specific category

    GET /rest/v1/categories/18

Get all entries belonging to the news channel & limit results to slug and title only:

    GET /rest/v1/entries?filter[section]=news&fields=slug,title
    
Get all entries in the news channel, expand featured image and apply a specific image transform:

    GET /rest/v1/entries?filter[section]=news&expand=featuredImage&transforms[featuredImage]=featuredFull,featuredThumb

## Settings

From the control panel the plugin can be configured with the following settings:

### General

`Include Disabled` - Should disabled entries, categories, etc. be included in the API results.

`Enable Assets` - Enable the asset endpoints

`Enable Tags` - Enable the tag endpoints

### Fields

Configure which custom fields are available in the API results.

### Sections

Configure which sections are available in the API results.

## Events

### Field Event

This plugin only supports a limited number of field types in the API responses, for other field
types and custom field types you can process the response yourself using the following event:

    Event::on(\futureactivities\rest\services\Fields::class, \futureactivities\rest\services\Fields::EVENT_PROCESS_FIELD, function(Event $event) {
        $field = $event->field;
        
        if (is_a($field, 'namespace\plugin\CustomFieldType'))
            $event->data = [
                'key' => 'value'   
            ];
        }
    });
    
### Extra Fields Event

Sometimes you might want to include additional data in the response, the following event will allow you to add your own fields
to the element.

    Event::on(\futureactivities\rest\models\Element::class, \futureactivities\rest\models\Element::EVENT_EXTRA_FIELDS, function(Event $event) {
        $model = $event->model;
        
        if (is_a($model, 'craft\elements\Entry') && $model->section->handle == 'news') {
            $event->fields = [
                'myCustomField' => 'Hello'  
            ];
        }
    });

## Cron

### Expiring tokens

Out of the box, user authentication tokens will never expire. To expire tokens, setup a cron job running
the following command:

    ./craft rest/token/expire <seconds>
    
`<seconds>` is optional. Default is 3600 seconds (1 hour).

## Changelog

### 1.2

- Fixed broken CMS settings layout
- Fixed bug in token verification
- Added `rest/v2/me` endpoint which returns a more useful User object that also works with field expands.

### 1.1

- Asset image transforms now expects `transforms` on the endpoint instead of `imageTransform`. This now allows a comma separated value of transform keys and will return an array of transforms along with the original image url.
- Added support to specify image transforms on entry & category requests, in the following format:
    
    `transforms[field] = 'transformKey,anotherKey'`
    `transforms[parent][child] = 'transformKey'`

## Roadmap

The following features are planned for future versions:

- API Authentication - Limit the use of the API to authenticated users only.
- Limit the inclusion of fields/sections/etc. to certain user roles.