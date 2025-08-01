<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

trait OrderAttributes
{

    /**
     * Calculates Total Price of collection
     *
     * @param Collection $collection
     *
     * @return integer
     */
    public function totals(Collection $collection)
    {
        return $collection->sum(function ($item) {
            if ($item->price) {
                return $item->price;
            }

            return $item->quantity * $item->unit_price;
        });
    }

    /**
     * Price attribute accessor
     * @return int
     */
    public function getSumAttribute()
    {
        $price = 0;
        $price += $this->products->sum(function ($item) {
            return $item->discountedPrice;
        });
        $price += $this->totals($this->orderServices);
        $price += $this->totals($this->orderMaterials);

        return round($price, 2);
    }

    /**
     * Price attribute accessor
     * @return float|int
     */
    public function getVatSumAttribute()
    {
        $result = ($this->total - $this->discount) * $this->vat / 100;

        return round($result, 2);
    }

    /**
     *  Equals to grand_total orders table column
     * @return float|int
     */
    public function getOrderSumAttribute()
    {
        return round($this->total - $this->discount + $this->vatSum, 2);
    }

    /**
     * Paid attribute accessor
     * @return mixed
     */
    public function getPaidAttribute()
    {
        return round($this->payments()->sum('amount'), 2);
    }

    /**
     * Due Now attribute accessor
     * @return mixed
     */
    public function getDueNowAttribute()
    {
        $result = $this->proforma
            ? $this->grand_total * ($this->due / 100)
            : $this->balance * ($this->due / 100);
        return round($result, 2);
    }

    /**
     * @return bool
     */
    public function getOverdueAttribute()
    {
        return ($this->status_name === 'Invoice' && $this->balance > 0 && $this->overdueBy > 0);
    }

    public function getAddressAttribute()
    {

        if ($this->project) {
            return "{$this->project->address_line_1}, {$this->project->postcode}";
        }
        $street = $this->customer->address_line_1;
        $postCode = $this->customer->postcode;
        if (!$street || !$postCode) {
            return "N/A";
        }

        return "{$street}, {$postCode}";
    }

    /**
     * @return int
     */
    public function getOverdueByAttribute()
    {
        $due = new Carbon($this->date_due);
        $diff = $due->diffInDays(Carbon::now(), false);
        if ($diff < 0) {
            return 0;
        }

        if ($this->status_name !== 'Invoice') {
            return 0;
        }

        if ($this->balance == 0) {
            return 0;
        }

        return $diff;
    }
}
