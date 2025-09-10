<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Orcamento;
use App\Models\Cliente;
use App\Models\Autor;
use Carbon\Carbon;

class OrcamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = Cliente::all();
        $autores = Autor::all();
        
        if ($clientes->isEmpty()) {
            $this->command->warn('Nenhum cliente encontrado. Execute ClienteSeeder primeiro.');
            return;
        }
        
        if ($autores->isEmpty()) {
            $this->command->warn('Nenhum autor encontrado. Execute AutorSeeder primeiro.');
            return;
        }

        $orcamentos = [
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Desenvolvimento de Site Institucional',
                'descricao' => 'Criação de site institucional responsivo com painel administrativo.',
                'valor_total' => 8500.00,
                'prazo_dias' => 30,
                'data_orcamento' => Carbon::now()->subDays(15),
                'data_validade' => Carbon::now()->addDays(15),
                'status' => Orcamento::STATUS_APROVADO,
                'observacoes' => 'Cliente aprovou proposta. Iniciar desenvolvimento.',
                'observacoes_internas' => 'Prioridade alta - cliente estratégico.',
                'autores_ids' => $autores->random(2)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Campanha de Marketing Digital',
                'descricao' => 'Estratégia completa de marketing digital incluindo redes sociais e Google Ads.',
                'valor_total' => 12000.00,
                'prazo_dias' => 60,
                'data_orcamento' => Carbon::now()->subDays(10),
                'data_validade' => Carbon::now()->addDays(20),
                'status' => Orcamento::STATUS_ANALISANDO,
                'observacoes' => 'Aguardando retorno do cliente sobre ajustes solicitados.',
                'observacoes_internas' => 'Cliente pediu desconto de 10%.',
                'autores_ids' => $autores->random(3)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Identidade Visual Completa',
                'descricao' => 'Criação de logotipo, manual de marca e aplicações.',
                'valor_total' => 4500.00,
                'prazo_dias' => 20,
                'data_orcamento' => Carbon::now()->subDays(5),
                'data_validade' => Carbon::now()->addDays(25),
                'status' => Orcamento::STATUS_FINALIZADO,
                'observacoes' => 'Projeto finalizado com sucesso.',
                'observacoes_internas' => 'Cliente muito satisfeito com resultado.',
                'autores_ids' => $autores->random(1)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Aplicativo Mobile',
                'descricao' => 'Desenvolvimento de aplicativo mobile para iOS e Android.',
                'valor_total' => 25000.00,
                'prazo_dias' => 90,
                'data_orcamento' => Carbon::now()->subDays(30),
                'data_validade' => Carbon::now()->subDays(15),
                'status' => Orcamento::STATUS_REJEITADO,
                'observacoes' => 'Cliente optou por solução mais simples.',
                'observacoes_internas' => 'Valor considerado alto pelo cliente.',
                'autores_ids' => $autores->random(2)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Consultoria em Processos',
                'descricao' => 'Análise e otimização de processos empresariais.',
                'valor_total' => 15000.00,
                'prazo_dias' => 45,
                'data_orcamento' => Carbon::now()->subDays(20),
                'data_validade' => Carbon::now()->addDays(10),
                'status' => Orcamento::STATUS_PAGO,
                'observacoes' => 'Projeto concluído e pago integralmente.',
                'observacoes_internas' => 'Excelente parceria. Cliente quer novos projetos.',
                'autores_ids' => $autores->random(1)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Sessão de Fotos Corporativas',
                'descricao' => 'Sessão de fotos para equipe e instalações da empresa.',
                'valor_total' => 3200.00,
                'prazo_dias' => 7,
                'data_orcamento' => Carbon::now()->subDays(3),
                'data_validade' => Carbon::now()->addDays(27),
                'status' => Orcamento::STATUS_APROVADO,
                'observacoes' => 'Agendado para próxima semana.',
                'observacoes_internas' => 'Verificar disponibilidade de equipamentos.',
                'autores_ids' => $autores->random(1)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'E-commerce Completo',
                'descricao' => 'Desenvolvimento de loja virtual com integração de pagamentos.',
                'valor_total' => 18000.00,
                'prazo_dias' => 50,
                'data_orcamento' => Carbon::now()->subDays(7),
                'data_validade' => Carbon::now()->addDays(23),
                'status' => Orcamento::STATUS_RASCUNHO,
                'observacoes' => 'Aguardando definições finais do cliente.',
                'observacoes_internas' => 'Cliente ainda decidindo sobre funcionalidades.',
                'autores_ids' => $autores->random(3)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Vídeo Institucional',
                'descricao' => 'Produção de vídeo institucional com motion graphics.',
                'valor_total' => 7800.00,
                'prazo_dias' => 25,
                'data_orcamento' => Carbon::now()->subDays(12),
                'data_validade' => Carbon::now()->addDays(18),
                'status' => Orcamento::STATUS_FINALIZADO,
                'observacoes' => 'Vídeo entregue e aprovado pelo cliente.',
                'observacoes_internas' => 'Possível projeto de continuidade.',
                'autores_ids' => $autores->random(2)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Auditoria de UX',
                'descricao' => 'Análise completa da experiência do usuário no site atual.',
                'valor_total' => 5500.00,
                'prazo_dias' => 15,
                'data_orcamento' => Carbon::now()->subDays(8),
                'data_validade' => Carbon::now()->addDays(22),
                'status' => Orcamento::STATUS_ANALISANDO,
                'observacoes' => 'Cliente solicitou mais detalhes sobre metodologia.',
                'observacoes_internas' => 'Preparar apresentação detalhada.',
                'autores_ids' => $autores->random(1)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Sistema de Gestão',
                'descricao' => 'Desenvolvimento de sistema web para gestão interna.',
                'valor_total' => 22000.00,
                'prazo_dias' => 75,
                'data_orcamento' => Carbon::now()->subDays(25),
                'data_validade' => Carbon::now()->addDays(5),
                'status' => Orcamento::STATUS_APROVADO,
                'observacoes' => 'Projeto aprovado. Iniciar levantamento de requisitos.',
                'observacoes_internas' => 'Cliente tem urgência na entrega.',
                'autores_ids' => $autores->random(2)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Redesign de Interface',
                'descricao' => 'Modernização da interface do sistema existente.',
                'valor_total' => 9500.00,
                'prazo_dias' => 35,
                'data_orcamento' => Carbon::now()->subDays(18),
                'data_validade' => Carbon::now()->addDays(12),
                'status' => Orcamento::STATUS_FINALIZADO,
                'observacoes' => 'Interface entregue e implementada.',
                'observacoes_internas' => 'Cliente aprovou todas as telas.',
                'autores_ids' => $autores->random(2)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Copywriting para Site',
                'descricao' => 'Criação de textos persuasivos para todas as páginas do site.',
                'valor_total' => 2800.00,
                'prazo_dias' => 10,
                'data_orcamento' => Carbon::now()->subDays(2),
                'data_validade' => Carbon::now()->addDays(28),
                'status' => Orcamento::STATUS_RASCUNHO,
                'observacoes' => 'Aguardando briefing detalhado do cliente.',
                'observacoes_internas' => 'Cliente novo - primeira interação.',
                'autores_ids' => $autores->random(1)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Integração de Sistemas',
                'descricao' => 'Integração entre ERP e sistema de vendas online.',
                'valor_total' => 13500.00,
                'prazo_dias' => 40,
                'data_orcamento' => Carbon::now()->subDays(14),
                'data_validade' => Carbon::now()->addDays(16),
                'status' => Orcamento::STATUS_PAGO,
                'observacoes' => 'Integração concluída e testada.',
                'observacoes_internas' => 'Projeto complexo mas bem executado.',
                'autores_ids' => $autores->random(2)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Treinamento em Marketing',
                'descricao' => 'Workshop de marketing digital para equipe interna.',
                'valor_total' => 4200.00,
                'prazo_dias' => 5,
                'data_orcamento' => Carbon::now()->subDays(6),
                'data_validade' => Carbon::now()->addDays(24),
                'status' => Orcamento::STATUS_ANALISANDO,
                'observacoes' => 'Cliente verificando agenda da equipe.',
                'observacoes_internas' => 'Flexibilidade de datas é importante.',
                'autores_ids' => $autores->random(1)->pluck('id')->toArray(),
            ],
            [
                'cliente_id' => $clientes->random()->id,
                'titulo' => 'Manutenção de Site',
                'descricao' => 'Contrato de manutenção mensal do site institucional.',
                'valor_total' => 1200.00,
                'prazo_dias' => 30,
                'data_orcamento' => Carbon::now()->subDays(1),
                'data_validade' => Carbon::now()->addDays(29),
                'status' => Orcamento::STATUS_APROVADO,
                'observacoes' => 'Contrato mensal aprovado.',
                'observacoes_internas' => 'Renovação automática acordada.',
                'autores_ids' => $autores->random(1)->pluck('id')->toArray(),
            ],
        ];

        foreach ($orcamentos as $orcamentoData) {
            $autoresIds = $orcamentoData['autores_ids'];
            unset($orcamentoData['autores_ids']);
            
            $orcamento = Orcamento::create($orcamentoData);
            
            // Anexar autores ao orçamento
            $orcamento->autores()->attach($autoresIds);
        }

        $this->command->info('Orçamentos criados com sucesso!');
    }
}