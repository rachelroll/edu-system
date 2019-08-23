<?php

return [
    'host' => env('ELASTICSEARCH_HOST'),
    'indices' => [
        'mappings' => [
            'default' => [
                'properties' => [
                    'id' => [
                        'type' => 'keyword',
                        'analyzer'=>'ik_max_word',
                    ],
                    'content' => [
                        'type' => 'text',
                        'analyzer'=>'ik_max_word',
                    ],

                ],
            ],
        ],
        'settings' => [
            'default' => [
                'number_of_shards' => 1,
                'number_of_replicas' => 0,
            ],
        ],
    ],
];
