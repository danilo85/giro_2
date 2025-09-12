<?php
// Script para verificar e orientar sobre a habilitação da extensão fileinfo

echo "=== Verificação da extensão fileinfo ===\n";
echo "Extensão fileinfo carregada: " . (extension_loaded('fileinfo') ? 'SIM' : 'NÃO') . "\n";

if (!extension_loaded('fileinfo')) {
    echo "\n=== SOLUÇÃO ===\n";
    echo "A extensão fileinfo não está habilitada.\n";
    echo "Para habilitar no Laragon:\n";
    echo "1. Abra o Laragon\n";
    echo "2. Clique em 'Menu' > 'PHP' > 'php.ini'\n";
    echo "3. Procure por ';extension=fileinfo'\n";
    echo "4. Remova o ';' para ficar 'extension=fileinfo'\n";
    echo "5. Salve o arquivo\n";
    echo "6. Reinicie o Apache no Laragon\n";
    echo "\nOu execute este comando no terminal do Laragon:\n";
    echo "Menu > Terminal > sed -i 's/;extension=fileinfo/extension=fileinfo/' php.ini\n";
} else {
    echo "\nExtensão fileinfo está funcionando corretamente!\n";
}

echo "\n=== Teste de MIME type ===\n";
if (extension_loaded('fileinfo')) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    echo "finfo criado com sucesso!\n";
} else {
    echo "Não foi possível criar finfo - extensão fileinfo necessária\n";
}
?>