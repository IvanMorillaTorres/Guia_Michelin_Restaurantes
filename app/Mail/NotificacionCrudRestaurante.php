<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Clase para enviar notificaciones por correo electrónico
 * cuando se realizan operaciones CRUD en el panel de administración
 */
class NotificacionCrudRestaurante extends Mailable
{
    use Queueable, SerializesModels;

    // Propiedades públicas que estarán disponibles en la vista del correo
    public $accion;           // Tipo de acción: 'crear', 'editar', 'eliminar'
    public $restaurante;      // Datos del restaurante afectado
    public $usuario;          // Usuario que realizó la acción

    /**
     * Constructor de la clase
     * 
     * @param string $accion - Tipo de operación realizada ('crear', 'editar', 'eliminar')
     * @param mixed $restaurante - Objeto o array con los datos del restaurante
     * @param mixed $usuario - Usuario que realizó la acción (opcional)
     */
    public function __construct($accion, $restaurante, $usuario = null)
    {
        // Asignamos los datos que se pasarán a la vista del correo
        $this->accion = $accion;
        $this->restaurante = $restaurante;
        $this->usuario = $usuario;
    }

    /**
     * Define el sobre del correo (asunto, remitente, etc.)
     * 
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        // Determinamos el asunto del correo según la acción realizada
        $asuntos = [
            'crear' => '✅ Nuevo restaurante creado',
            'editar' => '✏️ Restaurante actualizado',
            'eliminar' => '🗑️ Restaurante eliminado',
        ];

        // Obtenemos el asunto correspondiente o usamos uno por defecto
        $asunto = $asuntos[$this->accion] ?? 'Notificación de cambio en restaurante';

        // Retornamos el sobre con el asunto configurado
        return new Envelope(
            subject: $asunto,
        );
    }

    /**
     * Define el contenido del correo (vista y datos)
     * 
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        // Retornamos el contenido usando una vista Blade
        // La vista recibirá automáticamente las propiedades públicas de esta clase
        return new Content(
            view: 'emails.notificacion-crud-restaurante',
        );
    }

    /**
     * Obtiene los archivos adjuntos del mensaje (opcional)
     * 
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // No enviamos archivos adjuntos en este caso
        return [];
    }
}
