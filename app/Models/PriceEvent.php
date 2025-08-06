<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PriceEvent extends Model
{
    protected $table = 'price_events';

    public function products()
    {
        return $this->hasMany(Product::class, 'price_event_id');
    }
}
