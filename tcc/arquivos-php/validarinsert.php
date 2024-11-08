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
    $fotoPerfil = '../home-assets/images/logo.png'; // Substitua pelo caminho da sua imagem

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
    $operacao = "INSERT INTO tb_usuario (ds_email, ds_senha, nm_user, codigo_confirmacao, conta_ativa, ds_foto_perfil) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $operacao);
    mysqli_stmt_bind_param($stmt, "ssssis", $email, $senhaHash, $nome, $codigoConfirmacao, $contaAtiva, $fotoPerfil);

    if (mysqli_stmt_execute($stmt)) {
        // Assunto do email
        $assunto = "Confirme seu Email";
        
        // Carregar a imagem e converter para Base64
        $imagem = file_get_contents('../home-assets/images/logo.png'); // Substitua pelo caminho da sua imagem
        $imagemBase64 = base64_encode($imagem);

        // Corpo do email em HTML
        $mensagem = "
        <html>
        <head>
            <title>Confirme seu Email</title>
        </head>
        <body>
            <p>Olá, $nome!</p>
            <p>Seu código de confirmação é: <strong>$codigoConfirmacao</strong></p>
            <p>Digite este código no site para ativar sua conta.</p>
            <br>
            <p>Atenciosamente,</p>
            <p>The Glark</p>
            <br>
             <img src='data:image/png;base64,$imagemBase64' alt='Logo The Glark'>
        </body>
        </html>";

        // Cabeçalhos para email HTML
        $cabecalhos = "MIME-Version: 1.0" . "\r\n";
        $cabecalhos .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $cabecalhos .= "From: equipe_theglark@gmail.com" . "\r\n";
        
        // Enviar o email
        if (mail($email, $assunto, $mensagem, $cabecalhos)) {
            header("Location: confirmar_email.php");  
            exit();
        } else {
            echo "Erro ao enviar o email.";
        }

    } else {
        echo "Erro ao cadastrar: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
}
?>
