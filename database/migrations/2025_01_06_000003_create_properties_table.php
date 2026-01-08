<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('period')->default('noche');
            $table->unsignedInteger('guests')->default(1);
            $table->unsignedInteger('bedrooms')->default(1);
            $table->unsignedInteger('bathrooms')->default(1);
            $table->boolean('featured')->default(false)->index();
            $table->string('airbnb_url')->nullable();
            $table->longText('description')->nullable();
            $table->text('address')->nullable();
            $table->json('amenities')->nullable();
            $table->timestamps();
            $table->index('created_at');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
