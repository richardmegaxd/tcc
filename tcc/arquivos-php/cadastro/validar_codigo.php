
<div class="logo">
            <img src="../../assets/images/log5.png" width="50%" height="auto" loading="lazy" alt="Logotipo The Glarck" class="card-icon">
        </div>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigoDigitado = $_POST['codigo'];
    $email = $_POST['email']; // Email do usuário, pode ser salvo na sessão ao cadastrar

    $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

    if (!$conexao) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    // Buscar o código de confirmação e o status da conta
    $query = "SELECT codigo_confirmacao, conta_ativa FROM tb_usuario WHERE ds_email = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $codigoBanco, $contaAtiva);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($contaAtiva == 1) {
        echo "Conta já está ativada.";
    } elseif ($codigoBanco == $codigoDigitado) {
        // Atualiza o status da conta para ativa
        $query = "UPDATE tb_usuario SET conta_ativa = 1, codigo_confirmacao = NULL WHERE ds_email = ?";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: cadastro_sucesso.php");
            exit();
        } else {
            echo "<h1>Erro ao ativar a conta.<h1>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<h1>Código de confirmação incorreto.<h1>";
    }

    mysqli_close($conexao);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro The Glark</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/styleLogin.css">
</head>

<a href="confirmar_email.php" > <b>Voltar ao Menu!</b> </a>