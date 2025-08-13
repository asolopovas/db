<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\Extra
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Extra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Extra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Extra whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Extra wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Extra whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Extra extends Model
{
  use Searchable, HasFactory;

  protected $casts = [
    'price' => 'float',
  ];

  protected $fillable = [
    'name',
    'price',
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
    return 'extras_index';
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
