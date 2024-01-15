<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    use Queueable, SerializesModels;
    public $body;
    public $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body, $template, $subject)
    {
        $this->body = $body;
        $this->template = $template;
        $this->locale = 'fr';
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown($this->template)->with('body', $this->body);
    }
}
