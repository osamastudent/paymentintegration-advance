<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;


class Orders extends Mailable
{
    use Queueable, SerializesModels;
public $data;
    /**
     * Create a new message instance.
     */
    public function __construct($request)
    {
        $this->data=$request;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // subject: 'Orders',
            from:new Address('osamajanab9999@gmail.com','osama janab'),
            subject:$this->data->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'post.send-data-view',with:['name'=>$this->data->name,'subject'=>$this->data->subject,'body'=>$this->data->body],

        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array {
        
        foreach($this->data->myfiles as $file){
            $Attachment[]=Attachment::fromPath($file->path())
            ->as($file->getClientOriginalName())
            ->withMime($file->getClientMimeType());
            }
            
            return $Attachment;
    }

        // for single file
        // return [
        //     Attachment::fromPath($this->data->myfiles->path())
        //     ->as($this->data->myfiles->getClientOriginalName())
        //     ->withMime($this->data->myfiles->getClientMimeType()),
        // ];
    }
    

