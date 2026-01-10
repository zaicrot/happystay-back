<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $properties = DB::table('properties')->orderBy('id')->get();

        foreach ($properties as $property) {
            if (empty($property->images)) {
                continue;
            }

            $images = json_decode($property->images, true);

            // Handle case where images might not be an array or valid JSON
            if (!is_array($images)) {
                continue;
            }

            $updatedImages = array_map(function ($url) {
                // Check if it's a string before replacing
                if (is_string($url)) {
                    return str_replace('website_c6cf0a7f', 'website_12ee8434', $url);
                }
                return $url;
            }, $images);

            if ($images !== $updatedImages) {
                DB::table('properties')
                    ->where('id', $property->id)
                    ->update(['images' => json_encode($updatedImages)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $properties = DB::table('properties')->orderBy('id')->get();

        foreach ($properties as $property) {
            if (empty($property->images)) {
                continue;
            }

            $images = json_decode($property->images, true);

            if (!is_array($images)) {
                continue;
            }

            $updatedImages = array_map(function ($url) {
                if (is_string($url)) {
                    return str_replace('website_12ee8434', 'website_c6cf0a7f', $url);
                }
                return $url;
            }, $images);

            if ($images !== $updatedImages) {
                DB::table('properties')
                    ->where('id', $property->id)
                    ->update(['images' => json_encode($updatedImages)]);
            }
        }
    }
};
