<?php

namespace App\Observers;

class UpdateOrderSums
{
  public function created($model)
  {
    $model->order->updateOrderSums();
  }

  public function deleted($model)
  {
    $model->order->updateOrderSums();
  }

  public function updated($model)
  {
    $model->order->updateOrderSums();
  }
}
