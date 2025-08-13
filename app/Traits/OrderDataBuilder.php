<?php

namespace App\Traits;

trait OrderDataBuilder
{
  /**
   * @return array
   */
  public function buildData()
  {
    $orderDetails = $this->orderDetails();
    $output = [
      'order'          => $this,
      'company'        => $this->company,
      'orderMaterials' => $this->orderMaterials,
      'orderServices'  => $this->orderServices,
      'status'         => $this->status,
      'details'        => $orderDetails,
      'products'       => $this->getProducts(),
      'address'        => $this->projectAddress(),
    ];
    if ($this->status->name !== 'Quote') {
      $output['payment_due'] = $orderDetails['Due on:'];
    }

    return $output;
  }
}
