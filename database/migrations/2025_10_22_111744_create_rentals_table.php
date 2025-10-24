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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();

            $table->string('agreement_no')->unique();

            $table->dateTime('rental_start')->nullable();
            $table->dateTime('rental_end')->nullable();

            $table->string('pickup_location')->nullable();
            $table->string('dropOff_location')->nullable();

            $table->enum('trip_type', ['pickup_dropOff', 'hrs', 'roundtrip', 'days', 'weeks', 'months'])->default('days');

            $table->enum('status', ['pending','reserved', 'ongoing', 'completed', 'cancelled'])->default('pending');

            $table->decimal('base_amount', 10,2)->default(0);
            $table->decimal('deposit', 10,2)->default(0);
            $table->decimal('extra_charges', 10,2)->default(0);
            $table->decimal('penalties', 10,2)->default(0);
            $table->decimal('total', 10,2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
