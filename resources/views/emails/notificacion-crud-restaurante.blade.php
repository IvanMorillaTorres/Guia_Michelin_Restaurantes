<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación - Guía Michelin</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <div style="background-color: #f4f4f4; padding: 20px;">
        <div style="background-color: white; padding: 20px; border-radius: 5px; max-width: 600px; margin: 0 auto;">
            
            <!-- TÍTULO DINÁMICO -->
            <h1 style="color: #d32f2f; border-bottom: 2px solid #d32f2f; padding-bottom: 10px;">
                @if($tipoAccion === 'crear')
                    Nuevo Restaurante Creado
                @elseif($tipoAccion === 'editar')
                    Restaurante Actualizado
                @elseif($tipoAccion === 'eliminar')
                    Restaurante Eliminado
                @endif
            </h1>

            <p>Se ha registrado un cambio en el panel de administración de la Guía Michelin.</p>

            <!-- DETALLES DEL RESTAURANTE -->
            <h3>Detalles del restaurante:</h3>
            <ul style="list-style-type: none; padding: 0;">
                <li style="margin-bottom: 10px;">
                    <strong>Nombre:</strong> {{ $datosRestaurante->nombre_restaurante }}
                </li>

                {{-- Solo mostramos detalles extra si NO es una eliminación --}}
                @if($tipoAccion !== 'eliminar')
                    <li style="margin-bottom: 10px;">
                        <strong>Teléfono:</strong> {{ $datosRestaurante->telefono_restaurante ?? 'No indicado' }}
                    </li>
                    <li style="margin-bottom: 10px;">
                        <strong>Precio medio:</strong> {{ $datosRestaurante->precio_restaurante ? $datosRestaurante->precio_restaurante . ' €' : 'No indicado' }}
                    </li>
                    <li style="margin-bottom: 10px;">
                        <strong>Valoración:</strong> {{ $datosRestaurante->valoracion_restaurante ? $datosRestaurante->valoracion_restaurante . ' ⭐' : 'Sin valorar' }}
                    </li>
                    <li style="margin-bottom: 10px;">
                        <strong>Ciudad:</strong> {{ $datosRestaurante->ciudad->nombre_ciudad ?? 'No asignada' }}
                    </li>
                    <li style="margin-bottom: 10px;">
                        <strong>Web:</strong> 
                        <a href="{{ $datosRestaurante->web_real_restaurante }}" style="color: #d32f2f;">Visitar web</a>
                    </li>
                @endif
            </ul>

            <!-- INFORMACIÓN DEL USUARIO (SI EXISTE) -->
            @if($usuarioAutor)
                <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px;">
                    <p><strong>Realizado por:</strong> {{ $usuarioAutor->nombre ?? $usuarioAutor->email }}</p>
                    <p style="font-size: 12px; color: #777;">Fecha: {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            @endif

        </div>
        
        <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
            Este es un correo automático. Por favor, no respondas a este mensaje.
        </div>
    </div>

</body>
</html>
