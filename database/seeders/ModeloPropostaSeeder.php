<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModeloProposta;
use App\Models\User;

class ModeloPropostaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Nenhum usuário encontrado. Execute UserSeeder primeiro.');
            return;
        }

        $modelos = [
            [
                'user_id' => $users->random()->id,
                'nome' => 'Desenvolvimento Web',
                'conteudo' => '<h2>Proposta de Desenvolvimento Web</h2>\n<p><strong>Objetivo:</strong> Desenvolvimento de site institucional responsivo</p>\n<h3>Escopo do Projeto:</h3>\n<ul>\n<li>Design responsivo para desktop, tablet e mobile</li>\n<li>Painel administrativo para gestão de conteúdo</li>\n<li>Integração com redes sociais</li>\n<li>Otimização para SEO</li>\n<li>Formulário de contato</li>\n</ul>\n<h3>Prazo:</h3>\n<p>30 dias úteis a partir da aprovação</p>\n<h3>Investimento:</h3>\n<p>R$ {{valor_total}}</p>',
                'categoria' => 'Desenvolvimento',
                'status' => 'ativo',
                'descricao' => 'Modelo padrão para propostas de desenvolvimento web',
                'observacoes' => 'Incluir sempre detalhes sobre hospedagem e domínio',
                'valor_padrao' => 8500.00,
                'prazo_padrao' => 30,

                'ativo' => true,
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Marketing Digital',
                'conteudo' => '<h2>Proposta de Marketing Digital</h2>\n<p><strong>Objetivo:</strong> Estratégia completa de marketing digital</p>\n<h3>Serviços Inclusos:</h3>\n<ul>\n<li>Análise de mercado e concorrência</li>\n<li>Criação de personas</li>\n<li>Gestão de redes sociais</li>\n<li>Campanhas Google Ads</li>\n<li>Email marketing</li>\n<li>Relatórios mensais de performance</li>\n</ul>\n<h3>Duração:</h3>\n<p>{{prazo_dias}} dias</p>\n<h3>Investimento:</h3>\n<p>R$ {{valor_total}}</p>',
                'categoria' => 'Marketing',
                'status' => 'ativo',
                'descricao' => 'Modelo para campanhas de marketing digital',
                'observacoes' => 'Sempre incluir métricas e KPIs esperados',
                'valor_padrao' => 12000.00,
                'prazo_padrao' => 60,

                'ativo' => true,
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Identidade Visual',
                'conteudo' => '<h2>Proposta de Identidade Visual</h2>\n<p><strong>Objetivo:</strong> Criação de identidade visual completa</p>\n<h3>Entregáveis:</h3>\n<ul>\n<li>Logotipo principal e variações</li>\n<li>Manual de marca</li>\n<li>Paleta de cores</li>\n<li>Tipografia institucional</li>\n<li>Aplicações em papelaria</li>\n<li>Mockups digitais</li>\n</ul>\n<h3>Processo:</h3>\n<ol>\n<li>Briefing detalhado</li>\n<li>Pesquisa e conceituação</li>\n<li>Criação de alternativas</li>\n<li>Refinamento</li>\n<li>Entrega final</li>\n</ol>\n<h3>Investimento:</h3>\n<p>R$ {{valor_total}}</p>',
                'categoria' => 'Design',
                'status' => 'ativo',
                'descricao' => 'Modelo para projetos de identidade visual',
                'observacoes' => 'Incluir número de revisões permitidas',
                'valor_padrao' => 4500.00,
                'prazo_padrao' => 20,

                'ativo' => true,
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Consultoria Empresarial',
                'conteudo' => '<h2>Proposta de Consultoria Empresarial</h2>\n<p><strong>Objetivo:</strong> Análise e otimização de processos</p>\n<h3>Metodologia:</h3>\n<ul>\n<li>Diagnóstico inicial</li>\n<li>Mapeamento de processos atuais</li>\n<li>Identificação de gargalos</li>\n<li>Proposição de melhorias</li>\n<li>Implementação assistida</li>\n<li>Acompanhamento pós-implementação</li>\n</ul>\n<h3>Resultados Esperados:</h3>\n<ul>\n<li>Redução de custos operacionais</li>\n<li>Aumento da produtividade</li>\n<li>Melhoria na qualidade dos processos</li>\n</ul>\n<h3>Investimento:</h3>\n<p>R$ {{valor_total}}</p>',
                'categoria' => 'Consultoria',
                'status' => 'ativo',
                'descricao' => 'Modelo para serviços de consultoria',
                'observacoes' => 'Definir claramente os entregáveis',
                'valor_padrao' => 15000.00,
                'prazo_padrao' => 45,

                'ativo' => true,
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Produção Audiovisual',
                'conteudo' => '<h2>Proposta de Produção Audiovisual</h2>\n<p><strong>Objetivo:</strong> Criação de conteúdo audiovisual</p>\n<h3>Serviços:</h3>\n<ul>\n<li>Roteiro e storyboard</li>\n<li>Filmagem em locação</li>\n<li>Edição e pós-produção</li>\n<li>Motion graphics</li>\n<li>Trilha sonora</li>\n<li>Entrega em múltiplos formatos</li>\n</ul>\n<h3>Equipamentos:</h3>\n<ul>\n<li>Câmeras 4K</li>\n<li>Iluminação profissional</li>\n<li>Áudio de alta qualidade</li>\n</ul>\n<h3>Investimento:</h3>\n<p>R$ {{valor_total}}</p>',
                'categoria' => 'Audiovisual',
                'status' => 'ativo',
                'descricao' => 'Modelo para produções audiovisuais',
                'observacoes' => 'Especificar duração e formato final',
                'valor_padrao' => 7800.00,
                'prazo_padrao' => 25,

                'ativo' => true,
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'E-commerce',
                'conteudo' => '<h2>Proposta de E-commerce</h2>\n<p><strong>Objetivo:</strong> Desenvolvimento de loja virtual</p>\n<h3>Funcionalidades:</h3>\n<ul>\n<li>Catálogo de produtos</li>\n<li>Carrinho de compras</li>\n<li>Múltiplas formas de pagamento</li>\n<li>Gestão de estoque</li>\n<li>Painel administrativo</li>\n<li>Relatórios de vendas</li>\n<li>Integração com correios</li>\n</ul>\n<h3>Tecnologias:</h3>\n<ul>\n<li>Plataforma responsiva</li>\n<li>SSL incluso</li>\n<li>Backup automático</li>\n</ul>\n<h3>Investimento:</h3>\n<p>R$ {{valor_total}}</p>',
                'categoria' => 'E-commerce',
                'status' => 'rascunho',
                'descricao' => 'Modelo para lojas virtuais',
                'observacoes' => 'Verificar integrações necessárias',
                'valor_padrao' => 18000.00,
                'prazo_padrao' => 50,

                'ativo' => false, // Inativo para teste
            ],
        ];

        foreach ($modelos as $modeloData) {
            ModeloProposta::firstOrCreate(
                [
                    'nome' => $modeloData['nome'],
                    'user_id' => $modeloData['user_id']
                ],
                $modeloData
            );
        }

        $this->command->info('Modelos de propostas criados com sucesso!');
    }
}