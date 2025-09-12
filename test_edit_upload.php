<?php
echo "<h2>Teste de Upload - Edição</h2>";

if ($_POST) {
    echo "<h3>Dados POST recebidos:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h3>Arquivos recebidos:</h3>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        echo "<h3>Imagens encontradas:</h3>";
        foreach ($_FILES['images']['name'] as $index => $name) {
            if (!empty($name)) {
                echo "Imagem {$index}: {$name} - Tamanho: {$_FILES['images']['size'][$index]} bytes<br>";
            }
        }
    } else {
        echo "<p style='color: red;'>Nenhuma imagem foi enviada!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teste Upload Edição</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <h3>Formulário de Teste - Edição</h3>
        
        <label>Título:</label><br>
        <input type="text" name="title" value="Teste Edição"><br><br>
        
        <label>Imagens:</label><br>
        <input type="file" name="images[]" multiple accept="image/*"><br><br>
        
        <button type="submit">Enviar</button>
    </form>
</body>
</html>