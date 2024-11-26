<?php
session_start();

$conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

if (mysqli_connect_errno()) {
    echo "A conexão MYSQL apresentou erro: " . mysqli_connect_error();
    exit;
}

// Pasta onde as imagens serão armazenadas
$uploadDir = '../uploads_Obras/'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário (informações para o autor)
    $nome = $_POST['nome'] ?? '';
    $sobrenome = $_POST['sobrenome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $checkbox_status = isset($_POST['checkbox_status']) ? 1 : 0;

    // Inserir autor no banco de dados
    $sql_autor = "INSERT INTO tb_autor (nm_autor, ds_email, ds_telefone, checkbox_status) 
                  VALUES ('$nome $sobrenome', '$email', '$telefone', '$checkbox_status')";
    if (mysqli_query($conexao, $sql_autor)) {
        // Captura o ID do autor recém inserido
        $autor_id = mysqli_insert_id($conexao);

        // Aqui capturamos os dados da obra
        $nm_obra = $_POST['nm_obra'] ?? '';
        $ds_sinopse = $_POST['ds_sinopse'] ?? '';
        $ds_status = $_POST['nmstatus'] ?? 'Em Andamento';  // "Em Andamento" por padrão
        $ds_genero = isset($_POST['obras']) ? implode(", ", $_POST['obras']) : '';  // Gêneros selecionados

        // Processar o upload da imagem de capa
        $ds_imagem = '';  // Inicializa a variável de imagem
        if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);  // Extensão do arquivo
            $imagem_nome = uniqid('imagem_', true) . '.' . $extensao;  // Cria um nome único para a imagem
            $imagem_caminho = $uploadDir . $imagem_nome;  // Caminho completo da imagem na pasta 'uploads'

            // Move o arquivo para o diretório 'uploads_Obras'
            if (move_uploaded_file($_FILES['capa']['tmp_name'], $imagem_caminho)) {
                $ds_imagem = $imagem_caminho;  // Salva o caminho da imagem
            } else {
                echo "Erro ao fazer upload da imagem.";
                exit;
            }
        }

        // Checkboxs de status da imagem e obra
        $checkbox2_status = isset($_POST['checkbox_image']) ? 1 : 0;
        $checkbox3_status = isset($_POST['checkbox_autoral']) ? 1 : 0;

        // Inserir obra no banco de dados
        $sql_obra = "INSERT INTO tb_obra (nm_obra, ds_sinopse, ds_status, ds_genero, ds_imagem, 
                                         checkbox2_status, checkbox3_status, cd_autorObra) 
                     VALUES ('$nm_obra', '$ds_sinopse', '$ds_status', '$ds_genero', '$ds_imagem', 
                             '$checkbox2_status', '$checkbox3_status', '$autor_id')";

        if (mysqli_query($conexao, $sql_obra)) {
            header("Location: publique2.php" );
        } else {
            $_SESSION['error_message'] = "Erro ao salvar obra: " . mysqli_error($conexao);
        }
    } else {
        $_SESSION['error_message'] = "Erro ao salvar autor: " . mysqli_error($conexao);
    }
    header("Location: publique2.php" );
    exit;
}
?>
