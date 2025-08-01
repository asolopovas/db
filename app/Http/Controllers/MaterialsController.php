<?php

namespace App\Http\Controllers;

use App\Models\Material;

class MaterialsController extends CrudController
{
    protected string $model = Material::class;
    protected string $resource = "materials";
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "name"             => "sometimes|required|min:3|unique:materials,name,except,id",
            "price"            => "sometimes|required|numeric",
            "measurement_unit" => "sometimes|required",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "name"  => "required|min:3|unique:materials,name,except,id",
            "price" => "required|numeric",
        ];
    }
}
