<?php

namespace App\Notifications;

use App\Mail\SharedFileMail;
use App\Models\File;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SharedFileNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user1;
    public $user2;
    public $file;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user1, User $user2, File $file)
    {
        $this->file = $file;
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
     * @return SharedFileMail
     */
    public function toMail($notifiable)
    {
        return (new SharedFileMail($this->user1,$this->user2,$this->file))->to($notifiable->email);
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
