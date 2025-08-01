<?php

namespace App\Observers;

class OrderLastPaymentDateUpdate
{
  public function created($model)
  {
    $model->order->updatePaymentDate();
  }

  public function deleted($model)
  {
    $model->order->updatePaymentDate();
  }

  public function updated($model)
  {
    $model->order->updatePaymentDate();
  }
}
