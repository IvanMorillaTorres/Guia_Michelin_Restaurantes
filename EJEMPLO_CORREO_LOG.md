# 📧 Ejemplo de Correo en Modo LOG

Cuando creas/editas/eliminas un restaurante, el correo se guarda en `storage/logs/laravel.log` con este formato:

```
[2026-02-13 18:10:23] local.DEBUG: 
From: Guía Michelin - Notificaciones Admin <459406.joan23@fje.edu>
To: 459406.joan23@fje.edu
Subject: ✅ Nuevo restaurante creado
MIME-Version: 1.0
Date: Thu, 13 Feb 2026 18:10:23 +0100
Message-ID: <abc123@guiamichelin.com>
Content-Type: text/html; charset=utf-8

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificación CRUD - Guía Michelin</title>
</head>
<body>
    <div class="email-container">
        <div class="email-header crear">
            <h1>✅ Nuevo Restaurante Creado</h1>
        </div>
        <div class="email-body">
            <span class="action-badge crear">CREAR</span>
            <h2>Detalles de la operación</h2>
            <p>Se ha realizado una operación de <strong>crear</strong> en el panel de administración.</p>
            
            <table class="info-table">
                <tr>
                    <td>ID del Restaurante:</td>
                    <td>42</td>
                </tr>
                <tr>
                    <td>Nombre:</td>
                    <td><strong>El Celler de Can Roca</strong></td>
                </tr>
                <tr>
                    <td>Teléfono:</td>
                    <td>972222157</td>
                </tr>
                <tr>
                    <td>Precio:</td>
                    <td>180.00 €</td>
                </tr>
                <tr>
                    <td>Valoración:</td>
                    <td>4.9 ⭐</td>
                </tr>
                <tr>
                    <td>Ciudad:</td>
                    <td>Girona</td>
                </tr>
                <tr>
                    <td>Web:</td>
                    <td><a href="https://cellercanroca.com">https://cellercanroca.com</a></td>
                </tr>
            </table>
            
            <p style="margin-top: 30px;">
                <strong>Realizado por:</strong><br>
                Joan Administrador<br>
                <small style="color: #999;">459406.joan23@fje.edu</small>
            </p>
            
            <p style="margin-top: 20px; color: #999; font-size: 14px;">
                <strong>Fecha y hora:</strong> 13/02/2026 18:10:23
            </p>
        </div>
        <div class="email-footer">
            <p>Este es un correo automático generado por el sistema de administración.</p>
            <p><strong>Guía Michelin - Panel de Administración</strong></p>
        </div>
    </div>
</body>
</html>
```

## 🔍 Cómo Verificar que Funciona

1. **Realiza una operación CRUD** (crear/editar/eliminar un restaurante)
2. **Abre el archivo**: `storage/logs/laravel.log`
3. **Ve al final del archivo** (última entrada)
4. **Busca**: `Subject: ✅ Nuevo restaurante creado` (o editar/eliminar)
5. **Verás todo el HTML** del correo con los datos del restaurante

## 📝 Tipos de Correos

### Crear Restaurante
```
Subject: ✅ Nuevo restaurante creado
```

### Editar Restaurante
```
Subject: ✏️ Restaurante actualizado
```

### Eliminar Restaurante
```
Subject: 🗑️ Restaurante eliminado
```

---

**Esto demuestra que el sistema funciona correctamente**, solo que los correos se guardan en el log en lugar de enviarse por culpa de la restricción de Office 365.
