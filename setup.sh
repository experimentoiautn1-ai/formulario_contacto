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

# Cargar variables del .env
set -a
source "$DEST/.env"
set +a

echo "📦 Instalando dependencias con Composer..."
cd "$DEST"
composer install --no-interaction --prefer-dist

# Crear DB y usuario MySQL con root
echo "🔐 Se necesita la contraseña de root de MySQL para crear DB y usuario..."
read -s -p "Introduce la contraseña de root de MySQL: " MYSQL_ROOT_PASS
echo ""

mysql -u root -p"$MYSQL_ROOT_PASS" <<MYSQL_SCRIPT
CREATE DATABASE IF NOT EXISTS ${MYSQL_DATABASE};
CREATE USER IF NOT EXISTS '${MYSQL_USER}'@'localhost' IDENTIFIED BY '${MYSQL_PASSWORD}';
GRANT ALL PRIVILEGES ON ${MYSQL_DATABASE}.* TO '${MYSQL_USER}'@'localhost';
FLUSH PRIVILEGES;
MYSQL_SCRIPT

echo "✅ Base de datos y usuario MySQL configurados."

# Permisos correctos para Apache
sudo chown -R www-data:www-data "$DEST"
sudo chmod -R 755 "$DEST"

echo "🎉 Instalación completa. Proyecto disponible en $DEST"
