<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\User;
use Exception;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email? : Email address to send test email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if (!$email) {
            $email = $this->ask('Digite o email para enviar o teste:');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email inválido!');
            return 1;
        }
        
        $this->info('Testando configuração de email...');
        $this->info('Email de destino: ' . $email);
        $this->info('Configurações atuais:');
        $this->info('- MAIL_MAILER: ' . config('mail.default'));
        $this->info('- MAIL_HOST: ' . config('mail.mailers.smtp.host'));
        $this->info('- MAIL_PORT: ' . config('mail.mailers.smtp.port'));
        $this->info('- MAIL_FROM: ' . config('mail.from.address'));
        
        try {
            Mail::raw('Este é um email de teste do sistema Giro.\n\nSe você recebeu este email, a configuração SMTP está funcionando corretamente!\n\nData/Hora: ' . now()->format('d/m/Y H:i:s'), function (Message $message) use ($email) {
                $message->to($email)
                        ->subject('Teste de Email - ' . config('app.name'))
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            $this->info('✅ Email de teste enviado com sucesso!');
            $this->info('Verifique a caixa de entrada (e spam) do email: ' . $email);
            
            return 0;
            
        } catch (Exception $e) {
            $this->error('❌ Erro ao enviar email:');
            $this->error($e->getMessage());
            $this->newLine();
            $this->warn('Verifique:');
            $this->warn('1. As configurações SMTP no arquivo .env');
            $this->warn('2. Se as credenciais estão corretas');
            $this->warn('3. Se o servidor SMTP está acessível');
            $this->warn('4. Se a porta não está bloqueada pelo firewall');
            
            return 1;
        }
    }
}