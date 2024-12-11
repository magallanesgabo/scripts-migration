#!/bin/bash

# Número total de solicitudes
total_requests=1000

# URL del servidor a probar
url="http://fresh.produ.com"

# Función para enviar solicitudes en paralelo
send_requests() {
    local url=$1
    for ((i=0; i<$total_requests; i++)); do
        # Enviar solicitud GET con headers simulando un navegador
        curl -s -H "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.60 Safari/537.36" \
             -H "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3" \
             -H "Accept-Language: en-US,en;q=0.9" \
             -o /dev/null $url &
    done
    # Esperar a que todas las solicitudes se completen
    wait
}

# Iniciar prueba de estrés
send_requests $url
