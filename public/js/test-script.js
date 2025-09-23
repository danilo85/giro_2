console.log('=== SCRIPT EXTERNO FUNCIONANDO ===');
alert('Script externo carregado!');

window.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM CARREGADO - SCRIPT EXTERNO ===');
});