# Future Activities Craft 3 REST API

The REST API plugin by FutureActivities provides easy to use API endpoints for any Craft 3 installation. No configuration required, 
just enable the plugin and the endpoints are immediately available.

You can optionally exclude certain fields and sections from the API to avoid exposing data.

## Usage

Full documentation of all endpoints can be found at:

    https://craftrest.docs.apiary.io/
    
## Examples

    GET /rest/v1/categories
    
Returns all categories

    GET /rest/v1/categories?expand=related
    
Returns all categories with the related field expanded - this will return data objects instead of IDs.

    GET /rest/v1/categories/18
    
Return a specific category

## Settings

From the control panel the plugin can be configured with the following settings:

### General

**Include Disabled** - Should disabled entries, categories, etc. be included in the API results.

**Enable Assets** - Enable the asset endpoints

**Enable Tags** - Enable the tag endpoints

### Fields

Configure which custom fields are available in the API results.

### Sections

Configure which sections are available in the API results.

## Cron

### Expiring tokens

Out of the box, user authentication tokens will never expire. To expire tokens, setup a cron job running
the following command:

    ./craft rest/token/expire <seconds>
    
`<seconds>` is optional. Default is 3600 seconds (1 hour).