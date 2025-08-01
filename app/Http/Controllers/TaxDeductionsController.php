<?php

namespace App\Http\Controllers;

use App\Models\TaxDeduction;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class TaxDeductionsController extends CrudController
{
    protected string $model = TaxDeduction::class;
    protected string $resource = "tax_deductions";
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "date"   => "sometimes|required",
            "ref"    => "sometimes|required",
            "amount" => "sometimes|required|numeric",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "date"   => "required",
            "ref"    => "sometimes|required",
            "amount" => "required|numeric",
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
        if (!$model instanceof TaxDeduction) {
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

        $tax_deduction = TaxDeduction::create(request()->all());

        return [
            "type" => "success",
            "message" => "$this->name Successfully Created",
            "item" => $tax_deduction,
        ];
    }
}
