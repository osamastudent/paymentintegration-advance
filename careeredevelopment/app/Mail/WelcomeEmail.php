<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $userValues;
    /**
     * Create a new message instance.
     */
    public function __construct($request,$usersData)
    {
        $this->data=$request;
        $this->userValues=$usersData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from:new Address('osamajanab9999@gmail.com','Osama Janab Markdown'),
            subject: $this->data->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome',with:['name'=>$this->data->name, 'body'=>$this->data->body,$this->userValues],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
