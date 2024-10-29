<!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <title>Editar Perfil</title>
        <link rel="stylesheet" href="../assets/css/styleLog.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>  
    
    <?php
    session_start();

    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != true) {
        header("Location: login.php");
        exit;
    } else {
        $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

        if (mysqli_connect_errno()) {
            echo "Falha na conexão com o MySQL: " . mysqli_connect_error();
            exit;
        }

        $loginUsuario = $_SESSION['usuario'];
        $loginUsuario = mysqli_real_escape_string($conexao, $loginUsuario);

        $query = "SELECT * FROM tb_usuario WHERE ds_email = '$loginUsuario'";
        $result = mysqli_query($conexao, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
        } else {
            echo "Usuário não encontrado.";
            exit;
        }

        mysqli_close($conexao);
    }
    ?>

    <body>
        
        <div class="login-container">
            <h1>Editar Perfil</h1>
            <form id="form" action="validaEdit.php" method="POST" enctype="multipart/form-data">
                <div class="input-container">
                <label class="lala" for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nm_user']); ?>"><br>
                </div>

                <div class="input-container">
                <label class="lala" for="apelido">Apelido:</label>
                    <input type="text" id="apelido" name="apelido" value="<?php echo htmlspecialchars($user['nm_apelido']); ?>" ><br>
                    
                </div>

                <div class="input-container">
                <label class="lala" for="senha">Nova Senha:</label>
                
                    <input type="password" id="new-password" name="senha" minlength="6"><br>
                    <p class="lala">A senha deve conter no mínimo 6 caracteres</p>
                    
                </div>

                <div class="input-container">
                <label class="lala" for="senha">Confirme a Senha:</label>
                    <input type="password" id="confirm-new-password" minlength="6"  ><br>
                    
                    <p id="pass-error" class="error-message" style="display:none;">As senhas não coincidem</p>
                </div>

                <div class="input-container">
                <label class="lala" for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['ds_email']); ?>" ><br>
                    
                </div>

                <div class="input-container">
                <label class="lala" for="foto_perfil">Escolha uma foto de perfil:</label>
                <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*"><br>
                    
                </div>

                <div class="input-container">
                <input class="att" type="submit" value="Atualizar">
                </div>

            
            </form>
        </div>
        
        <a href="home.php">Cancelar</a>

        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <script>
        const password = document.getElementById('new-password');
        const confirmPassword = document.getElementById('confirm-new-password');
        const errorMessage = document.getElementById('pass-error');

        const form = document.getElementById('form');

        confirmPassword.addEventListener('input', function() {
            if (confirmPassword.value !== password.value) {
                errorMessage.style.display = 'block';
                confirmPassword.style.borderColor = 'red';
            } else {
                errorMessage.style.display = 'none';
                confirmPassword.style.borderColor = 'rgb(87, 0, 128)';
            }
        });

        form.addEventListener('submit', function(event) {
            if (confirmPassword.value !== password.value) {
                event.preventDefault();
                alert('Confira novamente os dados cadastrados.');
            }
        });
        </script>
    </body>

    </html>