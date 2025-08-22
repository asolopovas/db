<?php

namespace App\Http\Controllers;

use App\Helpers\StatsHelper;
use App\Models\OrderMaterial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class OrderMaterialsController extends CrudController
{
    protected string $model = OrderMaterial::class;
    protected string $resource = 'order_materials';
    protected array $messages = [];

    /**
     *  Exports Materials Statistics
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportMaterialsData()
    {
        $generator = (new StatsHelper())->materialsData();

        return response()->streamDownload(
            function () use ($generator) {
                $generator->save('php://output');
            },
            'materials-data.xlsx'
        );
    }

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "order_id"    => "sometimes|required|numeric",
            "material_id" => "sometimes|required|numeric",
            "quantity"    => "sometimes|required|numeric",
            "unit_price"  => "sometimes|required|numeric",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "order_id"    => "required|numeric",
            "material_id" => "required|numeric",
            "quantity"    => "required|numeric",
            "unit_price"  => "required|numeric",
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Model                            $material
     * @param Request|\Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Model $material): JsonResponse
    {
        if (!$material instanceof OrderMaterial) {
            throw new InvalidArgumentException();
        }
        $this->validate(request(), $this->updateValidationRules(), $this->messages);

        $material->update(request()->all());

        return response()->json([
            "type" => "Success",
            "message" => "$this->name Successfully updated.",
            'item' => $material->load('material'),
        ]);
    }

    /**
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(): JsonResponse
    {
        try {
            $this->validate(request(), $this->storeValidationRules(), $this->messages);
        } catch (\Throwable $th) {
            throw $th;
        }

        $data = request()->all();

        if (isset($data['material_id'])) {
            $materialModel = \App\Models\Material::find($data['material_id']);
            if ($materialModel) {
                $data['name'] = $materialModel->name;
                if (!isset($data['unit_price'])) {
                    $data['unit_price'] = $materialModel->price;
                }
            }
        }

        $material = OrderMaterial::create($data);
        $item = OrderMaterial::find($material->id);

        return response()->json([
            "type" => "success",
            "message" => "$this->name Successfully Created",
            "item" => $item->load('material'),
        ]);
    }
}
