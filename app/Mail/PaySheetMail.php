<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaySheetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $summary;

    public function __construct($user, $summary)
    {
        $this->user = $user;
        $this->summary = $summary;
    }

    public function build()
    {
        return $this->subject('Paysheet for ' . $this->summary['month'])
                    ->markdown('emails.paysheet');
    }
}
