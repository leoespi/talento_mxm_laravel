<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CesantiaAprobada extends Mailable
{
    use Queueable, SerializesModels;

    public $justificacion;

   

    public function build()
    {
        return $this->view('emails.cesantia_aprobada')
                    ->with([
                       


                        'justificacion' => $this->justificacion,
                    ]);
    }
}
