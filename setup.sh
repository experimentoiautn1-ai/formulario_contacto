#!/usr/bin/env bash
set -e

SRC_DIR="$(dirname "$0")/src"
DEST="/var/www/html/formulario_contacto"

echo "📂 Creando directorio destino en $DEST..."
sudo mkdir -p "$DEST"

echo "📂 Copiando archivos de src/ al servidor..."
sudo rsync -av "$SRC_DIR/" "$DEST/"

# Copiar .env.example si no existe
if [ ! -f "$DEST/.env" ]; then
    echo "⚠️  No se encontró .env en $DEST, copiando plantilla..."
    sudo cp "$SRC_DIR/../.env.example" "$DEST/.env"
    echo "🔑 Edita $DEST/.env con tus credenciales de MySQL y SMTP."
fi

echo "✅ Proyecto desplegado en $DEST"

echo "📦 Instalando dependencias con Composer..."
cd "$DEST"
composer install --no-interaction --prefer-dist

sudo chown -R www-data:www-data "$DEST"
sudo chmod -R 755 "$DEST"