<?php

namespace App\Http\Controllers;

use App\Models\Floor;

class FloorsController extends CrudController
{
    protected string $model = Floor::class;
    protected string $resource = 'Floors';
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            'name'  => 'sometimes|required|min:3|max:255|unique:floors,name,except,id',
            'price' => 'sometimes|required|numeric|min:3',

        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            'name'  => 'required|min:3|max:255|unique:floors,name,except,id',
            'price' => 'required|numeric|min:3',
        ];
    }
}

