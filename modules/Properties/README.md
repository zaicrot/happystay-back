# Módulo Properties

Módulo para gestión de propiedades, alojamientos y cuartos con autenticación Sanctum.

## 🚀 Características

✅ CRUD completo de propiedades  
✅ Autenticación con Laravel Sanctum  
✅ Filtros avanzados (ubicación, precio, huéspedes)  
✅ Búsqueda por texto  
✅ Propiedades destacadas  
✅ Paginación automática

## Estructura del Módulo

```
modules/Properties/
├── Controllers/
│   └── PropertyController.php    # API REST endpoints
├── Models/
│   └── Property.php              # Modelo Eloquent
├── Services/
│   └── PropertyService.php       # Lógica de negocio
├── Routes/
│   └── api.php                   # Rutas del módulo
├── ENDPOINTS.md                  # Documentación completa
├── README.md                     # Este archivo
└── PropertiesServiceProvider.php # Service Provider
```

## 📦 Instalación

### 1. Migraciones

Las migraciones ya fueron ejecutadas. Tabla `properties` disponible.

### 2. Service Provider

Ya registrado en `bootstrap/app.php`.

### 3. Sanctum

Laravel Sanctum instalado y configurado para autenticación API.

## 📖 Uso

### Obtener propiedades (público)

```php
use Modules\Properties\Services\PropertyService;

$service = app(PropertyService::class);

// Todas las propiedades
$properties = $service->getAll();

// Con filtros
$properties = $service->getAll([
    'location' => 'Punta Hermosa',
    'max_price' => 500,
    'guests' => 4,
    'per_page' => 10
]);

// Destacadas
$featured = $service->getFeatured(6);

// Buscar
$results = $service->search('vista mar');
```

### Usar el modelo directamente

```php
use Modules\Properties\Models\Property;

// Todas
$all = Property::all();

// Con scopes
$filtered = Property::byLocation('Lima')
    ->maxPrice(300)
    ->featured()
    ->get();

// Crear (requiere autenticación en API)
Property::create([
    'name' => 'Casa de Playa',
    'location' => 'Punta Hermosa',
    'price' => 150,
    'period' => 'noche',
    'guests' => 6,
    'bedrooms' => 3,
    'bathrooms' => 2,
    'amenities' => [
        ['icon' => 'wifi', 'label' => 'WiFi'],
        ['icon' => 'pool', 'label' => 'Piscina']
    ],
    'featured' => false
]);
```

## 🔐 Autenticación

## 🔐 Autenticación

### Endpoints de Auth (app/Http/Controllers/AuthController.php)

| Método | Ruta            | Descripción       | Auth |
| ------ | --------------- | ----------------- | ---- |
| POST   | `/api/register` | Registrar usuario | No   |
| POST   | `/api/login`    | Iniciar sesión    | No   |
| POST   | `/api/logout`   | Cerrar sesión     | Sí   |
| GET    | `/api/user`     | Usuario actual    | Sí   |

### Flujo de autenticación

1. **Registrarse o iniciar sesión**:

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Usuario",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

2. **Recibir token**:

```json
{
  "access_token": "1|xxxxxxxxxxxxx",
  "token_type": "Bearer",
  "user": { ... }
}
```

3. **Usar token en requests protegidos**:

```bash
curl -X POST http://localhost:8000/properties \
  -H "Authorization: Bearer 1|xxxxxxxxxxxxx" \
  -H "Content-Type: application/json" \
  -d '{ ... }'
```

## 🌐 API Endpoints

### Públicos (sin autenticación)

| Método | Ruta                       | Descripción        |
| ------ | -------------------------- | ------------------ |
| GET    | `/properties`              | Listar propiedades |
| GET    | `/properties/featured`     | Destacadas         |
| GET    | `/properties/search?q=...` | Buscar             |
| GET    | `/properties/price-range`  | Rango de precio    |
| GET    | `/properties/{id}`         | Detalle            |

### Protegidos (requieren token)

| Método | Ruta                        | Descripción                   | Auth         |
| ------ | --------------------------- | ----------------------------- | ------------ |
| POST   | `/properties`               | Crear                         | Bearer Token |
| PUT    | `/properties/{id}`          | Actualizar                    | Bearer Token |
| DELETE | `/properties/{id}`          | Eliminar                      | Bearer Token |
| POST   | `/properties/images/upload` | Subir imágenes y obtener URLs | Bearer Token |

