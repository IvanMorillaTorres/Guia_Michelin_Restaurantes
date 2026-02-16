# Configuración de Notificaciones por Correo Electrónico

## 📧 Descripción
Este sistema envía automáticamente correos electrónicos a `459406.joan23@fje.edu` cuando se realizan operaciones CRUD (Crear, Editar, Eliminar) en el panel de administración de restaurantes.

## ⚙️ Configuración Necesaria

### 1. Configurar Office 365 (Outlook) para enviar correos

Para que Laravel pueda enviar correos a través de tu cuenta del colegio (@fje.edu), solo necesitas tu **correo y contraseña normales**.

#### ✅ Ventajas de Office 365:
- No necesitas crear contraseñas de aplicación
- Usas tu contraseña normal del colegio
- Configuración más sencilla que Gmail

#### Requisitos:
- Tener una cuenta activa del colegio (ejemplo: `459406.joan23@fje.edu`)
- Conocer tu contraseña de acceso al correo del colegio

### 2. Configurar el archivo `.env`

Abre el archivo `.env` en la raíz del proyecto y actualiza las siguientes líneas:

```env
# Configuración de correo electrónico - Office 365 (Outlook)
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=459406.joan23@fje.edu          # ← Cambia esto por TU correo del colegio
MAIL_PASSWORD=tu_contraseña_del_colegio      # ← Pon tu contraseña NORMAL del colegio
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="459406.joan23@fje.edu"    # ← Debe ser el MISMO correo que MAIL_USERNAME
MAIL_FROM_NAME="Guía Michelin - Notificaciones Admin"
```

**Ejemplo real**:
```env
MAIL_USERNAME=100006512.joan23@fje.edu
MAIL_PASSWORD=MiContraseñaDelCole123
MAIL_FROM_ADDRESS="100006512.joan23@fje.edu"
```

⚠️ **IMPORTANTE**: 
- `MAIL_FROM_ADDRESS` debe ser **exactamente el mismo** que `MAIL_USERNAME`
- Usa tu contraseña normal (NO necesitas contraseña de aplicación como en Gmail)

### 3. Verificar la configuración

Después de configurar el `.env`, **reinicia el servidor de Laravel**:

```bash
# Detén el servidor (Ctrl+C en la terminal donde está corriendo)
# Luego vuelve a iniciarlo:
php artisan serve
```

## 🧪 Probar el envío de correos

Para probar que todo funciona correctamente:

1. Accede al panel de administración
2. Crea, edita o elimina un restaurante
3. Verifica que llegue un correo a `459406.joan23@fje.edu`

## 📝 ¿Cómo funciona?

### Archivos modificados/creados:

1. **`.env`** - Configuración del servidor SMTP
2. **`app/Mail/NotificacionCrudRestaurante.php`** - Clase que define el correo
3. **`resources/views/emails/notificacion-crud-restaurante.blade.php`** - Plantilla HTML del correo
4. **`app/Http/Controllers/Admin/AdminRestauranteController.php`** - Controlador modificado para enviar correos

### Flujo de ejecución:

```
Usuario realiza acción CRUD
    ↓
Controlador guarda/actualiza/elimina en BD
    ↓
Controlador llama a Mail::to()->send()
    ↓
Laravel crea el correo usando NotificacionCrudRestaurante
    ↓
Laravel renderiza la vista Blade con los datos
    ↓
Laravel envía el correo vía SMTP de Gmail
    ↓
Correo llega a 459406.joan23@fje.edu
```

### Código clave en el controlador:

```php
// Ejemplo del método guardar()
Mail::to('459406.joan23@fje.edu')->send(
    new NotificacionCrudRestaurante(
        'crear',                    // Tipo de acción
        $restaurante->load('ciudad'), // Datos del restaurante
        auth()->user()              // Usuario que realizó la acción
    )
);
```

## 🎨 Contenido del correo

El correo incluye:

- ✅ **Asunto dinámico** según la acción (crear/editar/eliminar)
- 📊 **Información del restaurante**: nombre, teléfono, precio, valoración, ciudad, web
- 👤 **Usuario que realizó la acción**: nombre y email
- 📅 **Fecha y hora** de la operación
- 🎨 **Diseño responsive** con colores diferenciados por acción

## 🔧 Cambiar el destinatario

Si quieres cambiar el correo destinatario, edita el archivo:
`app/Http/Controllers/Admin/AdminRestauranteController.php`

Busca las líneas que contienen:
```php
Mail::to('459406.joan23@fje.edu')->send(
```

Y cambia el correo por el que desees.

## ⚠️ Solución de problemas

### El correo no llega

1. **Verifica el archivo `.env`**:
   - Asegúrate de que `MAIL_USERNAME` sea tu correo del colegio (@fje.edu)
   - Asegúrate de que `MAIL_PASSWORD` sea tu contraseña correcta
   - Verifica que `MAIL_FROM_ADDRESS` sea **exactamente igual** que `MAIL_USERNAME`
   - Asegúrate de que `MAIL_HOST` sea `smtp.office365.com`

2. **Reinicia el servidor**:
   ```bash
   # Detén el servidor (Ctrl+C)
   php artisan serve
   ```

3. **Revisa los logs de Laravel**:
   ```bash
   # Abre el archivo de logs
   storage/logs/laravel.log
   ```
   Busca mensajes de error relacionados con "mail" o "SMTP"

4. **Verifica la carpeta de spam** del correo destinatario

### Error: "Connection could not be established"

- Verifica que tengas conexión a internet
- Asegúrate de que tu firewall no bloquee el puerto 587
- Verifica que Office 365 no esté bloqueando el acceso
- Comprueba que tu cuenta del colegio esté activa

### Error: "Invalid credentials" o "Authentication failed"

- La contraseña está mal escrita
- Tu cuenta del colegio puede estar bloqueada o desactivada
- Verifica que puedas acceder a tu correo en https://outlook.office365.com
- Asegúrate de que `MAIL_FROM_ADDRESS` sea igual a `MAIL_USERNAME`

### Error: "Sender address rejected"

- `MAIL_FROM_ADDRESS` debe ser **exactamente el mismo** que `MAIL_USERNAME`
- Office 365 no permite enviar correos desde direcciones diferentes a la autenticada

## 📚 Modo de desarrollo (sin enviar correos reales)

Si estás desarrollando y NO quieres enviar correos reales, cambia en el `.env`:

```env
MAIL_MAILER=log  # Los correos se guardarán en storage/logs/laravel.log
```

Luego reinicia el servidor. Los correos no se enviarán, pero se registrarán en el archivo de logs.

## 🔒 Seguridad

- ✅ El archivo `.env` NO debe subirse a Git (ya está en `.gitignore`)
- ✅ La contraseña de aplicación es específica para esta app y puede revocarse en cualquier momento
- ✅ Si el envío de correo falla, la operación CRUD se completa de todas formas (no se interrumpe)
- ✅ Los errores de envío se registran en los logs para debugging

---

**Última actualización**: 13/02/2026
