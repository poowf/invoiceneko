<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Otter Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will get attached onto each Otter route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => [
        'web' => ['web'],
        'api' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Otter Pagination Property
    |--------------------------------------------------------------------------
    |
    | Number of records to show in the index page
    |
    */

    'pagination' => 10,

    /*
    |--------------------------------------------------------------------------
    | Otter User Model Property
    |--------------------------------------------------------------------------
    |
    | The following will be used when Otter is retrieving the name and email
    | of a user through Auth::user(). Change these if you use some other names
    | for the properties on your User model.
    |
    */

    'user' => [
        'name'  => 'full_name',
        'email' => 'email',
    ],
];
