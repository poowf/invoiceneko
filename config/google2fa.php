<?php

return [

    /*
     * Auth container binding
     */

    'enabled' => true,

    /*
     * Lifetime in minutes.
     * In case you need your users to be asked for a new one time passwords from time to time.
     */

    'lifetime' => 43200, // 0 = eternal

    /*
     * Renew lifetime at every new request.
     */

    'keep_alive' => false,

    /*
     * Auth container binding
     */

    'auth' => 'auth',

    /*
     * 2FA verified session var
     */

    'session_var' => 'multifactor_status',

    /*
     * One Time Password request input name
     */
    'otp_input' => 'multifactor_code',

    /*
     * One Time Password Window
     */
    'window' => 1,

    /*
     * Forbid user to reuse One Time Passwords.
     */
    'forbid_old_passwords' => true,

    /*
     * User's table column for google2fa secret
     */
    'otp_secret_column' => 'twofa_secret',

    /*
     * One Time Password View
     */
    'view' => 'pages.multifactor',

    /*
     * One Time Password error message
     */
    'error_messages' => [
        'wrong_otp' => "The 'One Time Password' typed was wrong.",
    ],

    /*
     * Throw exceptions or just fire events?
     */
    'throw_exceptions' => true,

];
