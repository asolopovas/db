<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxDeduction extends Model
{
    use HasFactory;

    protected $touches = ['order'];
    protected $fillable = [
        'order_id',
        'ref',
        'amount',
        'date',
    ];
    protected $casts = [
        'amount' => 'float',
        'date' => 'datetime',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
