<?php

namespace Modules\Properties\Services;

use Modules\Properties\Models\Property;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;

class PropertyService
{
    /**
     * Obtener todas las propiedades con filtros opcionales
     */
    public function getAll(array $filters = [])
    {
        $query = Property::query();

        // Filtro por ubicación
        if (isset($filters['location'])) {
            $query->byLocation($filters['location']);
        }

        // Filtro por precio máximo
        if (isset($filters['max_price'])) {
            $query->maxPrice($filters['max_price']);
        }

        // Filtro por cantidad de huéspedes
        if (isset($filters['guests'])) {
            $query->byGuests($filters['guests']);
        }

        // Filtro por propiedades destacadas
        if (isset($filters['featured']) && $filters['featured'] === true) {
            $query->featured();
        }

        // Ordenamiento
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        // Paginación
        $perPage = $filters['per_page'] ?? 15;

        return $query->paginate($perPage);
    }

    /**
     * Obtener una propiedad por ID
     */
    public function getById($id)
    {
        return Property::findOrFail($id);
    }

    /**
     * Crear una nueva propiedad
     */
    public function create(array $data): Property
    {
        $payload = $this->preparePayload($data, true);

        return Property::create($payload);
    }

    /**
     * Actualizar una propiedad
     */
    public function update($id, array $data): Property
    {
        $property = Property::findOrFail($id);

        $payload = $this->preparePayload($data, false);

        $property->update($payload);

        return $property;
    }

    /**
     * Normaliza payload de propiedades para crear/actualizar
     */
    private function preparePayload(array $data, bool $isCreate): array
    {
        $payload = $data;

        // Asegurar price como string (permite "Consultar" u otro texto)
        if (array_key_exists('price', $data)) {
            $payload['price'] = (string) $data['price'];
        }

        // Normalizar imágenes
        if (array_key_exists('images', $data)) {
            $payload['images'] = $this->sanitizeStringArray($data['images']);
        } elseif ($isCreate) {
            $payload['images'] = [];
        } else {
            $payload = Arr::except($payload, ['images']);
        }

        // Normalizar amenidades
        if (array_key_exists('amenities', $data)) {
            $payload['amenities'] = $this->sanitizeAmenities($data['amenities']);
        } elseif ($isCreate) {
            $payload['amenities'] = [];
        } else {
            $payload = Arr::except($payload, ['amenities']);
        }

        return $payload;
    }

    /**
     * Filtra y convierte arreglo de strings
     */
    private function sanitizeStringArray($value): array
    {
        if (is_string($value)) {
            return $value === '' ? [] : [$value];
        }

        if (!is_array($value)) {
            return [];
        }

        return array_values(array_filter(array_map('strval', $value), fn($v) => $v !== ''));
    }

    /**
     * Filtra amenidades con icon y label
     */
    private function sanitizeAmenities($value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $amenities = array_map(function ($amenity) {
            $icon = is_array($amenity) && isset($amenity['icon']) ? (string) $amenity['icon'] : '';
            $label = is_array($amenity) && isset($amenity['label']) ? (string) $amenity['label'] : '';

            return ['icon' => $icon, 'label' => $label];
        }, $value);

        return array_values(array_filter($amenities, fn($item) => $item['icon'] !== '' && $item['label'] !== ''));
    }

    /**
     * Eliminar una propiedad
     */
    public function delete($id): bool
    {
        $property = Property::findOrFail($id);

        return $property->delete();
    }

    /**
     * Obtener propiedades destacadas
     */
    public function getFeatured($limit = 6)
    {
        return Property::featured()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Buscar propiedades por término
     */
    public function search($term)
    {
        return Property::where('name', 'like', "%{$term}%")
            ->orWhere('location', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%")
            ->paginate(15);
    }

    /**
     * Obtener propiedades por rango de precio
     */
    public function getByPriceRange($minPrice, $maxPrice)
    {
        return Property::whereBetween('price', [$minPrice, $maxPrice])
            ->orderBy('price')
            ->paginate(15);
    }

    /**
     * Obtener propiedades que aceptan X cantidad de huéspedes
     */
    public function getByGuestCount($guests)
    {
        return Property::where('guests', '>=', $guests)
            ->orderBy('price')
            ->paginate(15);
    }
}
