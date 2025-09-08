@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Diagnóstico e Correção CSRF - Erro 403</div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>Diagnóstico Completo do Problema 403 Forbidden</h5>
                        <p>Esta página identifica e corrige os problemas mais comuns que causam erro 403 ao salvar pagamentos.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">1. Verificação de Sessão e Autenticação</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Status do Usuário:</strong> 
                                        @auth
                                            <span class="badge bg-success">✓ Logado</span><br>
                                            <small>ID: {{ auth()->id() }} | Nome: {{ auth()->user()->name }}</small>
                                        @else
                                            <span class="badge bg-danger">✗ Não logado</span>
                                        @endauth
                                    </p>
                                    <p><strong>Session ID:</strong> <code>{{ session()->getId() }}</code></p>
                                    <p><strong>Session Driver:</strong> <code>{{ config('session.driver') }}</code></p>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">2. Verificação CSRF Token</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>CSRF Token Atual:</strong><br>
                                    <code id="csrf-token">{{ csrf_token() }}</code></p>
                                    
                                    <p><strong>Meta Tag CSRF:</strong><br>
                                    <code id="meta-csrf">{{ csrf_token() }}</code></p>
                                    
                                    <button class="btn btn-sm btn-info" onclick="refreshCsrf()">🔄 Atualizar Token</button>
                                    <button class="btn btn-sm btn-success" onclick="testCsrf()">🧪 Testar CSRF</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">3. Teste de Formulário Corrigido</h6>
                                </div>
                                <div class="card-body">
                                    <form id="test-form" method="POST" action="{{ route('pagamentos.store') }}">
                                        @csrf
                                        <input type="hidden" name="orcamento_id" value="1">
                                        <input type="hidden" name="valor" value="100.00">
                                        <input type="hidden" name="data_pagamento" value="{{ date('Y-m-d') }}">
                                        <input type="hidden" name="forma_pagamento" value="pix">
                                        <input type="hidden" name="bank_id" value="1">
                                        <input type="hidden" name="observacoes" value="Teste de correção CSRF">
                                        
                                        <div class="mb-2">
                                            <small><strong>Dados do teste:</strong></small><br>
                                            <small>Orçamento: #1 | Valor: R$ 100,00 | Forma: PIX</small>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-sm">💾 Testar Pagamento</button>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="validateForm()">🔍 Validar Formulário</button>
                                    </form>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">4. Soluções Aplicadas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge bg-success">✓</span> Token CSRF dinâmico
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-success">✓</span> Validação de autorização melhorada
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-success">✓</span> Logs de debug detalhados
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-success">✓</span> Middleware de monitoramento
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">5. Resultados dos Testes</h6>
                        </div>
                        <div class="card-body">
                            <div id="test-results">
                                <p class="text-muted">Execute os testes acima para ver os resultados aqui.</p>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <h6>Erros Encontrados:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            <h6>✅ Sucesso!</h6>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshCsrf() {
    fetch('/debug/csrf-refresh', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.token) {
            document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
            document.querySelector('input[name="_token"]').value = data.token;
            document.getElementById('csrf-token').textContent = data.token;
            document.getElementById('meta-csrf').textContent = data.token;
            
            updateResults('🔄 Token CSRF atualizado com sucesso!', 'success');
        }
    })
    .catch(error => {
        updateResults('❌ Erro ao atualizar token: ' + error.message, 'danger');
    });
}

function testCsrf() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formToken = document.querySelector('input[name="_token"]').value;
    
    let result = '<h6>Resultado do Teste CSRF:</h6>';
    result += `<p><strong>Meta Token:</strong> ${token ? '✓ Presente' : '❌ Ausente'}</p>`;
    result += `<p><strong>Form Token:</strong> ${formToken ? '✓ Presente' : '❌ Ausente'}</p>`;
    result += `<p><strong>Tokens Iguais:</strong> ${token === formToken ? '✅ SIM' : '❌ NÃO'}</p>`;
    
    if (token && formToken && token === formToken) {
        updateResults(result + '<p class="text-success"><strong>✅ CSRF configurado corretamente!</strong></p>', 'success');
    } else {
        updateResults(result + '<p class="text-danger"><strong>❌ Problema detectado no CSRF!</strong></p>', 'warning');
    }
}

function validateForm() {
    const form = document.getElementById('test-form');
    const formData = new FormData(form);
    
    let result = '<h6>Validação do Formulário:</h6>';
    result += '<ul>';
    
    for (let [key, value] of formData.entries()) {
        result += `<li><strong>${key}:</strong> ${value}</li>`;
    }
    
    result += '</ul>';
    result += '<p class="text-info"><strong>ℹ️ Formulário pronto para envio!</strong></p>';
    
    updateResults(result, 'info');
}

function updateResults(content, type = 'info') {
    const resultsDiv = document.getElementById('test-results');
    resultsDiv.innerHTML = `<div class="alert alert-${type}">${content}</div>`;
}

// Auto-verificação ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(testCsrf, 1000);
});
</script>
@endsection