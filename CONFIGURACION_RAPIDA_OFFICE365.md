# ⚡ Configuración Rápida - Office 365

## 📝 Pasos para configurar el envío de correos

### 1️⃣ Edita el archivo `.env`

Abre el archivo `.env` y busca la sección de correo (línea ~50). Cambia estos valores:

```env
MAIL_USERNAME=459406.joan23@fje.edu      # ← Pon TU correo del colegio
MAIL_PASSWORD=tu_contraseña              # ← Pon TU contraseña del colegio
MAIL_FROM_ADDRESS="459406.joan23@fje.edu" # ← Pon el MISMO correo que arriba
```

⚠️ **MUY IMPORTANTE**: 
- `MAIL_FROM_ADDRESS` debe ser **exactamente igual** que `MAIL_USERNAME`
- Si son diferentes, Office 365 rechazará el envío

### 2️⃣ Reinicia el servidor

```bash
# En la terminal donde está corriendo php artisan serve:
# Presiona Ctrl+C para detener el servidor
# Luego vuelve a iniciarlo:
php artisan serve
```

### 3️⃣ Prueba el sistema

1. Accede al panel de administración
2. Crea, edita o elimina un restaurante
3. Verifica que llegue el correo a `459406.joan23@fje.edu`

---

## ✅ Ejemplo de configuración completa

```env
# Configuración de correo electrónico - Office 365 (Outlook)
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=100006512.joan23@fje.edu
MAIL_PASSWORD=MiContraseña123
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="100006512.joan23@fje.edu"
MAIL_FROM_NAME="Guía Michelin - Notificaciones Admin"
```

---

## 🔧 Si algo falla

### ❌ Error: "Invalid credentials"
- Verifica que tu contraseña sea correcta
- Prueba a acceder a https://outlook.office365.com con las mismas credenciales

### ❌ Error: "Sender address rejected"
- `MAIL_FROM_ADDRESS` debe ser igual a `MAIL_USERNAME`
- Ambos deben ser tu correo del colegio

### ❌ El correo no llega
- Revisa la carpeta de spam
- Verifica que el servidor esté reiniciado
- Mira los logs en `storage/logs/laravel.log`

---

## 📧 Cambiar el destinatario

Si quieres que los correos lleguen a otra dirección (por ejemplo, a tu compañera):

1. Abre: `app/Http/Controllers/Admin/AdminRestauranteController.php`
2. Busca: `Mail::to('459406.joan23@fje.edu')`
3. Cambia por: `Mail::to('100006512.joan23@fje.edu')`

Hay **3 lugares** donde debes cambiarlo:
- Línea ~127 (método `guardar`)
- Línea ~211 (método `actualizar`)
- Línea ~271 (método `eliminar`)

O puedes enviar a múltiples destinatarios:
```php
Mail::to(['459406.joan23@fje.edu', '100006512.joan23@fje.edu'])->send(
```

---

**¡Listo!** Con estos 3 pasos ya deberías recibir correos cuando se hagan cambios en el CRUD.
