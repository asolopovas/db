<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $touches = ['order'];
    protected $fillable = [
        'order_id',
        'details',
        'category',
        'amount',
    ];
    protected $casts = [
        'amount' => 'float',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
