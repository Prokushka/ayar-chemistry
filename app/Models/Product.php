<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';

    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'image_url',
        'purchase_price',
        'sale_price',
        'quantity',
        'is_active'
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->withPivot(['quantity','purchase_price','sale_price', 'profit']);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
