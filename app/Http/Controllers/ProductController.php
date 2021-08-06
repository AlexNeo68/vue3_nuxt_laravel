<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{

    public function index()
    {
        Gate::authorize('views', 'products');
        $products = Product::paginate();
        return ProductResource::collection($products);
    }


    public function store(ProductCreateRequest $request)
    {

        Gate::authorize('edit', 'products');

        $product = Product::create($request->only('title', 'description', 'price', 'image')) ;

        return response($product, Response::HTTP_CREATED);
    }

    public function show(Product $product)
    {
        Gate::authorize('views', 'products');
        return new ProductResource($product);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {

        Gate::authorize('edit', 'products');
        $product->update($request->only('title', 'description', 'price', 'image'));
        return response($product, Response::HTTP_ACCEPTED);
    }

    public function destroy(Product $product)
    {
        Gate::authorize('edit', 'products');
        $product->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
