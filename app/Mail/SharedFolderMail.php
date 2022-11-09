<?php

namespace App\Mail;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SharedFolderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user1;
    public $user2;
    public $folder;

    /**
     * Create a new message instance.
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.shared-folder-mail');
    }
}
