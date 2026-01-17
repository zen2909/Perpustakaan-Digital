<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        $users = [
            // Admin user
            [
                'name' => 'Admin Perpustakaan',
                'email' => 'admin@perpustakaan.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Jl. Perpustakaan No. 1, Kota Buku',
                'photo' => 'users/admin-profile.jpg',
                'status' => 'active',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Librarian user
            [
                'name' => 'Petugas Perpustakaan',
                'email' => 'petugas@perpustakaan.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'librarian',
                'phone' => '081234567891',
                'address' => 'Jl. Buku Indah No. 5, Kota Buku',
                'photo' => 'users/librarian-profile.jpg',
                'status' => 'active',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Member user
            [
                'name' => 'Anggota Perpustakaan',
                'email' => 'anggota@perpustakaan.com',
                'email_verified_at' => null, // belum verifikasi email
                'password' => Hash::make('password123'),
                'role' => 'member',
                'phone' => '081234567892',
                'address' => 'Jl. Membaca No. 10, Kota Buku',
                'photo' => null, // tidak ada foto
                'status' => 'pending',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('User seeder berhasil: 3 User created!');
    }
}