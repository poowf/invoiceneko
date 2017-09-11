<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  => '/usr/bin/xvfb-run -a /usr/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => '/usr/bin/xvfb-run -a /usr/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
