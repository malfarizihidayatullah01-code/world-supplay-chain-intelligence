<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_code', 50)->unique();
            $table->foreignId('origin_port_id')->constrained('ports')->onDelete('restrict');
            $table->foreignId('destination_port_id')->constrained('ports')->onDelete('restrict');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_routes');
    }
};
