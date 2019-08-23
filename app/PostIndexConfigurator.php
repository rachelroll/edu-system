<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class PostIndexConfigurator extends IndexConfigurator
{
    use Migratable;
    protected $name = 'posts_index';
    // It's not obligatory to determine name. By default it'll be a snaked class name without `IndexConfigurator` part.

    // You can specify any settings you want, for example, analyzers.
    protected $settings = [
        'analysis' => [
            'analyzer' => [
                'ik_max_word' => [
                    'type' => 'standard',
                    'stopwords' => '_spanish_'
                ]
            ]
        ],
    ];
}
