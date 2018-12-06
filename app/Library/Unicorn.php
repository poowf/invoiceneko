<?php

namespace App\Library\Poowf;

use App\Models\Role;
use Carbon\Carbon;
use Recurr\Frequency;
use Recurr\Rule;
use Validator;
use Storage;
use Silber\Bouncer\BouncerFacade as Bouncer;

class Unicorn
{
    private static $modelClasses = [
        \App\Models\Invoice::class,
        \App\Models\Receipt::class,
        \App\Models\Quote::class,
        \App\Models\ItemTemplate::class,
        \App\Models\Payment::class,
        \App\Models\Client::class,
        \App\Models\Role::class,
        \App\Models\CompanyAddress::class,
        \App\Models\CompanySetting::class,
        \App\Models\CompanyUserRequest::class,
    ];

    private static $currencySymbols = [
        'AED' => '&#1583;.&#1573;', // ?
        'AFN' => '&#65;&#102;',
        'ALL' => '&#76;&#101;&#107;',
        'AMD' => '&#1380;',
        'ANG' => '&#402;',
        'AOA' => '&#75;&#122;', // ?
        'ARS' => '&#36;',
        'AUD' => '&#36;',
        'AWG' => '&#402;',
        'AZN' => '&#8380;',
        'BAM' => '&#75;&#77;',
        'BBD' => '&#36;',
        'BDT' => '&#2547;', // ?
        'BGN' => '&#1083;&#1074;',
        'BHD' => '.&#1583;.&#1576;', // ?
        'BIF' => '&#70;&#66;&#117;', // ?
        'BMD' => '&#36;',
        'BND' => '&#36;',
        'BOB' => '&#36;&#98;',
        'BRL' => '&#82;&#36;',
        'BSD' => '&#36;',
        'BTN' => '&#78;&#117;&#46;', // ?
        'BWP' => '&#80;',
        'BYR' => '&#112;&#46;',
        'BZD' => '&#66;&#90;&#36;',
        'CAD' => '&#36;',
        'CDF' => '&#70;&#67;',
        'CHF' => '&#67;&#72;&#70;',
        'CLF' => '&#85;&#70;', // ?
        'CLP' => '&#36;',
        'CNY' => '&#165;',
        'COP' => '&#36;',
        'CRC' => '&#8353;',
        'CUP' => '&#8396;',
        'CVE' => '&#36;', // ?
        'CZK' => '&#75;&#269;',
        'DJF' => '&#70;&#100;&#106;', // ?
        'DKK' => '&#107;&#114;',
        'DOP' => '&#82;&#68;&#36;',
        'DZD' => '&#1583;&#1580;', // ?
        'EGP' => 'E&#163;',
        'ETB' => '&#66;&#114;',
        'EUR' => '&#8364;',
        'FJD' => '&#36;',
        'FKP' => '&#163;',
        'GBP' => '&#163;',
        'GEL' => '&#4314;', // ?
        'GHS' => '&#162;',
        'GIP' => '&#163;',
        'GMD' => '&#68;', // ?
        'GNF' => '&#70;&#71;', // ?
        'GTQ' => '&#81;',
        'GYD' => '&#36;',
        'HKD' => '&#36;',
        'HNL' => '&#76;',
        'HRK' => '&#107;&#110;',
        'HTG' => '&#71;', // ?
        'HUF' => '&#70;&#116;',
        'IDR' => '&#82;&#112;',
        'ILS' => '&#8362;',
        'INR' => '&#8377;',
        'IQD' => '&#1593;.&#1583;', // ?
        'IRR' => '&#65020;',
        'ISK' => '&#107;&#114;',
        'JEP' => '&#163;',
        'JMD' => '&#74;&#36;',
        'JOD' => '&#74;&#68;', // ?
        'JPY' => '&#165;',
        'KES' => '&#75;&#83;&#104;', // ?
        'KGS' => '&#1083;&#1074;',
        'KHR' => '&#6107;',
        'KMF' => '&#67;&#70;', // ?
        'KPW' => '&#8361;',
        'KRW' => '&#8361;',
        'KWD' => '&#1583;.&#1603;', // ?
        'KYD' => '&#36;',
        'KZT' => '&#8376;',
        'LAK' => '&#8365;',
        'LBP' => '&#163;',
        'LKR' => '&#8360;',
        'LRD' => '&#36;',
        'LSL' => '&#76;', // ?
        'LTL' => '&#76;&#116;',
        'LVL' => '&#76;&#115;',
        'LYD' => '&#1604;.&#1583;', // ?
        'MAD' => '&#1583;.&#1605;.', //?
        'MDL' => '&#76;',
        'MGA' => '&#65;&#114;', // ?
        'MKD' => '&#1076;&#1077;&#1085;',
        'MMK' => '&#75;',
        'MNT' => '&#8366;',
        'MOP' => '&#77;&#79;&#80;&#36;', // ?
        'MRO' => '&#85;&#77;', // ?
        'MUR' => '&#8360;', // ?
        'MVR' => '.&#1923;', // ?
        'MWK' => '&#77;&#75;',
        'MXN' => '&#36;',
        'MYR' => '&#82;&#77;',
        'MZN' => '&#77;&#84;',
        'NAD' => '&#36;',
        'NGN' => '&#8358;',
        'NIO' => '&#67;&#36;',
        'NOK' => '&#107;&#114;',
        'NPR' => '&#8360;',
        'NZD' => '&#36;',
        'OMR' => '&#65020;',
        'PAB' => '&#66;&#47;&#46;',
        'PEN' => '&#83;&#47;&#46;',
        'PGK' => '&#75;', // ?
        'PHP' => '&#8369;',
        'PKR' => '&#8360;',
        'PLN' => '&#122;&#322;',
        'PYG' => '&#71;&#115;',
        'QAR' => '&#65020;',
        'RON' => '&#108;&#101;&#105;',
        'RSD' => '&#1044;&#1080;&#1085;&#46;',
        'RUB' => '&#8381;',
        'RWF' => '&#1585;.&#1587;',
        'SAR' => '&#65020;',
        'SBD' => '&#36;',
        'SCR' => '&#8360;',
        'SDG' => '&#163;', // ?
        'SEK' => '&#107;&#114;',
        'SGD' => '&#36;',
        'SHP' => '&#163;',
        'SLL' => '&#76;&#101;', // ?
        'SOS' => '&#83;',
        'SRD' => '&#36;',
        'STD' => '&#68;&#98;', // ?
        'SVC' => '&#36;',
        'SYP' => '&#163;',
        'SZL' => '&#76;', // ?
        'THB' => '&#3647;',
        'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
        'TMT' => '&#109;',
        'TND' => '&#1583;.&#1578;',
        'TOP' => '&#84;&#36;',
        'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
        'TTD' => '&#36;',
        'TWD' => '&#78;&#84;&#36;',
        'TZS' => '&#84;&#83;&#104;',
        'UAH' => '&#8372;',
        'UGX' => '&#85;&#83;&#104;',
        'USD' => '&#36;',
        'UYU' => '&#36;&#85;',
        'UZS' => '&#1083;&#1074;',
        'VEF' => '&#66;&#115;',
        'VND' => '&#8363;',
        'VUV' => '&#86;&#84;',
        'WST' => '&#87;&#83;&#36;',
        'XAF' => '&#70;&#67;&#70;&#65;',
        'XCD' => '&#36;',
        'XDR' => '&#83;&#68;&#82;',
        'XOF' => '&#70;&#67;&#70;&#65;',
        'XPF' => '&#70;',
        'YER' => '&#65020;',
        'ZAR' => '&#82;',
        'ZMK' => '&#90;&#75;', // ?
        'ZWL' => '&#90;&#36;',
    ];

