<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\Grade
 *
 * @property int                                                          $id
 * @property string                                                       $name
 * @property float                                                        $price
 * @property \Illuminate\Support\Carbon|null                              $created_at
 * @property \Illuminate\Support\Carbon|null                              $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Grade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Grade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Grade whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Grade wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Grade whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Grade extends Model
{
    use Searchable, HasFactory;

    protected $fillable = [
        'name',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
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
        return 'grades_index';
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
