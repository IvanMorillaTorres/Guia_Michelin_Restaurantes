<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// clase pa mandar correos cuando se toca algo de restaurantes
class NotificacionCrudRestaurante extends Mailable
{
    use Queueable, SerializesModels; // permite enviar el correo en segundo plano con colas

    // variables publicas: se pasan solas a la vista del correo
    public $tipoAccion;      // crear, editar o eliminar
    public $datosRestaurante; // datos del restaurante
    public $usuarioAutor;     // quien hizo la accion

    // constructor: recibe los datos
    public function __construct($tipoAccion, $datosRestaurante, $usuarioAutor = null)
    {
        $this->tipoAccion = $tipoAccion;
        $this->datosRestaurante = $datosRestaurante;
        $this->usuarioAutor = $usuarioAutor;
    }

    // definimos el asunto del correo
    public function envelope(): Envelope
    {
        // ponemos un asunto segun la accion (match es como switch pero mas limpio)
        $asunto = match ($this->tipoAccion) {
            'crear' => 'Nuevo restaurante creado',
            'editar' => 'Restaurante actualizado',
            'eliminar' => 'Restaurante eliminado',
            default => 'Notificación de Restaurante',
        };

        return new Envelope(
            subject: $asunto,
        );
    }

    // aqui indicamos que vista blade usamos para el cuerpo del correo
    public function content(): Content
    {
        return new Content(
            view: 'emails.notificacion-crud-restaurante',
        );
    }

    // archivos adjuntos (no mandamos ninguno)
    public function attachments(): array
    {
        return [];
    }
}
