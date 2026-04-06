<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique()->nullable();

            // Who & what
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();

            // Admin panel legacy FK (kept for compatibility, same as user_id)
            $table->unsignedBigInteger('customer_id')->nullable()->index();

            // Trip dates — admin uses pickup/return, customer uses start/end
            $table->date('pickup_date')->nullable();
            $table->date('return_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->default('10:00:00');
            $table->time('end_time')->default('10:00:00');
            $table->unsignedSmallInteger('days')->default(1);

            // Options
            $table->enum('pickup_option', ['self', 'delivery'])->default('self');
            $table->enum('rate_type', ['non_refundable', 'refundable'])->default('non_refundable');

            // Driver info
            $table->string('driver_first_name')->nullable();
            $table->string('driver_last_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('driver_email')->nullable();
            $table->string('driver_license')->nullable();
            $table->date('driver_license_expiry')->nullable();

            // Pricing
            $table->decimal('total_price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('rate_extra', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Payment
            $table->string('card_last_four')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->string('card_expiry')->nullable();
            $table->string('transaction_id')->nullable();

            // Status
            $table->string('status')->default('Pending');
            $table->string('booking_status')->default('confirmed');
            $table->string('payment_status')->default('Unpaid');
            $table->enum('payment_method', ['card', 'aba', 'cash'])->default('cash');

            // Review & notes
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('review_text')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'booking_status']);
            $table->index('car_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
