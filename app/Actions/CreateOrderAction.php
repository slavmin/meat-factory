<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class CreateOrderAction
{
    public static function handle(Request $request, User $user): Order|string
    {
        try {
            DB::beginTransaction();

            $products = $productQuantity = [];

            foreach ($request->input('items') as $item) {
                $product = Product::query()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new Exception("Not enough stock for product {$product->id}, only {$product->stock} available");
                }

                $products[] = $product;
                $productQuantity[$product->id] = $item['quantity'];
            }

            $order = Order::query()->create([
                'user_id' => $request->input('user_id'),
                'comment' => $request->input('comment'),
                'total' => 0,
                'status' => 'new',
            ]);

            $total = 0;

            foreach ($products as $product) {
                $orderItem = OrderProduct::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $productQuantity[$product->id],
                    'price' => $product->price,
                ]);

                $total += $product->price * $productQuantity[$product->id];

                $product->decrement('stock', $productQuantity[$product->id]);
            }

            $order->update(['total' => $total]);

            DB::commit();

            return $order;
        } catch (Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }
}
