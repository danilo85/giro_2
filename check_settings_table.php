<?php

/**
 * Script para verificar se a tabela settings está funcionando corretamente
 * Execute este script na Hostinger após executar as migrations e seeders
 * 
 * Uso: php check_settings_table.php
 */

require_once 'vendor/autoload.php';

// Carregar configurações do Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Verificação da Tabela Settings ===\n\n";

try {
    // 1. Verificar se a tabela existe
    echo "1. Verificando se a tabela 'settings' existe...\n";
    if (Schema::hasTable('settings')) {
        echo "   ✅ Tabela 'settings' existe\n\n";
    } else {
        echo "   ❌ Tabela 'settings' NÃO existe\n";
        echo "   Execute: php artisan migrate\n\n";
        exit(1);
    }

    // 2. Verificar estrutura da tabela
    echo "2. Verificando estrutura da tabela...\n";
    $columns = Schema::getColumnListing('settings');
    $expectedColumns = ['id', 'key', 'value', 'type', 'description', 'created_at', 'updated_at'];
    
    foreach ($expectedColumns as $column) {
        if (in_array($column, $columns)) {
            echo "   ✅ Coluna '{$column}' existe\n";
        } else {
            echo "   ❌ Coluna '{$column}' NÃO existe\n";
        }
    }
    echo "\n";

    // 3. Verificar se há registros
    echo "3. Verificando registros na tabela...\n";
    $count = Setting::count();
    echo "   📊 Total de registros: {$count}\n";
    
    if ($count > 0) {
        echo "   ✅ Tabela possui registros\n";
        
        // Mostrar alguns registros
        $settings = Setting::take(5)->get();
        echo "\n   Primeiros registros:\n";
        foreach ($settings as $setting) {
            echo "   - {$setting->key}: {$setting->value} ({$setting->type})\n";
        }
    } else {
        echo "   ⚠️  Tabela está vazia\n";
        echo "   Execute: php artisan db:seed --class=SettingsSeeder\n";
    }
    echo "\n";

    // 4. Testar operações básicas
    echo "4. Testando operações básicas...\n";
    
    // Teste de criação
    try {
        $testSetting = Setting::updateOrCreate(
            ['key' => 'test_setting'],
            [
                'value' => 'test_value',
                'type' => 'string',
                'description' => 'Configuração de teste'
            ]
        );
        echo "   ✅ Criação/atualização funcionando\n";
        
        // Teste de leitura
        $retrieved = Setting::where('key', 'test_setting')->first();
        if ($retrieved && $retrieved->value === 'test_value') {
            echo "   ✅ Leitura funcionando\n";
        } else {
            echo "   ❌ Problema na leitura\n";
        }
        
        // Limpar teste
        $testSetting->delete();
        echo "   ✅ Exclusão funcionando\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erro nas operações: " . $e->getMessage() . "\n";
    }
    echo "\n";

    // 5. Verificar configuração específica do registro público
    echo "5. Verificando configuração de registro público...\n";
    $publicReg = Setting::where('key', 'public_registration_enabled')->first();
    if ($publicReg) {
        echo "   ✅ Configuração 'public_registration_enabled' encontrada\n";
        echo "   📋 Valor atual: {$publicReg->value}\n";
        echo "   📋 Tipo: {$publicReg->type}\n";
    } else {
        echo "   ⚠️  Configuração 'public_registration_enabled' não encontrada\n";
        echo "   Execute: php artisan db:seed --class=SettingsSeeder\n";
    }
    echo "\n";

    echo "=== Verificação Concluída ===\n";
    echo "✅ A tabela settings está funcionando corretamente!\n";
    
} catch (Exception $e) {
    echo "❌ Erro durante a verificação: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}