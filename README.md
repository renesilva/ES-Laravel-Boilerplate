# ES Laravel Boilerplate

El paquete utiliza Laravel 10 como base.
El sistema cuenta con los siguientes paquetes

- [Keboola PHP CSV](https://github.com/keboola/php-csv) (Para leer y exportar archivos CSV)
- [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) (Para leer y exportar archivos Excel)
- [DOMPDF Wrapper for Laravel](https://github.com/barryvdh/laravel-dompdf) (Para generar PDFs)
- [Laravel JSON API Debugger](https://packagist.org/packages/lanin/laravel-api-debugger) (Para debuggear las peticiones
  JSON)
- [Laravel Meta](https://github.com/kodeine/laravel-meta) (Para generar tablas meta de los modelos)
- [JWT Auth](https://github.com/PHP-Open-Source-Saver/jwt-auth) (Para autenticación con JWT)
- [Laravel Permission](https://spatie.be/docs/laravel-permission/v5/introduction) (Para manejar los permisos de los
  usuarios)
- [IDE Helper Generator for Laravel](https://github.com/barryvdh/laravel-ide-helper) (Para autocompletado de modelos en
  los IDEs)

# Instalación

## Correr composer

Debe correr el siguiente comando para instalar las dependencias de composer.

```bash
composer install
```

## Configuración de .env

Debemos copiar el archivo `.env.example` a `.env`. Una vez copiado podemos generar la llave de la aplicación.

```bash
php artisan key:generate
```

Recordemos que cada edición del archivo .env debe estar seguida por config:cache.

```bash
php artisan config:cache
```

## Base de datos y Seeds

Crear la base de datos y configurar el archivo .env con los datos de la base de datos.
Después debes correr las migraciones y los seeds.

```
DB_DATABASE=BASEDEDATOS
DB_USERNAME=USUARIO
DB_PASSWORD=PASSWORD
```

```bash
php artisan config:cache
```

Migramos la base de datos e instalamos los seeds.

```bash
php artisan migrate
```

```bash
php artisan db:seed --class=UsersSeeder
```

```bash
php artisan db:seed --class=SuppliersSeeder
```

## JWT

Para habilitar JWT se debe correr el siguiente comando para generar el secret key.

```bash
php artisan jwt:secret
```

Y verificar que en .env genera lo siguiente:

```
JWT_SECRET=XXXXXXXXXX
JWT_ALGO=HS256
```

```bash
php artisan config:cache
```

Recordemos que la configuración de auth.php que utilice el driver de JWT y el Guard de "api".

```php
  'defaults' => [
    'guard' => 'api',
    'passwords' => 'users',
  ],
  'guards' => [
    'web' => [
      'driver' => 'session',
      'provider' => 'users',
    ],
    'api' => [
      'driver' => 'jwt',
      'provider' => 'users',
    ]
  ],
```

## Corriendo el proyecto

Para ejecutar proyecto debes correr el siguiente comando:

```bash
php artisan serve
```

## IDE Helper

IDE Helper nos ayuda con el autocompletado de los modelos.
Para generarlo podemos correr los siguientes comandos:

```bash
php artisan ide-helper:generate
```

```bash
php artisan ide-helper:models --write
```

# Ejemplo de Suppliers

Tenemos subido el ejemplo de la tabla Suppliers como Controladores, Rutas, Migrations.

# Documentación

- [Laravel 10](https://laravel.com/docs/10.x/releases)
- [Implementación de JWT Auth con Laravel](https://blog.logrocket.com/implementing-jwt-authentication-laravel-9/)
