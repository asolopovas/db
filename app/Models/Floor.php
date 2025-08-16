<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Floor
 */
class Floor extends Model
{
    use Searchable, HasFactory;

    protected $casts = [
        'price' => 'float',
    ];
    protected $fillable = [
        'name',
        'sku',
        'price',
        'vendor',
        'vendor_name',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'floors_index';
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
