<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class ExpensesController extends CrudController
{
    protected string $model = Expenses::class;
    protected string $resource = "expenses";
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "category" => "sometimes|required",
            "details"  => "sometimes|required",
            "amount"   => "sometimes|required|numeric",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "category" => "sometimes|required",
            "details"  => "sometimes|required",
            "amount"   => "required|numeric",
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Model                            $model
     * @param Request|\Illuminate\Http\Request $request
     *
     * @return array
     */
    public function update(Model $model)
    {
        if (!$model instanceof Expenses) {
            throw new InvalidArgumentException();
        }
        $this->validate(request(), $this->updateValidationRules(), $this->messages);

        $model->update(request()->all());

        return [
            "type" => "Success",
            "message" => "$this->name Successfully updated.",
            'item' => $model,
        ];
    }

    /**
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate(request(), $this->storeValidationRules(), $this->messages);

        $expenses = Expenses::create(request()->all());

        return [
            "type" => "success",
            "message" => ucfirst($this->name)." Successfully Created",
            "item" => $expenses,
        ];
    }
}
