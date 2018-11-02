<?php

namespace App\Library\Poowf;

use Carbon\Carbon;
use Log;
use Recurr\Frequency;
use Recurr\Rule;
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

    public static function generateRrule($startDate, $timezone, $interval, $frequency, $until_type, $until_meta, $object = false)
    {
        $rule = (new Rule)
            ->setStartDate($startDate)
            ->setTimezone($timezone)
            ->setInterval($interval);

        switch($until_type)
        {
            case 'occurence':
                $rule->setCount($until_meta);
                break;
            case 'date':
                $untilDate = Carbon::createFromFormat('Y-m-d H:i:s', $until_meta);
                $rule->setUntil($untilDate);
                break;
        }

        switch($frequency)
        {
            case 'day':
                $frequency = Frequency::DAILY;
                break;
            case 'week':
                $frequency = Frequency::WEEKLY;
                break;
            case 'month':
                $frequency = Frequency::MONTHLY;
                break;
            case 'year':
                $frequency = Frequency::YEARLY;
                break;
        }

        $rule->setFreq($frequency);

        if($object)
        {
            return $rule;
        }

        return $rule->getString();
    }
}