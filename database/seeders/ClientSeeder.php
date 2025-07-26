<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indonesianNames = [
            'Budi Santoso', 'Siti Rahayu', 'Ahmad Wijaya', 'Rina Wati',
            'Agus Sutanto', 'Dewi Lestari', 'Joko Susilo', 'Ani Permata',
            'Bambang Prakoso', 'Maya Anggraini', 'Dedi Supriadi', 'Lia Kurniawan',
            'Hendra Setiawan', 'Wati Kusuma', 'Eko Prasetyo', 'Ratna Sari'
        ];

        $indonesianAddresses = [
            'Jl. Sudirman No. 45, RT 03/RW 05, Jakarta Pusat',
            'Jl. Gatot Subroto No. 12, RT 08/RW 03, Jakarta Selatan',
            'Jl. Ahmad Yani No. 78, RT 06/RW 02, Bandung',
            'Jl. Diponegoro No. 23, RT 02/RW 04, Surabaya',
            'Jl. Pahlawan No. 56, RT 05/RW 01, Semarang',
            'Jl. Merdeka No. 89, RT 04/RW 07, Yogyakarta',
            'Jl. Veteran No. 34, RT 07/RW 06, Malang',
            'Jl. Cendrawasih No. 67, RT 01/RW 03, Denpasar',
            'Jl. Pemuda No. 123, RT 09/RW 05, Makassar',
            'Jl. Cendana No. 90, RT 04/RW 02, Palembang',
            'Jl. Hayam Wuruk No. 111, RT 02/RW 08, Medan',
            'Jl. KH. Agus Salim No. 54, RT 05/RW 03, Bekasi'
        ];

        for ($i = 0; $i < 12; $i++) {
            Client::create([
                'name' => $indonesianNames[$i],
                'address' => $indonesianAddresses[$i],
                'whatsapp_number' => '628' . mt_rand(1000000000, 9999999999),
                'id_card_photo_path' => null, // KTP field left empty as requested
                'package_id' => mt_rand(1, 6), // Random package ID between 1-6
            ]);
        }
    }
}
