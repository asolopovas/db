<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Dimension
 *
 * @property int $id
 * @property int $width
 * @property int $length
 * @property int $thickness
 * @property string $type
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dimension whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dimension whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dimension whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dimension wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dimension whereThickness($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dimension whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dimension whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dimension whereWidth($value)
 * @mixin \Eloquent
 */
class Dimension extends Model
{
  use Searchable, HasFactory;

  protected $fillable = [
    'width',
    'length',
    'thickness',
    'type',
    'price',
  ];

  protected $casts = [
    'price' => 'float',
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
    return 'dimensions_index';
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
