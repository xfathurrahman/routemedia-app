<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Package;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packageIds = Package::pluck('id')->toArray();

        Client::create([
            'name' => 'Alice Smith',
            'address' => '123 Main St, Anytown',
            'whatsapp_number' => '6281234567890',
            'id_card_photo_path' => 'dummy/alice_ktp.jpg',
            'package_id' => $packageIds[array_rand($packageIds)],
        ]);
        Client::create([
            'name' => 'Bob Johnson',
            'address' => '456 Oak Ave, Somewhere',
            'whatsapp_number' => '6281298765432',
            'id_card_photo_path' => 'dummy/bob_ktp.jpg',
            'package_id' => $packageIds[array_rand($packageIds)],
        ]);
        Client::create([
            'name' => 'Charlie Brown',
            'address' => '789 Pine Ln, Nowhere',
            'whatsapp_number' => '6281211223344',
            'id_card_photo_path' => 'dummy/charlie_ktp.jpg',
            'package_id' => $packageIds[array_rand($packageIds)],
        ]);
        Client::create([
            'name' => 'Diana Prince',
            'address' => '101 Justice Rd, Metropolis',
            'whatsapp_number' => '6281255667788',
            'id_card_photo_path' => 'dummy/diana_ktp.jpg',
            'package_id' => $packageIds[array_rand($packageIds)],
        ]);
    }
}
