<?php

namespace App\Traits\Notifications;

class FirebaseNotification
{
    public function sendPushNotification($notification, $notificationKey, $user, $title, $body)
    {
        $token = 'AAAAPRMsxW8:APA91bEy2IxbBmgsXdPIw_tnf95bOtN8XI4rMU_SOUjbP1EGo2pCNvJ3LE5Yo8rgR5-7kUvnnf7lA3rxSNjvq56PPYuySZA7-oulbynmx7lERVbDOpvZOWcffW-J0P_blNcuEWNAT345';
        $from = 'AIzaSyBwH2ZJh_-ezlR0aw_Y29wS9TQzMYHMF-I';
        $msg = [
            'user' => $user,
            'body' => $body,
            'title' => $title,
            'key' => $notificationKey,
            'property' => $notification,
            'icon' => 'https://image.flaticon.com/icons/png/512/270/270014.png', /*Default Icon*/
            'sound' => 'mySound', /*Default sound*/
            'id' => $notification->id,
            'type_class' => get_class($notification),
        ];

        $fields = [
            'to' => $token,
            'notification' => $msg,
        ];

        $headers = [
            'Authorization: key='.$token,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        ($result);
        curl_close($ch);
    }
}
