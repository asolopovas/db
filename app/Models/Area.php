<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\Area
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductArea[] $productArea
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Area whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Area whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Area extends Model
{
    use Searchable, HasFactory;

    protected $fillable = ['name'];
    protected $casts = [
        'price' => 'float',
    ];

    public function productArea()
    {
        return $this->hasMany(ProductArea::class);
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'areas_index';
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
