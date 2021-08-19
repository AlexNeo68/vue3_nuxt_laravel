<?php

namespace App\Http\Controllers\Influencer;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController
{
    public function index(Request $request){

        $products = Product::query();
        if($s = $request->input('s')){
            $products->whereRaw("title LIKE '%{$s}%'")
                ->orWhereRaw("description LIKE '%{$s}%'");
        };
        return $products->get();
    }
}
