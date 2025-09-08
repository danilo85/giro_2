<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo - {{ $pagamento->orcamento->titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .recibo-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
        }
        
        .titulo {
            font-size: 48px;
            font-weight: 600;
            color: #333;
            letter-spacing: 2px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background-color: #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            min-width: 150px;
        }
        
        .info-value {
            color: #333;
            flex: 1;
        }
        
        .valor-extenso {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        
        .assinatura-section {
            margin-top: 60px;
            text-align: center;
        }
        
        .assinatura-linha {
            border-top: 2px solid #333;
            width: 300px;
            margin: 40px auto 10px;
        }
        
        .assinatura-nome {
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }
        
        .botao-imprimir {
            text-align: center;
            margin-top: 40px;
        }
        
        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .recibo-container {
                box-shadow: none;
                padding: 20px;
            }
            
            .botao-imprimir {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="recibo-container">
        <div class="header">
            <h1 class="titulo">RECIBO</h1>
            <div class="logo">
                @php
                    $logo = $pagamento->orcamento->cliente->user->logos()->where('tipo', 'logo')->first();
                @endphp
                @if($logo && $logo->caminho)
                    <img src="{{ $logo->url }}" alt="Logo" style="max-height: 60px; max-width: 60px; object-fit: contain;">
                @else
                    Logomarca
                @endif
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Número:</span>
                <span class="info-value">{{ str_pad($pagamento->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Data de Emissão:</span>
                <span class="info-value">
                    @php
                        $dataExtenso = formatarDataExtenso($pagamento->data_pagamento);
                    @endphp
                    {{ $dataExtenso }}
                </span>
            </div>
        </div>
        
        <div class="info-section">
            <p style="line-height: 1.8; font-size: 16px; color: #333;">
                Eu, <strong>{{ $pagamento->orcamento->cliente->user->name }}</strong>, 
                inscrito(a) no CPF/CNPJ sob o nº <strong>{{ config('app.cnpj', 'Não informado') }}</strong>, 
                declaro para os devidos fins que recebi de <strong>{{ $pagamento->orcamento->cliente->nome }}</strong>, 
                a importância de <strong>{{ $pagamento->valor_formatted }}</strong> 
                <span style="font-style: italic;">({{ $pagamento->valor_extenso }})</span>, 
                referente ao pagamento de <strong>{{ strtoupper($pagamento->forma_pagamento_formatted) }}</strong> 
                do projeto "<strong>{{ $pagamento->orcamento->titulo }}</strong>".
            </p>
        </div>
        
        @if($pagamento->observacoes)
        <div class="valor-extenso">
            <strong>Observações:</strong> {{ $pagamento->observacoes }}
        </div>
        @endif
        
        <div class="assinatura-section">
            @php
                $assinatura = $pagamento->orcamento->cliente->user->logos()->where('tipo', 'signature')->first();
            @endphp
            @if($assinatura && $assinatura->caminho)
                <img src="{{ $assinatura->url }}" alt="Assinatura" style="max-height: 48px; margin: 40px auto 10px; display: block;">
            @else
                <div class="assinatura-linha"></div>
            @endif
            <div class="assinatura-nome">{{ $pagamento->orcamento->cliente->user->name }}</div>
        </div>
        
        <div class="botao-imprimir">
            <button onclick="window.print()" class="btn">Imprimir / Salvar PDF</button>
        </div>
    </div>
</body>
</html>