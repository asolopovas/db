<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductArea extends Model
{
    use HasFactory;

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['order'];

    protected $fillable = [
        'order_id',
        'area_id',
        'product_id',
        'meterage',
        'price',
    ];

    protected $casts = [
        'price'    => 'float',
        'meterage' => 'float',
    ];

    /**
     * @return Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return  Area
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * @return Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @param $product
     *
     * @return Model
     */
    public function addProduct($product)
    {
        return $this->product()->associate($product);
    }

}
