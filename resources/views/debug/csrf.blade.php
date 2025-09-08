@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Debug - CSRF e Sessão</div>
                <div class="card-body">
                    <h5>Informações de Sessão</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Session ID:</strong> {{ session()->getId() }}</li>
                        <li class="list-group-item"><strong>CSRF Token:</strong> {{ csrf_token() }}</li>
                        <li class="list-group-item"><strong>Usuário Logado:</strong> {{ Auth::check() ? Auth::user()->name : 'Não logado' }}</li>
                        <li class="list-group-item"><strong>User ID:</strong> {{ Auth::check() ? Auth::id() : 'N/A' }}</li>
                        <li class="list-group-item"><strong>Usuário Ativo:</strong> {{ Auth::check() && Auth::user()->is_active ? 'Sim' : 'Não' }}</li>
                    </ul>

                    <h5>Teste de Formulário</h5>
                    <form id="testForm" action="{{ route('debug.test-csrf') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="test_data">Dados de Teste:</label>
                            <input type="text" class="form-control" id="test_data" name="test_data" value="Teste CSRF">
                        </div>
                        <button type="submit" class="btn btn-primary">Testar CSRF</button>
                    </form>

                    <div id="result" class="mt-3"></div>

                    <h5 class="mt-4">Teste de Pagamento</h5>
                    <form id="paymentTestForm" action="{{ route('pagamentos.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="orcamento_id">Orçamento:</label>
                                    <select class="form-control" id="orcamento_id" name="orcamento_id" required>
                                        @foreach(Auth::user()->orcamentos as $orcamento)
                                            <option value="{{ $orcamento->id }}">{{ $orcamento->titulo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="bank_id">Banco:</label>
                                    <select class="form-control" id="bank_id" name="bank_id" required>
                                        @foreach(Auth::user()->banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="valor">Valor:</label>
                                    <input type="number" class="form-control" id="valor" name="valor" value="100.00" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="data_pagamento">Data do Pagamento:</label>
                                    <input type="date" class="form-control" id="data_pagamento" name="data_pagamento" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="forma_pagamento">Forma de Pagamento:</label>
                            <select class="form-control" id="forma_pagamento" name="forma_pagamento" required>
                                <option value="pix">PIX</option>
                                <option value="transferencia">Transferência</option>
                                <option value="dinheiro">Dinheiro</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="observacoes">Observações:</label>
                            <textarea class="form-control" id="observacoes" name="observacoes">Teste de debug</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Testar Pagamento</button>
                    </form>

                    <div id="paymentResult" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Interceptar formulário de teste CSRF
document.getElementById('testForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        document.getElementById('result').innerHTML = 
            `<div class="alert alert-${response.ok ? 'success' : 'danger'}">
                Status: ${response.status} - ${response.statusText}
            </div>`;
        return response.text();
    })
    .then(data => {
        console.log('Resposta:', data);
    })
    .catch(error => {
        document.getElementById('result').innerHTML = 
            `<div class="alert alert-danger">Erro: ${error.message}</div>`;
    });
});

// Interceptar formulário de teste de pagamento
document.getElementById('paymentTestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        document.getElementById('paymentResult').innerHTML = 
            `<div class="alert alert-${response.ok ? 'success' : 'danger'}">
                Status: ${response.status} - ${response.statusText}
            </div>`;
        return response.text();
    })
    .then(data => {
        console.log('Resposta do pagamento:', data);
        if (data.includes('403') || data.includes('Forbidden')) {
            document.getElementById('paymentResult').innerHTML += 
                `<div class="alert alert-danger"><strong>ERRO 403 DETECTADO!</strong><br>Resposta: ${data}</div>`;
        }
    })
    .catch(error => {
        document.getElementById('paymentResult').innerHTML = 
            `<div class="alert alert-danger">Erro: ${error.message}</div>`;
    });
});
</script>
@endsection