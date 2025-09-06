<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@giro.com'],
            [
                'name' => 'Administrador',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'is_active' => true,
                'is_online' => false
            ]
        );

        // Create standard user
        User::updateOrCreate(
            ['email' => 'user@giro.com'],
            [
                'name' => 'Usuário Padrão',
                'email_verified_at' => now(),
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'is_active' => true,
                'is_online' => false
            ]
        );
    }
}
