<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_risk_analyses', function (Blueprint $table) {
            $table->id();

            // Each shipment has at most one Route Risk Analysis record
            $table->foreignId('shipment_id')
                  ->unique()
                  ->constrained('shipments')
                  ->onDelete('cascade');

            // Per-dimension risk scores (0–100)
            $table->decimal('origin_country_risk', 5, 2)->default(0);
            $table->decimal('destination_country_risk', 5, 2)->default(0);
            $table->decimal('weather_risk', 5, 2)->default(0);

            // Composite score and resulting level
            $table->decimal('route_score', 5, 2)->default(0);
            $table->enum('risk_level', ['LOW', 'MEDIUM', 'HIGH'])->default('LOW');

            // Human-readable notes on what drove the scores
            $table->text('analysis_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_risk_analyses');
    }
};
