<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationPaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment_url;

    /**
     * Create a new message instance.
     */
    public function __construct($payment_url)
    {
        $this->payment_url = $payment_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $payment_url = $this->payment_url;
        return $this->view('email.donation_payment', compact('payment_url'))->subject('Reminder: Donate to Support Our Project');
    }
}
