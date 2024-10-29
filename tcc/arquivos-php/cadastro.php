
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro The Glark</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styleLogin.css">
</head>

<body>

    <a href="../index.html"><ion-icon name="return-up-back-sharp"></ion-icon></a>

    <header>
        <div class="logo">
            <img src="../assets/images/log5.png" width="50%" height="auto" loading="lazy" alt="Logotipo The Glarck" class="card-icon">
        </div>
        <h1>Bem-vindo, Crie sua conta!</h1>
        <p>Já tem um cadastro? <span class="text-white" onclick="document.location='../arquivos-php/login.php'">Acesse sua conta</span></p>
    </header>

    <?php
    if (isset($_GET['erro'])) {
        echo "<p class='erro' style='color: red; margin-top:20px;'>" . htmlspecialchars($_GET['erro']) . "</p>";
    }
    ?>

    <form id="formc" action="validarinsert.php" method="post">

        <input type="text" id="username" required name="nome" placeholder="Nome" />

        <input type="email" id="e-mail" required name="usuario" placeholder="E-mail" />


        <input type="password" id="user-password" required name="senha" placeholder="Senha" minlength="6" />
        <p class="text-senha">A senha deve conter no mínimo 6 caracteres</p>

        <input type="password" id="confirm-password" required placeholder="Confirmar senha" minlength="6" />
        <p id="password-error" class="error-message" style="display:none;">As senhas não coincidem</p>

        <button type="submit">CRIAR</button>

        <div class="or">ou</div>

        <div class="media-options">
        <div id="buttonDiv"></div>
        </div>
        <p class="termos">
            Você reconhece que leu e concorda com nossos
            <a href="#">Termos de Serviço</a> e nossa
            <a href="#">Política de Privacidade</a>.
        </p>
    </form>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3/build/jwt-decode.min.js"></script>

    <script>
        const password = document.getElementById('user-password');
        const confirmPassword = document.getElementById('confirm-password');
        const errorMessage = document.getElementById('password-error');

        const form = document.getElementById('formc');

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


        // Função para verificar se o email já está registrado
        function checkEmailExists(email) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "checkEmail.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        resolve(response.exists); // Retorna 'true' se o email existir, 'false' caso contrário
                    } else {
                        reject('Erro ao verificar o e-mail');
                    }
                };
                xhr.send(`email=${email}`);
            });
        }

        // Verifica o e-mail no input
        emailInput.addEventListener('input', function() {
            checkEmailExists(emailInput.value).then(exists => {
                if (exists) {
                    emailWarning.style.display = 'block'; // Exibe a mensagem "Este e-mail já está cadastrado"
                    emailInput.style.borderColor = 'red'; // Muda a borda do input para vermelho
                    emailInput.setCustomValidity('Este e-mail já está cadastrado');
                } else {
                    emailWarning.style.display = 'none'; // Esconde a mensagem
                    emailInput.style.borderColor = ''; // Remove a borda vermelha
                    emailInput.setCustomValidity(''); // Remove a mensagem de erro para que o formulário possa ser enviado
                }
            }).catch(error => console.error(error));
        });

        // Impede o envio do formulário se o e-mail já existir
        form.addEventListener('submit', function(event) {
            if (emailInput.checkValidity() === false) {
                event.preventDefault(); // Impede o envio se o e-mail não for válido
            }
        });
    </script>
        <script>
        function handleCredentialResponse(response) {
    const data = jwt_decode(response.credential);
    const email = data.email;
    const photoUrl = data.picture;
    const nome = data.given_name;
    const sobrenome = data.family_name;

    fetch('valida_google_login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}&photo_url=${encodeURIComponent(photoUrl)}&nome=${encodeURIComponent(nome)}&sobrenome=${encodeURIComponent(sobrenome)}`
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            window.location.href = result.redirect; // Redireciona para a URL retornada pelo PHP
        } else {
            console.error("Erro ao salvar o e-mail:", result.message);
            alert("Ocorreu um erro ao fazer login. Por favor, tente novamente.");
        }
    })
    .catch(error => {
        console.error("Erro na requisição:", error);
        alert("Erro de rede. Por favor, tente novamente.");
    });
}

      window.onload = function () {
        
     google.accounts.id.initialize({
        client_id: "1028970424611-5g112tt1l0bqbgoe8a57clrkb7f0ks5l.apps.googleusercontent.com",
        callback: handleCredentialResponse, // A callback precisa estar dentro da inicialização
        use_fedcm_for_prompt: "false" // Desativando FedCM
    });

    google.accounts.id.renderButton(
        document.getElementById("buttonDiv"),
        { 
            theme: "outline", 
            size: "large", 
            type: "standard",
            shape: "pill",
            text: "signin_with",
            logo_alignment: "center",
            width: "343"
        }  // customização dos atributos
    );


};

    </script>
    <script>
         const button = document.getElementById('buttonDiv');
        button.style.marginLeft = '7px';
    </script>
</body>

</html>
