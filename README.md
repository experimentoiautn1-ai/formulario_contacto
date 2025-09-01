# 📩 Formulario de Contacto PHP + MySQL

Este proyecto implementa un **formulario de contacto** con validación, almacenamiento en base de datos y envío de correos usando **PHPMailer**.  
Está diseñado para funcionar tanto en **servidor local** como en **servidor dedicado** (producción).

---

## ✅ Requisitos del sistema

Antes de iniciar la instalación, asegúrate de contar con:

- **PHP ≥ 8.0** con extensiones:
  - `mysqli`
  - `openssl`
  - `mbstring`
  - `pdo`
- **MySQL ≥ 5.7** o **MariaDB ≥ 10.x**
- **Composer ≥ 2.x**
- Acceso a un servidor **SMTP** válido (ejemplo: Gmail, Outlook, o tu propio dominio).
- Acceso SSH al servidor dedicado.

---

## ⚙️ Instalación

1. **Clonar el repositorio en tu servidor**
   ```bash
   git clone https://github.com/tuusuario/formulario_contacto.git
   cd formulario_contacto
   ```

2. **Instalar dependencias PHP con Composer**
   ```bash
   composer install
   ```

3. **Configurar variables de entorno**
   - Copiar el archivo `.env.example` a `.env`:
     ```bash
     cp .env.example .env
     ```
   - Editar `.env` y completar con:
     - Credenciales MySQL (`MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`).
     - Datos de tu servidor SMTP (`SMTP_HOST`, `SMTP_PORT`, `SMTP_USERNAME`, `SMTP_PASSWORD`).
     - Direcciones de correo (`MAIL_FROM`, `MAIL_TO`).

4. **Configurar la base de datos**
   - Conectarse a MySQL con el root:
     ```bash
     mysql -u root -p
     ```
   - Crear la base y el usuario (ajusta según tu `.env`):
     ```sql
     CREATE DATABASE database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     CREATE USER 'nombre_usuario'@'localhost' IDENTIFIED BY 'contraseña123';
     GRANT ALL PRIVILEGES ON database_name.* TO 'nombre_usuario'@'localhost';
     FLUSH PRIVILEGES;
     ```

   ⚠️ Si usas Docker, reemplaza `localhost` por el nombre del servicio (ej. `db`).

5. **Configurar permisos de la carpeta**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/formulario_contacto
   sudo chmod -R 755 /var/www/html/formulario_contacto
   ```

---

## ▶️ Puesta en marcha

1. Verifica que Apache/Nginx y MySQL estén corriendo:
   ```bash
   sudo systemctl start apache2
   sudo systemctl start mysql
   ```

2. Copia el proyecto a la carpeta pública de tu servidor web (si aún no lo hiciste):
   ```bash
   sudo cp -r formulario_contacto /var/www/html/
   ```

3. Accede desde tu navegador:
   ```
   http://tuservidor/formulario_contacto/
   ```

---

## 🔍 Testing y Debug

- Para verificar la instalación de PHP:
  ```php
  <?php phpinfo(); ?>
  ```
- Si el formulario no envía correos:
  - Revisa `error_log` de Apache/Nginx.
  - Verifica tus credenciales SMTP.
  - Si usas Gmail → habilita [App Passwords](https://myaccount.google.com/apppasswords).
- Si no se guarda en MySQL → revisa credenciales en `.env`.

---

## 📦 Estructura del proyecto

```
formulario_contacto/
├── index.php          # Lógica principal del formulario
├── mail.php           # Envío de correos con PHPMailer
├── formulario.html    # Estructura del formulario
├── formulario.css     # Estilos
├── vendor/            # Dependencias (Composer)
├── .env               # Variables de entorno (configuración)
├── .env.example       # Ejemplo de configuración
└── README.md          # Este archivo
```

---

## 🛡️ Notas de Seguridad

- **No uses el root de MySQL** en `.env`, crea un usuario específico.
- Mantén tu `.env` fuera de acceso público (`.gitignore` ya lo cubre).
- En producción, establece:
  ```env
  APP_ENV=production
  APP_DEBUG=false
  ```

---

## 👨‍💻 Contribuir

1. Haz un fork del repositorio.
2. Crea una rama (`feature/nueva-funcionalidad`).
3. Envía un PR.

---

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**.
