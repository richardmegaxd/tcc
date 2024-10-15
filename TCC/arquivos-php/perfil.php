<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERFIL</title>
    <link rel="stylesheet" href="../home-assets/css/style.css">
    <link rel="stylesheet" href="../home-assets/css/lightslider.css">   
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != true) {
        header("Location: login.php");
        exit;
    }

    $user = $_SESSION['usuario'];
    $conexao = mysqli_connect("localhost", "root", "", "bd_glark");

    if(mysqli_connect_errno()){
        echo "Erro no Banco de Dados!" . mysqli_connect_errno();
    }

    $seleciona_info = "SELECT * FROM tb_usuario WHERE ds_email='$user'"; //efetua a seleção no banco de dados e atribui a uma variável

    $busca = mysqli_query($conexao, $seleciona_info);

    $resultado = mysqli_fetch_array($busca);

    ?>

    <div class="usuario-info">
        <p>Nome: <?php echo $resultado[1]; ?></p> <!-- Exibindo o nome -->
        <p>Idade: <?php echo $resultado[3]; ?></p> <!-- Exibindo a idade -->
        <p>Endereço: <?php echo $resultado[4]; ?></p> <!-- Exibindo o endereço -->
    </div>

    <!-- # MENU LATERAL -->
    <div class="sidebar">
        <div class="logo_details">
            <i class="bx icon"><img src="../assets/images/log5.png" alt="logo" width="50"></i>
            <div class="logo_name">THE GLARK</div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
            <form method="POST">
                    <i class="bx bx-search"></i>
                    <input type="text" name="pesquisar" placeholder="Pesquisa">
                </form>
            <span class="tooltip" name="pesquisar">Pesquisa</span>
            </li>
            <li>
                <a href="./home.php">
                    <i class="bx bx-grid-alt"></i>
                    <span class="link_name">Início</span>
                </a>
                <span class="tooltip">Início</span>
            </li>
            <li>
                <a href="#" data-target="section-perfil">
                    <i class="bx bx-user"></i>
                    <span class="link_name">Perfil</span>
                </a>
                <span class="tooltip">Perfil</span>
            </li>
            <li>
                <a href="#" data-target="section-favoritos">
                    <i class='bx bxs-star'></i>
                    <span class="link_name">Favoritos</span>
                </a>
                <span class="tooltip">Favoritos</span>
            </li>
            <li>
                <a href="#" data-target="section-publicacao-obras">
                    <i class='bx bx-book-reader'></i>
                    <span class="link_name">Publique Obras</span>
                </a>
                <span class="tooltip">Publique Obras</span>
            </li>
            <li>
                <a href="#" data-target="section-planos-mensais">
                    <i class='bx bx-money'></i>
                    <span class="link_name">Planos Mensais</span>
                </a>
                <span class="tooltip">Planos Mensais</span>
            </li>
            <li>
                <a href="#" data-target="section-suporte">
                    <i class='bx bxs-help-circle'></i>
                    <span class="link_name">Suporte</span>
                </a>
                <span class="tooltip">Suporte</span>
            </li>
            <li>
                <a href="#" data-target="section-config">
                    <i class="bx bx-cog"></i>
                    <span class="link_name">Configurações</span>
                </a>
                <span class="tooltip">Configurações</span>
            </li>
            <li>
                <a href="#" class="theme-toggle" id="themeIconContainer" onclick="toggleTheme()">
                    <i class='bx bx-sun'></i>
                    <span class="link_name">Alterar Tema</span>
                </a>
                <span class="tooltip">Alterar Tema</span>
            </li>


            <li class="profile">
                <div class="profile_details">
                    <img src="../assets/images/EU.jpg" alt="profile image">
                    <div class="profile_content">
                        <div class="name">Marcelo Azevedo</div>
                        <div class="designation">O Mior</div>
                    </div>
                </div>
                <a href="./logout.php" id="log_out"><i class="bx bx-log-out"></i></a>
            </li>
        </ul>
    </div>
    <!-- # FIM MENU LATERAL -->

         <!-- # FAVORITOS -->
    <main id="section-favoritos" class="home-section content-section">
        <img src="https://www.protecaomaxima.com.br/imgs/em_desenvolvimento.jpg" alt="" class="manutenção">
    </main>
    <!-- # FIM FAVORITOS -->

    <!-- # PUBLICAÇÃO DE OBRAS -->
    <main id="section-publicacao-obras" class="home-section content-section">
        <img src="https://www.protecaomaxima.com.br/imgs/em_desenvolvimento.jpg" alt="" class="manutenção">
    </main>
    <!-- # FIM DA PUBLICAÇÃO DE OBRAS -->


    <!-- # PLANOS MENSAIS -->
    <main id="section-planos-mensais" class="home-section content-section">
        <img src="https://www.protecaomaxima.com.br/imgs/em_desenvolvimento.jpg" alt="" class="manutenção">
    </main>
    <!-- # FIM PLANOS MENSAIS -->

    <!-- # SUPORTE -->
    <main id="section-suporte" class="home-section content-section">
        <img src="https://www.protecaomaxima.com.br/imgs/em_desenvolvimento.jpg" alt="" class="manutenção">
    </main>
    <!-- # FIM SUPORTE -->

    <!-- # CONFIGURAÇÕES -->
    <main id="section-config" class="home-section content-section">
        <img src="https://www.protecaomaxima.com.br/imgs/em_desenvolvimento.jpg" alt="" class="manutenção">
    </main>


    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="../home-assets/Js/JQuery3.3.1.js"></script>
    <script src="../home-assets/Js/lightslider.js"></script>
    <script src="../home-assets/Js/script2.js"></script>
</body>

</html>

