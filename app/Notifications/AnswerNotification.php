<?php

namespace App\Notifications;

use App\Models\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnswerNotification extends Notification
{
    use Queueable;
    public $thread;

    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'thread_title'=>$this->thread->title,
            'url'=>route('threads.show',$this->thread->id),
            'time'=>now()->format('Y-m-d H:i:s')
        ];
    }
}
