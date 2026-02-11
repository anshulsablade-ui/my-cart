<?php

namespace App\Services;

use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\SystemMessage;

class AIService
{
    public function reply(array $messages): string
    {
        $prismMessages = [];

        // System instruction for your chatbot
        $prismMessages[] = new SystemMessage(
            "You are an AI shopping assistant for an ecommerce website.

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
            "
        );

        foreach ($messages as $m) {

            if ($m['role'] === 'assistant') {
                $prismMessages[] = new AssistantMessage($m['content']);
            } else {
                $prismMessages[] = new UserMessage($m['content']);
            }
        }

        $response = Prism::text()
            ->using(Provider::OpenAI, 'gpt-4o-mini')
            ->withMessages($prismMessages)
            ->generate();

        return trim($response->text);
    }
}
