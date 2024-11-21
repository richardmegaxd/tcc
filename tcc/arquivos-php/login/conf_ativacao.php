<?php
session_start();

if (!isset($_SESSION['temp_usuario'])) {
    // Se não há um usuário temporário, redireciona para o login
    header("Location: login.php");
    exit;
}

$idUsuario = $_SESSION['temp_usuario'];

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/styleLogin.css">
    <title>Confirmação de Reativação</title>
</head>
<body>
     <div id="section-delete" class="logo">
     <img src="../../assets/images/log5.png" width="50%" height="auto" loading="lazy" alt="Logotipo The Glarck" class="card-icon">
     </div> 
    <h1>Reativação de Conta</h1><br>
    <p>Sua conta está desativada. Deseja reativar?</p>
    <form method="post" action="valida_reativar_conta.php">
        <input type="hidden" name="id_usuario" value="<?php echo $idUsuario; ?>">
        <button type="submit">Sim, reativar conta</button>
        <a href="../../index.html" class="button-link">Voltar à tela inicial</a>
    </form>
</body>
</html>
