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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturer_id')->constrained()->cascadeOnDelete();
            $table->string('model');
            $table->string('year');
            $table->string('plate_number')->unique();
            $table->string('color')->nullable();
            $table->enum('transmission', ['automatic', 'manual']);
            $table->string('seats')->default(5);

            $table->string('avatar')->nullable();
            $table->json('photos');

            $table->decimal('rate_hour',10,2)->nullable();
            $table->decimal('rate_day',10,2)->nullable();
            $table->decimal('rate_week',10,2)->nullable();

            $table->longText('description')->nullable();

            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
