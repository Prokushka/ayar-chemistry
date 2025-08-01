<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {

    }

    public function categoryProductShow(Category $category)
    {

        $product = $category->products();
        return Inertia::render('Product/Index', [
            'products' => $product->select(['id','title', 'image_url', 'sale_price', 'slug'])->get(),
            'categories' => Category::all(),
            'breadcrumbs' => Breadcrumbs::generate('product.category.index'),
        ]);
    }


}
