<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;

class CustomersController extends CrudController
{
    protected string $model = Customer::class;
    protected string $resource = "customers";
    protected array $messages = [];


    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "email1" => "nullable|email",
            "email2" => "nullable|email",
            "email3" => "nullable|email",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "title"     => "required",
            "firstname" => "required",
            "email1" => "nullable|email",
            "email2" => "nullable|email",
            "email3" => "nullable|email",
        ];
    }
}
