<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Laravel\Scout\Searchable;

/**
 * App\Material
 *
 * @property int    $id
 * @property string $name
 * @mixin \Eloquent
 */
class Material extends Model
{
    use Searchable, HasFactory;

    protected $fillable = [
        'name',
        'price',
        'measurement_unit',
    ];
    protected $casts = [
        'price' => 'float',
        'order_date' => 'datetime',
    ];

    public function materialGroup()
    {
        return $this->belongsToMany(OrderMaterial::class);
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'materials_index';
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
