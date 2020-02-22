<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return $this->success(ProductResource::collection($products));
    }

    public function show(Product $product)
    {
        return $this->success(new ProductResource($product));
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->all());
        return $this->setStatusCode(201)->success(new ProductResource($product));
    }
}
