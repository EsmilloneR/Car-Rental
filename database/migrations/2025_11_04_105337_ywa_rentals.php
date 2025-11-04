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
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('pickup_lat');
            $table->dropColumn('pickup_lng');
            $table->dropColumn('dropoff_lat');
            $table->dropColumn('dropoff_lng');
            $table->decimal('reservation_fee', 10, 2)
            ->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['reservation_fee']);
        });
    }
};
