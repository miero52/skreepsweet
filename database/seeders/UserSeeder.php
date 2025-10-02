<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin/Petugas - gunakan updateOrCreate untuk avoid duplikasi
        User::updateOrCreate(
            ['email' => 'admin@kemenag.go.id'],
            [
                'name' => 'Admin Kemenag',
                'password' => Hash::make('admin123'),
                'role' => 'petugas',
                'phone' => '08123456789',
                'status' => 'active',
            ]
        );

        // Petugas 1
        User::updateOrCreate(
            ['email' => 'petugas1@kemenag.go.id'],
            [
                'name' => 'Petugas 1',
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
                'phone' => '08123456790',
                'status' => 'active',
            ]
        );

        // User Masyarakat untuk testing
        User::updateOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'masyarakat',
                'phone' => '08123456791',
                'address' => 'Jl. Contoh No. 123, Palembang',
                'nik' => '1234567890123456',
                'status' => 'active',
            ]
        );
    }
}
