<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CreateOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request): JsonResponse
    {
        $user = $request->user();

        $order = CreateOrderAction::handle($request, $user);

        if ($order instanceof Order) {
            return response()->json($order->toArray(), Response::HTTP_CREATED);
        }

        return response()->json([
            'message' => 'Order creation failed',
            'error' => $order,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
