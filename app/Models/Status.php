<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Status extends Model
{
    use Searchable, HasFactory;

    protected $fillable = ['name'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs(): string
    {
        return 'statuses_index';
    }

}
