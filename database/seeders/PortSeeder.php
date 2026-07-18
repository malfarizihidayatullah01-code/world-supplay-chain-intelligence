<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;
use App\Models\Country;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        if (Port::count() > 0) {
            return;
        }

        $ports = [
            ['name' => 'Port of Singapore', 'code' => 'SGSIN', 'city' => 'Singapore', 'country_name' => 'Singapore', 'lat' => 1.264, 'lng' => 103.840],
            ['name' => 'Port of Shanghai', 'code' => 'CNSGH', 'city' => 'Shanghai', 'country_name' => 'China', 'lat' => 31.230, 'lng' => 121.473],
            ['name' => 'Port of Rotterdam', 'code' => 'NLRTM', 'city' => 'Rotterdam', 'country_name' => 'Netherlands', 'lat' => 51.922, 'lng' => 4.481],
            ['name' => 'Port of Busan', 'code' => 'KRPUS', 'city' => 'Busan', 'country_name' => 'South Korea', 'lat' => 35.101, 'lng' => 129.032],
            ['name' => 'Port of Los Angeles', 'code' => 'USLAX', 'city' => 'Los Angeles', 'country_name' => 'United States', 'lat' => 33.728, 'lng' => -118.262],
            ['name' => 'Port Klang', 'code' => 'MYPKG', 'city' => 'Klang', 'country_name' => 'Malaysia', 'lat' => 3.000, 'lng' => 101.400],
            ['name' => 'Tanjung Priok', 'code' => 'IDTPP', 'city' => 'Jakarta', 'country_name' => 'Indonesia', 'lat' => -6.110, 'lng' => 106.880],
            ['name' => 'Port of Antwerp', 'code' => 'BEANR', 'city' => 'Antwerp', 'country_name' => 'Belgium', 'lat' => 51.219, 'lng' => 4.402],
            ['name' => 'Port of Hamburg', 'code' => 'DEHAM', 'city' => 'Hamburg', 'country_name' => 'Germany', 'lat' => 53.548, 'lng' => 9.987],
            ['name' => 'Port of Hong Kong', 'code' => 'HKHKG', 'city' => 'Hong Kong', 'country_name' => 'Hong Kong', 'lat' => 22.333, 'lng' => 114.120],
            ['name' => 'Jebel Ali Port', 'code' => 'AEJEA', 'city' => 'Dubai', 'country_name' => 'United Arab Emirates', 'lat' => 24.985, 'lng' => 55.027],
            ['name' => 'Port of Ningbo-Zhoushan', 'code' => 'CNNGB', 'city' => 'Ningbo', 'country_name' => 'China', 'lat' => 29.868, 'lng' => 121.544],
            ['name' => 'Port of Guangzhou', 'code' => 'CNCAN', 'city' => 'Guangzhou', 'country_name' => 'China', 'lat' => 23.129, 'lng' => 113.264],
            ['name' => 'Port of Qingdao', 'code' => 'CNTAO', 'city' => 'Qingdao', 'country_name' => 'China', 'lat' => 36.067, 'lng' => 120.382],
            ['name' => 'Port of Shenzhen', 'code' => 'CNSZX', 'city' => 'Shenzhen', 'country_name' => 'China', 'lat' => 22.543, 'lng' => 114.057],
        ];

        foreach ($ports as $p) {
            $country = Country::where('country_name', 'like', '%' . $p['country_name'] . '%')->first();
            
            if ($country) {
                Port::create([
                    'country_id' => $country->id,
                    'port_code' => $p['code'],
                    'port_name' => $p['name'],
                    'city' => $p['city'],
                    'latitude' => $p['lat'],
                    'longitude' => $p['lng'],
                    'status' => 'Active',
                ]);
            }
        }
    }
}
