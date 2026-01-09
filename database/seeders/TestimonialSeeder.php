<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Testimonials\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Tatiana',
                'location' => 'Familia · Playa Señoritas',
                'rating' => 5,
                'text' => 'Un apartamento precioso, en la mejor ubicación con vista al mar. Limpio, amplio y con restaurantes a pasos. Will atento y con grandes recomendaciones.',
                'avatar' => 'TA',
                'is_active' => true,
            ],
            [
                'name' => 'David Bermúdez',
                'location' => 'Amigos · Punta Hermosa',
                'rating' => 5,
                'text' => 'Todo perfecto desde el check-in. William estuvo atento en todo momento y el depa impecable. Fácil de llegar, volvería a reservar.',
                'avatar' => 'DB',
                'is_active' => true,
            ],
            [
                'name' => 'Raquel',
                'location' => 'Estadía larga',
                'rating' => 5,
                'text' => 'Espacioso y limpio, con todo lo necesario: toallas, cocina completa y una vista hermosa. Edificio tranquilo y seguro, cerca de restaurantes.',
                'avatar' => 'RA',
                'is_active' => true,
            ],
            [
                'name' => 'Alisson',
                'location' => 'Pareja',
                'rating' => 5,
                'text' => 'William fue un anfitrión excelente. El departamento impecable y con vista hermosa; definitivamente volveríamos.',
                'avatar' => 'AL',
                'is_active' => true,
            ],
            [
                'name' => 'Lautaro',
                'location' => 'Amigos',
                'rating' => 5,
                'text' => 'El departamento es igual a las fotos, súper buena ubicación y Will siempre atento.',
                'avatar' => 'LA',
                'is_active' => true,
            ],
            [
                'name' => 'Adel',
                'location' => 'Viaje en pareja',
                'rating' => 5,
                'text' => 'El anfitrión muy amable, el lugar con vista increíble. Muy buena experiencia.',
                'avatar' => 'AD',
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
