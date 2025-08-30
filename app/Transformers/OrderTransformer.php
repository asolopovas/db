<?php

namespace App\Transformers;

use App\Models\Order;
use App\Models\OrderMaterial;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class OrderTransformer
{

    protected array $relations = [
        'customer',
        'status',
        'user',
        'company',
        'payments',
        'project',
        'products',
        'products.floor',
        'products.extra',
        'products.dimension',
        'products.grade',
        'products.areas.area',
        'orderServices.service',
        'orderMaterials.material',
    ];

    /**
     * Maps items and returns them in paginated form
     *
     * @param          $items
     * @param callable $format
     *
     * @return LengthAwarePaginator / Collections
     */
    public function map($items, callable $format)
    {
        $mappedItems = $items->map($format);
        if ($items instanceof LengthAwarePaginator) {
            return $this->paginate($mappedItems, $items);
        }

        return $mappedItems;
    }

    /**
     * Paginates Collection
     *
     * @param                      $items
     * @param LengthAwarePaginator $paginatedItems
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, LengthAwarePaginator $paginatedItems)
    {
        return new LengthAwarePaginator($items, $paginatedItems->total(), $paginatedItems->perPage(), $paginatedItems->currentPage(), [
            'path'  => \Request::url(),
            'query' => [
                'page' => $paginatedItems->currentPage(),
            ],
        ]);
    }

    public function products($order)
    {

        $output = [
            'standalone' => collect([]),
            'withAreas'  => collect([]),
        ];

        foreach ($order->products as $product) {
            if ($product->areas->isEmpty()) {
                $output['standalone']->push($product);
            } else {
                $output['withAreas']->push($product);
            }
        }

        return $output;
    }

    public function address($order)
    {

        $address = ($order->project === null) ? $order->customer : $order->project;
        $output  = array_filter([
            'Street:'    => $address->street,
            'Town:'      => $address->town,
            'County:'    => $address->county,
            'City:'      => $address->city,
            'Post Code:' => $address->postcode,
            'Country:'   => $address->country,
        ]);

        return count($output) === 1 ? [] : $output;
    }

    public function details($order)
    {
        $output = [
            'Salesman:' => $order->user->name,
            'Order Nr:' => sprintf("%04d", $order->id),
            'Company:'  => $order->customer->company,
            'Customer:' => $order->customer->fullname,
            'Date:'     => $order->updated_at->format('d M Y'),
            'VAT:'      => $order->vat . ' %',
        ];
        if ($order->status_name !== 'Quote') {
            $output['Due on:'] = $order->updated_at->addDays(3)->format('d M Y');
        }

        return array_filter($output);
    }

    public function getAddress($item)
    {
        if ($item->pr_street) {
            return "$item->pr_street, $item->pr_postcode";
        }
        $street   = $item->customer->street;
        $postCode = $item->customer->postcode;
        if (!$street || !$postCode) {
            return "N/A";
        }

        return "$street, $postCode";
    }

    public function materialsServicesData($data)
    {

        $titles = [
            'Order Id',
            'Date',
            'Customer',
            'Address',
            'Name',
            'Unit Price',
            'Units',
            'Price',
        ];

        return $data->map(function (OrderMaterial $item) {
            return [
                'order_id'   => $item->order_id,
                'date'       => Carbon::parse($item->order_date)->format('d/m/Y'),
                'customer'   => $item->customer->fullName,
                'address'    => $this->getAddress($item),
                'name'       => $item->name,
                'unit_price' => $item->unit_price,
                'quantity'   => $item->quantity === null ? 0 : $item->quantity,
                'price'      => $item->price,
            ];
        })->prepend($titles);
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function productsData($data)
    {
        $titles = [
            'Order Id',
            'Date',
            'Customer',
            'Address',
            'Name',
            'Dimensions',
            'Grade',
            'Unit Price',
            'Units',
            'Price',
        ];

        return $data->map(function ($item) {
            return [
                'order_id'   => $item->order_id,
                'order_date' => Carbon::parse($item->order_date)->format('d/m/Y'),
                'customer'   => $item->customer->fullName,
                'address'    => $this->getAddress($item),
                'name'       => $item->floor,
                'dimensions' => $item->dimensions,
                'grade'      => $item->grade,
                'unit_price' => $item->discountedUnitPrice,
                'units'      => $item->totalMeterage,
                'price'      => $item->discountedPrice,
            ];
        })->prepend($titles);
    }

    /**
     * Generates Order Stats Data
     *
     * @param $orders
     *
     * @return mixed
     */
    public function commissionsData($orders)
    {
        $stats = $orders->filter(function ($order) {
            return $order->products->count() > 0;
        })->map(function ($order) {
            $supply_only = $order->orderServices->filter(function ($service) {
                $service_item = collect($service->service);

                return $service_item->contains('Chevron installation') || $service_item->contains('Parquet installation') || $service_item->contains('Plank Installation');
            })->count() === 0;
            $meterage = round($order->products->sum('meterage') + $order->products->sum('wastage'), 2);
            $products = $order->products->map(function ($product) {
                return $product->name . " " . $product->dimensions . " " . $product->grade;
            });

            return [
                'id'          => $order->id,
                'date'        => Carbon::parse($order->payment_date)->format('d/m/Y') ?? null,
                'customer'    => $order->customer->fullName,
                'address'     => $order->customer->address,
                'products'    => $products->implode(','),
                'supply_only' => $supply_only ? 'Yes' : 'No',
                'meterage'    => $meterage,
                'commission'  => $supply_only ? $meterage * 0.75 : $meterage * 1,
            ];
        });

        $titles = [
            'Order Id',
            'Date',
            'Customer',
            'Address',
            'Products',
            'Supply Only',
            'Meterage',
            'Commission',
        ];

        return $stats->groupBy(function ($item) {
            try {
                return Carbon::createfromformat('d/m/Y', $item['date'])->format('M-Y');
            } catch (\Throwable $th) {
                dd($item['id']);
            }
        })->map(function ($item) use ($titles) {
            $commissionsSum = $item->sum('commission');
            $meterageSum    = $item->sum('meterage');
            $item->push([
                'Totals',
                '',
                '',
                '',
                '',
                '',
                $meterageSum,
                $commissionsSum,
            ]);

            return $item->prepend($titles);
        });
    }

    /**
     * Changes the output of Product model
     *
     * @param $product
     *
     * @return array
     */
    public function formatProduct($product)
    {

        return [
            'name'       => "$product->name $product->dimensions $product->grade",
            'meterage'   => round($product->meterage + $product->wastage, 2),
            'unit_price' => round($product->unit_price * (1 - $product->discount / 100), 2),
            'price'      => $product->discountedPrice,
        ];
    }

    /**
     * @param $orders
     *
     * @return LengthAwarePaginator
     */
    public function orderStats($orders)
    {
        return $this->map($orders, fn($order) => [
            'id'                => $order->id,
            'customer'          => trim("{$order->customer?->firstname} {$order->customer?->lastname}"),
            'products'          => $products = $order->products->map([$this, 'formatProduct']),
            'materialsTotal'    => $materialsTotal = round($order->orderMaterials->sum('price'), 2),
            'servicesTotal'     => $servicesTotal = round($order->orderServices->sum('price'), 2),
            'productsSold'      => round($products->sum('meterage'), 2),
            'productsTotal'     => $productsTotal = round($products->sum('price'), 2),
            'paymentsTotal'     => round($order->payments->sum('amount'), 2),
            'servicesVatTotal'  => round($servicesTotal * ($order->vat / 100), 2),
            'materialsVatTotal' => round($materialsTotal * ($order->vat / 100), 2),
            'productsVatTotal'  => round($productsTotal * ($order->vat / 100), 2),
            'payment_date'      => $order->payment_date,
            'vat_total'         => $order->vat_total,
            'total'             => $order->total,
            'expenses'          => $order->expenses->sum('amount'),
            'grand_total'       => $materialsTotal + $servicesTotal + $productsTotal,
            'vat_grand_total'   => $order->grand_total,
            'balance'           => $order->balance,
        ]);
    }

    public function notesView($orders)
    {
        return $this->map($orders, function ($order) {
            return (object) [
                'id'             => $order->id,
                'customer'       => $order->customer->fullName,
                'review_request' => $order->review_request ? $order->review_request->format('d/M/Y H:i') : null,
                'notes'          => $order->notes,
            ];
        });
    }

    public function orderList($orders)
    {
        return $this->map($orders, function ($order) {
            return [
                'id'             => $order->id,
                'base_id'  => $order->base_id,
                'customer'       => $order->title . " " . $order->customer->fullName,
                'status'         => $order->status,
                'address'        => $order->address,
                'date'           => $order->created_at->format('d/m/Y'),
                'notes'          => $order->notes,
                'lp_date'        => $order->payment_date ? $order->payment_date->format('d/m/Y') : null,
                'grand_total'    => $order->grand_total,
                'photo_request'  => $order->photo_request ? $order->photo_request->format('d/m/Y H:i') : null,
                'review_request' => $order->review_request ? $order->review_request->format('d/m/Y  H:i') : null,
                'vat'            => $order->vat,
                'balance'        => $order->balance,
            ];
        });
    }


    public function calcProductsPrice($products)
    {
        return [
            "standalone" => $products['standalone']->sum('discountedPrice'),
            "withAreas"  => $products['withAreas']->sum('discountedPrice'),
        ];
    }
    /**
     *
     * @return array
     */
    public function transform(Order $order)
    {
        $products = $this->products($order);
        return [
            'order'              => $order,
            'id'                 => $order->id,
            'details'            => $this->details($order),
            'due_date'           => $order->due_date,
            'address'            => $order->project ?? $order->customer,
            'proforma'           => $order->proforma,
            'proforma_breakdown' => $order->proforma_breakdown,
            'reverse_charge'     => $order->reverse_charge,
            'cc'                 => array_map('trim', explode(',', $order->cc)),
            'status'             => $order->status_name,
            'company'            => $order->company,
            'customer'           => $order->customer,
            'user'               => $order->user,
            'proforma_message'   => $order->proforma_message,
            'mail_message'       => $order->mail_message,
            'updated_at'         => $order->updated_at,
            'materials'          => $order->orderMaterials,
            'materials_total'    => $order->orderMaterials->sum('price'),
            'services'           => $order->orderServices,
            'services_total'     => $order->orderServices->filter(fn($service) => stripos("delivery", $service->name) === false)->sum('price'),
            'delivery_price'     => $order->orderServices->filter(fn($service) => stripos("delivery", $service->name) !== false)->sum('price'),
            'products'           => $products,
            'products_sums'      => $this->calcProductsPrice($products),
            'payments'           => $order->payments,
            'payment_terms'      => $order->payment_terms,
            'tax_deductions'     => $order->taxDeductions,
            'due'                => $order->due,
            'due_amount'         => $order->due_amount,
            'dueNow'             => $order->dueNow,
            'discount'           => $order->discount,
            'totalPrice'         => $order->total,
            'vat_total'          => $order->vat_total,
            'vat'                => $order->vat,
            'grand_total'        => $order->grand_total,
            'paid'               => $order->paid,
            'balance'            => $order->balance,
        ];
    }
}
