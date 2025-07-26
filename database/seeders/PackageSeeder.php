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
        $packages = [
            [
                'name' => 'Paket Rumahan',
                'price' => 150000.00,
                'speed' => '10 Mbps',
            ],
            [
                'name' => 'Paket Standar',
                'price' => 275000.00,
                'speed' => '30 Mbps',
            ],
            [
                'name' => 'Paket Bisnis',
                'price' => 450000.00,
                'speed' => '100 Mbps',
            ],
            [
                'name' => 'Paket Ultra Fiber',
                'price' => 650000.00,
                'speed' => '500 Mbps',
            ],
            [
                'name' => 'Paket Gamers',
                'price' => 350000.00,
                'speed' => '50 Mbps',
            ],
            [
                'name' => 'Paket Keluarga',
                'price' => 325000.00,
                'speed' => '40 Mbps',
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
