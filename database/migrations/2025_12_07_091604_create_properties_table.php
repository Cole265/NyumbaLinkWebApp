<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
{
    Schema::create('properties', function (Blueprint $table) {
        $table->id();
        $table->foreignId('landlord_id')->constrained('landlord_profiles')->cascadeOnDelete();
        $table->enum('property_type', ['residential', 'land', 'commercial']);
        $table->string('title');
        $table->text('description');
        $table->string('city');
        $table->string('district');
        $table->string('area')->nullable();
        $table->decimal('price', 12, 2);
        $table->enum('currency', ['MWK', 'USD'])->default('MWK');
        $table->integer('bedrooms')->nullable();
        $table->integer('bathrooms')->nullable();
        $table->decimal('size_sqm', 10, 2)->nullable();
        $table->boolean('is_furnished')->default(false);
        
        // GPS coordinates - FIXED
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
        
        $table->enum('status', ['draft', 'pending_review', 'published', 'rented', 'sold', 'expired'])->default('draft');
        $table->timestamps();
        
        // Indexes for search performance
        $table->index('city');
        $table->index('property_type');
        $table->index('status');
        $table->index('price');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
