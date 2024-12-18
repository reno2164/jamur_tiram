<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar nama pengguna
        $usernames = [
            'Mulyo', 'Joko',
            'Siti', 'Upik', 'Gina', 'Juanidi', 'Ipah',
            'Indah', 'Dwi', 'Esti', 'Putra', 'Surya',
            'Lestari', 'Shifa', 'Sylvi', 'Eko', 'Ari',
        ];

        // Loop untuk membuat user
        foreach ($usernames as $username) {
            User::create([
                'username' => $username,
                'email' => strtolower(str_replace(' ', '', $username)) . '@gmail.com', // Generate email otomatis
                'password' => Hash::make('12345678'), // Password default
                'role' => 'USR', // Role User
            ]);
        }
    }
}