<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Diretório de upload
    $uploadDir = '../capitulo1/'; // Direciona para o diretório capitulo1
    
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
