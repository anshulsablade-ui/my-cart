<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mycart\CartController;
use App\Models\Product;
use App\Models\User;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIChatController extends Controller
{
    public function index()
    {
        return view('mycart.chatbot.index');
    }

    public function chat(Request $request, AIService $bot)
    {
        if (!session()->has('user')) {
            return response()->json('Please login to use the chatbot.');
        }

        $user = User::findOrFail(session()->get('user.id'));

        $userDetails = [
            'wishlists_products' => $this->wishlistPayload($user),
            'cart_items' => $this->cartPayload($user),
            'orders' => $this->orderPayload($user),
        ];

        $context = Product::where('name', 'like', '%' . $request->message . '%')->limit(5)->get();

        $result = $bot->ask($request->message, $context, json_encode($userDetails));

        $content = $result['choices'][0]['message']['content'] ?? $result['error']['message'] ?? null;

        if (!$content) {
            return response()->json('AI response error', 500);
        }

        $data = json_decode($content, true);

        if (($data['action'] ?? null) === 'update_cart') {

            $cartResponse = app(CartController::class)
                ->update(new Request($data));

            $cartData = $cartResponse->getData(true);

            $summary = [
                'subtotal' => $cartData['subtotal'],
                'discounted_price' => $cartData['discounted_price'],
                'gstAmount' => $cartData['gstAmount'],
                'grandTotal' => $cartData['grand_total'],
            ];

            $followup = $bot->ask( json_encode($cartData['message']), 'update_cart', json_encode($summary) );

            return response()->json( $followup['choices'][0]['message']['content'] ?? null );
        }

        return response()->json($content);
    }

    private function wishlistPayload(User $user)
    {
        return $user->wishlists()
            ->with(['product.primaryImage', 'product.brand'])
            ->get()
            ->map(function ($wishlist) {

                $product = $wishlist->product;

                return [
                    'product_name' => $product->name,
                    'product_slug' => $product->slug,
                    'product_image' => asset('images/products/thumb/' . ($product->primaryImage->image ?? 'no-image.png')),
                    'product_brand_name' => $product->brand->name,
                    'base_price' => $product->base_price,
                    'discount_percentage' => $product->discount_percentage,
                    'discounted_price' => $product->discounted_price,
                    'final_price' => $product->final_price,
                    'stock' => $product->stock,
                ];
            })
            ->values();
    }

    private function cartPayload(User $user)
    {
        return $user->carts()
            ->with(['product.primaryImage', 'product.brand'])
            ->whereHas('product', function ($q) {
                $q->where('status', 'active')->where('stock', '>', 0);
            })
            ->get()
            ->map(function ($cartItem) {

                $product = $cartItem->product;

                return [
                    'cart_id' => $cartItem->id,
                    'product_name' => $product->name,
                    'product_slug' => $product->slug,
                    'product_image' => asset('images/products/thumb/' . ($product->primaryImage->image ?? 'no-image.png')),
                    'product_brand_name' => $product->brand->name,
                    'base_price' => $product->base_price,
                    'discount_percentage' => $product->discount_percentage,
                    'discounted_price' => $product->discounted_price,
                    'final_price' => $product->final_price,
                    'stock' => $product->stock,
                    'user_cart_quantity' => $cartItem->quantity,
                ];
            })
            ->values();
    }

    private function orderPayload(User $user)
    {
        return $user->orders()
            ->with('orderItems.product.brand', 'orderItems.product.primaryImage')
            ->get()
            ->map(function ($order) {

                return [
                    'order_id' => $order->order_no,
                    'order_date' => $order->created_at->format('Y-m-d h:i A'),
                    'payment_method' => $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Online Payment',
                    'payment_status' => $order->payment_status,
                    'order_status' => $order->order_status,
                    'order_subtotal' => $order->subtotal,
                    'order_discounted_price' => $order->discounted_price,
                    'tax_amount' => $order->tax_amount,
                    'grand_total' => $order->grand_total,
                    'totat_items' => $order->orderItems->count(),

                    'order_items' => $order->orderItems->map(function ($item) {

                        $product = $item->product;

                        return [
                            'order_item_price' => $item->price,
                            'order_item_quantity' => $item->quantity,
                            'order_item_total' => $item->total,

                            'product_name' => $product?->name,
                            'product_slug' => $product?->slug,
                            'product_image' => asset('images/products/thumb/' . ($product?->primaryImage->image ?? 'no-image.png')),
                            'product_brand_name' => $product?->brand?->name,
                            'base_price' => $product?->base_price,
                            'discount_percentage' => $product?->discount_percentage,
                            'discounted_price' => $product?->discounted_price,
                            'final_price' => $product?->final_price,
                        ];
                    })->values(),
                ];
            })
            ->values();
    }
}
