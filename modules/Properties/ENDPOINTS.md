# Endpoints - API Happy Back

Documentación completa de endpoints para la API de gestión de propiedades.

## Autenticación

La API utiliza **Laravel Sanctum** para autenticación mediante tokens Bearer.

Base URL: `http://localhost:8000`

---

## 🔓 Endpoints Públicos

### Autenticación

#### Registrar Usuario

-   **Método**: POST
-   **Ruta**: `/api/register`
-   **Descripción**: Crea un nuevo usuario y retorna token de acceso
-   **Body** (JSON):
    ```json
    {
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "password": "password123",
        "password_confirmation": "password123"
    }
    ```
-   **Respuesta**:
    ```json
    {
        "access_token": "1|xxxxxxxxxxxxx",
        "token_type": "Bearer",
        "user": {
            "id": 1,
            "name": "Juan Pérez",
            "email": "juan@example.com"
        }
    }
    ```

#### Iniciar Sesión

-   **Método**: POST
-   **Ruta**: `/api/login`
-   **Descripción**: Autentica un usuario y retorna token de acceso
-   **Body** (JSON):
    ```json
    {
        "email": "juan@example.com",
        "password": "password123"
    }
    ```
-   **Respuesta**: Igual a `/api/register`

---

## 🔒 Endpoints Protegidos

> **Nota**: Incluir header `Authorization: Bearer {token}` en todas las peticiones protegidas.

#### Cerrar Sesión

-   **Método**: POST
-   **Ruta**: `/api/logout`
-   **Headers**: `Authorization: Bearer {token}`
-   **Respuesta**:
    ```json
    {
        "message": "Sesión cerrada correctamente"
    }
    ```

#### Obtener Usuario Actual

-   **Método**: GET
-   **Ruta**: `/api/user`
-   **Headers**: `Authorization: Bearer {token}`
-   **Respuesta**: Objeto usuario autenticado

---

## 📦 Propiedades - Endpoints Públicos

## 📦 Propiedades - Endpoints Públicos

### 1) Listar propiedades

-   **Método**: GET
-   **Ruta**: `/properties`
-   **Descripción**: Lista propiedades con filtros y paginación.
-   **Query params opcionales**:
    -   `location` (string) - Filtrar por ubicación
    -   `max_price` (number) - Precio máximo
    -   `guests` (integer) - Cantidad mínima de huéspedes
    -   `featured` (boolean) - Solo destacadas
    -   `sort_by` (string) - Campo de ordenamiento
    -   `sort_order` (asc|desc) - Dirección del ordenamiento
    -   `per_page` (integer) - Elementos por página

**Ejemplo**:

```bash
curl "http://localhost:8000/properties?location=Punta%20Hermosa&per_page=10"
```

**Respuesta**: Lista paginada de propiedades

---

### 2) Propiedades destacadas

-   **Método**: GET
-   **Ruta**: `/properties/featured`
-   **Descripción**: Obtiene propiedades marcadas como destacadas
-   **Query params**:
    -   `limit` (integer, default: 6) - Cantidad de resultados

**Ejemplo**:

```bash
curl "http://localhost:8000/properties/featured?limit=6"
```

---

### 3) Buscar propiedades

-   **Método**: GET
-   **Ruta**: `/properties/search`
-   **Descripción**: Busca en nombre, ubicación y descripción
-   **Query params**:
    -   `q` (string, requerido) - Término de búsqueda

**Ejemplo**:

```bash
curl "http://localhost:8000/properties/search?q=vista%20mar"
```

---

### 4) Filtrar por rango de precio

-   **Método**: GET
-   **Ruta**: `/properties/price-range`
-   **Query params**:
    -   `min_price` (number, default: 0)
    -   `max_price` (number, default: 999999)

**Ejemplo**:

```bash
curl "http://localhost:8000/properties/price-range?min_price=50&max_price=500"
```

---

### 5) Obtener detalle de propiedad

-   **Método**: GET
-   **Ruta**: `/properties/{id}`
-   **Descripción**: Obtiene una propiedad específica

**Ejemplo**:

```bash
curl "http://localhost:8000/properties/5"
```

---

## 🔒 Propiedades - Endpoints Protegidos

> **Importante**: Requieren autenticación con token Bearer

### 6) Crear propiedad

-   **Método**: POST
-   **Ruta**: `/properties`
-   **Headers**: `Authorization: Bearer {token}`
-   **Body** (JSON):