> 📘 Ver [ENDPOINTS.md](ENDPOINTS.md) para documentación completa con ejemplos.

## 🧪 Testing Rápido

### Con cURL

```bash
# 1. Registrar
TOKEN=$(curl -s -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"12345678","password_confirmation":"12345678"}' \
  | jq -r '.access_token')

# 2. Crear propiedad
curl -X POST http://localhost:8000/properties \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Casa Test",
    "location": "Lima",
    "price": 100,
    "period": "noche",
    "guests": 4,
    "bedrooms": 2,
    "bathrooms": 1,
    "featured": false
  }'

# 3. Listar (público)
curl http://localhost:8000/properties
```

### Con Thunder Client / Postman

1. POST `http://localhost:8000/api/register` con body JSON
2. Copiar `access_token`
3. En Headers agregar: `Authorization: Bearer {token}`
4. POST `http://localhost:8000/properties` con datos de propiedad

## 📊 Modelo de Datos

### Tabla `properties`

| Campo       | Tipo      | Descripción                   |
| ----------- | --------- | ----------------------------- |
| id          | int       | ID autoincrementable          |
| name        | string    | Nombre de la propiedad        |
| location    | string    | Ubicación                     |
| price       | decimal   | Precio (puede ser string)     |
| period      | string    | Período (noche, mes, etc.)    |
| guests      | int       | Capacidad de huéspedes        |
| bedrooms    | int       | Número de habitaciones        |
| bathrooms   | int       | Número de baños               |
| amenities   | json      | Array de amenidades           |
| featured    | boolean   | Propiedad destacada           |
| airbnb_url  | string    | URL de Airbnb (opcional)      |
| description | text      | Descripción (opcional)        |
| address     | string    | Dirección completa (opcional) |
| created_at  | timestamp | Fecha de creación             |
| updated_at  | timestamp | Fecha de actualización        |

### Formato de amenities

```json
[
    { "icon": "wifi", "label": "WiFi" },
    { "icon": "pool", "label": "Piscina" },
    { "icon": "parking", "label": "Estacionamiento" }
]
```

## 🛠️ Scopes Disponibles

```php
// Filtrar por ubicación
Property::byLocation('Punta Hermosa')->get();

// Precio máximo
Property::maxPrice(500)->get();

// Capacidad de huéspedes
Property::byGuests(4)->get();

// Solo destacadas
Property::featured()->get();

// Combinar scopes
Property::byLocation('Lima')
    ->maxPrice(300)
    ->featured()
    ->orderBy('price')
    ->get();
```

    ->get();

````

## 🔧 Archivos Clave

| Archivo                           | Descripción                           |
| --------------------------------- | ------------------------------------- |
| `Controllers/PropertyController.php` | Endpoints REST                     |
| `Services/PropertyService.php`    | Lógica de negocio                     |
| `Models/Property.php`             | Modelo Eloquent con scopes            |
| `Routes/api.php`                  | Rutas del módulo                      |
| `PropertiesServiceProvider.php`   | Registra rutas automáticamente        |
| `ENDPOINTS.md`                    | Documentación completa de la API      |

## 📝 Configuración Adicional

### Ejecutar migraciones

```bash
php artisan migrate
````

### Publicar config de Sanctum (opcional)

```bash
php artisan vendor:publish --tag=sanctum-config
```

## ⚠️ Próximos Pasos (Opcional)

Para un módulo más robusto considera:

-   ✅ Validación con FormRequests
-   ✅ Transformación de respuestas con API Resources
-   ✅ Tests automatizados con Pest/PHPUnit
-   ✅ Seeders para datos de prueba
-   ✅ Rate limiting en endpoints
-   ✅ Paginación personalizada
-   ✅ Filtros más avanzados
-   ✅ Manejo de imágenes/uploads

### Alternativa Profesional: laravel-modules

Para proyectos grandes, usar [nwidart/laravel-modules](https://github.com/nWidart/laravel-modules):

```bash
composer require nwidart/laravel-modules
php artisan module:make Properties
```

Proporciona estructura modular completa con migrations, factories, tests y más.

---

**Documentación completa**: Ver [ENDPOINTS.md](ENDPOINTS.md)  
**Versión**: Laravel 12.45.0 con Sanctum 4.2  
**Última actualización**: Enero 2026