    public function  __construct()
    {
    }

    /**
     * @param $data
     * @return bool
     */
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

    /**
     * @param $path
     * @param array $imagesize
     * @return string
     */
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

    /**
     * @param $startDate
     * @param $timezone
     * @param $interval
     * @param $frequency
     * @param $until_type
     * @param $until_meta
     * @param bool $object
     * @return Rule|string
     * @throws \Recurr\Exception\InvalidArgument
     * @throws \Recurr\Exception\InvalidRRule
     */
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

    /**
     * @param null $scopeId
     */
    public static function createRoleAndPermissions($scopeId = null)
    {
        Bouncer::scope()->to($scopeId);
        Bouncer::useRoleModel(Role::class);

        $gadmin = Bouncer::role()->firstOrCreate([
            'name' => str_slug('Global Administrator'),
            'title' => 'Global Administrator',
        ]);

        $admin = Bouncer::role()->firstOrCreate([
            'name' => str_slug('Administrator'),
            'title' => 'Administrator',
        ]);

        $user = Bouncer::role()->firstOrCreate([
            'name' => str_slug('User'),
            'title' => 'User',
        ]);

        Bouncer::allow('global-administrator')->everything();
        self::createPermissions($scopeId);
        self::assignCrudPermissions($scopeId, $user, 'view');
    }

