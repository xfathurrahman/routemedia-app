<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::create([
            'name' => 'Basic Internet',
            'price' => 150000.00,
            'speed' => '10 Mbps',
        ]);
        Package::create([
            'name' => 'Standard Internet',
            'price' => 250000.00,
            'speed' => '30 Mbps',
        ]);
        Package::create([
            'name' => 'Premium Internet',
            'price' => 400000.00,
            'speed' => '100 Mbps',
        ]);
        Package::create([
            'name' => 'Fiber Optic',
            'price' => 600000.00,
            'speed' => '500 Mbps',
        ]);
    }
}
