<?php

return [

    'pdf' => [
        'enabled' => true,
        'binary'  => '/usr/bin/xvfb-run -a /usr/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => [
            'enable-local-file-access' => true,
            'encoding'                 => 'UTF-8',
        ],
        'env'     => [],
    ],
    'image' => [
        'enabled' => true,
        'binary'  => '/usr/bin/xvfb-run -a /usr/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => [
            'enable-local-file-access' => true,
            'encoding'                 => 'UTF-8',
        ],
        'env'     => [],
    ],

];
