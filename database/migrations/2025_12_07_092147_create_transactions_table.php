<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('transaction_type', ['registration_fee', 'listing_fee', 'boost_fee']);
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['mobile_money', 'bank_transfer', 'cash', 'airtel_money', 'tnm_mpamba']);
            $table->string('reference_number')->unique();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamps();
            
            $table->index('status');
            $table->index('reference_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
