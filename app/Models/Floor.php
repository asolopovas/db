<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Floor
 *
 * @property int                                                          $id
 * @property string                                                       $name
 * @property float                                                        $price
 * @property \Illuminate\Support\Carbon|null                              $created_at
 * @property \Illuminate\Support\Carbon|null                              $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Floor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Floor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Floor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Floor wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Floor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Floor extends Model
{
    use Searchable, HasFactory;

    protected $casts = [
        'price' => 'float',
    ];
    protected $fillable = [
        'name',
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
