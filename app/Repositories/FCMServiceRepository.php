<?php

namespace App\Repositories;

use App\Interfaces\FCMServiceInterface;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class FCMServiceRepository implements FCMServiceInterface
{
    public function sendFCMPushNotification($user, $title = null, $body = null)
    {
        try {
            $fcmToken = $user->fcm_token;
            Log::channel('fcm_notify')->info("{$user->uuid} - Start Sending notification.");
            if (!is_null($fcmToken)) {
                $fcmUrl = config('constant.fcm.fcm_url');
                $serverKey = config('constant.fcm.fcm_server_key');

                $data = [
                    "to" => $fcmToken,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                    ],
                ];

                $headers = [
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type' => 'application/json',
                ];

                $response = Http::withHeaders($headers)->post($fcmUrl, $data);
                Log::channel('fcm_notify')->info("{$user->uuid} - Notification sent successful.");
                return $response->json();
            } else {
                Log::channel('fcm_notify')->error("{$user->uuid} - FCM token is null.");
            }
        } catch (Exception $e) {
            Log::channel('fcm_notify')->error("Error : " . $e->getMessage());
        }
    }
}
