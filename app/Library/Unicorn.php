<?php

namespace App\Library\Poowf;

use Log;
use Validator;

class Unicorn
{
    public static function validateQueryString($data)
    {
        $dataFormat = [
            'value' => $data
        ];

        $validator = Validator::make($dataFormat, [
           'value' => 'regex:([A-Za-z0-9,-]+)'
        ]);

        if ($validator->fails())
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function ifExists($model, $key)
    {
        if($model)
        {
            return $model->{$key};
        }
        else
        {
            return null;
        }
    }

}