<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CesantiaDenegada extends Mailable
{
    use Queueable, SerializesModels;

    public $justificacion;

    public function __construct($justificacion)
    {
        $this->justificacion = $justificacion;
    }

    public function build()
    {
        return $this->view('emails.cesantia_denegada')
                    ->with([
                        'justificacion' => $this->justificacion,
                    ]);
    }
}

