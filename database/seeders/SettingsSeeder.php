<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'public_registration_enabled'],
            [
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Controla se o registro público de novos usuários está habilitado'
            ]
        );
    }
}
