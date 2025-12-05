# Plataforma CAD

Sistema para gestión de capacitaciones internas, externas y diplomados desarrollado en Laravel.

## Requisitos del Sistema

### Software Necesario
- **PHP**: 8.1 o superior
- **Composer**: 2.0 o superior  
- **Node.js**: 16.0 o superior
- **npm**: 8.0 o superior
- **MySQL**: 8.0 o superior
- **Git**: Para clonar el repositorio

### Extensiones de PHP Requeridas
```bash
# Instalar estas extensiones de PHP
php-mysql
php-mbstring
php-xml
php-curl
php-zip
php-gd
php-json
php-tokenizer
php-openssl
php-fileinfo
php-bcmath
```

### Verificar Instalación de Extensiones
```bash
# Verificar que PHP tenga las extensiones
php -m | grep mysql
php -m | grep mbstring
php -m | grep xml
php -m | grep curl
php -m | grep zip
php -m | grep gd
```

## Instalación

### 1. Clonar el Repositorio
```bash
git clone [URL_DEL_REPOSITORIO]
cd CapacitacionInterna
```

### 2. Instalar Dependencias PHP
```bash
# Verificar versión de PHP
php --version

# Instalar dependencias con Composer
composer install --no-dev --optimize-autoloader
```

### 3. Configurar Variables de Entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

**Editar el archivo `.env` con tus datos:**
```env
APP_NAME="Plataforma de Capacitación"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=CAD
DB_USERNAME=cap_docente
DB_PASSWORD=des_academico
```

### 4. Configurar Base de Datos
```bash
# Crear base de datos y usuario MySQL
mysql -u root -p

CREATE DATABASE CAD;
CREATE USER 'cap_docente'@'localhost' IDENTIFIED BY 'des_academico';
GRANT ALL PRIVILEGES ON CAD.* TO 'cap_docente'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Ejecutar Migraciones
```bash
# Crear tablas en la base de datos
php artisan migrate --force

# Insertar datos iniciales (opcional)
php artisan db:seed
```

### 6. Instalar Dependencias Frontend
```bash
# Instalar dependencias Node.js
npm install

# Compilar assets para producción
npm run build
```

### 7. Configurar Permisos y Storage
```bash
# Crear enlace simbólico para archivos
php artisan storage:link

# Configurar permisos (Linux/Mac)
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# En Windows: Dar permisos completos a las carpetas storage y bootstrap/cache
```

### 8. Iniciar la Aplicación
```bash
# Iniciar servidor de desarrollo
php artisan serve

# La aplicación estará disponible en: http://localhost:8000
```

## Configuración Adicional

### Para Servidor Web (Apache/Nginx)
- Configurar el documento root hacia la carpeta `public/`
- Habilitar mod_rewrite en Apache
- Configurar permisos de escritura en `storage/` y `bootstrap/cache/`

### Optimización para Producción
```bash
# Cachear configuración
php artisan config:cache

# Cachear rutas
php artisan route:cache

# Cachear vistas
php artisan view:cache

# Optimizar autoloader
composer install --no-dev --optimize-autoloader
```

## Comandos Útiles

### Laravel Artisan
```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Regenerar optimizaciones
php artisan optimize

# Ver rutas disponibles
php artisan route:list
```

### Base de Datos
```bash
# Ejecutar migraciones
php artisan migrate

# Rollback de migraciones
php artisan migrate:rollback

# Resetear BD y ejecutar seeders
php artisan migrate:fresh --seed
```

## Solución de Problemas Comunes

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: Permisos de archivos
```bash
# Linux/Mac
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Windows: Propiedades → Seguridad → Dar control completo
```

### Error: "No application encryption key"
```bash
php artisan key:generate
```

### Error: Base de datos no conecta
- Verificar credenciales en `.env`
- Asegurar que MySQL esté ejecutándose
- Verificar que la base de datos `CAD` existe

### Assets no cargan correctamente
```bash
npm run build
php artisan view:clear
```
