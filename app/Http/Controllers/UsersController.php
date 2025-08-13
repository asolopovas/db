<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends CrudController
{
    protected string $model = User::class;
    protected string $resource = 'Users';
    protected array $messages = [];
    protected array $relations = ['role'];
    protected bool $paginated = false;

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "name"     => "sometimes|required|min:5",
            "username" => "sometimes|required|min:5",
            "password" => "sometimes|required|min:8",
            "phone"    => "sometimes|required|min:11",
            "mobile"   => "sometimes|required|min:11",
            "role_id"  => "sometimes|required",
            "email"    => "sometimes|required|email|min:3",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "name"     => "required|min:5",
            "username" => "required|min:5",
            "password" => "required|min:8",
            "phone"    => "required|min:11",
            "role_id"  => "required|min:1",
            "mobile"   => "required|min:11",
            "email"    => "required|email",
        ];
    }
}
