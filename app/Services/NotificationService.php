<?php

namespace App\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = app('firebase.messaging');
    }

    public function sendToToken(string $token, string $title, string $body): void
    {
        try {
            $message = CloudMessage::fromArray([
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
            ]);

            $this->messaging->send($message);
        } catch (\Exception $e) {
            Log::error('FCM send error: ' . $e->getMessage());
        }
    }

    public function sendToMultiple(array $tokens, string $title, string $body): void
    {
        if (empty($tokens)) return;

        try {
            $messages = array_map(function ($token) use ($title, $body) {
                return CloudMessage::fromArray([
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                ]);
            }, $tokens);

            $this->messaging->sendAll($messages);
        } catch (\Exception $e) {
            Log::error('FCM multicast error: ' . $e->getMessage());
        }
    }
}