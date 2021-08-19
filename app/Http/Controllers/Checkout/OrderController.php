<?php

namespace App\Http\Controllers\Checkout;

use App\Events\OrderCompleteEvent;
use App\Models\Link;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController
{
    public function store(Request $request){

        $request->validate([
            'code' => 'required|exists:links,code',
        ]);

        DB::beginTransaction();

        $link = Link::where('code', request('code'))->first();

        $order = new Order();
        $order->fill($request->only('first_name', 'last_name', 'email', 'address', 'address2', 'city', 'country', 'zip'));
        $order->code = $link->code;
        $order->user_id = $link->user_id;
        $order->influencer_email = $link->user->email;
        $order->save();

        $lineItems = [];
        foreach (request('items') as $item) {
            $product = Product::find($item['product_id']);
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_title = $product->title;
            $orderItem->price = $product->price;
            $orderItem->quantity = $item['quantity'];

            $total = $product->price * $item['quantity'];

            $orderItem->influencer_revenue = 0.1 * $total;
            $orderItem->admin_revenue = 0.9 * $total;

            $orderItem->save();

            $lineItems[] = [
                'name' => $product->title,
                'description' => $product->description,
                'images' => [$product->image],
                'amount' => 100 * $product->price, // in cent
                'currency' => 'usd',
                'quantity' => $orderItem->quantity,
            ];
        }

        $stripe = Stripe::make(env('STRIPE_SECRET'));

        $source = $stripe->checkout()->sessions()->create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'success_url' => env('CHECKOUT_URL') . '/success?source={CHECKOUT_SESSION_ID}',
            'cancel_url' => env('checkout_url') . '/error',
        ]);

        $order->transaction_id = $source['id'];
        $order->save();

        DB::commit();

        return $source;
    }

    public function confirm(Request $request) {
        if (!$order = Order::whereTransactionId(request('source'))->first()){
            return response([
                'error' => 'Order not found'
            ], 404);
        }

        $order->complete = 1;
        $order->save();

        OrderCompleteEvent::dispatch($order);

        return response([
            'message' => 'Success'
        ]);
    }
}
