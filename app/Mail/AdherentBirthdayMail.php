<?php

namespace App\Mail;

use App\Models\Adherent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdherentBirthdayMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Adherent $adherent, public int $age)
    {
    }

    public function build()
    {
        return $this->subject('🎂 Joyeux Anniversaire !')
            ->view('emails.adherent.birthday')
            ->with([
                'adherent' => $this->adherent,
                'age' => $this->age,
            ]);
    }
}
