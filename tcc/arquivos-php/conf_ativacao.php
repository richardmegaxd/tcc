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
    <title>Confirmação de Reativação</title>
</head>
<body>
    <h1>Reativar Conta</h1>
    <p>Você deseja reativar sua conta?</p>
    <form method="post" action="valida_reativar_conta.php">
        <input type="hidden" name="id_usuario" value="<?php echo $idUsuario; ?>">
        <button type="submit">Sim, reativar conta</button>
        <button onclick="location.href='../index.html'" type="button">Voltar a tela inicial</button>
    </form>
</body>
</html>
