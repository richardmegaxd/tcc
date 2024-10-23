<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="stylesheet" href="../home-assets/css/style.css">
    <link rel="stylesheet" href="../home-assets/css/lightslider.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

</head>

<body>
<?php
    session_start();

    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != true) {
        header("Location: login.php");
        exit;
    }
?>

<!-- # MENU LATERAL -->
<div class="sidebar" id="sidebar">
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
                <a href="#" data-target="section-inicio">
                    <i class="bx bx-grid-alt"></i>
                    <span class="link_name">Início</span>
                </a>
                <span class="tooltip">Início</span>
            </li>
            <li>
                <a href="./perfil.php">
                    <i class="bx bx-user"></i>
                    <span class="link_name">Perfil</span>
                </a>
                <span class="tooltip">Perfil</span>
            </li>
            <li>
                <a href="./fav.php">
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

<!-- # LEITURA DO MANGÁ -->
<main id="section-leitura-manga" class=" content-section">
        <button id="toggleMenu" class="toggle-menu">Tnu</button>

        <a data-target="section-obra"><i class='bx bxs-left-arrow-circle'></i></a>

        <div class="btn-troca">
            <button class="toggle-mode" onclick="toggleMode()"><i class='bx bxs-binoculars'></i></button>
        </div>

        <div id="viewer" class="container">

            <div id="image-container" class="lazy">
                <!-- As imagens serão carregadas dinamicamente aqui -->
            </div>

            <div class="controls" id="arrow-navigation">
                <button class="btn-page" id="prevButton" onclick="prevPage()">Página Anterior</button>
                <button class="btn-page" id="nextButton" onclick="nextPage()">Próxima Página</button>
            </div>
        </div>

        <!-- #BACK TO TOP -->
        <button id="backToTopBtn" onclick="scrollToTop()"><i class='bx bxs-up-arrow-square'></i></button>

    </main>
    <!-- # FIM LEITURA DO MANGÁ -->

    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="../home-assets/Js/JQuery3.3.1.js"></script>
    <script src="../home-assets/Js/lightslider.js"></script>
    <script src="../home-assets/Js/script2.js"></script>

</body>
</html>