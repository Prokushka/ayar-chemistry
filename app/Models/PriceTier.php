<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PriceTier extends Model
{

    protected $table = 'price_tiers';
    protected $guarded = [];


    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'price_tier_product');
    }
}
