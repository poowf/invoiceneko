<?php

namespace App\Library\Poowf;

use Log;
use Validator;
use Storage;

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

    public static function getStorageFile($path, $imagesize = [500,500])
    {
        $filepath = "//via.placeholder.com/{$imagesize[0]}x{$imagesize[1]}";
        if($path)
        {
            try {
                $filepath = Storage::url($path);
            } catch(\Exception $exception) {

            }
        }

        return $filepath;
    }
}