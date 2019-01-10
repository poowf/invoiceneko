<?php

namespace App\Library\Poowf;

use App\Models\Role;
use Carbon\Carbon;
use Recurr\Frequency;
use Recurr\Rule;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Storage;
use Validator;

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

    public function __construct()
    {
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public static function validateQueryString($data)
    {
        $dataFormat = [
            'value' => $data,
        ];

        $validator = Validator::make($dataFormat, [
           'value' => 'regex:([A-Za-z0-9,-]+)',
        ]);

        if ($validator->fails()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $path
     * @param array $imagesize
     *
     * @return string
     */
    public static function getStorageFile($path, $imagesize = [500, 500])
    {
        $filepath = "//via.placeholder.com/{$imagesize[0]}x{$imagesize[1]}";
        if ($path) {
            try {
                $filepath = Storage::url($path);
            } catch (\Exception $exception) {
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
     *
     * @throws \Recurr\Exception\InvalidArgument
     * @throws \Recurr\Exception\InvalidRRule
     *
     * @return Rule|string
     */
    public static function generateRrule($startDate, $timezone, $interval, $frequency, $until_type, $until_meta, $object = false)
    {
        $rule = (new Rule())
            ->setStartDate($startDate)
            ->setTimezone($timezone)
            ->setInterval($interval);

        switch ($until_type) {
            case 'occurence':
                $rule->setCount($until_meta);
                break;
            case 'date':
                $untilDate = Carbon::createFromFormat('Y-m-d H:i:s', $until_meta);
                $rule->setUntil($untilDate);
                break;
        }

        switch ($frequency) {
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

        if ($object) {
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
            'name'  => str_slug('Global Administrator'),
            'title' => 'Global Administrator',
        ]);

        $admin = Bouncer::role()->firstOrCreate([
            'name'  => str_slug('Administrator'),
            'title' => 'Administrator',
        ]);

        $user = Bouncer::role()->firstOrCreate([
            'name'  => str_slug('User'),
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
        foreach (self::$modelClasses as $key => $model) {
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
            'name'  => 'view-'.str_slug(strtolower(self::getModelNiceName($model))),
            'title' => 'View '.self::getModelNiceName($model),
        ])->save();

        Bouncer::ability()->makeForModel($model, [
            'name'  => 'create-'.str_slug(strtolower(self::getModelNiceName($model))),
            'title' => 'Create '.self::getModelNiceName($model),
        ])->save();

        Bouncer::ability()->makeForModel($model, [
            'name'  => 'update-'.str_slug(strtolower(self::getModelNiceName($model))),
            'title' => 'Update '.self::getModelNiceName($model),
        ])->save();

        Bouncer::ability()->makeForModel($model, [
            'name'  => 'delete-'.str_slug(strtolower(self::getModelNiceName($model))),
            'title' => 'Delete '.self::getModelNiceName($model),
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
        switch ($methodName) {
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

        if ($modelClass === 'all') {
            foreach (self::$modelClasses as $key => $model) {
                Bouncer::allow($role)->to($methodName.'-'.str_slug(strtolower(self::getModelNiceName($model))), $model);
            }
        } else {
            Bouncer::allow($role)->to($methodName.'-'.str_slug(strtolower(self::getModelNiceName($modelClass))), $modelClass);
        }
    }

    /**
     * @param $modelClass
     *
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
        if (auth()->check()) {
            if (session()->has('current_company_fqdn')) {
                return session()->get('current_company_fqdn');
            } else {
                $user = auth()->user();

                return $user->getFirstCompanyKey();
            }
        } else {
            return;
        }
    }

    public static function redirectTo()
    {
        $routeKey = self::getCompanyKey();
        $url = '/';

        if ($routeKey) {
            $url = route('dashboard', ['company' => $routeKey]);
        }

        return $url;
    }
}
