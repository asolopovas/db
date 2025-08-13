<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Expenses;
use App\Models\OrderMaterial;
use App\Models\OrderService;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Project;
use App\Models\Status;
use App\Models\TaxDeduction;
use App\Models\User;

trait OrderRelations
{
  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  public function addCustomer($id)
  {
    return $this->customer()
                ->associate($id);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function status()
  {
    return $this->belongsTo(Status::class);
  }

  /**
   * @param Status $status
   *
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function addStatus(Status $status)
  {
    return $this->status()
                ->associate($status);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function products()
  {
    return $this->hasMany(Product::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function orderServices()
  {
    return $this->hasMany(OrderService::class);
  }

  /**
   * @param $orderService
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function addOrderService($orderService)
  {
    return $this->orderServices()
                ->associate($orderService);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function orderMaterials()
  {
    return $this->hasMany(OrderMaterial::class);
  }

  /**
   * @return mixed
   */
  public function taxDeductions()
  {
    return $this->hasMany(TaxDeduction::class);
  }

  /**
   * @return mixed
   */
  public function expenses()
  {
    return $this->hasMany(Expenses::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function payments()
  {
    return $this->hasMany(Payment::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function project()
  {
    return $this->belongsTo(Project::class);
  }
}
