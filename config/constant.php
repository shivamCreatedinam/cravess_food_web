<?php
return [
    "restaurant_portal_url" => env("RESTAURANT_PORTAL_URL"),
    "fcm" => [
        "fcm_url" => env('FCM_URL', 'https://fcm.googleapis.com/fcm/send'),
        "fcm_server_key" => env("FCM_SERVER_KEY"),
    ],

];
