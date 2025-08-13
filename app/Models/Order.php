<?php

namespace App\Models;

use App\Traits\OrderAttributes;
use App\Traits\OrderRelations;
use App\Traits\OrderScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\Order
 */
class Order extends Model
{
    use Searchable, OrderAttributes, OrderRelations, OrderScopes, HasFactory;

    protected $dates = [
        'created_at',
        'updated_at',
        'payment_date',
        'review_request',
        'photo_request',
        'date_due',
        'sent',
    ];

    protected $casts = [
        'vat'                => 'float',
        'total'              => 'float',
        'discount'           => 'float',
        'due_amount'         => 'float',
        'vat_total'          => 'float',
        'grand_total'        => 'float',
        'proforma'           => 'bool',
        'proforma_breakdown' => 'bool',
        'reverse_charge'     => 'bool',
        'balance'            => 'float',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
        'review_request'     => 'datetime',
        'photo_request'      => 'datetime',
        'payment_date'       => 'datetime',
    ];

    protected $fillable = [
        'company_id',
        'customer_id',
        'status_id',
        'user_id',
        'project_id',
        'cc',
        'mail_message',
        'proforma_message',
        'payment_terms',
        'notes',
        'proforma',
        'proforma_breakdown',
        'reverse_charge',
        'sent',
        'vat',
        'balance',
        'discount',
        'due',
        'due_amount',
        'date_due',
        'payment_date',
        'grand_total',
    ];

    /**
     * Updates Balance and Total
     */
    public function updateOrderSums(): void
    {
        // $order->total column to $order->sum attribute
        $this->total = $this->sum;
        $this->save();
        $this->vat_total   = $this->vatSum;
        $this->grand_total = $this->orderSum;
        $this->balance     = $this->grand_total - $this->payments()->sum('amount') - $this->taxDeductions()->sum('amount');
        $this->save();
    }

    /**
     * Update Last Payment Date
     */
    public function updatePaymentDate(): void
    {
        $payment = $this->payments->sortBy('date')->first();
        if ($payment) {
            $this->payment_date = $payment->date;
            $this->save();
        }
    }

    /**
     * Get the index name for the model.
     */
    public function searchableAs(): string
    {
        return 'orders_index';
    }

    /**
     * Get the indexable actions array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id'             => $this->id,
            'customer'       => "{$this->title} {$this->customer->fullName}",
            'status'         => $this->status->name,
            'address'        => $this->address,
            'date'           => $this->created_at,
            'notes'          => $this->notes,
            'grand_total'    => $this->grand_total,
            'photo_request'  => $this->photo_request ? $this->photo_request : null,
            'review_request' => $this->review_request ? $this->review_request : null,
            'vat'            => $this->vat,
            'balance'        => $this->balance,
        ];
    }
}
