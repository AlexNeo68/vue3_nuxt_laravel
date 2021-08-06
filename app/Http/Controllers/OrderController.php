<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{

    public function index()
    {
        Gate::authorize('views', 'orders');
        $orders = Order::paginate();
        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        Gate::authorize('views', 'orders');
        return new OrderResource($order);
    }

    public function export(){
        Gate::authorize('views', 'orders');
        $headers = [
            "Content-type"=> "text/csv",
            "Content-Disposition"=> "attachment; filename=orders.csv",
            "Pragma"=> "no-cache",
            "Cache-Control"=> "must-revalidate, post-check=0, pre-check=0",
            "Exprires"=> "0",

        ];


        $callback = function(){
            $orders = Order::all();

            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'NAME', 'EMAIL', 'PRODUCT TITLE', 'PRICE', 'QUANTITY']);
            foreach ($orders as $order) {
                fputcsv($file, [$order->id, $order->name, $order->email, '', '', '']);
                foreach($order->orderItems as $item){
                    fputcsv($file, ['', '', '', $item->prdoduct_title, $item->price, $item->quantity]);
                }
            }

        };


       return \Response::stream($callback, 200, $headers);
    }
}
