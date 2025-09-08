@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Teste de Pagamento - Debug</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Informações de Debug:</strong><br>
                        <small>
                            Session ID: {{ session()->getId() }}<br>
                            CSRF Token: {{ csrf_token() }}<br>
                            User ID: {{ auth()->id() }}<br>
                            User: {{ auth()->user()->name ?? 'Não logado' }}
                        </small>
                    </div>

                    <form method="POST" action="{{ route('pagamentos.store') }}" id="payment-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="orcamento_id" class="form-label">Orçamento</label>
                            <select class="form-control" id="orcamento_id" name="orcamento_id" required>
                                <option value="">Selecione um orçamento</option>
                                <option value="1" selected>Orçamento #1 - Teste</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <input type="number" class="form-control" id="valor" name="valor" value="100.00" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label for="data_pagamento" class="form-label">Data do Pagamento</label>
                            <input type="date" class="form-control" id="data_pagamento" name="data_pagamento" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="forma_pagamento" class="form-label">Forma de Pagamento</label>
                            <select class="form-control" id="forma_pagamento" name="forma_pagamento" required>
                                <option value="pix" selected>PIX</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="cartao_credito">Cartão de Crédito</option>
                                <option value="cartao_debito">Cartão de Débito</option>
                                <option value="transferencia">Transferência</option>
                                <option value="boleto">Boleto</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="bank_id" class="form-label">Banco</label>
                            <select class="form-control" id="bank_id" name="bank_id">
                                <option value="">Selecione um banco</option>
                                <option value="1" selected>Banco do Brasil</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3">Teste via navegador - Debug</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Salvar Pagamento</button>
                    </form>

                    <div class="mt-4">
                        <h5>Debug JavaScript</h5>
                        <button type="button" class="btn btn-info" onclick="debugCsrf()">Verificar CSRF Token</button>
                        <div id="debug-output" class="mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function debugCsrf() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const formToken = document.querySelector('input[name="_token"]')?.value;
    
    const output = document.getElementById('debug-output');
    output.innerHTML = `
        <div class="alert alert-info">
            <strong>CSRF Debug:</strong><br>
            Meta token: ${token || 'NÃO ENCONTRADO'}<br>
            Form token: ${formToken || 'NÃO ENCONTRADO'}<br>
            Tokens iguais: ${token === formToken ? 'SIM' : 'NÃO'}
        </div>
    `;
}

// Configurar CSRF token para AJAX (se necessário)
if (window.axios) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    }
}
</script>
@endsection