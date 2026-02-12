<?php

namespace App\Services;

use Auth;
use Illuminate\Support\Facades\Http;

class AIService
{
    public function ask(string $message, string $context, string $userDetails)
    {
        $user = "";
        if (Auth::check()) {
            $user .= "Login user email is " . auth()->user()->email . ".";
        }
        $systemPrompt = "You are Selvia, an AI shopping assistant for an ecommerce website.

                        Your name is Selvia.
                        My ecommerce website name is " . config('app.name') . ".
                        You are chatting with the user: " . $user . "
                        
                        Rules:
                        - Only use the data provided by the system.
                        - Never guess prices, stock, delivery time or policies.
                        - If information is missing, say you do not have that information.
                        - Be short, friendly and clear.
                        - You can help with:
                          - product questions
                          - order status
                          - delivery
                          - returns
                          - recommendations
                        ";


        return Http::withToken(config('services.openrouter.key'))
            ->acceptJson()
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openai/gpt-oss-20b:free',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'system',
                        'content' => "Store data:\n" . $context,
                    ],
                    [
                        'role' => 'system',
                        'content' => "User data:\n" . $userDetails,
                    ],
                    [
                        'role' => 'user',
                        'content' => $message,
                    ],
                ],
                'temperature' => 0.2,
            ])
            ->json();
    }
}
