<?php
session_start();

$conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

// Verifica se a conexão foi bem-sucedida
if (mysqli_connect_errno()) {
    echo json_encode(['success' => false, 'message' => "A conexão MYSQL apresentou erro: " . mysqli_connect_error()]);
    exit;
}

// Captura o email e a URL da foto da requisição
$email = $_POST['email'];
$photoUrl = $_POST['photo_url']; 
$nome = $_POST['nome'];
$sobrenome = $_POST['sobrenome'];

$nome_completo = $nome . ' ' . $sobrenome;

// Verifica se o usuário já existe
$seleciona_usuario = "SELECT * FROM tb_usuario WHERE ds_email = ?";
$stmt = mysqli_prepare($conexao, $seleciona_usuario);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$procura = mysqli_stmt_get_result($stmt);

if (!$procura) {
    echo json_encode(['success' => false, 'message' => "Erro na consulta: " . mysqli_error($conexao)]);
    exit;
}

$checar_usuario = mysqli_num_rows($procura);

if ($checar_usuario > 0) {
    $usuario = mysqli_fetch_assoc($procura); // Pega o usuário existente
    $cdUsuario = $usuario['cd_usuario']; // Pega o ID do usuário

    // Verifica se a conta está ativa
    if ($usuario['conta_ativa'] == 1) {
        // A conta está ativa, atualiza a foto e o nome
        $queryUpdate = "UPDATE tb_usuario SET ds_foto_perfil = ?, nm_user = ?, login_google = 1 WHERE ds_email = ?";
        $stmtUpdate = mysqli_prepare($conexao, $queryUpdate);
        mysqli_stmt_bind_param($stmtUpdate, "sss", $photoUrl, $nome_completo, $email);
        mysqli_stmt_execute($stmtUpdate);

        // Define a sessão para o usuário logado
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $email;
        $_SESSION['cd_usuario'] = $cdUsuario; // Define o ID do usuário na sessão
        $_SESSION['nome'] = $nome_completo; // Atualiza o nome na sessão
        $_SESSION['apelido'] = $usuario['nm_apelido']; // Preserva o apelido

        // Responde com redirecionamento para home.php
        echo json_encode(['success' => true, 'redirect' => 'home.php']);
    } else {
        // Conta inativa, redireciona para a página de ativação
        $_SESSION['temp_usuario'] = $cdUsuario; // Armazena o ID do usuário inativo
        $_SESSION['nome'] = $usuario['ds_email']; // Armazena o nome para exibir na confirmação

        // Responde com redirecionamento para conf_ativacao.php
        echo json_encode(['success' => true, 'redirect' => 'conf_ativacao.php']);
    }
} else {
    // O usuário não existe, insira um novo registro
    $queryInsert = "INSERT INTO tb_usuario (ds_email, ds_foto_perfil, nm_user, login_google, conta_ativa) VALUES (?, ?, ?, 1, 1)";
    $stmtInsert = mysqli_prepare($conexao, $queryInsert);
    mysqli_stmt_bind_param($stmtInsert, "sss", $email, $photoUrl, $nome_completo);
    
    if (!mysqli_stmt_execute($stmtInsert)) {
        echo json_encode(['success' => false, 'message' => "Erro ao inserir o usuário: " . mysqli_error($conexao)]);
        exit;
    }

    // Recupera o ID do usuário recém-criado
    $cdUsuario = mysqli_insert_id($conexao);

    // Define a sessão para o usuário logado
    $_SESSION['logado'] = true;
    $_SESSION['usuario'] = $email;
    $_SESSION['cd_usuario'] = $cdUsuario; // Define o ID do usuário na sessão
    $_SESSION['nome'] = $nome_completo; // Atualiza o nome na sessão
    $_SESSION['apelido'] = ''; // Defina como vazio ou conforme sua lógica

    // Responde com redirecionamento para home.php
    echo json_encode(['success' => true, 'redirect' => 'home.php']);
}

mysqli_close($conexao);
?>
