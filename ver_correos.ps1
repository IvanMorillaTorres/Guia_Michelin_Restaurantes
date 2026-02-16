# Script para ver los últimos correos generados
# Lee el archivo laravel.log y muestra los correos en modo LOG

$logFile = "storage\logs\laravel.log"

if (Test-Path $logFile) {
    Write-Host "=== ÚLTIMOS CORREOS GENERADOS ===" -ForegroundColor Green
    Write-Host ""
    
    # Leer el contenido del archivo
    $content = Get-Content $logFile -Raw
    
    # Buscar todas las entradas que contengan "From:" (inicio de un correo)
    $emails = [regex]::Matches($content, '\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] local\.DEBUG: From:.*?(?=\[\d{4}-\d{2}-\d{2}|\z)', 
        [System.Text.RegularExpressions.RegexOptions]::Singleline)
    
    # Mostrar los últimos 3 correos
    $lastEmails = $emails | Select-Object -Last 3
    
    if ($lastEmails.Count -eq 0) {
        Write-Host "No se encontraron correos en el log." -ForegroundColor Yellow
        Write-Host "Intenta crear, editar o eliminar un restaurante." -ForegroundColor Yellow
    } else {
        foreach ($email in $lastEmails) {
            $emailText = $email.Value
            
            # Extraer información clave
            if ($emailText -match 'Subject: (.+)') {
                $subject = $matches[1].Trim()
                Write-Host "📧 Asunto: $subject" -ForegroundColor Cyan
            }
            
            if ($emailText -match 'To: (.+)') {
                $to = $matches[1].Trim()
                Write-Host "   Para: $to" -ForegroundColor Gray
            }
            
            if ($emailText -match '\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]') {
                $date = $matches[1]
                Write-Host "   Fecha: $date" -ForegroundColor Gray
            }
            
            Write-Host ""
        }
        
        Write-Host "✅ Los correos se están generando correctamente!" -ForegroundColor Green
        Write-Host "📄 Archivo completo: $logFile" -ForegroundColor Gray
    }
} else {
    Write-Host "❌ No se encontró el archivo de logs: $logFile" -ForegroundColor Red
}
