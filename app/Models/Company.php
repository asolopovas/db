<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Company extends Model
{
    use Searchable, HasFactory;

    protected $fillable = [
        'name',
        'address',
        'telephone1',
        'telephone2',
        'web',
        'email',
        'vat_number',
        'bank',
        'sort_code',
        'account_nr',
        'notes'
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'companies_index';
    }
}
