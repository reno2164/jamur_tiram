<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User ID dari 10 sampai 26
        for ($userId = 11; $userId <= 22; $userId++) {
            Address::create([
                'user_id' => $userId,
                'name' => "Alamat User $userId", // Nama alamat
                'phone_number' => '08123456789' . $userId, // Nomor telepon unik
                'address' => "Jalan tirtajaya No.$userId, plh", // Alamat contoh
                'is_default' => 0, // Default 0
            ]);
        }
    }
}