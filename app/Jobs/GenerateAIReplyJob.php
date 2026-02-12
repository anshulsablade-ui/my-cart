<?php

namespace App\Jobs;

use App\Services\AIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateAIReplyJob implements ShouldQueue
{
    public function handle()
    {
        $reply = app(AIService::class)
                    ->reply($this->message->body);

        $aiMessage = Message::create([
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => config('chat.ai_user_id'),
            'body' => $reply,
        ]);

        broadcast(new NewMessage($aiMessage));
    }
}