```json
{
    "name": "Vista Mar Prime",
    "location": "Vista Mar Prime · Punta Hermosa",
    "price": "Consultar",
    "period": "noche",
    "images": [
        "/images/vista-mar-prime/1.jpg",
        "/images/vista-mar-prime/2.jpg"
    ],
    "guests": 10,
    "bedrooms": 4,
    "bathrooms": 4,
    "amenities": [
        { "icon": "beach", "label": "Vista al mar" },
        { "icon": "terrace", "label": "Terraza panorámica" },
        { "icon": "kitchen", "label": "Cocina equipada" },
        { "icon": "wifi", "label": "WiFi" },
        { "icon": "pool", "label": "Piscina" },
        { "icon": "parking", "label": "Estacionamiento" }
    ],
    "featured": false,
    "airbnb_url": "https://airbnb.com"
}
```

**Campos**:

-   `name` (string) - Nombre de la propiedad
-   `location` (string) - Ubicación
-   `price` (number|string) - Precio o "Consultar"
-   `period` (string) - Período (noche, mes, etc.)
-   `images` (array<string>) - URLs de imágenes (ordenadas). Enviar `[]` para limpiar.
-   `guests` (integer) - Capacidad de huéspedes
-   `bedrooms` (integer) - Número de habitaciones
-   `bathrooms` (integer) - Número de baños
-   `amenities` (array) - Amenidades con icon y label
-   `featured` (boolean) - Es destacada
-   `airbnb_url` (string, opcional) - URL de Airbnb
-   `description` (string, opcional) - Descripción
-   `address` (string, opcional) - Dirección completa
    > Para subir archivos y obtener URLs usa el endpoint protegido `/properties/images/upload` (ver más abajo) y luego coloca esas URLs en `images`.

**Ejemplo**:

```bash
curl -X POST http://localhost:8000/properties \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|xxxxxxxxxxxxx" \
  -d '{
    "name": "Casa de Playa",
    "location": "Punta Hermosa",
    "price": 150,
    "period": "noche",
    "guests": 6,
    "bedrooms": 3,
    "bathrooms": 2,
    "amenities": [{"icon": "wifi", "label": "WiFi"}],
    "featured": false
  }'
```

---

### 7) Actualizar propiedad

-   **Método**: PUT
-   **Ruta**: `/properties/{id}`
-   **Headers**: `Authorization: Bearer {token}`
-   **Body**: Campos a actualizar (mismo formato que crear). Para reemplazar imágenes envía `images` con el arreglo completo; para borrar todas las imágenes envía `"images": []`.

**Ejemplo**:

```bash
curl -X PUT http://localhost:8000/properties/5 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|xxxxxxxxxxxxx" \
  -d '{"featured": true, "price": 200}'
```

---

### 8) Eliminar propiedad

-   **Método**: DELETE
-   **Ruta**: `/properties/{id}`
-   **Headers**: `Authorization: Bearer {token}`

**Ejemplo**:

```bash
curl -X DELETE http://localhost:8000/properties/5 \
  -H "Authorization: Bearer 1|xxxxxxxxxxxxx"
```

**Respuesta**:

```json
{
    "message": "Propiedad eliminada"
}
```

---

### 9) Subir imágenes (obtener URLs)

-   **Método**: POST
-   **Ruta**: `/properties/images/upload`
-   **Headers**: `Authorization: Bearer {token}`
-   **Body**: `form-data` con `images[]` (uno o varios archivos). Máx 5MB por imagen.
-   **Respuesta**:

```json
{
    "images": [
        {
            "url": "/storage/properties/abc123.jpg",
            "path": "properties/abc123.jpg"
        }
    ]
}
```

Usa los valores de `url` devueltos en el campo `images` al crear/actualizar propiedades.

---

## 📝 Notas Importantes

### Autenticación

1. **Registrarse** o **iniciar sesión** en `/api/register` o `/api/login`
2. Copiar el `access_token` de la respuesta
3. Incluir en header: `Authorization: Bearer {access_token}`

### Errores Comunes

-   **401 Unauthenticated**: Token no proporcionado o inválido
-   **404 Not Found**: Propiedad no existe
-   **422 Validation Error**: Datos de entrada inválidos

### Respuestas de Error

```json
{
    "message": "Unauthenticated."
}
```

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["El campo email es requerido."]
    }
}
```

---

## 🚀 Prueba Rápida

### 1. Registrar usuario

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Guardar el token

Copia el `access_token` de la respuesta.

### 3. Crear una propiedad

```bash
curl -X POST http://localhost:8000/properties \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -d '{
    "name": "Casa Test",
    "location": "Lima",
    "price": 100,
    "period": "noche",
    "guests": 4,
    "bedrooms": 2,
    "bathrooms": 1,
    "amenities": [],
    "featured": false
  }'
```

---

## 📚 Recursos Adicionales

-   **Modelo**: `modules/Properties/Models/Property.php`
-   **Controlador**: `modules/Properties/Controllers/PropertyController.php`
-   **Servicio**: `modules/Properties/Services/PropertyService.php`
-   **Rutas**: `modules/Properties/Routes/api.php`
