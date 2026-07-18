<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingRoute;
use App\Models\Port;

class ShippingRouteSeeder extends Seeder
{
    public function run(): void
    {
        if (ShippingRoute::count() > 0) {
            return;
        }

        $ports = Port::take(15)->get();

        if ($ports->count() < 2) {
            return;
        }

        // Generate 15 sample routes
        for ($i = 0; $i < 15; $i++) {
            $origin = $ports->random();
            $destination = $ports->except($origin->id)->random();

            ShippingRoute::create([
                'route_code' => 'RTE-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'origin_port_id' => $origin->id,
                'destination_port_id' => $destination->id,
                'status' => 'Active',
            ]);
        }
    }
}
