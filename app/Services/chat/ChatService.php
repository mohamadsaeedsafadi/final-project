<?php
namespace App\Services\chat;

use App\Repositories\chat\ConversationRepository;
use App\Repositories\chat\MessageRepository;
use App\Events\MessageSent;

class ChatService
{
    public function __construct(
        protected ConversationRepository $conversationRepo,
        protected MessageRepository $messageRepo
    ) {}

    public function createConversation($request)
    {
        return $this->conversationRepo->create([
            'service_request_id' => $request->id,
            'user_id' => $request->user_id,
            'provider_id' => $request->provider_id
        ]);
    }

    public function sendMessage($conversation, $senderType, $senderId, $message)
    {
        $msg = $this->messageRepo->create([
            'conversation_id' => $conversation->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $message
        ]);

        broadcast(new MessageSent($msg))->toOthers();

        return $msg;
    }

    public function getMessages($conversationId)
    {
        return $this->messageRepo->getByConversation($conversationId);
    }
}