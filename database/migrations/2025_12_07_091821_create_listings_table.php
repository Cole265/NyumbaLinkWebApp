<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('expiry_date');
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0);
            $table->integer('inquiry_count')->default(0);
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
