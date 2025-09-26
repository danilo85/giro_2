// Execute este código no console do navegador na página http://localhost:8000/portfolio/categories

// Obter o token CSRF da meta tag ou do formulário
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                  document.querySelector('input[name="_token"]')?.value;

console.log('CSRF Token:', csrfToken);

// Fazer a requisição AJAX
fetch('/portfolio/categories', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
    },
    body: new URLSearchParams({
        name: 'Test Admin Category Console',
        description: 'Teste via console do navegador',
        is_active: '1',
        _token: csrfToken
    })
})
.then(response => {
    console.log('Response status:', response.status);
    console.log('Response headers:', response.headers);
    console.log('Content-Type:', response.headers.get('content-type'));
    
    return response.text(); // Usar text() em vez de json() para ver o conteúdo bruto
})
.then(data => {
    console.log('Response data (raw):', data);
    
    // Tentar fazer parse como JSON
    try {
        const jsonData = JSON.parse(data);
        console.log('Response data (JSON):', jsonData);
    } catch (e) {
        console.log('Não é JSON válido. Conteúdo HTML:', data.substring(0, 200));
    }
})
.catch(error => {
    console.error('Erro na requisição:', error);
});

console.log('Requisição enviada. Aguarde a resposta...');