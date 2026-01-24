<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landlord_id')->constrained('landlord_profiles')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenant_profiles')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->integer('communication_rating'); // 1-5
            $table->integer('accuracy_rating'); // 1-5
            $table->integer('cleanliness_rating'); // 1-5
            $table->integer('professionalism_rating'); // 1-5
            $table->integer('fairness_rating'); // 1-5
            $table->decimal('overall_rating', 3, 2); // Calculated average
            $table->text('review')->nullable();
            $table->timestamps();
            
            // Prevent duplicate ratings
            $table->unique(['landlord_id', 'tenant_id', 'property_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
