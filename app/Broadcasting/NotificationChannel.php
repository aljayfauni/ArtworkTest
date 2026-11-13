<?php

namespace App\Broadcasting;

use Illuminate\Notifications\Notification;

class NotificationChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send($notifiable, Notification $notification)
    {
        // 1. Get the array data from the notification's toDatabase method
        $data = $notification->toDatabase($notifiable);

        // 2. Save the record using your custom notification model
        return $notifiable->routeNotificationFor('database', $notification)->create([
            'id'              => $notification->id,
            'type'            => get_class($notification),
            'recieved_by'         => $data['recieved_by'] ?? null,
            'item_code'       => $data['item_code'] ?? null,
            'artwork_id'      => $data['artwork_id'] ?? null,
            'created_by'      => $data['created_by'] ?? null,
            'data'            => $data, // Keeps data duplicated in both JSON and your individual columns
            'read_at'         => null,
        ]);
    }
}
