<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::create('service_offers', function (Blueprint $table) {
    $table->id();

    $table->foreignId('service_request_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('provider_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->decimal('min_price', 10, 2);
    $table->decimal('max_price', 10, 2);

    $table->text('message')->nullable();

    $table->enum('status', [
        'pending',
        'accepted',
        'rejected'
    ])->default('pending');

    $table->timestamps();

 
    $table->unique(['service_request_id', 'provider_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_offers');
    }
};
