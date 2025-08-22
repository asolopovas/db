<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductArea extends Model
{
    use HasFactory;

    protected $touches = ['order'];

    protected $fillable = [
        'order_id',
        'area_id',
        'product_id',
        'name',
        'meterage',
    ];

    protected $casts = [
        'price'    => 'float',
        'meterage' => 'float',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function addProduct($product): Model
    {
        return $this->product()->associate($product);
    }
}
