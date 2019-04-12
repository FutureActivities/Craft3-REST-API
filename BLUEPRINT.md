FORMAT: 1A
HOST: [your-site]/rest

# Craft REST API

The REST API plugin by FutureActivities provides easy to use API endpoints for any Craft 3 installation. No configuration required, 
just enable the plugin and the endpoints are immediately available.

You can optionally exclude certain fields and sections from the API to avoid exposing data.


# Group Entries

## Collection [/v1/entries{?filter,perpage,page,search}]

### List [GET]

Get a collection of entries.

+ Parameters
    + filter (array) - Optional filter results by fields. Supports any value you can do with an [element query](https://docs.craftcms.com/v3/dev/element-queries/entry-queries.html)
    + perpage (number) - The number of entries to return per page
    + page (number) - The current pages
    + search (string) - Optional search data as defined in the [Craft CMS documentation](https://docs.craftcms.com/v3/searching.html)

+ Response 200 (application/json)

    + Headers

            X-Pagination-Total-Count: XX
            X-Pagination-Page-Count: XX
            X-Pagination-Current-Page: X
            X-Pagination-Per-Page: X
        
    + Body

            [
                {
                    id: 0,
                    title: 'Lorem Ipsum',
                    slug: 'lorem-ipsum',
                    postDate: '2018-12-25 00:00:00',
                    parentId: false,
                    descendants: [],
                    fields: [
                        'your-custom-field': '123'
                    ]
                }
            ]

## Entry [/v1/entries/{id}{?expand}]

### Get [GET]

Get a specific entry.

+ Parameters
    + expand (string) - Comma separated list of Element fields to expand. If not set, element IDs are returned in the response.

+ Response 200 (application/json)

    + Body

            {
                id: 0,
                title: 'Lorem Ipsum',
                slug: 'lorem-ipsum',
                postDate: '2018-12-25 00:00:00',
                parentId: false,
                descendants: [],
                fields: [
                    'your-custom-field': '123'
                ]
            }
            
# Group Categories
            
## Collection [/v1/categories{?filter,perpage,page,search}]

### List [GET]

Get a collection of categories.

+ Parameters
    + filter (array) - Optional filter results by fields. Supports any value you can do with an [element query](https://docs.craftcms.com/v3/dev/element-queries/entry-queries.html)
    + perpage (number) - The number of entries to return per page
    + page (number) - The current pages
    + search (string) - Optional search data as defined in the [Craft CMS documentation](https://docs.craftcms.com/v3/searching.html)

+ Response 200 (application/json)

    + Body

            [
                {
                    id: 0,
                    title: 'Lorem Ipsum',
                    slug: 'lorem-ipsum',
                    parentId: false,
                    descendants: [],
                    fields: [
                        'your-custom-field': '123'
                    ]
                }
            ]
            
## Category [/v1/categories/{id}{?expand}]

### Get [GET]

Get a specific category.

+ Parameters
    + expand (string) - Comma separated list of Element fields to expand. If not set, element IDs are returned in the response.

+ Response 200 (application/json)

    + Body

            {
                id: 0,
                title: 'Lorem Ipsum',
                slug: 'lorem-ipsum',
                parentId: false,
                descendants: [],
                fields: [
                    'your-custom-field': '123'
                ]
            }
            
# Group Globals

## Collection [/v1/globals{?perpage,page,search}]

### List [GET]

Get a collection of globals.

+ Parameters
    + perpage (number) - The number of entries to return per page
    + page (number) - The current pages
    + search (string) - Optional search data as defined in the [Craft CMS documentation](https://docs.craftcms.com/v3/searching.html)

+ Response 200 (application/json)

    + Headers

            X-Pagination-Total-Count: XX
            X-Pagination-Page-Count: XX
            X-Pagination-Current-Page: X
            X-Pagination-Per-Page: X
        
    + Body

            [
                {
                    id: 0,
                    title: 'Lorem Ipsum',
                    slug: 'lorem-ipsum',
                    parentId: false,
                    descendants: [],
                    fields: [
                        'your-custom-field': '123'
                    ]
                }
            ]

## Entry [/v1/globals/{id}{?expand}]

### Get [GET]

Get a specific global.

+ Parameters
    + expand (string) - Comma separated list of Element fields to expand. If not set, element IDs are returned in the response.

+ Response 200 (application/json)

    + Body

            {
                id: 0,
                title: 'Lorem Ipsum',
                slug: 'lorem-ipsum',
                parentId: false,
                descendants: [],
                fields: [
                    'your-custom-field': '123'
                ]
            }
            
# Group Assets
            
## Collection [/v1/assets]

### List [GET]

Get a collection of assets.

+ Response 200 (application/json)

    + Body

            [
                {
                    id: 0,
                    title: 'An Image',
                    slug: 'an-image',
                    fields: [
                        'your-custom-field': '123'
                    ],
                    url: 'XXX'
                }
            ]
            
## Asset [/v1/assets/{id}{?expand}{?imageTransform}]

### Get [GET]

Get a specific tag.

+ Parameters
    + expand (string) - Comma separated list of Element fields to expand. If not set, element IDs are returned in the response.
    + imageTransform (string) - A Craft image transform handle to apply.

+ Response 200 (application/json)

    + Body

            {
                id: 0,
                title: 'An Image',
                slug: 'an-image',
                fields: [
                    'your-custom-field': '123'
                ],
                url: 'XXX'
            }
            
# Group Tags
            
## Collection [/v1/tags]

### List [GET]

Get a collection of tags.

+ Response 200 (application/json)

    + Body

            {
            }
            
## Tag [/v1/tags/{id}{?expand}]

### Get [GET]

Get a specific tag.

+ Parameters
    + expand (string) - Comma separated list of Element fields to expand. If not set, element IDs are returned in the response.

+ Response 200 (application/json)

    + Body

            {
                id: 0,
                title: 'Lorem Ipsum',
                slug: 'lorem-ipsum',
                fields: [
                    'your-custom-field': '123'
                ]
            }

# Group Users

## Authentication [/v1/users/auth]

### Login [POST]

Login a user and generate an access token.

+ Attributes
    + username (string) - The username or email of the Craft user
    + password (string) - The users password
    
+ Request (application/x-www-form-urlencoded)

+ Response 200 (application/json)

    + Body

            {
                "token": "[token]"
            }
            
## Verify Token [/v1/users/verify]

### Verify [POST]

Verify authentication token is valid.

+ Attributes
    + verifyToken (string) - The user authentication token
    
+ Request (application/x-www-form-urlencoded)

+ Response 200 (application/json)

    + Body

            {
                true
            }
            
## Collection [/v1/users]

### List [GET]

Get a list of users.

+ Request

    + Headers
    
            Authorization: "Bearer [token]"

+ Response 200 (application/json)

    + Body

            [
                {
                    "username": "user",
                    "photoId": null,
                    "firstName": "First",
                    "lastName": "Last",
                    "email": "user@example.com",
                    "lastLoginDate": "2018-12-04T01:52:52-08:00",
                    "invalidLoginCount": null,
                    "lastInvalidLoginDate": null,
                    ...
                },
                {
                    ...
                },
                ...
            ]


### Create [POST]

Create a new user.

+ Request (application/x-www-form-urlencoded)

    + Body

            {
                "customer": {
                    "firstName": "",
                    "lastName": "",
                    "email": "",
                    ...
                },
                "password": ""
            }

+ Response 200 (application/json)

    + Body

            {
                "success": true
            }
            
## Me [/me]
            
### Get Own Account [GET]

Get details about the current user.

+ Response 200 (application/json)

    + Body

            {
                "username": "user",
                "photoId": null,
                "firstName": "First",
                "lastName": "Last",
                "email": "user@example.com",
                "lastLoginDate": "2018-12-04T01:52:52-08:00",
                "invalidLoginCount": null,
                "lastInvalidLoginDate": null,
                ...
            }
            
## Users [/v1/users/{id}]

### Get Account [GET]

Get a users account.

+ Request (application/json)

    + Headers
    
            Authorization: "Bearer [token]"

+ Response 200 (application/json)

    + Body

            {
                "username": "user",
                "photoId": null,
                "firstName": "First",
                "lastName": "Last",
                "email": "user@example.com",
                "lastLoginDate": "2018-12-04T01:52:52-08:00",
                "invalidLoginCount": null,
                "lastInvalidLoginDate": null,
                ...
            }

### Update Account [PUT]

Update a users account.

+ Request (application/json)

    + Headers
    
            Authorization: "Bearer [token]"
    
    + Body

            {
                "customer": {
                    "firstName": "",
                    "lastName": "",
                    "email": ""
                },
                "password": ""
            }

+ Response 200 (application/json)

    + Body

            {
                "success": true
            }

## Passwords [/v1/users/password/reset]

### Generate reset link [POST]

Generate the password reset link.

+ Request (application/x-www-form-urlencoded)

    + Body 
    
            {
                "username": "admin"
            }

+ Response 200 (application/json)

    + Body
    
            {
                true
            }
        
### Reset user password [PUT]

Reset the user password

+ Request (application/x-www-form-urlencoded)

    + Body
    
            {
                "code": "LJ0FcBCNKcuMd-2WgCrIz213nF4PFASD",
                "id": "dfd449f8-89ee-43d5-9c9a-347a6cdc7632",
                "newPassword": "new-password-123"
            }
        
+ Response 200 (application/json)
    
    + Body 
        
            {
                true
            }

# Group General

## URI [/v1/uri/{uri}]

### Get Details [GET]

Returns a URIs associated element type and ID.

+ Response 200 (application/json)

    + Body

            {
                "id": 0
                "type": "entry",
                "handle": "blog"
            }
            
# Group Sites

This plugin will automatically support multi-sites.