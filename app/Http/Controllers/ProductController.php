<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['priceTiers', 'priceEvent'])->where('is_active', 1)->get()->map(function ($product) {
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {


        return Inertia::render('Product/Show', [
            'product' => $product,
            'priceTiers' => $product->price('price', 'from_quantity'),
            'event' => [
               'title' => $product->priceEvent?->title,
               'color' => $product->priceEvent?->color,
            ]

        ]);
    }


    public function search(Request $request)
    {
        $query = $request->query_string;
        $products = Product::with('priceTiers')
            ->where('title', 'LIKE', "%$query%")
            ->where('is_active', 1)
            ->get()
            ->map(function ($product) {
                $minPrice = $product->priceTiers->sortBy('price')->first()?->price;

                return [
                    'title' => $product->title,
                    'image_url' => $product->image_url,
                    'slug' => $product->slug,
                    'size' => $product->size,
                    'min_price' => $minPrice,
                ];
            });;

        return Inertia::render('Product/Index', [
            'products' => $products,

        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
