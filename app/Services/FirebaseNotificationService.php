<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(base_path('firebase/firebase-credentials.json'));

        $this->messaging = $factory->createMessaging();
    }

    /**
     * @param array $data Örn: ['post_id' => '2', 'user_id' => '3', 'comment_id' => '7']
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): void
    {
        $notification = Notification::create($title, $body);

        // click_action + diğer dataları birleştir
        $payloadData = array_merge([
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ], collect($data)->mapWithKeys(function ($value, $key) {
            return [$key => (string) $value]; // Tüm verileri string olarak gönder
        })->toArray());

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification)
            ->withData($payloadData); // 🔥 Veri buraya eklendi

        $this->messaging->send($message);
    }
}
