<?php
namespace App\Interfaces;

interface FCMServiceInterface extends BaseInterface
{
    public function sendFCMPushNotification($user, $title=null, $body=null);
}
