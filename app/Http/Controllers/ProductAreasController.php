<?php

namespace App\Http\Controllers;

use App\Models\ProductArea;
use App\Models\Area;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;


class ProductAreasController extends CrudController
{
    protected string $model = ProductArea::class;
    protected string $resource = 'product_areas';
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            'product_id' => 'required|integer',
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Model                            $installation
     * @param Request|\Illuminate\Http\Request $request
     *
     * @return array
     */
    public function update(Model $installation)
    {
        if (!$installation instanceof ProductArea) {
            throw new InvalidArgumentException();
        }
        $this->validate(request(), $this->updateValidationRules(), $this->messages);
        $installation->update(request()->all());

        return [
            'type' => 'Success',
            'message' => "$this->name Successfully updated.",
            'item' => $installation->load('area'),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        try {
            $data = request()->all();

            if (isset($data['area_id'])) {
                $areaModel = Area::find($data['area_id']);
                if ($areaModel) {
                    $data['name'] = $areaModel->name;
                    if (!isset($data['price'])) {
                        $data['price'] = $areaModel->price;
                    }
                }
            }

            $installation = ProductArea::create($data);
        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                return response([
                    'error'   => 'Unprocessable Entity',
                    'message' => 'Form validation error(s) have occurred please see below.',
                    'errors'  => ['area_id' => ['Area duplication is not allowed within a single product']],
                ], 422);
            }
        }

        $item = ProductArea::find($installation->id)
            ->load('area');

        return response([
            'type' => 'success',
            'message' => 'Product Area Successfully Created',
            'item' => $item,
        ]);
    }
}
