<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Diretório de upload
    $uploadDir = '../obras/mangas/'; // Diretório onde as páginas do manga serão armazenadas
    
    // Verifica se o diretório existe, caso contrário, cria
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Verifica se o arquivo foi enviado
    if (isset($_FILES['paginas_manga']) && !empty($_FILES['paginas_manga']['name'][0])) {
        foreach ($_FILES['paginas_manga']['name'] as $key => $filename) {
            // Define o caminho completo do arquivo
            $uploadFile = $uploadDir . '/' . basename($filename);

            // Verifica o tipo de arquivo (somente imagens)
            $fileType = pathinfo($uploadFile, PATHINFO_EXTENSION);
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($fileType), $allowedTypes)) {
                // Move o arquivo enviado para o diretório de upload
                if (move_uploaded_file($_FILES['paginas_manga']['tmp_name'][$key], $uploadFile)) {
                    echo "Arquivo enviado com sucesso: " . $filename . "<br>";
                } else {
                    echo "Erro ao enviar o arquivo: " . $filename . "<br>";
                }
            } else {
                echo "Tipo de arquivo não permitido. Apenas imagens são aceitas: " . $filename . "<br>";
            }
        }
    } else {
        echo "Nenhuma página foi enviada.";
    }
}
?>
