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
- Acceso a la contraseña de **root de MySQL** (solo se usa durante la instalación).

---

## ⚙️ Instalación

1. **Clonar el repositorio en tu servidor**
   ```bash
   git clone https://github.com/experimentoiautn1-ai/formulario_contacto.git
   cd formulario_contacto
   ```

2. **Correr el instalador**  
   Esto copia los archivos dentro de `/var/www/html/formulario_contacto`, instala dependencias con Composer y crea la base de datos + usuario MySQL.  
   Durante la instalación, se te pedirá la contraseña de **root de MySQL** para poder crear el usuario de aplicación de forma segura.
   ```bash
   ./setup.sh
   ```

3. **Configurar variables de entorno**  
   - Editar `.env` en `/var/www/html/formulario_contacto` y completar:
     - Credenciales MySQL (`MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`).
     - Datos de tu servidor SMTP (`SMTP_HOST`, `SMTP_PORT`, `SMTP_USERNAME`, `SMTP_PASSWORD`).
     - Direcciones de correo (`MAIL_FROM`, `MAIL_TO`).
  
⚠️ Si usas Docker, reemplaza `localhost` por el nombre del servicio (ej. `db`).

4. **Configurar permisos de la carpeta**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/formulario_contacto
   sudo chmod -R 755 /var/www/html/formulario_contacto
   ```

---

## ▶️ Puesta en marcha

1. Verifica que Apache/Nginx y MySQL estén corriendo.
2. Accede desde tu navegador:
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
Después de correr `./setup.sh`, deberías tener en `/var/www/html/formulario_contacto`:  

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

- **No uses el root de MySQL** en `.env`, el usuario de aplicación se crea automáticamente en la instalación.
- En producción, establece:
  ```env
  APP_ENV=production
  APP_DEBUG=false
  ```
- La contraseña de **root de MySQL nunca se guarda** en el proyecto. Solo se usa durante el `setup.sh`.

---

## 👨‍💻 Contribuir

1. Haz un fork del repositorio.
2. Crea una rama (`feature/nueva-funcionalidad`).
3. Envía un PR.

---

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**.
