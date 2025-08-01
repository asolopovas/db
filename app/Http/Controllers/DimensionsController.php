<?php

namespace App\Http\Controllers;

use App\Models\Dimension;

class DimensionsController extends CrudController
{
    protected string $model = Dimension::class;
    protected string $resource = "dimensions";
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "width"     => "sometimes|required|alpha_num|min:2",
            "thickness" => "sometimes|required|numeric|min:2",
            "price"     => "sometimes|required|numeric",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "width"     => "required|alpha_num|min:2",
            "thickness" => "required|numeric|min:2",
            "price"     => "required|numeric",
        ];
    }

}
