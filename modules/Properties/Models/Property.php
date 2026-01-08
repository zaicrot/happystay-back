<?php

namespace Modules\Properties\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Properties\Factories\PropertyFactory;

class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';

    protected $fillable = [
        'name',
        'location',
        'price',
        'period',
        'images',
        'guests',
        'bedrooms',
        'bathrooms',
        'amenities',
        'featured',
        'airbnb_url',
        'description',
        'address',
    ];

    protected $casts = [
        'price' => 'string',
        'guests' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'featured' => 'boolean',
        'images' => 'array',
        'amenities' => 'array',
        /**
         * Hide internal timestamps from API responses
         */

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return PropertyFactory::new();
    }

    /**
     * Obtener las amenidades de la propiedad
     */
    public function getAmenities()
    {
        return $this->amenities ?? [];
    }

    /**
     * Establecer las amenidades de la propiedad
     */
    public function setAmenitiesAttribute($value)
    {
        $this->attributes['amenities'] = json_encode($value);
    }

    /**
     * Scope para propiedades destacadas
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope para buscar por ubicación
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /**
     * Scope para filtrar por precio máximo
     */
    public function scopeMaxPrice($query, $maxPrice)
    {
        return $query->where('price', '<=', $maxPrice);
    }

    /**
     * Scope para filtrar por cantidad de huéspedes
     */
    public function scopeByGuests($query, $guests)
    {
        return $query->where('guests', '>=', $guests);
    }
}
