<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $touches = ['order'];

    protected $fillable = [
        'order_id',
        'description',
        'amount',
        'date',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'date',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
