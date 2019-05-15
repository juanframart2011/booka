<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $info )
    {
        $this->data = $info;
        $this->url = env( "APP_URL" );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->recovery();
    }

    #Función para recuperación de contraseña
    private function recovery(){

        $data["data"] = $this->data;
        $data["url"] = $this->url;
        
        return $this->subject( "Recuperación de Contraseña" )->view( 'mail.recovery', $data );
    }
}