<?php

namespace App\Helpers;

use App\Generators\ExcelGenerator;
use App\Models\Order;
use App\Models\OrderMaterial;
use App\Models\OrderService;
use App\Models\Product;
use App\Transformers\OrderTransformer;
use Illuminate\Support\Carbon;

class StatsHelper
{
  public $lastMonth;
  public $transformer;
  public $excelGenerator;

  public function __construct($lastMonth = false)
  {
    $this->lastMonth = $lastMonth;
    $this->transformer = new OrderTransformer();
    $this->excelGenerator = new ExcelGenerator();
  }

  public function servicesData()
  {
    $query = $this->datesArray(OrderService::data());
    $data = $this->transformer->materialsServicesData($query->get());

    return $this->excelGenerator->getMaterialsSheet($data);
  }

  public function materialsData()
  {
    $query = $this->datesArray(OrderMaterial::data());
    $data = $this->transformer->materialsServicesData($query->get());

    return $this->excelGenerator->getMaterialsSheet($data);
  }

  public function commissionsData()
  {

    $query = $this->datesArray(Order::data());
    $data = $this->transformer->commissionsData($query->get());

    return $this->excelGenerator->genCommissionsSheet($data);
  }

  public function productsData()
  {
    $query = $this->datesArray(Product::data());
    $data = $this->transformer->productsData($query->get());

    return $this->excelGenerator->getProductsSheet($data);
  }

  /**
   * @param $query
   *
   * @return mixed
   */
  public function datesArray($query)
  {
    if ($this->lastMonth) {
      $start = new Carbon('first day of last month');
      $end = new Carbon('last day of last month');
      $dates = [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 00:00:00')];
      $query->whereBetween('orders.payment_date', $dates);
    }

    return $query;
  }

}
