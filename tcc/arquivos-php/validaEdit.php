<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        // Função para mostrar alertas de erro e redirecionar
        function showAlertAndRedirect(message, redirectUrl) {
            alert(message); // Exibe o alerta
            window.location.href = redirectUrl; // Redireciona após o fechamento do alerta
        }
    </script>
</head>

<body>

    <?php
    session_start(); // Inicia a sessão para acessar dados do usuário logado

    // Verifica se o usuário está logado. Se não estiver, redireciona para a página de login
    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != true) {
        header("Location: login.php");
        exit;
    }

    // Array para armazenar mensagens de erro
    $erros = [];

    // Verifica se o método de requisição é POST (formulário enviado)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $loginUsuario = $_SESSION['usuario']; // Obtém o email do usuário logado
        $nome = $_POST['nome']; // Obtém o nome do formulário
        $email = $_POST['email']; // Obtém o email do formulário
        $apelido = $_POST['apelido']; // Obtém o apelido do formulário
        $senha = $_POST['senha']; // Obtém a nova senha do formulário

        // Conecta ao banco de dados
        $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

        // Verifica se houve erro na conexão
        if (mysqli_connect_errno()) {
            $erros[] = "Falha na conexão com o MySQL: " . mysqli_connect_error();
        }

        // Sanitiza os dados para evitar injeção de SQL
        $nome = mysqli_real_escape_string($conexao, $nome);
        $apelido = mysqli_real_escape_string($conexao, $apelido);
        $email = mysqli_real_escape_string($conexao, $email);

        // Verifica se o novo email já está em uso
        $queryEmail = "SELECT * FROM tb_usuario WHERE ds_email = '$email' AND ds_email != '$loginUsuario'";
        $resultEmail = mysqli_query($conexao, $queryEmail);

        if (mysqli_num_rows($resultEmail) > 0) {
            // Se o email já existe, adiciona uma mensagem de erro ao array
            $erros[] = "Esse email já está em uso. Por favor, escolha outro.";
        }

        // Inicializa a variável para o caminho da foto
        $caminhoFoto = null;

        // Verifica se uma nova foto de perfil foi enviada
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
            $foto = $_FILES['foto_perfil']; // Obtém a informação do arquivo
            $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION); // Obtém a extensão do arquivo
            $nomeFoto = "perfil_" . $_SESSION['cd_usuario'] . "." . $extensao; // Nomeia a nova foto
            $caminhoFoto = "../uploads/" . $nomeFoto; // Define o caminho da nova foto

            // Move a imagem para a pasta de uploads
            if (!move_uploaded_file($foto['tmp_name'], $caminhoFoto)) {
                $erros[] = "Erro ao mover o arquivo."; // Adiciona mensagem de erro
            }
        }

        // Monta a consulta SQL para atualizar os dados do usuário
        $query = "UPDATE tb_usuario SET 
              nm_user = '$nome',  
              ds_email = '$email', 
              nm_apelido = '$apelido'";

        // Se a nova senha foi fornecida, processa a atualização da senha
        if (!empty($senha)) {
            $senha = mysqli_real_escape_string($conexao, $senha); // Sanitiza a nova senha
            $hashedPassword = password_hash($senha, PASSWORD_DEFAULT); // Criptografa a nova senha
            $query .= ", ds_senha = '$hashedPassword'"; // Adiciona a senha na consulta
        }

        // Se uma nova foto foi carregada, atualiza o caminho da foto também
        if ($caminhoFoto) {
            $query .= ", ds_foto_perfil = '$caminhoFoto'"; // Adiciona o caminho da nova foto na consulta
        }

        // Finaliza a consulta com a condição WHERE
        $query .= " WHERE ds_email = '$loginUsuario'";

        // Executa a consulta no banco de dados
        if (!empty($erros)) {
            // Se houve erros, exibe alertas e redireciona para a página de edição
            echo "<script>showAlertAndRedirect('" . implode("\\n", $erros) . "', 'edit.php');</script>";
        } else {
            // Tenta executar a consulta
            if (mysqli_query($conexao, $query)) {
                // Atualiza as variáveis de sessão com os novos dados
                $_SESSION['nome'] = $nome;      
                $_SESSION['apelido'] = $apelido;
                if ($caminhoFoto) {
                    $_SESSION['foto_perfil'] = $caminhoFoto; // Atualiza a sessão com a nova foto
                }
                $_SESSION['message'] = "Perfil atualizado com sucesso!"; // Mensagem de sucesso
                header("Location: perfil.php"); // Redireciona para a página inicial
                exit; // Para a execução do script
            } else {
                // Adiciona mensagem de erro caso a atualização falhe
                $erros[] = "Erro ao atualizar o perfil: " . mysqli_error($conexao);
                echo "<script>showAlertAndRedirect('" . implode("\\n", $erros) . "', 'edit/edit.php');</script>"; // Exibe os erros e redireciona
            }
        }

        // Fecha a conexão com o banco de dados
        mysqli_close($conexao);
    } else {
        // Se não foi um POST, redireciona para a página de edição
        header("Location: edit/edit.php");
        exit;
    }
    ?>
</body>

</html>
