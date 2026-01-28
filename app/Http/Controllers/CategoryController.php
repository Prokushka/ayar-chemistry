<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {

    }

    public function categoryProductShow(Category $category)
    {

        $product = $category->products();
        $products = $product->where('is_active', 1)->with(['priceTiers', 'priceEvent'])->get()->map(function ($product) {
            $minPrice = $product->priceTiers->sortBy('price')->first()?->price;

            return [
                'title' => $product->title,
                'image_url' => $product->image_url,
                'slug' => $product->slug,
                'size' => $product->size,
                'min_price' => $minPrice,
                'event' => $product->priceEvent?->title,
                'event_color' => $product->priceEvent?->color
            ];
        });
        return Inertia::render('Product/Index', [
            'products' => $products,
        ]);
    }


}
