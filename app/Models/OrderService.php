<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\OrderService
 *
 * @mixin \Eloquent
 */
class OrderService extends Model
{

    use HasFactory;

    protected $appends = ['price'];
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['order'];

    /**
     * Casts Model attributes in specific format
     * @var array
     */
    protected $casts = [
        'unit_price' => 'float',
        'quantity'   => 'float',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'service_id',
        'unit_price',
        'quantity',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }


    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeWithMeta(Builder $query)
    {
        $query->join('services', 'order_services.service_id', '=', 'services.id')
            ->addSelect(['services.name']);
    }

    public function scopeData($query)
    {
        $query->join('orders', 'order_services.order_id', '=', 'orders.id')
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->leftjoin('projects', 'orders.project_id', '=', 'projects.id')
            ->withMeta()
            ->with('customer')
            ->where('orders.balance', 0)
            ->where('statuses.name', 'Invoice')
            ->select(
                [
                    'order_services.unit_price',
                    'order_services.quantity',
                    'services.name as name',
                    'projects.street as pr_street',
                    'projects.postcode as pr_postcode',
                    'orders.id as order_id',
                    'orders.payment_date as order_date',
                    'orders.customer_id',
                ]
            )
            ->orderBy('orders.payment_date');
    }


    public function getNameAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->service->name));
    }
    /**
     * Total Price attribute accessor
     * @return mixed
     */
    public function getPriceAttribute()
    {
        return $this->unit_price * $this->quantity;
    }
}
