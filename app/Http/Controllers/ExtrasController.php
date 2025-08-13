<?php

namespace App\Http\Controllers;

use App\Models\Extra;

class ExtrasController extends CrudController
{
    protected string $model = Extra::class;
    protected string $resource = "extras";
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "name"  => "sometimes|required|unique:extras,name,except,id",
            "price" => "sometimes|required|numeric",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "name"  => "required|unique:extras,name,except,id",
            "price" => "required|numeric",
        ];
    }
}
