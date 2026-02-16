<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Clase para el envío de notificaciones por correo electrónico.
 * Se encarga de pasar los datos a la vista y definir el asunto.
 */
class NotificacionCrudRestaurante extends Mailable
{
    use Queueable, SerializesModels;

    // Variables públicas: Se pasan automáticamente a la vista del correo
    public $tipoAccion;      // 'crear', 'editar' o 'eliminar'
    public $datosRestaurante; // Objeto con los datos del restaurante
    public $usuarioAutor;     // Usuario que realizó la acción

    /**
     * Constructor: Recibe los datos al crear una nueva notificación.
     * 
     * @param string $tipoAccion Tipo de operación realizada
     * @param object $datosRestaurante Información del restaurante
     * @param object $usuarioAutor (Opcional) Usuario que hizo el cambio
     */
    public function __construct($tipoAccion, $datosRestaurante, $usuarioAutor = null)
    {
        $this->tipoAccion = $tipoAccion;
        $this->datosRestaurante = $datosRestaurante;
        $this->usuarioAutor = $usuarioAutor;
    }

    /**
     * Define el "sobre" del correo (Asunto y remitente).
     */
    public function envelope(): Envelope
    {
        // Definimos un asunto descriptivo según la acción
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

    /**
     * Define el contenido del correo (Vista HTML).
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notificacion-crud-restaurante',
        );
    }

    /**
     * Adjuntos del correo (vacío en este caso).
     */
    public function attachments(): array
    {
        return [];
    }
}
