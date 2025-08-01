<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $order = Order::query()->where('user_id', auth()->id())->latest()->first();
        if ($order){
            $idsToDetach = $order->products()
                ->WherePivot('quantity')
                ->orwherePivot('quantity', '<=', 0)
                ->pluck('products.id')
                ->all();
            if ($idsToDetach) {
                $order->products()->detach($idsToDetach);
            }
            if ($order->products()->count() === 0) {
                $order->delete();
            }
        }

        return Inertia::render('Order/Index', [
            'orders' => Order::query()->where('user_id', auth()->id())
                ->where('status', '!=', 'Отменён')->get()
        ]);
    }

    public function check(string $id)
    {
        $order = Order::query()->find($id);
        $order->load('products');



        return Inertia::render('Order/Check', [
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'total_cost' => $order->total_cost,
                'products' => $order->products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'title' => $product->title,
                        'image_url' => $product->image_url,
                        'pivot' => [
                            'id' => $product->pivot->id,
                            'quantity' => $product->pivot->quantity,
                            'sale_price' => $product->pivot->sale_price,
                        ],
                    ];
                }),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $order = Order::query()->find($request->id);

        $order->update([
           'status' => 'В обработке'
        ]);

        return redirect()->route('order.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*quantity
        product_id
        total_sum
        sale_price
        */
        $product = Product::query()->find($request->product_id);
        $order = Order::query()
            ->where('user_id', auth()->id())
            ->where('status', 'Новый')->first();
        if ($order){
             $order->products()->wherePivot('product_id', $request->product_id)->detach();
        }else{
            $order = Order::query()->create([
                'status' => 'Новый',
                'user_id' => auth()->id(),
            ]);
        }
        $profit = ($request->sale_price * $request->quantity) - ($product->purchase_price * $request->quantity);
        $order->products()->attach($request->product_id, [
            'purchase_price' => $product->purchase_price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'profit' => $profit
        ]);



        return Inertia::render('Order/Index', [
           'orders' => Order::query()->where('user_id', auth()->id())
               ->where('status', '!=', 'Отменён')->get()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Product::query()->find($id);

        return Inertia::render('Product/Show', [
            'order' => $order
        ]);
    }


    public function convertion(Request $request)
    {

        if (!$request->total_price){
            return redirect()->back();
        }
        $product = Product::find($request->id);

        return Inertia::render('Order/Convertion',[
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
                'image_url' => $product->image_url,
            ],
            'salePrice' => $request->total_price
        ])->with([
            'message' => 'Продукт успешно загружен',
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
        $order = Order::query()->find($id);
        //$order->products()->detach();
        //$order->delete();
        $order->update(['status' => 'Отменён']);
        return redirect()->route('order.index');
    }

    public function productDelete(string $id): RedirectResponse
    {
        $order = Order::query()
            ->where('user_id', auth()->id())
            ->where('status', 'Новый')->first();
        if ($order){
            $order->products()->detach($id);
        }
        if ($order->products()->count()  == 0){
            return redirect()->route('order.index');
        }

        return redirect()->route('order.check', $order->id);
    }
}
