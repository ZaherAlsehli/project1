<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CourseMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $course;

    public function __construct($message, $course)
    {
        $this->message = $message;
        $this->course = $course;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'course_id' => $this->course->id,
            'course_name' => $this->course->name,
        ];
    }
}
