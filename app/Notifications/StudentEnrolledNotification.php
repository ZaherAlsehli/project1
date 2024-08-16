<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class StudentEnrolledNotification extends Notification
{
    use Queueable;

    protected $student;
    protected $course;

    /**
     * Create a new notification instance.
     */
    public function __construct($student, $course)
    {
        $this->student = $student;
        $this->course = $course;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "Student {$this->student->name} has enrolled in your course: {$this->course->title}.",
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "Student {$this->student->name} has enrolled in your course: {$this->course->title}.",
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);
    }
}
