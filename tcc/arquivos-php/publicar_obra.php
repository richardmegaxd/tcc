<?php
// Estabelecendo a conexão com o banco de dados
$conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

// Verifica se a conexão foi bem-sucedida
if (mysqli_connect_errno()) {
    echo json_encode(['success' => false, 'message' => "A conexão MYSQL apresentou erro: " . mysqli_connect_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se a capa foi enviada
    if (isset($_FILES['imagem_capa']) && $_FILES['imagem_capa']['error'] == 0) {
        $fileTmpPathCapa = $_FILES['imagem_capa']['tmp_name'];
        $fileNameCapa = $_FILES['imagem_capa']['name'];
        $uploadPathCapa = '../obras/mangas/' . $fileNameCapa;

        // Move a capa para a pasta de uploads
        if (move_uploaded_file($fileTmpPathCapa, $uploadPathCapa)) {
            // Obter o título e a descrição do manga
            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];

            // Preparar a consulta para inserir o manga
            $stmt = mysqli_prepare($conexao, "INSERT INTO tb_manga (nm_titulo, ds_sinopse, ds_arquivo_zip) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $titulo, $descricao, $uploadPathCapa);

            if (mysqli_stmt_execute($stmt)) {
                $manga_id = mysqli_insert_id($conexao); // Obter o ID do manga inserido

                // Verificar se as páginas foram enviadas
                if (isset($_FILES['paginas_manga']) && !empty($_FILES['paginas_manga']['name'][0])) {
                    foreach ($_FILES['paginas_manga']['name'] as $key => $filename) {
                        $fileTmpPath = $_FILES['paginas_manga']['tmp_name'][$key];
                        $fileName = $_FILES['paginas_manga']['name'][$key];
                        $fileSize = $_FILES['paginas_manga']['size'][$key];
                        $fileType = $_FILES['paginas_manga']['type'][$key];

                        // Definir o caminho de upload para as páginas
                        $uploadPathPaginas = '../obras/mangas/' . $fileName;

                        // Mover o arquivo para o diretório de páginas
                        if (move_uploaded_file($fileTmpPath, $uploadPathPaginas)) {
                            // Inserir o caminho da página na tabela 'tb_paginas_manga'
                            $stmt = mysqli_prepare($conexao, "INSERT INTO tb_paginas_manga (cd_manga, ds_caminho_arquivo) VALUES (?, ?)");
                            $caminho_arquivo = $uploadPathPaginas;
                            mysqli_stmt_bind_param($stmt, "is", $manga_id, $caminho_arquivo);

                            if (!mysqli_stmt_execute($stmt)) {
                                echo "Erro ao inserir caminho da página: " . mysqli_error($conexao);
                            }
                        } else {
                            echo "Erro ao mover o arquivo da página: " . $fileName . "<br>";
                        }
                    }
                } else {
                    echo "Nenhuma página foi enviada!";
                }

                echo "Manga e páginas publicadas com sucesso!";
            } else {
                echo "Erro ao inserir manga: " . mysqli_error($conexao);
            }
        } else {
            echo "Erro ao mover a capa: " . $fileNameCapa . "<br>";
        }
    } else {
        echo "Nenhuma capa foi enviada!";
    }
}

// Fechar a conexão após o uso
mysqli_close($conexao);
?>
