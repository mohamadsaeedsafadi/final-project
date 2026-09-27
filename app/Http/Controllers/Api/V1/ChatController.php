<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\chat\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(protected ChatService $chatService) {}

    public function send(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required'
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        if (auth('user')->check()) {
            $senderType = 'user';
            $senderId = auth('user')->id();
        } elseif (auth('provider')->check()) {
            $senderType = 'provider';
            $senderId = auth('provider')->id();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // حماية
        if (
            ($senderType == 'user' && $conversation->user_id != $senderId) ||
            ($senderType == 'provider' && $conversation->provider_id != $senderId)
        ) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $msg = $this->chatService->sendMessage(
            $conversation,
            $senderType,
            $senderId,
            $request->message
        );

        return response()->json($msg);
    }

    public function messages($conversationId)
    {
        return response()->json(
            $this->chatService->getMessages($conversationId)
        );
    }
}