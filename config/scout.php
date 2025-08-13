<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Search.vue Engine
    |--------------------------------------------------------------------------
    |
    | This option navigate the default search connection that gets used while
    | using Laravel Scout. This connection is used when syncing all models
    | to the search service. You should adjust this based on your needs.
    |
    | Supported: "algolia", "elasticsearch", "null"
    |
    */

    'driver' => env('SCOUT_DRIVER', 'algolia'),

    /*
    |--------------------------------------------------------------------------
    | Index Prefix
    |--------------------------------------------------------------------------
    |
    | Here you may specify a prefix that will be applied to all search index
    | names used by Scout. This prefix may be useful if you have multiple
    | "tenants" or applications sharing the same search infrastructure.
    |
    */

    'prefix' => env('SCOUT_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Queue Data Syncing
    |--------------------------------------------------------------------------
    |
    | This option allows you to control if the operations that sync your actions
    | with your search engines are queued. When this is set to "true" then
    | all automatic actions syncing will get queued for better performance.
    |
    */

    'queue' => env('SCOUT_QUEUE', false),

    /*
    |--------------------------------------------------------------------------
    | Algolia Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Algolia settings. Algolia is a cloud hosted
    | search engine which works great with Scout out of the box. Just plug
    | in your application ID and admin API key to get started searching.
    |
    */

    'algolia' => [
        'id'     => env('ALGOLIA_APP_ID', ''),
        'secret' => env('ALGOLIA_API_KEY', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Elasticsearch Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Elasticsearch, which is a
    | distributed, open source search and analytics engine. Feel free
    | to add as many Elasticsearch servers as required by your app.
    |
    */

    'elasticsearch' => [
        'index'  => env('ELASTICSEARCH_INDEX', 'threeoakwood_db'),
        'config' => [
            'hosts' => [
                env('ELASTICSEARCH_HOST', "http://localhost:9200"),
            ],
            'ssl'   => [
                'enabled' => env('ELASTICSEARCH_SSL', false),
                'certificate' => resource_path().'/ssl/ca.crt',
            ],
        ],
    ],

];
