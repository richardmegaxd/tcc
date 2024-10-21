<?php
session_start();
$conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

if (mysqli_connect_errno()) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão: ' . mysqli_connect_error()]);
    exit;
}

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conexao, $_POST['email']);

    // Verifica se o usuário já existe
    $query = "SELECT * FROM tb_usuario WHERE ds_email = '$email'";
    $result = mysqli_query($conexao, $query);

    if (mysqli_num_rows($result) > 0) {
        // Usuário já existe, logar o usuário
        $usuario = mysqli_fetch_assoc($result);
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $usuario['ds_email'];
        echo json_encode(['success' => true]);
    } else {
        // Usuário não existe, criar novo
        $insert_query = "INSERT INTO tb_usuario (ds_email) VALUES ('$email')";
        if (mysqli_query($conexao, $insert_query)) {
            $_SESSION['logado'] = true;
            $_SESSION['usuario'] = $email;
            $_SESSION['id_usuario'] = mysqli_insert_id($conexao);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar usuário: ' . mysqli_error($conexao)]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'E-mail não fornecido.']);
}

mysqli_close($conexao);
?>
