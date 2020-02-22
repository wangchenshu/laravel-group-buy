<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\OrderRequest;
use App\Http\Resources\Api\OrderResource;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return $this->success(OrderResource::collection($orders));
    }

    public function show(Order $order)
    {
        return $this->success(new OrderResource($order));
    }

    public function store(OrderRequest $request)
    {
        $order = Order::create($request->all());
        return $this->setStatusCode(201)->success(new OrderResource($order));
    }
}