    /**
     * @param null $scopeId
     */
    public static function createPermissions($scopeId = null)
    {
        foreach(self::$modelClasses as $key => $model)
        {
            self::createCrudPermissions($scopeId, $model);
        }
    }

    /**
     * @param $scopeId
     * @param $model
     */
    protected static function createCrudPermissions($scopeId, $model)
    {
        Bouncer::scope()->to($scopeId);
        Bouncer::useRoleModel(Role::class);

        Bouncer::ability()->makeForModel($model, [
            'name' => 'view-' . str_slug(strtolower(self::getModelNiceName($model))),
            'title' => 'View ' . self::getModelNiceName($model),
        ])->save();

        Bouncer::ability()->makeForModel($model, [
            'name' => 'create-' . str_slug(strtolower(self::getModelNiceName($model))),
            'title' => 'Create ' . self::getModelNiceName($model),
        ])->save();

        Bouncer::ability()->makeForModel($model, [
            'name' => 'update-' . str_slug(strtolower(self::getModelNiceName($model))),
            'title' => 'Update ' . self::getModelNiceName($model),
        ])->save();

        Bouncer::ability()->makeForModel($model, [
            'name' => 'delete-' . str_slug(strtolower(self::getModelNiceName($model))),
            'title' => 'Delete ' . self::getModelNiceName($model),
        ])->save();
    }

    /**
     * @param $scopeId
     * @param $role
     * @param string $methodName
     * @param string $modelClass
     */
    protected static function assignCrudPermissions($scopeId, $role, $methodName = 'all', $modelClass = 'all')
    {
        switch($methodName)
        {
            case 'all':
                self::assignPermissions($scopeId, $role, 'view', $modelClass);
                self::assignPermissions($scopeId, $role, 'create', $modelClass);
                self::assignPermissions($scopeId, $role, 'update', $modelClass);
                self::assignPermissions($scopeId, $role, 'delete', $modelClass);
                break;
            case 'view':
                self::assignPermissions($scopeId, $role, 'view', $modelClass);
                break;
            case 'create':
                self::assignPermissions($scopeId, $role, 'create', $modelClass);
                break;
            case 'update':
                self::assignPermissions($scopeId, $role, 'update', $modelClass);
                break;
            case 'delete':
                self::assignPermissions($scopeId, $role, 'delete', $modelClass);
                break;
        }
    }

    /**
     * @param $scopeId
     * @param $role
     * @param $methodName
     * @param $modelClass
     */
    protected static function assignPermissions($scopeId, $role, $methodName, $modelClass)
    {
        Bouncer::scope()->to($scopeId);
        Bouncer::useRoleModel(Role::class);

        if($modelClass === 'all')
        {
            foreach(self::$modelClasses as $key => $model)
            {
                Bouncer::allow($role)->to($methodName . '-' . str_slug(strtolower(self::getModelNiceName($model))), $model);
            }
        }
        else
        {
            Bouncer::allow($role)->to($methodName . '-' . str_slug(strtolower(self::getModelNiceName($modelClass))), $modelClass);
        }
    }

    /**
     * @param $modelClass
     * @return string
     */
    protected static function getModelNiceName($modelClass)
    {
        $transformed = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', str_replace('::class', '', str_replace('App\\Models\\', '', $modelClass))));
        return $transformed;
    }

    /**
     * @return mixed|null
     */
    public static function getCompanyKey()
    {
        if(auth()->check())
        {
            if(session()->has('current_company_fqdn'))
            {
                return session()->get('current_company_fqdn');
            }
            else
            {
                $user = auth()->user();
                return $user->getFirstCompanyKey();
            }
        }
        else
        {
            return null;
        }
    }

    public static function redirectTo()
    {
        $routeKey = self::getCompanyKey();
        $url = '/';

        if($routeKey)
        {
            $url = route('dashboard', [ 'company' => $routeKey ]);
        }

        return $url;
    }

    public static function currencies()
    {
        return self::$currencySymbols;
    }

    public static function currency($iso_3166_1_alpha2)
    {
        $country = country($iso_3166_1_alpha2);
        $currency = $country->getCurrency()['iso_4217_code'];

        if (array_key_exists($currency, self::$currencySymbols))
        {
            return self::$currencySymbols[$currency];
        }
    }
}