<?php

namespace Modules\Properties\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Properties\Services\PropertyService;

class PropertyController extends Controller
{
    protected PropertyService $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    // Listar propiedades (con filtros opcionales)
    public function index(Request $request)
    {
        $filters = $request->only(['location', 'max_price', 'guests', 'featured', 'sort_by', 'sort_order', 'per_page']);
        $properties = $this->propertyService->getAll($filters);

        return response()->json($properties);
    }

    // Propiedades destacadas
    public function featured(Request $request)
    {
        $limit = $request->input('limit', 6);
        $properties = $this->propertyService->getFeatured($limit);

        return response()->json($properties);
    }

    // Buscar propiedades
    public function search(Request $request)
    {
        $term = $request->input('q', '');

        if (empty($term)) {
            return response()->json(['error' => 'Término de búsqueda requerido'], 400);
        }

        $properties = $this->propertyService->search($term);

        return response()->json($properties);
    }

    // Filtrar por rango de precio
    public function priceRange(Request $request)
    {
        $min = $request->input('min_price', 0);
        $max = $request->input('max_price', 999999);

        $properties = $this->propertyService->getByPriceRange($min, $max);

        return response()->json($properties);
    }

    // Detalle
    public function show($id)
    {
        $property = $this->propertyService->getById($id);

        return response()->json($property);
    }

    // Crear
    public function store(Request $request)
    {
        $property = $this->propertyService->create($request->all());

        return response()->json($property, 201);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $property = $this->propertyService->update($id, $request->all());

        return response()->json($property);
    }

    // Eliminar
    public function destroy($id)
    {
        $this->propertyService->delete($id);

        return response()->json(['message' => 'Propiedad eliminada']);
    }

    // Subir imágenes y devolver rutas públicas
    public function uploadImages(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'file|image|max:10240', // 10MB por imagen
        ]);

        $stored = [];
        $publicPath = public_path('uploads/properties');

        // Crear carpeta si no existe
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        foreach ($request->file('images', []) as $file) {
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($publicPath, $filename);
            $url = url('/uploads/properties/' . $filename);
            $stored[] = [
                'url' => $url,
                'path' => 'uploads/properties/' . $filename,
            ];
        }

        return response()->json([
            'images' => $stored,
        ], 201);
    }
}