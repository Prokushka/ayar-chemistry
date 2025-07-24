<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $order = Order::query()->where('user_id', auth()->id())->first();
        $idsToDetach = $order->products
        ->filter(fn($product) => $product->pivot->quantity <= 0)
        ->pluck('id')
        ->all();
        if ($idsToDetach) {
            $order->products()->detach($idsToDetach);
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
        $order = Order::query()->find($request->order_id);

        $product = Product::query()->find($request->product_id);

        $pivot = $product->orders()->where('orders.id', $order->id)->first();

        $profit = ((int) $pivot->pivot->sale_price * $request->quantity)
            - ((int) $pivot->pivot->purchase_price * $request->quantity);
        if ($profit < 0){
            $profit = 0;
        }
        $order->products()->updateExistingPivot($request->product_id,[
            'quantity' => $request->quantity,
            'profit' => $profit
        ]);
        $order->update([
            'total_cost' => $order->total_cost += $request->total_sum,
            'total_profit' => $order->total_profit += $profit
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
        $user = User::query()->find(auth()->id());
        $order = $user->orders()->where('orders.status', 'Новый');
        if($order->exists()){
            $order = $order->first();
        }
        else{
            $order = $user->orders()->create([
                'status' => 'Новый',
            ]);
            assert($order instanceof Order);
        }
        $product = Product::query()->find($request->id);
        if (!$product) {
            abort(404, 'Product not found');
        }
        $order->products()
            ->attach($product->id,
                [
                    'purchase_price' => $product->purchase_price,
                    'sale_price' => $request->totalPrice
                ]);
        $order->load('products');
        $addedProduct = $order->products->find($product->id);
        return Inertia::render('Order/Convertion',[
            'order' => $order,
            'product' => [
                'id' => $addedProduct->id,
                'title' => $addedProduct->title,
                'image_url' => $addedProduct->image_url,
                'pivot' => [
                    'id' => $addedProduct->pivot->id,
                    'sale_price' => $addedProduct->pivot->sale_price,
                ]
            ],
            'salePrice' => $request->totalPrice
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

    public function productDelete(string $id)
    {
        /*$user = User::query()->find(auth()->id());
        $order = $user->orders()->where('status', 'Новый');
        if($order->exists()){
        $order = $order->first();
        }
        assert($order instanceof Order);*/
        $product = Product::query()->whereHas('orders', function($q) use ($id) {
            $q->where('order_product.id', $id);
        })->with(['orders' => function($q) use ($id) {
            $q->where('order_product.id', $id);
        }])->first();
        $order = $product->orders->first();

        $newTotalCost = $order->total_cost - ($order->pivot->sale_price * $order->pivot->quantity);
        $newTotalProfit = $order->total_profit - $order->pivot->profit;

        if (!empty($newTotalCost)){
            $order->update([
                'total_cost' => $newTotalCost,
                'total_profit' => $newTotalProfit,
            ]);
        }
        $order->products()->detach($product->id);
        return redirect()->route('order.index');
    }
}
