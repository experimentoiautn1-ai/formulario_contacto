# Formulario de Contacto con PHP y MySQL

Este proyecto implementa un formulario de contacto en PHP con almacenamiento en MySQL y envío de correos mediante PHPMailer. Está diseñado para funcionar en un entorno LAMP o similar, y su estructura permite un fácil despliegue y testing.

---

## 📋 Requisitos

Antes de iniciar, asegúrate de tener instalado:

- **Sistema operativo**: Linux.
- **Servidor web**: Apache.
- **PHP** >= 7.4 con extensiones:
  - `mysqli`
  - `mbstring`
  - `curl`
  - `openssl`
- **Composer** versión 2
- **Base de datos**: MySQL.  
- **Acceso a Internet** para instalar dependencias de Composer y enviar correos vía SMTP

---

## ⚙️ Instalación

1. **Clonar o copiar el proyecto** a tu servidor:  

```bash
git clone https://github.com/experimentoiautn1-ai/formulario_contacto <donde sea>
cd /var/www/html/formulario_contacto/src
```  
  
Aunque es recomendado en `/var/www/html/formulario_contacto`

2. **Iniciar el setup**:  
Corriendo `./setup.sh` los archivos del `src/` serán copiados dentro de `/var/www/html/formulario_contacto` 

3. **Iniciar los servicios**:  
Correr `./init_servicios.sh` inicializa (si es que no están inicializados) los servicios de PHP y MySQL