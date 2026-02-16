<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación CRUD - Guía Michelin</title>
    <style>
        /* Estilos generales del correo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        /* Contenedor principal del correo */
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        /* Cabecera del correo con color según la acción */
        .email-header {
            padding: 30px;
            text-align: center;
            color: white;
        }
        /* Color verde para creación */
        .email-header.crear {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        /* Color azul para edición */
        .email-header.editar {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        /* Color rojo para eliminación */
        .email-header.eliminar {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        /* Cuerpo del correo */
        .email-body {
            padding: 30px;
            color: #333333;
        }
        .email-body h2 {
            color: #333333;
            margin-top: 0;
        }
        /* Tabla de información del restaurante */
        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #eeeeee;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #666666;
        }
        /* Pie del correo */
        .email-footer {
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
            color: #999999;
            font-size: 12px;
        }
        /* Etiqueta de acción */
        .action-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .action-badge.crear {
            background-color: #4CAF50;
            color: white;
        }
        .action-badge.editar {
            background-color: #2196F3;
            color: white;
        }
        .action-badge.eliminar {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Contenedor principal del correo -->
    <div class="email-container">
        
        <!-- Cabecera con color según la acción (crear/editar/eliminar) -->
        <div class="email-header {{ $accion }}">
            <h1>
                @if($accion === 'crear')
                    ✅ Nuevo Restaurante Creado
                @elseif($accion === 'editar')
                    ✏️ Restaurante Actualizado
                @elseif($accion === 'eliminar')
                    🗑️ Restaurante Eliminado
                @endif
            </h1>
        </div>

        <!-- Cuerpo del correo con la información -->
        <div class="email-body">
            <!-- Etiqueta de la acción realizada -->
            <span class="action-badge {{ $accion }}">
                {{ strtoupper($accion) }}
            </span>

            <h2>Detalles de la operación</h2>
            
            <p>
                Se ha realizado una operación de 
                <strong>{{ $accion }}</strong> 
                en el panel de administración de restaurantes.
            </p>

            <!-- Tabla con la información del restaurante -->
            <table class="info-table">
                @if($accion !== 'eliminar')
                    <!-- Si no es eliminación, mostramos todos los datos actuales -->
                    <tr>
                        <td>ID del Restaurante:</td>
                        <td>{{ $restaurante->id_restaurante ?? 'N/A' }}</td>
                    </tr>
                @endif
                
                <tr>
                    <td>Nombre:</td>
                    <td><strong>{{ $restaurante->nombre_restaurante ?? $restaurante['nombre_restaurante'] ?? 'N/A' }}</strong></td>
                </tr>

                @if($accion !== 'eliminar')
                    <tr>
                        <td>Teléfono:</td>
                        <td>{{ $restaurante->telefono_restaurante ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Precio:</td>
                        <td>{{ $restaurante->precio_restaurante ? number_format($restaurante->precio_restaurante, 2) . ' €' : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Valoración:</td>
                        <td>{{ $restaurante->valoracion_restaurante ? $restaurante->valoracion_restaurante . ' ⭐' : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Ciudad:</td>
                        <td>{{ $restaurante->ciudad->nombre_ciudad ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Web:</td>
                        <td>
                            @if($restaurante->web_real_restaurante)
                                <a href="{{ $restaurante->web_real_restaurante }}" target="_blank">
                                    {{ $restaurante->web_real_restaurante }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endif
            </table>

            <!-- Información del usuario que realizó la acción (si está disponible) -->
            @if($usuario)
                <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eeeeee;">
                    <strong>Realizado por:</strong><br>
                    {{ $usuario->nombre ?? 'Usuario' }} {{ $usuario->apellidos ?? '' }}<br>
                    <small style="color: #999;">{{ $usuario->email ?? '' }}</small>
                </p>
            @endif

            <!-- Fecha y hora de la operación -->
            <p style="margin-top: 20px; color: #999; font-size: 14px;">
                <strong>Fecha y hora:</strong> {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>

        <!-- Pie del correo -->
        <div class="email-footer">
            <p>
                Este es un correo automático generado por el sistema de administración.<br>
                <strong>Guía Michelin - Panel de Administración</strong>
            </p>
            <p style="margin-top: 10px;">
                Por favor, no responda a este correo.
            </p>
        </div>

    </div>
</body>
</html>
