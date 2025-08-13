<?php

namespace App\Traits;

use Carbon\Carbon;

trait OrderScopes
{


    public function scopeWithServices($query)
    {
        $query->with(
            [
                'orderServices' => function ($query) {
                    $query->join('services', 'order_services.service_id', '=', 'services.id')
                        ->addSelect(
                            [
                                'order_services.*',
                                'services.name',
                                'services.measurement_unit',
                            ]
                        );
                },
            ]
        );
    }

    /**
     * @param $query
     */
    public function scopeWithProducts($query)
    {
        $query->with(
            [
                'products'       => function ($query) {
                    $query->withMeta();
                },
                'products.areas' => function ($query) {
                    $query->join('areas', 'product_areas.area_id', '=', 'areas.id');
                },
            ]
        );
    }

    /**
     * @param $query
     */
    public function scopeWithMaterials($query)
    {
        $query->with(
            [
                'orderMaterials' => function ($query) {
                    $query->join('materials', 'order_materials.material_id', '=', 'materials.id')
                        ->addSelect(
                            [
                                'order_materials.*',
                                'materials.name',
                                'materials.measurement_unit',
                            ]
                        );
                },
            ]
        );
    }

    /**
     * @param       $query
     * @param array $filters
     *
     * @return mixed
     */
    public function scopeFilters($query, array $filters)
    {
        if (empty($filters)) {
            return $query;
        }

        foreach ($filters as $filter => $value) {
            $query = call_user_func_array([$query, $filter], [$value]);
        }

        return $query;
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeJoinStatusName($query)
    {
        return $query->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->select(
                [
                    'orders.*',
                    'statuses.name as status_name',
                ]
            );
    }

    /**
     * @param     $query
     * @param int $id
     *
     * @return mixed
     */
    public function scopeCustomer($query, int $id)
    {
        return $query->where('customer_id', $id);
    }

    /**
     * @param $query
     * @param $status
     *
     * @return mixed
     */
    public function scopeOrderStatus($query, $status)
    {
        if (is_array($status)) {
            $mapStatuses = array_map(
                function ($status) {
                    return "statuses.name = '$status'";
                },
                $status
            );

            return $query->whereRaw("(" . implode(' or ', $mapStatuses) . ")");
        }

        return $query->where('statuses.name', ucfirst($status));
    }

    /**
     * @param $query
     * @param $vat
     */
    public function scopeVat($query, $vat)
    {
        $query->where('vat', '=', $vat);
    }

    /**
     * @param $query
     * @param $status
     *
     * @return mixed
     */
    public function scopeVarious($query, $status)
    {

        $query = $query->orderStatus(['Invoice', 'Proforma Invoice']);

        if ($status === 'paid') {
            return $query->whereRaw('balance >= 0 and balance < grand_total');
        }

        if ($status === 'grand_design') {
            return $query->where('grand_design', true);
        }

        if ($status === 'in_progress') {
            return $query->whereRaw('balance > 0 and balance < grand_total');
        }

        if ($status === 'unpaid') {
            return $query->whereRaw('balance = grand_total');
        }

        $rawQuery = 'balance <= 0 and balance < grand_total';
        if ($status === 'tax_deductions') {
            return $query->whereHas('taxDeductions');
        }

        return $query->whereRaw($rawQuery);
    }

    /**
     * @param      $query
     *
     * @param bool $last_month
     *
     * @return mixed
     */
    public function scopeTaxDeducted($query, $last_month = false)
    {

        $query->joinStatusName()
            ->filters(
                ['various' => 'tax_deductions']
            );

        if ($last_month) {
            $start = new Carbon('first day of last month');
            $end = new Carbon('last day of last month');
            $dates = [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 00:00:00')];
            $query->whereBetween('orders.updated_at', $dates);
        }

        return $query;
    }

    public function scopeWithMeta($query, $id)
    {
        $relations = [
            'customer',
            'orderMaterials',
            'orderServices',
            'payments',
            'expenses',
        ];
        $orders = $query->with($relations)->withProducts()
            ->JoinStatusName()
            ->select(
                'orders.*',
                'statuses.name as status_name'
            )
            ->where('orders.id', $id);
        return $orders;
    }

    public function scopeData($query)
    {
        $query->with(['customer', 'orderMaterials', 'orderServices', 'payments',])
            ->withProducts()
            ->JoinStatusName()
            ->Various('paid')
            ->select('orders.*', 'statuses.name as status_name')
            ->orderBy('payment_date', 'desc');
    }
}
