<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Diretório de upload
    $uploadDir = 'capitulos/';
    
    // Verifica se o diretório existe, caso contrário, cria
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Define o caminho completo do arquivo
    $uploadFile = $uploadDir . basename($_FILES['comicPage']['name']);

    // Verifica o tipo de arquivo (somente imagens)
    $fileType = pathinfo($uploadFile, PATHINFO_EXTENSION);
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array(strtolower($fileType), $allowedTypes)) {
        // Move o arquivo enviado para o diretório de upload
        if (move_uploaded_file($_FILES['comicPage']['tmp_name'], $uploadFile)) {
            echo "Arquivo enviado com sucesso.";
        } else {
            echo "Erro ao enviar arquivo.";
        }
    } else {
        echo "Tipo de arquivo não permitido. Apenas imagens são aceitas.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Páginas do Gibi</title>
</head>
<body>
    <form enctype="multipart/form-data" action="upload.php" method="POST">
        <input type="file" name="comicPage" accept="image/*" required>
        <button type="submit">Enviar Página</button>
    </form>
</body>
</html>
