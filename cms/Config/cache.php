<?php

return [
    'Cache' => [
        'short' => [
            'className' => 'File',
            'duration' => '+1 hours',
            'path' => ROOT_DIR . DS,
            'prefix' => 'cake_short_'
        ],
        // Использование пространства имён в качестве имени
        'long' => [
            'className' => 'Cake\Cache\Engine\FileEngine',
            'duration' => '+1 week',
            'probability' => 100,
            'path' => ROOT_DIR . DS . 'long' . DS,
        ]
    ]
];
