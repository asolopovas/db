<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\Project
 *
 * @property int $id
 * @mixin \Eloquent
 */
class Project extends Model
{
  use Searchable;
  protected $fillable = [
    'street',
    'county',
    'town',
    'city',
    'postcode',
    'country',
  ];

  /**
   * Get the index name for the model.
   *
   * @return string
   */
  public function searchableAs()
  {
    return 'projects_index';
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
  }    //
}
