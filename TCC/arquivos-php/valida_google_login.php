<?php
session_start();

$conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

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
$seleciona_usuario = "SELECT * FROM tb_usuario WHERE ds_email = '$email'";
$procura = mysqli_query($conexao, $seleciona_usuario);
$checar_usuario = mysqli_num_rows($procura);

if ($checar_usuario > 0) {
    // O usuário já existe, apenas atualize a foto
    $queryUpdate = "UPDATE tb_usuario SET ds_foto_perfil = '$photoUrl', nm_user = '$nome_completo', login_google = 1 WHERE ds_email = '$email'";
    mysqli_query($conexao, $queryUpdate);
} else {
    // O usuário não existe, insira um novo registro
    $queryInsert = "INSERT INTO tb_usuario (ds_email, ds_foto_perfil, nm_user, login_google) VALUES ('$email', '$photoUrl', '$nome_completo', 1)";
    mysqli_query($conexao, $queryInsert);

    $_SESSION['image'] = $photoUrl;
    
}

// Define a sessão para o usuário logado
$_SESSION['logado'] = true;
$_SESSION['usuario'] = $email;

// Retorna sucesso
echo json_encode(['success' => true]);

mysqli_close($conexao);
?>
