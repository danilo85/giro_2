<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateBanksSeeder extends Seeder
{
    public function run()
    {
        $banks = DB::table('banks')->get();
        
        foreach ($banks as $bank) {
            DB::table('banks')
                ->where('id', $bank->id)
                ->update([
                    'agencia' => '0001',
                    'conta' => str_pad($bank->id, 6, '0', STR_PAD_LEFT) . '-' . rand(0, 9),
                    'tipo_conta' => 'corrente'
                ]);
        }
    }
}