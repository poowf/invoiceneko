<?php

return [

    'pdf' => [
        'enabled' => true,
        'binary'  => '/usr/bin/xvfb-run -a /usr/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
    'image' => [
        'enabled' => true,
        'binary'  => '/usr/bin/xvfb-run -a /usr/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
