<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\ValueObjects\ProviderTool;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Illuminate\Support\Facades\Log;

class AIChatController extends Controller
{
    // public function chat(Request $request)
    // {
    //     $user = $request->user();
    //     $message = $request->message;

    //     // Build ecommerce context (products / orders etc)
    //     $context = app(\App\Services\EcommerceAIContext::class)
    //                     ->build($user, $message);

    //     $reply = app(\App\Services\AIService::class)
    //                 ->ecommerceReply([
    //                     [
    //                         'role'    => 'user',
    //                         'content' => $message
    //                     ]
    //                 ], $context);

    //     return response()->json([
    //         'reply' => $reply
    //     ]);
    // }

    public function generate(Request $request)
    {
        $response = Prism::text()
            ->using(Provider::Gemini, 'gemini-2.0-flash')
            ->withPrompt('What is the stock price of Google right now?')
            ->withProviderTools([
                    new ProviderTool('google_search')
                ])
            ->asText();
            return response()->json(['data' => $response]);


        try {
            $response = Prism::text()
                ->using('openai', 'gpt-5')
                ->withPrompt('Write a PHP function to implement a binary search algorithm with proper error handling')
                ->asText();

            // success
            return response()->json(['ai' => $response]);
        } catch (PrismRateLimitedException $e) {
            Log::warning('AI rate limit hit', [
                'provider' => 'openai',
                'message' => $e->getMessage(),
                'rateLimits' => $e->rateLimits,
                'retryAfter' => $e->retryAfter,
            ]);

            // Option A: User-friendly message
            return response()->json(['ai' => 'AI is busy right now (rate limit). Please try again in 1–2 minutes.', 'error' => $e]);
        } catch (\Exception $e) {
            Log::error('AI request failed', ['error' => $e->getMessage()]);
            return response()->json(['ai' => 'Something went wrong with AI. Please try later.']);
        }

    }
}
