<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public string $messageBody;

    public function __construct(string $subject, string $message)
    {
        $this->subjectLine = $subject;
        $this->messageBody = $message;
    }

    public function build()
    {
        $mailData = [
            'name' => null, // or pass user name dynamically
            'title' => $this->subjectLine,
            'message' => $this->messageBody,
            'dashboardUrl' => url('/'),
        ];

        return $this->subject($this->subjectLine)
            ->markdown('mail.generic_notification')
            ->with([
                'mailData' => $mailData
            ]);
    }
}
