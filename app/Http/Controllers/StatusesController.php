<?php

namespace App\Http\Controllers;

use App\Models\Status;

class StatusesController extends CrudController
{
    protected string $model = Status::class;
    protected string $resource = 'statuses';
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            'name' => 'sometimes|required|min:3|max:255|unique:statuses,name,except,id',
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            'name' => 'required|min:3|max:255|unique:statuses,name,except,id',
        ];
    }
}
