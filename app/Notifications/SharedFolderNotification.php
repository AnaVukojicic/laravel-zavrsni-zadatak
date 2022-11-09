<?php

namespace App\Notifications;

use App\Mail\SharedFileMail;
use App\Mail\SharedFolderMail;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SharedFolderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user1;
    public $user2;
    public $folder;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user1, User $user2, Folder $folder)
    {
        $this->folder = $folder;
        $this->user2 = $user2;
        $this->user1 = $user1;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SharedFolderMail
     */
    public function toMail($notifiable)
    {
        return (new SharedFolderMail($this->user1,$this->user2,$this->folder))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
