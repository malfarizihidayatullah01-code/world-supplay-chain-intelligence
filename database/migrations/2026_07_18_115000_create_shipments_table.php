<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_code', 20)->unique();

            // Origin
            $table->foreignId('origin_country_id')->constrained('countries')->onDelete('restrict');
            $table->foreignId('origin_port_id')->constrained('ports')->onDelete('restrict');

            // Destination
            $table->foreignId('destination_country_id')->constrained('countries')->onDelete('restrict');
            $table->foreignId('destination_port_id')->constrained('ports')->onDelete('restrict');

            // Cargo
            $table->string('cargo_type', 100);
            $table->text('cargo_description')->nullable();

            // Schedule
            $table->date('departure_date');
            $table->date('estimated_arrival');

            // Status
            $table->enum('shipment_status', [
                'Planning',
                'In Transit',
                'Delivered',
                'Delayed',
                'Cancelled',
            ])->default('Planning');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
