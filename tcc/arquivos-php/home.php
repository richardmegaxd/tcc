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

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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

    // URL base da API
    $base_url = "https://api.mangadex.org";

    // Função para buscar um mangá sem necessidade de autenticação
    function buscar_manga($title)
    {
        global $base_url;

        if (empty(trim($title))) {
            echo "Por favor, forneça um título para buscar.";
            return;
        }

        $url = $base_url . "/manga?title=" . urlencode($title);

        echo "<p>URL da requisição: " . htmlspecialchars($url) . "</p>"; // Exibir a URL para depuração
    
        // Inicializa o cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        // Adiciona um cabeçalho User-Agent
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
        ]);

        // Desabilita a verificação de certificado SSL (apenas para testar, não recomendado em produção)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Executa a requisição
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Verifica se houve erro na requisição
        if (curl_errno($ch)) {
            echo "Erro na requisição: " . curl_error($ch);
            curl_close($ch);
            return;
        }

        // Fecha o cURL
        curl_close($ch);

        // Verifica o código de resposta HTTP
        if ($http_code != 200) {
            echo "Falha ao buscar mangá. Código HTTP: " . $http_code;
            return;
        }

        // Decodifica a resposta JSON
        $mangas = json_decode($response, true)['data'];

        if (empty($mangas)) {
            echo "Nenhum mangá encontrado.";
            return;
        }

        // Para cada mangá encontrado, buscar a imagem da capa
        foreach ($mangas as $manga) {
            $titulo = isset($manga['attributes']['title']['en']) ? $manga['attributes']['title']['en'] : "Título indisponível";
            $id = $manga['id'];

            // Encontrar a capa do mangá
            $cover_id = '';
            foreach ($manga['relationships'] as $relationship) {
                if ($relationship['type'] == 'cover_art') {
                    $cover_id = $relationship['id'];
                    break;
                }
            }

            // Se encontrarmos um ID de capa, buscar a informação da capa
            $cover_url = '';
            if (!empty($cover_id)) {
                $cover_api_url = $base_url . "/cover/" . $cover_id;

                // Executa uma requisição para buscar a URL da capa
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $cover_api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
                ]);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $cover_response = curl_exec($ch);
                curl_close($ch);

                if ($cover_response !== false) {
                    $cover_data = json_decode($cover_response, true)['data'];
                    if (isset($cover_data['attributes']['fileName'])) {
                        $file_name = $cover_data['attributes']['fileName'];
                        $cover_url = "https://uploads.mangadex.org/covers/$id/$file_name";
                    }
                }
            }

            // Exibir informações do mangá
            echo "<div style='margin-bottom: 20px;'>";
            echo "<h3>" . htmlspecialchars($titulo) . "</h3>";
            if (!empty($cover_url)) {
                echo "<img src='" . htmlspecialchars($cover_url) . "' alt='Capa do Mangá' style='max-width: 200px;'><br>";
            } else {
                echo "<p>Capa não disponível.</p>";
            }
            echo "<p>ID: " . htmlspecialchars($id) . "</p>";
            echo "</div>";
        }
    }

    // Função principal
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesquisar'])) {
        $title = $_POST['pesquisar'];
        buscar_manga($title);
    }
    ?>

    <header class="header">
        <div class="header__container container-gp">
            <div class="header__toggle" id="header-toggle">
                <i class='bx bx-menu text-color'></i>
            </div>

            <div class="container-wrapper">
                <div class="container-gp">
                    <div class="input-wrapper">
                        <input type="text" placeholder="Pesquisar...">
                        <i class='bx bx-search'></i>
                    </div>
                </div>

            </div>

        </div>
    </header>

    <!--=============== SIDEBAR ===============-->
    <div class="sidebar text-color" id="sidebar">
        <nav class="sidebar__container">
            <div class="sidebar__logo">
                <img src="../assets/images/log5.png" alt="" class="sidebar__logo-img">
                <span class="sidebar__logo-text text-color">THE GLARK</span>
            </div>

            <div class="sidebar__content">
                <div class="sidebar__list">
                    <a href="#" class="sidebar__link active-link" data-target="section-inicio">
                        <i class="bx bx-grid-alt text-color"></i>
                        <span class="sidebar__link-name text-color">Início</span>
                        <span class="sidebar__link-floating text-color">Início</span>
                    </a>

                    <a href="#" class="sidebar__link" data-target="section-biblioteca">
                        <i class='bx bxs-star text-color'></i>
                        <span class="sidebar__link-name text-color">Biblioteca</span>
                        <span class="sidebar__link-floating text-color">Biblioteca</span>
                    </a>

                    <a href="#" class="sidebar__link" data-target="section-publicacao-obras">
                        <i class='bx bx-book-reader text-color'></i>
                        <span class="sidebar__link-name text-color">Publique Obras</span>
                        <span class="sidebar__link-floating text-color">Publique Obras</span>
                    </a>

                    <a href="#" class="sidebar__link" data-target="section-planos-mensais">
                        <i class='bx bx-money text-color'></i>
                        <span class="sidebar__link-name text-color">Planos Mensais</span>
                        <span class="sidebar__link-floating text-color">Planos Mensais</span>
                    </a>


                </div>

                <h3 class="sidebar__title">
                    <span>-------------</span>
                </h3>

                <div class="sidebar__list">

                    <a href="#" class="sidebar__link theme-toggle" id="themeIconContainer" onclick="toggleTheme()"
                        aria-label="Alternar tema" aria-pressed="false">
                        <i class='bx bx-sun text-color'></i>
                        <span class="sidebar__link-name text-color">Alterar Tema</span>
                        <span class="sidebar__link-floating text-color">Alterar Tema</span>
                    </a>

                    <a href="#" class="sidebar__link" data-target="section-suporte">
                        <i class='bx bxs-help-circle text-color'></i>
                        <span class="sidebar__link-name text-color">Suporte</span>
                        <span class="sidebar__link-floating text-color">Suporte</span>
                    </a>

                    <a href="#" class="sidebar__link" data-target="section-config">
                        <i class="bx bx-cog text-color"></i>
                        <span class="sidebar__link-name text-color">Configurações</span>
                        <span class="sidebar__link-floating text-color">Configurações</span>
                    </a>

                    <a href="./logout.php" class="sidebar__link">
                        <i class="bx bx-log-out text-color"></i>
                        <span class="sidebar__link-name text-color">Logout</span>
                        <span class="sidebar__link-floating text-color">Logout</span>
                    </a>
                </div>
            </div>

            <div class="sidebar__account">
                <?php
                $nome = isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : '';
                $apelido = isset($_SESSION['apelido']) ? htmlspecialchars($_SESSION['apelido']) : '';
                ?>
                <img src="<?php echo $resultado['ds_foto_perfil']; ?>" alt="Foto de Perfil" width="50px" />
                <?php
                echo "<p> $nome <br> $apelido</p>"
                    ?>

                <a data-target="section-perfil">
                    <i class='bx bxs-chevron-right-circle' data-target="section-perfil"></i>
                </a>
            </div>
        </nav>
    </div>

    <!--=============== MAIN ===============-->
    <main class=" main content-section active" id="section-inicio">

        <!-- LATEST MOVIES SECTION -->
        <div class="section">
            <div class="container-gp">
                <div class="section-header text-color">
                    Destaques
                </div>
                <div class="movies-slide carousel-nav-center owl-carousel">
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item" data-target="section-obra">
                        <img src="https://www.europanet.com.br/image_gen/resizeimg.php?cod_produto=130008&h=420"
                            alt="#" />
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Theatre of the dead
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
                        <img src="https://spawnbrasil.com.br/wp-content/uploads/2020/02/spawn-112-capa-editora-abril-por-guia-dos-quadrinhos.jpg"
                            alt="#">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Transformer
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
                        <img src="https://f.i.uol.com.br/fotografia/2021/10/05/1633460103615c9f879caa1_1633460103_3x2_md.jpg"
                            alt="#">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Resident Evil
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
                        <img src="https://img.olx.com.br/images/19/199402202691144.jpg" alt="#">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Captain Marvel
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
                        <img src="https://lh4.googleusercontent.com/proxy/E1BCT7J87lag4WhJ2aWJTPrsxNvkUF5tpVhJNSglh3TeSerfFZ-9yHWnmXTCF5hgkeWLJ8e9nEk9HfcBdnwc-TkeUpDFH11hV7AOUdDUIwwxWFYHOyw5Pw9lXIMEZ1fyxWso4i8OWy3m"
                            alt="">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Hunter Killer
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
                        <img src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/anime-manga-style-album-cover-1.0-design-template-0fc6d256e9ff17603475dfb129b132f0_screen.jpg?ts=1664026643"
                            alt="#">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Bloodshot
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

                </div>
            </div>
        </div>
        <!-- END LATEST MOVIES SECTION -->

        <!-- LATEST SERIES SECTION -->
        <div class="section">
            <div class="container-gp">
                <div class="section-header text-color">
                    Continue Lendo
                </div>
                <div class="movies-slide carousel-nav-center owl-carousel">

                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item">
                        <img src="https://cupulatrovao.com.br/wp-content/uploads/2020/06/Divis%C3%A3o-5-mang%C3%A1.jpg"
                            alt="#">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Stranger Things
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
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEh_-IT7E14qX1WECoLooV00Rdir53nicYluhC35KpDVX4FSThGm0zBjscrrVvbw8lCyvoCxpz9_3zYpGqZOWsqIU8x5uPNX33hrlZ6eIFS7UOw5fNCyglt2Q2m0PedWFcbMAmbr1n-mfWfH/s1600/Jaspion.jpg"
                            alt="#">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Star Trek
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
                        <img src="https://hyperioncomics.com.br/wp-content/uploads/2022/10/0-300x459.jpg" alt="">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Penthouses
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
                        <img src="https://imgv2-1-f.scribdassets.com/img/word_document/605138375/original/99852f7170/1719936830?v=1"
                            alt="">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Mandalorian
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
                        <img src="https://i.pinimg.com/474x/08/a7/18/08a7185ecfd01971106503f9d4be3961.jpg" alt="">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                The Falcon And The Winter Soldier
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
                        <img src="https://cdn.kobo.com/book-images/9b82874e-df48-4981-a011-e4c3156ba65c/353/569/90/False/submechanophobia-an-afk-book-five-nights-at-freddy-s-tales-from-the-pizzaplex-4.jpg"
                            alt="">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Wanda Vision
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
                        <img src="https://m.media-amazon.com/images/I/91i217MtWbL._AC_UF1000,1000_QL80_.jpg" alt="">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Demon Slayer
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
                        <img src="https://loja.ligiazanella.com.br/wp-content/uploads/2023/08/calendar-capa-300x432-1.jpg"
                            alt="#">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Supergirl
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
                        <img src="https://devir.com.br/diadoquadrinhogratis/assets/img/capas/conrad-digital2023_1.jpg"
                            alt="">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Croods
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
                        <img src="https://zinebrasil.wordpress.com/wp-content/uploads/2015/04/capa-capitao-brasil1.jpg?w=584"
                            alt="">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Dragonball
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
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSy1hqh9t4ovdhLbxh4cJiHgYgf6O-9cYvsug&s"
                            alt="">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Over The Moon
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


                    <a href="#" class="movie-item">
                        <img src="https://i.pinimg.com/236x/3c/e4/7a/3ce47a75024ee3c4a6d2833a75bc1670.jpg" alt="">
                        <div class="movie-item-content">
                            <div class="movie-item-title">
                                Weathering With You
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

                </div>
            </div>
        </div>
        <!-- END LATEST CARTOONS SECTION -->
    </main>
    <!-- # FIM INÍCIO  -->

    <!-- # PERFIL -->
    <main id="section-perfil" class="home-section content-section">
        <?php
        if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != true) {
            header("Location: login.php");
            exit;
        }

        $user = $_SESSION['usuario'];

        $conexao = mysqli_connect("localhost", "root", "", "bd_glark");

        if (mysqli_connect_errno()) {
            echo "Erro no Banco de Dados!" . mysqli_connect_errno();
        }

        $seleciona_info = "SELECT * FROM tb_usuario WHERE ds_email='$user'"; //efetua a seleção no banco de dados e atribui a uma variável
        
        $busca = mysqli_query($conexao, $seleciona_info);

        $resultado = mysqli_fetch_array($busca);

        $_SESSION['nome'] = $resultado['nm_user'];
        $_SESSION['apelido'] = $resultado['nm_apelido'];

        $login_google = $resultado['login_google'];

        ?>
        <div class="usuario-info">
            <img src="<?php echo $resultado['ds_foto_perfil']; ?>" alt="Foto de Perfil" />
            <p>Email: <?php echo $resultado[1]; ?></p> <!-- Exibindo o nome -->
            <p>Nome: <?php echo $resultado[3]; ?></p> <!-- Exibindo a idade -->
            <p>Apelido: <?php echo $resultado[4]; ?></p> <!-- Exibindo o endereço -->
        </div>
        <?php if ($login_google == 1): ?>
            <!-- Se o usuário fez login com o Google, a opção de editar perfil é desativada -->
        <?php else: ?>
            <!-- Se não fez login com o Google, a opção de editar perfil é ativada -->
            <a href="edit.php" class="a1">Editar Usuário</a>
        <?php endif; ?>
            <a href="delete.php" class="a1">Excluir Usuário</a>
    </main>
    <!-- # FIM PERFIL -->

    <!-- # BIBLIOTECA -->
    <main id="section-biblioteca" class="home-section content-section">
        <section>
            <?php
            $qtObras = 8;
            ?>
            <div class="lancamentos-heading2">

                <h2><?php echo "$qtObras" ?> Obras favoritadas</h2>

                <button class="dropdown-button" onclick="toggleDropdown()">

                    <h2>
                        Organizar

                        <i class='bx bxs-up-arrow-alt'></i>
                        <i class='bx bxs-down-arrow-alt'></i>

                        <ul class="dropdown-content" id="dropdown">
                            <li onclick="ordenar('az')">A - Z</li>
                            <li onclick="ordenar('za')">Z - A</li>
                            <li onclick="ordenar('ultimo')">Último Adicionado</li>
                            <li onclick="ordenar('primeiro')">Primeiro Adicionado</li>
                        </ul>
                    </h2>

                </button>

            </div>

            <script>
                // Função para alternar a visibilidade do dropdown
                function toggleDropdown() {
                    document.getElementById("dropdown").classList.toggle("show");
                }
            </script>

            <div id="lancamentos-list">

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <img
                            src="https://zinebrasil.wordpress.com/wp-content/uploads/2015/04/capa-capitao-brasil1.jpg?w=584">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <img src="https://devir.com.br/diadoquadrinhogratis/assets/img/capas/conrad-digital2023_3.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <img src="https://devir.com.br/diadoquadrinhogratis/assets/img/capas/devir-digital2023_5.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <img
                            src="https://www.jbchost.com.br/editorajbc/wp-content/uploads/2023/11/9horas-master-edition-capa.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <img
                            src="https://editoradraco.com/wp-content/uploads/2023/03/Retratosbrutos-CC-capa-500x718.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <img src="https://i.pinimg.com/236x/ce/44/04/ce44046067f7d3121f4d81fed5f9b146.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <img
                            src="https://acdn.mitiendanube.com/stores/141/982/products/contosorixas1-0b0a04e13e6fbb2bb915661866635852-480-0.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <img src="https://www.newpop.com.br/wp-content/uploads/2020/03/NewPOP_Grimms01.jpg">
                    </div>
                </div>

            </div>

        </section>
    </main>
    <!-- # FIM BIBLIOTECA -->

    <!-- # PUBLICAÇÃO DE OBRAS -->
    <main id="section-publicacao-obras" class="home-section content-section">
        <div class="containerM"><!-- PEGA TUDO -->
            <div class="containerM2"><!-- PEGA TUDO 2 -->
                <div class="tela1">
                    <div class="tela11"><!-- INICIO DA PRIMEIRA PARTE -->
                        <div class="hey">
                            <h3>Hey autor</h3>
                        </div>
                        <div class="quer">
                            <h1>Quer publicar no The Glark?</h1>
                        </div>
                        <div class="Mtexto">
                            <p>Na The Glark, acreditamos que cada história merece ser contada e compartilhada.<br>Se
                                você tem uma obra que gostaria de ver publicada, estamos aqui para ajudar!</p>
                        </div>
                        <div class="Mroxo">
                            <h2>Faça parte do nosso catálogo!</h2>
                        </div>
                    </div>
                    <div class="imgQ1">
                        <img src="TCC/home-assets/images/sofrimento.png" alt="">
                    </div>
                </div><!-- FINAL DA PRIMEIRA PARTE -->
                <div class="tela2"><!-- INICIO DA SEGUNDA PARTE -->
                    <div class="benef">
                        <h2>Beneficios</h2>
                    </div>
                    <div class="tela22">
                        <div class="qB1"><!-- QUADRADO BENEFICIOS 1 -->
                            <div class="divImgB1">
                                <img class="imgB1" src="../home-assets/images/19.png" alt="">
                            </div>
                            <div class="justa">
                                <h3 class="justa">Justa Monetização</h3>
                            </div>
                             <div class="just2">
                                <p class="justa2">texto aqui texto aqui texto aqui<br>texto aqui texto aqui texto aqui</p>
                            </div>
                        </div>
                    </div>
                        <div class="qB2"><!-- QUADRADO BENEFICIOS 2 -->
                            <div class="imgB2">
                                <img src="TCC/home-assets/images/20.png" alt="">
                            </div>
                            <div class="qB22"><!-- QUADRADO BENEFICIOS 2.2 -->
                                <h3>Direitos Autorais</h3>
                                <p>texto aqui</p>
                            </div>
                        </div>
                        <div class="qB3"><!-- QUADRADO BENEFICIOS 3 -->
                            <div class="imgB3">
                                <img src="TCC/home-assets/images/21.png" alt="">
                            </div>
                            <div class="qB33"><!-- QUADRADO BENEFICIOS 3.3 -->
                                <h3>Exposição e Alcance</h3>
                                <p>texto aqui</p>
                            </div>
                        </div>
                    </div>
                </div><!-- FINAL DA SEGUNDA PARTE -->
                <div class="tela3"><!-- INICIO DA TERCEIRA PARTE -->
                    <div class="requisitos">
                        <h2>Requisitos</h2>
                    </div>
                    <div class="qR1"><!-- QUADRADO REQUISITOS 1 -->
                        <div class="imgR1">
                            <img src="" alt="">
                        </div>
                        <div class="qR11"><!-- QUADRADO REQUISITOS 1.1 -->
                            <h3>Conteudo Autoral</h3>
                            <p>texto aqui</p>
                        </div>
                    </div>
                    <div class="qR2"><!-- QUADRADO REQUISITOS 2 -->
                        <div class="imgR2">
                            <img src="" alt="">
                        </div>
                        <div class="qR22"><!-- QUADRADO REQUISITOS 2.2 -->
                            <h3>Conteudo Inapropriado</h3>
                            <p>texto aqui texto aqui texto aqui texto aqui<br>texto aqui texto aqui texto aqui texto
                                aqui</p>
                        </div>
                    </div>
                    <div class="qR3"><!-- QUADRADO REQUISITOS 3 -->
                        <div class="imgR3">
                            <img src="" alt="">
                        </div>
                        <div class="qR33"><!-- QUADRADO REQUISITOS 3.3 -->
                            <h3>Frequencia de Postagem</h3>
                            <p>texto aqui</p>
                        </div>
                    </div>
                    <div class="qR4"><!-- QUADRADO REQUISITOS 4 -->
                        <div class="imgR4">
                            <img src="" alt="">
                        </div>
                        <div class="qR44"><!-- QUADRADO REQUISITOS 4.4 -->
                            <h3>Titulo aqui</h3>
                            <p>texto aqui</p>
                        </div>
                    </div>
                </div><!-- FINAL DA TERCEIRA PARTE -->
                <div class="tela4"><!-- INICIO DA QUARTA PARTE -->
                    <div class="imgCap">
                        <img src="" alt="">
                    </div>
                    <div class="qC1"><!-- QUADRADO COMO PUBLICAR -->
                        <h2>Como publicar?</h2>
                        <p>texto aqui</p>
                    </div>
                    <div class="btnAces"><!-- BOTAO ACESSAR FORM DE MANDAR OBRA -->
                        <p>Acessar formulário</p>
                    </div>
                </div><!-- FINAL DA QUARTA PARTE -->
            </div>
        </div>
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
    <!-- # FIM CONFIGURAÇÕES -->

    l

    <!-- # PERFIL DO MANGÁ -->
    <main id="section-obra" class="home-section content-section">
        <a data-target="section-inicio"><i class='bx bxs-left-arrow-circle'></i></a>
        <div class="container">
            <img src="https://www.nautiljon.com/images/manga/00/03/fukigen_na_mononokean_5230.webp" alt="Capa do Mangá"
                class="manga-cover">
            <div class="manga-info">
                <h2 class="text-color">Fukigen</h2>

                <div class="info-item">
                    <p class="info-label text-color">Gêneros:</p>
                    <p class="info-value text-color">Ação, Aventura, Romance</p>
                </div>
                <div class="info-item">
                    <p class="info-label text-color">Tipo:</p>
                    <p class="info-value text-color">Preto e Branco</p>
                </div>
                <div class="info-item">
                    <p class="info-label text-color">Ano:</p>
                    <p class="info-value text-color">2024</p>
                </div>
                <div class="info-item">
                    <p class="info-label text-color">Status:</p>
                    <p class="info-value text-color">Ativo</p>
                </div>
                <div class="info-item">
                    <p class="info-label text-color">Lançamento:</p>
                    <p class="info-value text-color">08 de Outubro de 2015</p>
                </div>

                <div class="star-rating">
                    <i class="fa fa-star" aria-hidden="true" onclick="rate(1)">★</i>
                    <i class="fa fa-star" aria-hidden="true" onclick="rate(2)">★</i>
                    <i class="fa fa-star" aria-hidden="true" onclick="rate(3)">★</i>
                    <i class="fa fa-star" aria-hidden="true" onclick="rate(4)">★</i>
                    <i class="fa fa-star" aria-hidden="true" onclick="rate(5)">★</i>
                </div>

            </div>
        </div>

        <div class="chapters">
            <div class="titulo-cap">
                <img width="60" height="60" src="https://img.icons8.com/color/48/comics-magazine.png"
                    alt="comics-magazine" />
                <h2 class="text-color">CAPÍTULOS</h2>

            </div>
            <a class="chapter" data-target="section-leitura-manga">
                <img src="https://cm.blazefast.co/25/b6/25b661d784cc6ac96e1726c5e45f9666.jpg" alt="Capa do Capítulo"
                    class="chapter-cover">
                <div>
                    <span class="chapter-number">Capítulo 26</span>
                </div>

            </a>
            <a href="./leitura.php" class="chapter" data-target="section-leitura-manga">
                <img src="https://i.ebayimg.com/images/g/lVUAAOSwfZxguF6M/s-l1200.jpg" alt="Capa do Capítulo"
                    class="chapter-cover">
                <div>
                    <span class="chapter-number">Capítulo 25</span>

                </div>
            </a>
            <a class="chapter" data-target="section-leitura-manga">
                <img src="https://i0.wp.com/www.otakupt.com/wp-content/uploads/2021/03/Fukigen-na-Mononokean-volume-17-cover.jpg?resize=696%2C990&ssl=1"
                    alt="Capa do Capítulo" class="chapter-cover">
                <div>
                    <span class="chapter-number">Capítulo 24</span>
                </div>
            </a>

        </div>

        </div>

        <div class="comments">
            <ul>
                <!-- Exibe os comentários aqui -->
            </ul>
            <form id="comment-form">
                <label for="comment" class="text-color">Deixe seu comentário:</label>
                <textarea id="comment" name="comment" rows="4" placeholder="Digite seu comentário"></textarea>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </main>
    <!-- # FIM PERFIL MANGÁ -->

    <!-- # LEITURA DO MANGÁ -->
    <main id="section-leitura-manga" class=" content-section">

        <a data-target="section-inicio"><i class='bx bxs-left-arrow-circle'></i></a>

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
    <script src="../home-assets/Js/script2.js"></script>
    <!--  FIM SCRIPT -->



</body>

</html>