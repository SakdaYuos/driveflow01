<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand');
            $table->string('model')->nullable();
            $table->unsignedSmallInteger('year');
            $table->string('license_plate')->nullable()->unique();
            $table->string('type')->default('Sedan');
            $table->string('city')->default('Phnom Penh');
            $table->string('fuel_type')->default('Petrol');
            $table->string('car_seat')->nullable();
            $table->unsignedTinyInteger('seats')->default(5);
            $table->decimal('price_per_day', 10, 2);
            $table->enum('status', ['Available', 'Rented', 'Maintenance'])->default('Available');
            $table->boolean('is_available')->default(true);
            $table->string('pickup_option')->default('Self Pick-up');
            $table->string('host_name')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->unsignedInteger('trips_count')->default(0);
            $table->string('emoji')->default('🚗');
            $table->json('features')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status', 'city']);
            $table->index('brand');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
