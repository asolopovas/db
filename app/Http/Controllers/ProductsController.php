<?php

namespace App\Http\Controllers;

use App\Helpers\StatsHelper;
use App\Models\Product;

class ProductsController extends CrudController
{
    protected string $model = Product::class;
    protected string $resource = "products";
    protected array $messages = [];
    protected array $relations = ['extra', 'floor', 'grade', 'dimension', 'areas'];

    /**
     * @return mixed
     */
    public function data()
    {
        return Product::data()->get();
    }

    /**
     *  Exports Products Statistics
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportProductsData()
    {
        $generator = (new StatsHelper())->productsData();

        return response()->streamDownload(
            function() use ($generator) {
                $generator->save('php://output');
            },
            'products-data.xlsx'
        );
    }

    /**
     * @param $id
     *
     * @return Product|Product[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function updateMeterage($id)
    {
        $product = Product::findOrFail($id);
        $product->meterageUpdate();

        return $product;
    }

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
        return [];
    }
}
