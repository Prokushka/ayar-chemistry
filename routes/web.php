<?php

use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::get('/status', function () {
    return response('OK', 200);
});

Route::get('/', function (?Request $request) {
    $code = $request->query('code');
    if ($code){
        Artisan::call('avito:token', [
            'token' => $code,
        ]);
        Log::info('token placed');
    }
    $products = Product::with('priceTiers')->where('is_active', 1)->limit(4)->get()->map(function ($product) {
        $minPrice = $product->priceTiers->sortBy('price')->first()?->price;

        return [
            'title' => $product->title,
            'image_url' => $product->image_url,
            'slug' => $product->slug,
            'size' => $product->size,
            'min_price' => $minPrice,
        ];
    });
    return Inertia::render('Welcome', [
        'products' => $products

    ]);
})->name('main');

Route::get('/broadcast', function (){
    return Inertia::render('Whatsapp/Index');
})->middleware('adminPanel');
Route::post('/broadcast', function (Request $request){
    \Illuminate\Support\Facades\Http::get('http://whatsapp-bot:3000/broadcast', [
        'message' => $request->message
    ]);
})->name('broadcast');

Route::get('/info', function () {
    Log::info('Phpinfo page visited');
    return phpinfo();
});

Route::group(['middleware' => \App\Http\Middleware\BreadCrumbsMiddleware::class], function (){
    Route::group(['prefix' => 'category'], function () {
        Route::get('{category}', [\App\Http\Controllers\CategoryController::class, 'categoryProductShow'])->name('product.category.show');
    });
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', [\App\Http\Controllers\ProductController::class, 'index'])->name('product.index');
        Route::get('/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
        Route::get('/search/{query_string}', [\App\Http\Controllers\ProductController::class, 'search'])->name('product.search');
    });
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [\App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
        Route::get('/convertion', [\App\Http\Controllers\OrderController::class, 'convertion'])->name('order.convertion');
        Route::post('/', [\App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
        Route::delete('/{id}', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('order.delete');
        Route::delete('/product/{id}', [\App\Http\Controllers\OrderController::class, 'productDelete'])->name('order.product.delete');
        Route::get('/check/{id}', [\App\Http\Controllers\OrderController::class, 'check'])->name('order.check');
        Route::post('/order-create', [\App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
        Route::get('/{id}', [\App\Http\Controllers\OrderController::class, 'show'])->name('order.show');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

Route::get('/download-products', [\App\Http\Controllers\ExcelController::class, 'download'])
->name('excel.download');


require __DIR__.'/auth.php';
