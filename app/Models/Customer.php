<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\Customer
 */
class Customer extends Model
{
    use Searchable, HasFactory;

    protected $fillable = [
        'title',
        'firstname',
        'lastname',
        'company',
        'contact_status',
        'address_type',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postcode',
        'region',
        'town',
        'county',
        'country',
        'phone',
        'mobile_phone',
        'home_phone',
        'work_phone',
        'fax',
        'email1',
        'email2',
        'email3',
        'note',
        'web_page',
        'status',
    ];

    public function getEmailsAttribute(): array
    {
        return [$this->email1, $this->email2, $this->email3];
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getAddressAttribute()
    {
        $addressArr = [
            'status' => $this->status,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'company'  => $this->company,
            'town'     => $this->town,
            'county'   => $this->county,
            'city'     => $this->city,
            'country'  => $this->country,
            'postcode' => $this->postcode,
        ];

        return implode(',', array_filter($addressArr));
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the index name for the model.
     *
     */
    public function searchableAs(): string
    {
        return 'customers_index';
    }

    /**
     * Get the indexable actions array for the model.
     */
    public function toSearchableArray(): array
    {
        return array_merge($this->toArray(), ['address' => $this->address]);
    }
}
