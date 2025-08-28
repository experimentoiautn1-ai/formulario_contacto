#!/usr/bin/env bash
set -e

# Función para iniciar un servicio si no está activo
iniciar_si_no_activo() {
    local servicio=$1
    if systemctl is-active --quiet "$servicio"; then
        echo "✅ Servicio $servicio ya está activo."
    else
        echo "🚀 Iniciando servicio $servicio..."
        sudo systemctl start "$servicio"
        echo "✅ Servicio $servicio iniciado."
    fi
}

# Verificar Apache
iniciar_si_no_activo "apache2"

# Verificar MySQL
iniciar_si_no_activo "mysql"