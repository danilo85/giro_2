<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-test {--email=admin@admin.com} {--password=admin123} {--name=Administrador}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria um usuário de teste para desenvolvimento';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        // Verificar se o usuário já existe
        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser) {
            $this->info("Usuário já existe: {$existingUser->email}");
            $this->info("ID: {$existingUser->id}");
            $this->info("Nome: {$existingUser->name}");
            $this->info("Ativo: " . ($existingUser->is_active ? 'Sim' : 'Não'));
            $this->info("Role: {$existingUser->role}");
            
            if ($this->confirm('Deseja atualizar a senha deste usuário?')) {
                $existingUser->update([
                    'password' => Hash::make($password),
                    'is_active' => true
                ]);
                $this->info('Senha atualizada com sucesso!');
            }
            
            return 0;
        }

        // Criar novo usuário
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_active' => true,
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        $this->info('Usuário criado com sucesso!');
        $this->info("ID: {$user->id}");
        $this->info("Nome: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("Senha: {$password}");
        $this->info("Role: {$user->role}");
        
        $this->newLine();
        $this->info('Agora você pode fazer login em: http://localhost:8000/login');
        
        return 0;
    }
}