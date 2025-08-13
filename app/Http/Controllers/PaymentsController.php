<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class PaymentsController extends CrudController
{
    protected string $model = Payment::class;
    protected string $resource = "payments";
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "description" => "sometimes|required",
            "amount"      => "sometimes|required|numeric",
            "date"        => "sometimes|required",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "amount" => "required|numeric",
            "date"   => "required",
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Model           $model
     * @param Request|Request $request
     *
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Model $model)
    {
        if (!$model instanceof Payment) {
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

        $payment = Payment::create(request()->all());
        $item = Payment::find($payment->id);

        return [
            "type" => "success",
            "message" => "$this->name Successfully Created",
            "item" => $item,
        ];
    }
}
