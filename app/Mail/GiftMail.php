<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GiftMail extends Mailable
{
    use Queueable, SerializesModels;
    public $template_url;
    public $recipient_name;
    public $sender_name;
    public $projectName;

    /**
     * Create a new message instance.
     */
    public function __construct($gift)
    {
        $this->template_url = config('app.dashboard') . $gift->giftTemplate?->original_image;
        $this->recipient_name =   $gift->recipient_name;
        $this->sender_name =   $gift->sender_name;
        $this->projectName =   $gift->project->name;

    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template_url = $this->template_url;
        $recipient_name = $this->template_url;
        $sender_name = $this->template_url;
        $projectName = $this->projectName;
        return $this->view('email.gift_template', compact('template_url','sender_name','recipient_name','projectName'))->subject('هدية مقدمه من  '.$this->sender_name);
    }
}
