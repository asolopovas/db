<?php

namespace App\Http\Controllers;

use App\Models\Grade;

class GradesController extends CrudController
{
    protected string $model = Grade::class;
    protected string $resource = 'grades';
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            'name'  => 'sometimes|required|alpha_num|unique:grades,name,except,id',
            'price' => 'sometimes|required|numeric',
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            'name'  => 'required|alpha_num|unique:grades,name,except,id',
            'price' => 'required|numeric',
        ];
    }
}
