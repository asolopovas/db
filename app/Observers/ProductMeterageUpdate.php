<?php

namespace App\Observers;

use App\Models\Product;

class ProductMeterageUpdate
{
    public function created($model)
    {
        $model->product->meterageUpdate($model);
    }

    public function deleted($model)
    {
        $model->product->meterageUpdate($model);
    }

    public function updated($model)
    {
        $model->product->meterageUpdate($model);

        if ($model->product->id !== $model->getOriginal('product_id')) {
            $oldProduct = Product::findorfail($model->getOriginal('product_id'));
            $oldProduct->meterageUpdate();
        }
    }
}
