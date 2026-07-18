<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_risk_analyses', function (Blueprint $table) {
            $table->id();

            // Foreign Key to Shipments
            $table->foreignId('shipment_id')
                  ->unique()
                  ->constrained('shipments')
                  ->onDelete('cascade');

            // Scores
            $table->decimal('route_risk_score', 5, 2)->default(0);
            $table->decimal('shipment_risk_score', 5, 2)->default(0);
            
            // Level: LOW (0-30), MEDIUM (31-70), HIGH (71-100)
            $table->enum('risk_level', ['LOW', 'MEDIUM', 'HIGH'])->default('LOW');

            // Summary notes
            $table->text('analysis_summary')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_risk_analyses');
    }
};
