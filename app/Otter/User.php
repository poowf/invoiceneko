<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class User extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\User';

    /**
     * The column of the model to display in select options.
     *
     * @var string
     */
    public static $title = 'full_name';

    /**
     * Get the fields and types used by the resource.
     *
     * @return array
     */
    public static function fields()
    {
        return [
            'full_name' => 'text',
            'username'  => 'text',
            'password'  => 'password',
            'email'     => 'email',
            'phone'     => 'text',
            'gender'    => 'text',
        ];
    }

    /**
     * Get the validation rules used by the resource.
     *
     * @return array
     */
    public static function validations()
    {
        return [
            'client' => [
                'create' => [
                    'full_name' => 'required|min:4',
                    'username'  => 'required|min:4',
                    'password'  => 'required',
                    'email'     => 'required|email',
                    'phone'     => 'required',
                    'gender'    => 'required|included:male,female',
                ],
                'update' => [
                    'full_name' => 'required|min:4',
                    'username'  => 'required|min:4',
                    'password'  => '',
                    'email'     => 'required|email',
                    'phone'     => 'required',
                    'gender'    => 'required|included:male,female',
                ],
            ],
            'server' => [
                'create' => [
                    'full_name' => 'required|min:4',
                    'username'  => 'required|min:4|unique:users',
                    'password'  => 'required',
                    'email'     => 'required|email|unique:users',
                    'phone'     => 'required|unique:users',
                    'gender'    => 'required|in:male,female',
                ],
                'update' => [
                    'full_name' => 'required|string|min:4',
                    'username'  => 'required|string|min:4|unique:users,username,' . auth()->user()->id,
                    'email'     => 'required|email|unique:users,email,' . auth()->user()->id,
                    'phone'     => 'required|unique:users,phone,' . auth()->user()->id,
                    'gender'    => 'required|in:male,female',
                ],
            ],
        ];
    }

    /**
     * Fields to be hidden in the resource collection.
     *
     * @return array
     */
    public static function hidden()
    {
        return [
            'password',
        ];
    }

    /**
     * Get the relations used by the resource.
     *
     * @return array
     */
    public static function relations()
    {
        return [
            'companies' => 'Company',
        ];
    }
}
