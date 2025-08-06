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
    public function priceTiers(): BelongsToMany
    {
        return $this->belongsToMany(PriceTier::class, 'price_tier_product', 'product_id')->orderByDesc('from_quantity');
    }

    public function priceEvent(): BelongsTo
    {
        return $this->BelongsTo(PriceEvent::class, 'price_event_id');
    }


    public function priceForQuantity($quantity)
    {
        return $this->priceTiers()
            ->where('from_quantity', '<=', $quantity)
            ->orderByDesc('from_quantity')
            ->first()
            ?->price ?? $this->base_price;
    }

    public function price(...$values)
    {
        return $this->priceTiers()
        ->get()
        ->map(function ($item) use ($values){
            $arr = [];
            foreach ($values as $attr){
               $arr[$attr] = $item->$attr;
            }
            return $arr;
        });
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
