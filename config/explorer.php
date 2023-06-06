<?php

declare(strict_types=1);

/*use App\Models\System;
use App\Models\Station;*/
return [
    /*
     * There are different options for the connection. Since Explorer uses the Elasticsearch PHP SDK
     * under the hood, all the host configuration options of the SDK are applicable here. See
     * https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/configuration.html
     */
    'connection' => [
        'host' => env('ELASTIC_HOST', 'localhost'),
        'port' => env('ELASTIC_PORT', '9200'),
        'scheme' => env('ELASTIC_SCHEME', 'http'),
        'auth' => [
            'username' => env('ELASTIC_USER', 'elastic'),
            'password' => env('ELASTIC_PASSWORD', ''),
        ],
        'ssl' => [
            'verify' => env('ELASTIC_SSL_VERIFY', false),
        ]
    ],

    /**
     * The default index settings used when creating a new index. You can override these settings
     * on a per-index basis by implementing the IndexSettings interface on your model or defining
     * them in the index configuration below.
     */
    'default_index_settings' => [
        //'index' => [],
        //'analysis' => [],
    ],

    /**
     * An index may be defined on an Eloquent model or inline below. A more in depth explanation
     * of the mapping possibilities can be found in the documentation of Explorer's repository.
     */
    'indexes' => [
        \App\Models\System::class,
        \App\Models\Station::class,
        \App\Models\Commodity::class,
        \App\Models\Faction::class,
        \App\Models\Star::class,
    ],

    /**
     * You may opt to keep the old indices after the alias is pointed to a new index.
     * A model is only using index aliases if it implements the Aliased interface.
     */
    'prune_old_aliases' => true,
];
