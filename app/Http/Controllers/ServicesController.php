<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServicesController extends CrudController
{
    protected string $model = Service::class;
    protected string $resource = "services";
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "name"  => "sometimes|required|min:3|unique:services,name,except,id",
            "price" => "sometimes|required|numeric",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "name"  => "required|min:3|unique:services,name,except,id",
            "price" => "required|numeric",
        ];
    }
}
