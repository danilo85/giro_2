<?php
// Teste para verificar se as imagens estão sendo enviadas pelo formulário

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Dados POST recebidos:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h2>Arquivos recebidos:</h2>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    if (isset($_FILES['images'])) {
        echo "<h2>Detalhes das imagens:</h2>";
        foreach ($_FILES['images']['name'] as $index => $name) {
            if (!empty($name)) {
                echo "Imagem {$index}: {$name} - Tamanho: {$_FILES['images']['size'][$index]} bytes<br>";
            }
        }
    } else {
        echo "<p style='color: red;'>Nenhuma imagem foi enviada!</p>";
    }
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste de Upload de Imagens</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .upload-area { border: 2px dashed #ccc; padding: 20px; text-align: center; margin: 20px 0; }
        .preview { display: flex; flex-wrap: wrap; gap: 10px; margin: 20px 0; }
        .preview img { width: 100px; height: 100px; object-fit: cover; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>Teste de Upload de Imagens</h1>
    
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="title">Título:</label><br>
            <input type="text" name="title" id="title" value="Teste de Portfolio">
        </div><br>
        
        <div>
            <label for="images">Selecionar Imagens:</label><br>
            <input type="file" name="images[]" id="images" multiple accept="image/*" onchange="previewImages(this)">
        </div><br>
        
        <div id="preview" class="preview"></div>
        
        <button type="submit">Enviar Teste</button>
    </form>
    
    <script>
    function previewImages(input) {
        const preview = document.getElementById('preview');
        preview.innerHTML = '';
        
        if (input.files) {
            Array.from(input.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        console.log('Arquivos selecionados:', input.files.length);
        for (let i = 0; i < input.files.length; i++) {
            console.log('Arquivo ' + i + ':', input.files[i].name, input.files[i].size + ' bytes');
        }
    }
    </script>
</body>
</html>
<?php
}
?>