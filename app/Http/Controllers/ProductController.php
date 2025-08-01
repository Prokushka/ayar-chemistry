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
        return Inertia::render('Product/Index', [
            'products' => Product::query()->select(['id','title', 'image_url', 'sale_price', 'slug'])->get(),
            'categories' => Category::all(),
            'breadcrumbs' => Breadcrumbs::generate('product.index'),
        ]);
    }

    public function categoryProductShow(Category $category)
    {
        $product = $category->products();
        return Inertia::render('Product/Index', [
            'products' => $product->select(['id','title', 'image_url', 'sale_price', 'slug'])->get(),
            'categories' => Category::all(),
            'breadcrumbs' => Breadcrumbs::generate('product.category.show', $category),
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
            'breadcrumbs' => Breadcrumbs::generate('product.show', $product)
        ]);
    }


    public function search(Request $request)
    {
        $query = $request->query_string;
        $products = DB::table('products')
            ->where('title', 'LIKE', "%$query%")
            ->get();;

       return Inertia::render('Product/Index', [
           'products' => $products,
           'categories' => Category::all(),
           'breadcrumbs' => Breadcrumbs::generate('product.index')
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
