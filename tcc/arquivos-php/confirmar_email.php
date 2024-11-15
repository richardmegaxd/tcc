<!DOCTYPE html>
<html>
<head>
    <title>Confirme seu Email</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styleLogin.css">
</head>
<body>
    <h1>Confirmação de Email</h1>
    <form action="validar_codigo.php" method="POST">
        <label for="codigo">Insira o código de confirmação:</label>
        <input type="text" name="codigo" required>
        <input type="email" name="email" placeholder="Email cadastrado" required>
        <button type="submit">Confirmar</button>
    </form>
</body>
</html>
