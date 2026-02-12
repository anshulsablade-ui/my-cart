<?php

namespace App\Http\Controllers;

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
    public function generate(Request $request)
    {
        // dd(env('OPENAI_API_KEY'));
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openrouter.key'),
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => 'openai/gpt-oss-20b:free',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => 'What is new laravel version?',
                        ],
                    ],
                    'reasoning' => [
                        'enabled' => true
                    ]
                ]);

        return $response->json();
    }

    public function chat(Request $request, AIService $bot)
    {
        $user = User::where('id', session()->get('user.id'))->first();
        $wishlists_products = $user->wishlists()->with('product')->get()
            ->map(function ($wishlist) {
                return [
                    'product_name' => $wishlist->product->name,
                    'product_brand_name' => $wishlist->product->brand->name,
                    'base_price' => $wishlist->product->base_price,
                    'discount_percentage' => $wishlist->product->discount_percentage,
                    'discounted_price' => $wishlist->product->discounted_price,
                    'final_price' => $wishlist->product->final_price,
                    'stock' => $wishlist->product->stock,
                ];
            });
        $cartItems = $user->carts()->with('product')->get()
            ->map(function ($cartItem) {
                return [
                    'product_name' => $cartItem->product->name,
                    'product_brand_name' => $cartItem->product->brand->name,
                    'base_price' => $cartItem->product->base_price,
                    'discount_percentage' => $cartItem->product->discount_percentage,
                    'discounted_price' => $cartItem->product->discounted_price,
                    'final_price' => $cartItem->product->final_price,
                    'stock' => $cartItem->product->stock,
                    'user_cart_quantity' => $cartItem->quantity
                ];
            });
        $orders = $user->orders()->with('orderItems.product.brand')->get()
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
                    'totat_items' => $order->orderItems()->count(),

                    'order_items' => $order->orderItems->map(function ($item) {

                        return [
                            'order_item_price' => $item->price,
                            'order_item_quantity' => $item->quantity,
                            'order_item_total' => $item->total,

                            'product_name' => $item->product?->name,
                            'product_brand_name' => $item->product?->brand?->name,
                            'base_price' => $item->product?->base_price,
                            'discount_percentage' => $item->product?->discount_percentage,
                            'discounted_price' => $item->product?->discounted_price,
                            'final_price' => $item->product?->final_price,
                        ];

                    })->values(),
                ];
            });


        // dd($orders);
        $userDetails = [
            'wishlists_products' => $wishlists_products,
            'cart_items' => $cartItems,
            'orders' => $orders
        ];

        $context = Product::where('name', 'like', '%' . $request->message . '%')->limit(5)->get();
        // $context = $this->buildContext();
        $result = $bot->ask($request->message, $context, json_encode($userDetails));

        return response()->json($result['choices'][0]['message']['content'] ?? 'No response');
    }

    private function buildContext()
    {
        $products = Product::select('name', 'final_price', 'stock', 'description')
            ->limit(30)
            ->get();

        $text = "";

        foreach ($products as $p) {
            $text .= "Product: {$p->name}, price: {$p->final_price}, stock: {$p->stock}, info: {$p->description}\n";
        }

        return $text;
    }

}
