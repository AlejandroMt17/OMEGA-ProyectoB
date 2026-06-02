# OMEGA-ProyectoB

Base del **Proyecto B** (OMEGA WEB-MÓVIL): API REST en **Laravel** y app **Flutter** separadas, lista para escalar por módulos.

## Estructura del repositorio

| Carpeta    | Rol |
|-----------|-----|
| `backend/` | Laravel 11 — MVC, `Services` + `Repositories`, JSON en `routes/api.php` |
| `mobile/`  | Flutter — MVVM por feature, BLoC, cliente HTTP hacia el API |

## Backend (Laravel)

Requisitos: PHP 8.2+, Composer, extensiones habituales de Laravel.

```bash
cd backend
composer install
copy .env.example .env
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --seed
php artisan serve
```

- API de usuarios: `GET|POST /api/usuarios`, `GET|PUT|DELETE /api/usuarios/{usuario}`  
- CORS abierto para desarrollo (`config/cors.php`).

## App móvil (Flutter)

```bash
cd mobile
flutter pub get
flutter run
```

La URL base del API por defecto es `http://10.0.2.2:8000` (emulador Android → `localhost` del PC). Para iOS simulator suele usarse `http://127.0.0.1:8000`. Puedes definir otra al compilar:

```bash
flutter run --dart-define=API_BASE_URL=http://TU_IP:8000
```

En **Android**, `usesCleartextTraffic` está activado solo para poder usar HTTP en desarrollo; en producción conviene HTTPS y retirar o acotar cleartext.

## Comentarios por carpetas

- **Laravel**: docblocks en `Models`, `Services`, `Repositories`, `Http/Controllers` y cabeceras en `routes/`.  
- **Flutter**: comentarios de carpeta en `lib/core/core.dart`, `lib/features/usuarios/usuarios.dart` y documentación breve en archivos clave del feature.
