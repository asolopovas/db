<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Service extends Model
{
    use Searchable, HasFactory;

    protected $fillable = [
        'name',
        'price',
        'measurement_unit',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function orderServices()
    {
        return $this->belongsToMany(OrderService::class);
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'services_index';
    }

    /**
     * Get the indexable actions array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }
}
