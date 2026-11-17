<?php

namespace App\Notifications;

use App\Broadcasting\NotificationChannel; // 1. Import your corrected custom channel
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ArtWorkNotification extends Notification
{
    use Queueable;

    // Use public properties so they can be accessed within the class or channel
    public $artwork;
    public $stage;

    /**
     * Create a new notification instance.
     * 
     * @param mixed $artwork - The database record payload (AoModel instance)
     * @param string $stage  - 'ao_to_gd', 'gd_to_artist', 'artist_to_gd', etc.
     */
    public function __construct($artwork, string $stage = 'default')
    {
        $this->artwork = $artwork;
        $this->stage = $stage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // 2. Direct Laravel to use your custom channel class instead of the 'database' string
        return [NotificationChannel::class];
    }

    /**
     * Store attributes inside the data column and pass structural tracking IDs.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        
        // Dynamically compute titles, messages, and icons based on the workflow stage
           $uiMetadata = match ($this->stage) {
        // Case 1: Account Officer submits a new file to the Graphic Design Supervisor
        'ao_to_gd' => [
            'Item_code' => $this->artwork->item_code,
            'title'   => 'AO Submit a New Rfa',
            'message' => 'AO Send You A File ' . ($this->artwork->ao_name ?? 'User') . ' uploaded file for Item: ' . $this->artwork->item_code,
            'icon'    => 'fas fa-file-upload text-primary',
        ],

        // Case 2: GD Supervisor routes / assigns the layout work down to an Artist
        'gd_to_artist' => [
            'Item_code' => $this->artwork->item_code,
            'title'   => 'New Artwork Assigned',
            'message' => 'Supervisor assigned artwork for Item: ' . $this->artwork->item_code,
            'icon'    => 'fas fa-paint-brush text-warning',
        ],

        // Case 3: Artist completes layout and sends it back to GD Supervisor for checking
        'artist_to_gd' => [
            'Item_code' => $this->artwork->item_code,
            'title'   => 'Artwork For Review',
            'message' => 'Artist submitted layout for Item: ' . $this->artwork->item_code,
            'icon'    => 'fas fa-check-double text-success',
        ],
      
        // Case 4: Gd confirms the supplied verified form and sends it back to ao for approval
            'gd_to_ao' => [
            'Item_code' => $this->artwork->item_code,
            'title'   => 'Gd Send Back Supplied Verified form',
            'message' => 'Commercial Gd SuperVisor For GD ASSESS The Status of Supplied Verfied form : ' . $this->artwork->item_code,
            'icon'    => 'fas fa-paint-brush text-warning',
        ],

         'ao_to_traffic' => [
            'Item_code' => $this->artwork->item_code,
            'title'   => 'New Artwork Assigned',
            'message' => 'Traffic Controller assigned artwork for Item: ' . $this->artwork->item_code,
            'icon'    => 'fas fa-paint-brush text-warning',
        ],

        // Default fallback if an unexpected stage value is given
        default => [
            'title'   => 'New Artwork Assigned',
            'message' => 'You have been assigned: ' . $this->artwork->item_code,
            'icon'    => 'fas fa-tasks text-info',
        ],
    }; // FIX: Correctly close the match block with };

    return [
        // 3. Add these fields so your NotificationChannel can extract them into their own actual columns
        'recieved_by'    => $notifiable->id, // The recipient user's ID
        'artwork_id' => $this->artwork->id,
        'item_code'  => $this->artwork->item_code,
        'created_by' => auth()->id() ?? null, // Captures whoever triggered the notification

        // UI Display Information (stays clean inside the 'data' payload)
        'title'      => $uiMetadata['title'],
        'message'    => $uiMetadata['message'],
        'icon'       => $uiMetadata['icon'], // FontAwesome icons dynamically matched for AdminLTE
        'url'        => url($this->artwork->file_link ?? ''),
    ];

    }
}
