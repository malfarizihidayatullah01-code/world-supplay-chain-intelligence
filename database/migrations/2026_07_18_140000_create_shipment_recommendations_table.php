<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_recommendations', function (Blueprint $table) {
            $table->id();

            // Foreign Key to Shipments
            $table->foreignId('shipment_id')
                  ->unique()
                  ->constrained('shipments')
                  ->onDelete('cascade');

            $table->decimal('shipment_risk_score', 5, 2)->default(0);
            
            $table->string('recommendation', 255);
            $table->string('action_required', 255);
            
            $table->enum('recommendation_status', [
                'Approved',
                'Monitoring',
                'Attention Required'
            ])->default('Approved');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_recommendations');
    }
};
