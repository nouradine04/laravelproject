<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommandePayee extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;
    public $pdf;

    public function __construct($commande, $pdf)
    {
        $this->commande = $commande;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Votre commande chez ISI BURGER')
                    ->view('emails.commande_payee')
                    ->attachData($this->pdf, 'facture.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}