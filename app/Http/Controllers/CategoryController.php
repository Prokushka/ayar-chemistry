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
        $categories = Category::query()->whereNull('parent_id')->with('children')->get();

        $product = $category->products();
        return Inertia::render('Product/Index', [
            'products' => $product->select(['id','title', 'image_url', 'sale_price', 'slug'])->get(),
            'categories' => $categories
        ]);
    }


}
