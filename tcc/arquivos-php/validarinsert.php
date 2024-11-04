<?php
session_start();

function isEmailExists($email, $conexao) {
    $query = "SELECT COUNT(*) FROM tb_usuario WHERE ds_email = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $email = $_POST['usuario'];

    $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

    if (!$conexao) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    if (isEmailExists($email, $conexao)) {
        mysqli_close($conexao);
        header("Location: cadastro.php?erro=Este e-mail já está cadastrado");
        exit();
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Gerar um código de confirmação de 6 dígitos
    $codigoConfirmacao = rand(100000, 999999);
    $contaAtiva = 0;

    // Inserir o novo usuário com o código de confirmação e conta_ativa
    $operacao = "INSERT INTO tb_usuario (ds_email, ds_senha, nm_user, codigo_confirmacao, conta_ativa) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $operacao);
    mysqli_stmt_bind_param($stmt, "ssssi", $email, $senhaHash, $nome, $codigoConfirmacao, $contaAtiva);

    if (mysqli_stmt_execute($stmt)) {
        // Enviar email com o código de confirmação
        $assunto = "Confirme seu Email";
        $mensagem = "Olá, $nome!\n\nSeu código de confirmação é: $codigoConfirmacao\n\nDigite este código no site para ativar sua conta.";
        $cabecalhos = "From: no-reply@seusite.com";

        mail($email, $assunto, $mensagem, $cabecalhos);

        header("Location: confirmar_email.php");  
        exit();
    } else {
        echo "Erro ao cadastrar: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
}
?>
