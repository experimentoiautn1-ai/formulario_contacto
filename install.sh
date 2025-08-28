#!/bin/bash
set -e

DEST="/var/www/html/formulario_contacto"

echo "🚀 Instalador del proyecto Formulario Contacto"
echo "---------------------------------------------"

# 1. Crear carpeta en Apache
echo "📂 Creando carpeta en Apache: $DEST"
sudo mkdir -p "$DEST"

# 2. Copiar archivos desde ./src
echo "📥 Copiando archivos desde ./src..."
sudo rsync -av --delete ./src/ "$DEST/"

# 3. Ajustar permisos
echo "🔑 Ajustando permisos..."
sudo chown -R www-data:www-data "$DEST"
sudo chmod -R 755 "$DEST"

# 4. Descargar Composer 2 local al proyecto
echo "📦 Descargando Composer 2 en el proyecto..."
cd "$DEST"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --2 --filename=composer.phar
rm composer-setup.php

# 5. Instalar dependencias PHP del proyecto usando composer local
echo "📦 Instalando dependencias PHP del proyecto..."
php composer.phar install --no-interaction --no-progress --optimize-autoloader

# 6. Listo
echo "✅ Instalación completada."
echo "👉 Archivos desplegados en: $DEST"
echo "👉 Composer local disponible en: $DEST/composer.phar"
echo "👉 Para usarlo: php composer.phar <comando>"
echo "👉 Ahora configura tu archivo .env en $DEST"
