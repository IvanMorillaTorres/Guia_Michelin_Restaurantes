# ⚠️ Problema con Office 365 - SMTP Deshabilitado

## 🔴 El Problema

Office 365 del colegio tiene **deshabilitada la autenticación SMTP** por seguridad. Este es el error que aparece en los logs:

```
SmtpClientAuthentication is disabled for the Tenant
```

Esto significa que el administrador de IT del colegio ha bloqueado el envío de correos vía SMTP para todas las cuentas @fje.edu.

---

## ✅ Solución Actual: Modo LOG

He configurado el sistema para usar **modo LOG**. Esto significa que:

- ✅ **Los correos NO se envían realmente**
- ✅ **Se guardan en el archivo de logs** (`storage/logs/laravel.log`)
- ✅ **Puedes ver el contenido completo del correo** en el log
- ✅ **Perfecto para desarrollo y pruebas**

### Cómo ver los correos en modo LOG:

1. Realiza una operación CRUD (crear/editar/eliminar restaurante)
2. Abre el archivo: `storage/logs/laravel.log`
3. Busca al final del archivo
4. Verás el HTML completo del correo que se habría enviado

**Ejemplo de lo que verás en el log:**
```
[2026-02-13 18:10:00] local.DEBUG: 
From: Guía Michelin - Notificaciones Admin <459406.joan23@fje.edu>
To: 459406.joan23@fje.edu
Subject: ✅ Nuevo restaurante creado

<!DOCTYPE html>
<html>
...contenido del correo...
</html>
```

---

## 🔧 Soluciones Alternativas

### Opción 1: Pedir al administrador que habilite SMTP (Recomendado para producción)

El administrador de IT del colegio debe:

1. Ir al **Centro de administración de Microsoft 365**
2. Navegar a: **Usuarios activos** > **Tu cuenta**
3. En la pestaña **Correo**, habilitar **Autenticación SMTP**

**Enlace de referencia**: https://aka.ms/smtp_auth_disabled

⚠️ **Nota**: Esto requiere permisos de administrador que probablemente no tienes.

### Opción 2: Usar Gmail (Alternativa personal)

Si tienes una cuenta de Gmail personal, puedes usarla:

1. Crea una contraseña de aplicación en Google: https://myaccount.google.com/apppasswords
2. Edita el `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=tu_gmail@gmail.com
   MAIL_PASSWORD=contraseña_de_aplicacion_generada
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="tu_gmail@gmail.com"
   ```
3. Reinicia el servidor

### Opción 3: Usar Mailtrap (Para desarrollo)

Mailtrap es un servicio gratuito para probar correos sin enviarlos realmente:

1. Crea una cuenta en: https://mailtrap.io (gratis)
2. Obtén las credenciales SMTP
3. Edita el `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=tu_usuario_mailtrap
   MAIL_PASSWORD=tu_password_mailtrap
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="test@example.com"
   ```
4. Reinicia el servidor
5. Ve a https://mailtrap.io/inboxes para ver los correos

### Opción 4: Mantener modo LOG (Actual)

- ✅ **Ventaja**: Funciona inmediatamente, sin configuración extra
- ✅ **Ventaja**: Puedes ver el contenido completo del correo
- ❌ **Desventaja**: No se envían correos reales

---

## 📝 Configuración Actual

En este momento, el sistema está configurado en **modo LOG**:

```env
MAIL_MAILER=log  # Los correos se guardan en storage/logs/laravel.log
```

### Para cambiar a otro modo:

1. Edita el archivo `.env`
2. Cambia `MAIL_MAILER=log` por `MAIL_MAILER=smtp`
3. Configura las credenciales correctas (Gmail, Mailtrap, etc.)
4. Reinicia el servidor: `php artisan serve`

---

## 🧪 Cómo Probar

1. **Accede al panel de administración**
2. **Crea, edita o elimina un restaurante**
3. **Abre**: `storage/logs/laravel.log`
4. **Busca al final del archivo** - verás el correo completo con:
   - Asunto
   - Destinatario
   - Contenido HTML del correo
   - Todos los datos del restaurante

---

## 📊 Resumen de Opciones

| Opción | Dificultad | Correos Reales | Recomendado Para |
|--------|------------|----------------|------------------|
| **Modo LOG** (actual) | ⭐ Fácil | ❌ No | Desarrollo/Pruebas |
| **Mailtrap** | ⭐⭐ Media | ❌ No (inbox virtual) | Desarrollo/Pruebas |
| **Gmail Personal** | ⭐⭐ Media | ✅ Sí | Desarrollo/Demos |
| **Office 365 (arreglado)** | ⭐⭐⭐ Difícil | ✅ Sí | Producción |

---

## ✅ Recomendación

Para tu proyecto de clase:

1. **Ahora**: Usa **modo LOG** (ya configurado)
2. **Para demostrar**: Muestra el archivo `laravel.log` con el correo generado
3. **Si necesitas envíos reales**: Usa **Gmail personal** o **Mailtrap**

El código funciona perfectamente, solo que Office 365 del colegio bloquea el envío. En modo LOG puedes demostrar que el sistema genera correctamente los correos con toda la información.

---

**Última actualización**: 13/02/2026
