<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Teste de Upload - Resultado</h2>";
    
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        echo "<p style='color: green;'>✅ Imagens recebidas com sucesso!</p>";
        echo "<h3>Detalhes:</h3>";
        foreach ($_FILES['images']['name'] as $index => $name) {
            if (!empty($name)) {
                $size = $_FILES['images']['size'][$index];
                $type = $_FILES['images']['type'][$index];
                echo "<p>📷 <strong>{$name}</strong> - {$size} bytes - {$type}</p>";
            }
        }
    } else {
        echo "<p style='color: red;'>❌ Nenhuma imagem foi recebida!</p>";
        echo "<h3>Debug $_FILES:</h3>";
        echo "<pre>" . print_r($_FILES, true) . "</pre>";
    }
    
    echo "<br><a href='test_simple_upload.php'>← Voltar ao teste</a>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste Simples de Upload</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .upload-box { border: 2px dashed #007cba; padding: 30px; text-align: center; margin: 20px 0; background: #f8f9fa; }
        .preview { margin: 20px 0; }
        .preview img { width: 80px; height: 80px; object-fit: cover; margin: 5px; border: 1px solid #ddd; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #005a87; }
    </style>
</head>
<body>
    <h1>🧪 Teste Simples de Upload de Imagens</h1>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="upload-box">
            <p>📁 Selecione uma ou mais imagens:</p>
            <input type="file" name="images[]" multiple accept="image/*" onchange="showPreview(this)" style="margin: 10px 0;">
        </div>
        
        <div id="preview" class="preview"></div>
        
        <button type="submit">🚀 Testar Upload</button>
    </form>
    
    <script>
    function showPreview(input) {
        const preview = document.getElementById('preview');
        preview.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            preview.innerHTML = '<h3>📷 Preview das imagens:</h3>';
            
            Array.from(input.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.title = file.name + ' (' + Math.round(file.size/1024) + 'KB)';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            console.log('Arquivos selecionados:', input.files.length);
        }
    }
    </script>
</body>
</html>