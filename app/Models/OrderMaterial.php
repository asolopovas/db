<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\OrderMaterial
 *
 */
class OrderMaterial extends Model
{

    use HasFactory;

    protected $appends = ['price'];
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['order'];

    protected $casts = [
        'unit_price' => 'float',
        'quantity'   => 'float',
        'order_date' => 'datetime'
    ];

    protected $fillable = [
        'order_id',
        'material_id',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @param $query
     */
    public function scopeWithMeta($query)
    {
        $query->join('materials', 'order_materials.material_id', '=', 'materials.id')
            ->addSelect(['materials.name']);
    }

    public function scopeData($query)
    {
        $query->join('orders', 'order_materials.order_id', '=', 'orders.id')
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->leftjoin('projects', 'orders.project_id', '=', 'projects.id')
            ->with('customer')
            ->withMeta()
            ->where('orders.balance', 0)
            ->where('statuses.name', 'Invoice')
            ->select(
                [
                    'order_materials.unit_price',
                    'order_materials.quantity',
                    'projects.street as pr_street',
                    'projects.postcode as pr_postcode',
                    'orders.id as order_id',
                    'orders.payment_date as order_date',
                    'orders.customer_id',
                ]
            )
            ->orderBy('orders.payment_date');
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
