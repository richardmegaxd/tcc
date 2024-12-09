<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="stylesheet" href="../home-assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap"
        rel="stylesheet">
    <!-- OWL CAROUSEL -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
        integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
        crossorigin="anonymous" />
    <!-- BOX ICONS -->
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css"
        rel="stylesheet" />
    <!-- APP CSS -->
    <link rel="stylesheet" href="../home-assets/css/grid.css">
    <link rel="stylesheet" href="../home-assets/css/app.css">

</head>

<body id="main">
    <?php
    session_start();

    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != true) {
        header("Location: login.php");
        exit;
    }
    ?>

    <header class="header">
        <div class="header__container container-gp">
            <div class="header__toggle" id="header-toggle">
                <i class='bx bx-menu text-color'></i>
            </div>

            <div class="container-wrapper">

                <div class="input-wrapper">
                    <input type="text" placeholder="Pesquisar..." class="pesquisa" name="pesquisar" id="search">
                    <i class='bx bx-search'></i>
                </div>
            </div>

        </div>

    </header>

    <!--=============== SIDEBAR ===============-->
    <div class="sidebar show-sidebar  fundo-color" id="sidebar">
        <nav class="sidebar__container text-color">
            <div class="sidebar__logo">
                <img src="../assets/images/log5.png" alt="" class="sidebar__logo-img">
                <span class="sidebar__logo-text text-color">THE GLARK</span>
            </div>

            <div class="sidebar__content">
                <div class="sidebar__list">
                    <a href="#" class="sidebar__link active-link">
                        <i class="bx bx-grid-alt text-color"></i>
                        <span class="sidebar__link-name text-color">Início</span>
                        <span class="sidebar__link-floating text-color">Início</span>
                    </a>

                    <a href="biblioteca.php" class="sidebar__link" >
                        <i class='bx bxs-star text-color'></i>
                        <span class="sidebar__link-name text-color">Biblioteca</span>
                        <span class="sidebar__link-floating text-color">Biblioteca</span>
                    </a>

                    <a href="publique.php" class="sidebar__link" >
                        <i class='bx bx-book-reader text-color'></i>
                        <span class="sidebar__link-name text-color">Publique Obras</span>
                        <span class="sidebar__link-floating text-color">Publique Obras</span>
                    </a>

                    <a href="planos.php" class="sidebar__link" >
                        <i class='bx bx-money text-color'></i>
                        <span class="sidebar__link-name text-color">Planos Mensais</span>
                        <span class="sidebar__link-floating text-color">Planos Mensais</span>
                    </a>


                </div>

                <h3 class="sidebar__title">
                    <span class="text-color">Utilitários</span>
                </h3>

                <div class="sidebar__list">

                    <a href="#" class="sidebar__link theme-toggle" id="themeIconContainer" onclick="toggleTheme()"
                        aria-label="Alternar tema" aria-pressed="false">
                        <i class='bx bx-sun text-color'></i>
                        <span class="sidebar__link-name text-color">Alterar Tema</span>
                        <span class="sidebar__link-floating text-color">Alterar Tema</span>
                    </a>

                    <a href="suporte.php" class="sidebar__link" >
                        <i class='bx bxs-help-circle text-color'></i>
                        <span class="sidebar__link-name text-color">Suporte</span>
                        <span class="sidebar__link-floating text-color">Suporte</span>
                    </a>

                    

                    <a href="./logout.php" class="sidebar__link">
                        <i class="bx bx-log-out text-color"></i>
                        <span class="sidebar__link-name text-color">Logout</span>
                        <span class="sidebar__link-floating text-color">Logout</span>
                    </a>
                </div>
            </div>

            <div class="sidebar__account" data-target="section-perfil">
                <?php
                $conexao = mysqli_connect("localhost", "root", "", "bd_glark");

                $user = $_SESSION['usuario'];

                if (mysqli_connect_errno()) {
                    echo "Erro no Banco de Dados!" . mysqli_connect_errno();
                }

                $seleciona_info = "SELECT * FROM tb_usuario WHERE ds_email='$user'"; //efetua a seleção no banco de dados e atribui a uma variável

                $busca = mysqli_query($conexao, $seleciona_info);

                $resultado = mysqli_fetch_array($busca);

                $nome = isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : '';
                $apelido = isset($_SESSION['apelido']) ? htmlspecialchars($_SESSION['apelido']) : '';
                ?>

                <img onclick="location.href='perfil.php';"  class="sidebar__perfil" src="<?php echo $resultado['ds_foto_perfil']; ?>" alt="Foto de Perfil" />
                <?php
                echo "<p class='sidebar__email'> $nome <br> $apelido</p>"
                ?>

                <a href="perfil.php">
                    <i class='bx bxs-chevron-right-circle text-color'></i>
                </a>
            </div>
        </nav>
    </div>

    <!--=============== MAIN ===============-->
    <main class="main-pd main content-section active" id="section-inicio">

        <!-- LATEST MOVIES SECTION -->
        <div class="section">
            <div class="container-gp">
                <div class="section-header text-color">
                    Destaques
                </div>
                <div class="movies-slide carousel-nav-center owl-carousel">
                    <!-- MOVIE ITEM -->
                    <a href="obra.php" class="movie-item" >
                        <img src="../capitulo2/o-menino-nemo-na-terra-dos-sonhos-1_page-0001.jpg" alt="#" />
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                O Menino Nemo
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.4</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://spawnbrasil.com.br/wp-content/uploads/2020/02/spawn-112-capa-editora-abril-por-guia-dos-quadrinhos.jpg"
                            alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Spawn
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.5</span>
                                </div>
                            </div>

                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://f.i.uol.com.br/fotografia/2021/10/05/1633460103615c9f879caa1_1633460103_3x2_md.jpg"
                            alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Fronteiras do Além
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>9.5</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://img.olx.com.br/images/19/199402202691144.jpg" alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Death Metal
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>7.9</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://www.europanet.com.br/image_gen/resizeimg.php?cod_produto=130008&h=420" alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Rocketeer
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>7.8</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/anime-manga-style-album-cover-1.0-design-template-0fc6d256e9ff17603475dfb129b132f0_screen.jpg?ts=1664026643"
                            alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Forgotten Feelinggs
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.7</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->

                </div>
            </div>
        </div>
        <!-- END LATEST MOVIES SECTION -->

        <!-- LATEST SERIES SECTION -->
        <div class="section">
            <div class="container-gp">
                <div class="section-header text-color">
                    Mais Acessados
                </div>
                <div class="movies-slide carousel-nav-center owl-carousel">

                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item" data-target="section-obra">
                        <img src="https://acdn.mitiendanube.com/stores/973/807/products/0121-327f3d19f1163edf6f16258954305539-640-0.jpg"
                            alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Fugiken
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>7.7</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEh_-IT7E14qX1WECoLooV00Rdir53nicYluhC35KpDVX4FSThGm0zBjscrrVvbw8lCyvoCxpz9_3zYpGqZOWsqIU8x5uPNX33hrlZ6eIFS7UOw5fNCyglt2Q2m0PedWFcbMAmbr1n-mfWfH/s1600/Jaspion.jpg"
                            alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                O Regresso de Jaspion
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.4</span>
                                </div>

                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://hyperioncomics.com.br/wp-content/uploads/2022/10/0-300x459.jpg" alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Eu Odeio Conto de Fadas
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>7.9</span>
                                </div>

                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://zinebrasil.wordpress.com/wp-content/uploads/2012/04/capa-contos.jpg" alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Contos do Absurdo
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.5</span>
                                </div>

                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://i.pinimg.com/474x/08/a7/18/08a7185ecfd01971106503f9d4be3961.jpg" alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Almanaque do Fantasma
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.1</span>
                                </div>

                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://assets.isu.pub/document-structure/240608035401-449011b4ba0981f4a6d247f252fe56ec/v1/8bfaa39e3bd05313a7ebf7e2d3952e13.jpeg"
                            alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Cartilha da Justiça
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>6.5</span>
                                </div>

                            </div>
                        </div>
                    </a>

                    <!-- END MOVIE ITEM -->
                </div>
            </div>
        </div>
        <!-- END LATEST SERIES SECTION -->

        <!-- LATEST CARTOONS SECTION -->
        <div class="section">
            <div class="container-gp">
                <div class="section-header text-color">
                    Lançamentos
                </div>
                <div class="movies-slide carousel-nav-center owl-carousel">
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://m.media-amazon.com/images/I/91i217MtWbL._AC_UF1000,1000_QL80_.jpg" alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Five Ninghts at Freddy's
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>6.8</span>
                                </div>

                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://loja.ligiazanella.com.br/wp-content/uploads/2023/08/calendar-capa-300x432-1.jpg"
                            alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Calendar
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>7.5</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://cupulatrovao.com.br/wp-content/uploads/2020/06/Divis%C3%A3o-5-mang%C3%A1.jpg"
                            alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Divisão 5
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.7</span>
                                </div>

                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://zinebrasil.wordpress.com/wp-content/uploads/2015/04/capa-capitao-brasil1.jpg?w=584"
                            alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Capitão Brasil
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>6.8</span>
                                </div>

                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://www.jbchost.com.br/editorajbc/wp-content/uploads/2023/11/9horas-master-edition-capa.jpg"
                            alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                9 Horas
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.5</span>
                                </div>

                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->


                    <a href="#" class="movie-item">
                        <img src="https://i.pinimg.com/236x/3c/e4/7a/3ce47a75024ee3c4a6d2833a75bc1670.jpg" alt="#" class="img-escura">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Wonder Girl
                            </div>
                            <div class="movie-infos">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>7.2</span>
                                </div>

                            </div>
                        </div>
                    </a>
                    <!-- END MOVIE ITEM -->

                </div>
            </div>
        </div>
        <!-- END LATEST CARTOONS SECTION -->

       
    </main>
    <!-- # FIM INÍCIO  -->

    <!-- # CONFIGURAÇÕES -->
    <main id="section-config" class="home-section content-section">
        <img src="https://www.protecaomaxima.com.br/imgs/em_desenvolvimento.jpg" alt="" class="manutenção">
    </main>
    <!-- # FIM CONFIGURAÇÕES -->




                <div class="star-rating">
                </div>


    <!-- # LEITURA DO MANGÁ -->
    <main id="section-leitura-manga" class=" content-section">

        <div class="btn-troca">
            <a data-target="section-obra1"><i class='bx bxs-left-arrow-circle'></i></a>
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

    <!-- SCRIPTS -->
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- OWL CAROUSEL -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
        integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
        crossorigin="anonymous"></script>

    <!-- APP SCRIPT -->
    <script src="../home-assets/Js/app.js"></script>
    <script src="../home-assets/Js/script.js"></script>
    <!--  FIM SCRIPT -->



</body>

</html>