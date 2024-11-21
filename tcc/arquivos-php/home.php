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
    <link rel="stylesheet" href="../home-assets/css/app2.css">
    <!-- ICON DE REDES SOCIAIS - ABA DE SUPPORT <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">-->
    
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

    if (isset($_SESSION['success_message'])) {
        echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
        unset($_SESSION['success_message']);
    }

    // Exibe mensagem de erro, se houver
    if (isset($_SESSION['error_message'])) {
        echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
        unset($_SESSION['error_message']);
    }
    ?>

    <header class="header">
        <div class="header__container container-gp">
            <div class="header__toggle" id="header-toggle">
                <i class='bx bx-menu text-color'></i>
            </div>

            <div class="container-wrapper">

                <div class="input-wrapper">
                    <input type="text" placeholder="Pesquisar..." class="pesquisa">
                    <i class='bx bx-search'></i>
                </div>
            </div>

        </div>

    </header>

    <!--=============== SIDEBAR ===============-->
    <div class="sidebar fundo-color" id="sidebar">
        <nav class="sidebar__container text-color">
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
                    <span class="text-color">Utilitários</span>
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

                <img class="sidebar__perfil" src="<?php echo $resultado['ds_foto_perfil']; ?>" alt="Foto de Perfil" />
                <?php
                echo "<p class='sidebar__email'> $nome <br> $apelido</p>"
                ?>

                <a data-target="section-perfil">
                    <i class='bx bxs-chevron-right-circle text-color' data-target="section-perfil"></i>
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
                    <a href="#" class="movie-item" data-target="section-obra1">
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                    Continue Lendo
                </div>
                <div class="movies-slide carousel-nav-center owl-carousel">

                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item" data-target="section-obra">
                        <img src="https://acdn.mitiendanube.com/stores/973/807/products/0121-327f3d19f1163edf6f16258954305539-640-0.jpg"
                            alt="#" class="img-escura">
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
                        <div class="overlay">Indisponível</div>
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
        <header class="usuario-back text-color">
            <div class="user_top">
                <img class="foto-perfil" src="<?php echo $resultado['ds_foto_perfil']; ?>" alt="Foto de Perfil" />
                <div class="info-usuario">
                    <h2 class="nome-perfil text-color"><?php echo "$resultado[3]" ?></h2>
                    <h2 class="nome-perfil text-color"><?php echo "$resultado[4]" ?></h2> <!-- Exibindo o nome -->

                    <div class="area-seguidos ">

                        <h3>02 Seguidos</h3>

                        <h3>02 Seguidores</h>

                    </div>

                </div>
            </div>
            <div class="obg">

                <button class="dropb-button" onclick="toggleDropb()">
                    <h1 class="text-color dropb">

                        <i class='bx bx-dots-horizontal-rounded ppp'></i>

                        <ul class="dropb-content" id="dropb">
                            <?php if ($login_google == 1): ?>
                                <!-- Se o usuário fez login com o Google, a opção de editar perfil é desativada -->
                            <?php else: ?>
                                <!-- Se não fez login com o Google, a opção de editar perfil é ativada -->
                                <li><a class="oof" href="edit.php">Editar Perfil</a></li>
                            <?php endif; ?>
                            <li><a class="oof2" href="edit2.php">Editar Aparencia</a></li>
                            <li><a class="oof2" href="confirmadelete.php">Deletar Perfil</a></li>
                        </ul>
                    </h1>
                </button>

                <script>
                    // Função para alternar a visibilidade do dropdown
                    function toggleDropb() {
                        document.getElementById("dropb").classList.toggle("show");
                    }
                </script>

            </div>

        </header>


        <div class="usuario-back2 text-color">
            <div class="area-seguir">
                <div class="seguir">

                    <h3 class="icu">+</h3>
                    <h3>Seguir</h3>

                </div>

                <div class="seguir">

                    <h2><i class='bx bxs-heart corazon'></i></h2>
                    <h3>12</h3>

                </div>
            </div>

            <div class="area-biografia">

                <h3>Biografia</h3>

                <p>
                    Tenho 18 anos, gosto de escrever e gostaria de fazer novos amigos. Prazer em te conhecer!
                </p>

                <Button class="ler-mais text-color">
                    Ler mais
                </Button>

            </div>

            <div class="area-tags">

                <div class="tags">

                    <p>#Romance</p>

                </div>

                <div class="tags">

                    <p>#Romance</p>

                </div>

                <div class="tags">

                    <p>#Romance</p>

                </div>

            </div>
        </div>

        <div class="conteudo-perfil">
            <header class="header-perfil">
                <div class="topo-perfil">
                    <h2 class="tab active text-color" data-target="obras">Obras</h2>
                    <h2 class="tab text-color" data-target="recomendados">Recomendados</h2>
                    <!-- <h2 class="tab text-color" data-target="comentarios">Comentários</h2> -->
                </div>
                <hr>
            </header>

            <br>

            <button class="dropup-button" onclick="toggleDropup()">
                <h2 class="text-color dropup-p">

                    Organizar
                    <i class='bx bxs-up-arrow-alt'></i>
                    <i class='bx bxs-down-arrow-alt'></i>

                    <ul class="dropup-content" id="dropup">
                        <li onclick="ordenar('az')">A - Z</li>
                        <li onclick="ordenar('za')">Z - A</li>
                        <li onclick="ordenar('ultimo')">Último Adicionado</li>
                        <li onclick="ordenar('primeiro')">Primeiro Adicionado</li>
                    </ul>
                </h2>
            </button>

            <script>
                // Função para alternar a visibilidade do dropdown
                function toggleDropup() {
                    document.getElementById("dropup").classList.toggle("show");
                }
            </script>

            <br><br>

            <section id="obras" class="content active">

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

        </section>

        <section id="recomendados" class="content">

            <div class="movies-slide carousel-nav-center owl-carousel">
                <!-- MOVIE ITEM -->
                <a href="#" class="movie-item" data-target="section-obra">
                    <img src="https://www.europanet.com.br/image_gen/resizeimg.php?cod_produto=130008&h=420" alt="#" />
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

        </section>

        <section id="comentarios" class="content">

            Conteúdo comentarios

        </section>

        </div>




        <script>
            const tabs = document.querySelectorAll('.tab');
            const sections = document.querySelectorAll('.content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remover a classe 'active' da aba e da seção ativa
                    document.querySelector('.tab.active')?.classList.remove('active');
                    document.querySelector('.content.active')?.classList.remove('active');

                    // Adicionar a classe 'active' à aba clicada
                    tab.classList.add('active');

                    // Mostrar a seção correspondente
                    const target = tab.getAttribute('data-target');
                    document.getElementById(target).classList.add('active');

                    // Atualizar a posição da barra
                    updateBarPosition(tab);
                });
            });

            function updateBarPosition(activeTab) {
                const bar = document.querySelector('.bicabeca::after'); // A barra está no ::after
                const tabRect = activeTab.getBoundingClientRect();
                const headerRect = activeTab.parentElement.getBoundingClientRect();

                bar.style.width = `${tabRect.width}px`;
                bar.style.left = `${tabRect.left - headerRect.left}px`;
            }

            // Inicializa a barra na posição da aba ativa ao carregar a página
            updateBarPosition(document.querySelector('.tab.active'));
        </script>


    </main>
    <!-- # FIM PERFIL -->

    <!-- # BIBLIOTECA -->
    <?php
    //"SELECT COUNT(*) FROM tb_favoritos WHERE id_usuario = $resultado[0]"
    $qt_favoritos = 8;
    ?>
    <main id="section-biblioteca" class="home-section content-section">

        <br>

        <header class="header-bilioteca ">
            <div class="topo-biblioteca ">
                <h2 class="tab2 active text-color" data-target="favoritos"><i class='bx bxs-heart text-color'></i>
                    Favoritos</h2>
                <h2 class="tab2 text-color" data-target="continuar-lendo"><i class='bx bxs-book text-color'></i>
                    Continuar Lendo</h2>
                <h2 class="tab2 text-color" data-target="historico"><i class='bx bxs-time-five text-color'></i>
                    Histórico</h2>
                <h2 class="tab2 text-color" data-target="minhas-obras"><i class='bx bxs-pencil text-color'></i> Minhas
                    Obras</h2>
            </div>
            <hr>
        </header>

        <script>
            const tabs2 = document.querySelectorAll('.tab2');
            const sections2 = document.querySelectorAll('.content');

            tabs2.forEach(tab2 => {
                tab2.addEventListener('click', () => {
                    // Remover a classe 'active' da aba e da seção ativa
                    document.querySelector('.tab2.active')?.classList.remove('active');
                    document.querySelector('.content2.active')?.classList.remove('active');

                    // Adicionar a classe 'active' à aba clicada
                    tab2.classList.add('active');

                    // Mostrar a seção correspondente
                    const target = tab2.getAttribute('data-target');
                    document.getElementById(target).classList.add('active');

                    // Atualizar a posição da barra
                    updateBarPosition(tab2);
                });
            });

            // Inicializa a barra na posição da aba ativa ao carregar a página
            updateBarPosition(document.querySelector('.tab2.active'));
        </script>

        <section id="favoritos" class="content2 active">

            <div class="container-gp">
                <button class="dropdown-button" onclick="toggleDropdown()">
                    <h2 class="text-color dropdown-p">

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

                <script>
                    // Função para alternar a visibilidade do dropdown
                    function toggleDropdown() {
                        document.getElementById("dropdown").classList.toggle("show");
                    }
                </script>

                <div class="section-header text-color">
                    Obras Favoritadas
                </div>
                <div class="movies-slide carousel-nav-center owl-carousel">
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item" data-target="section-obra1">
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
                            alt="#">
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
                            alt="#">
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
                        <img src="https://img.olx.com.br/images/19/199402202691144.jpg" alt="#">
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
                        <img src="https://www.europanet.com.br/image_gen/resizeimg.php?cod_produto=130008&h=420" alt="">
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
                            alt="#">
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
        </section>

        <section id="continuar-lendo" class="content2">
            <div class="container-gp">

                <button class="dropdown-button" onclick="toggleDropdown2()">
                    <h2 class="text-color dropdown-p">

                        Organizar
                        <i class='bx bxs-up-arrow-alt'></i>
                        <i class='bx bxs-down-arrow-alt'></i>

                        <ul class="dropdown-content" id="dropdown2">
                            <li onclick="ordenar('az')">A - Z</li>
                            <li onclick="ordenar('za')">Z - A</li>
                            <li onclick="ordenar('ultimo')">Último Adicionado</li>
                            <li onclick="ordenar('primeiro')">Primeiro Adicionado</li>
                        </ul>
                    </h2>
                </button>

                <script>
                    // Função para alternar a visibilidade do dropdown
                    function toggleDropdown2() {
                        document.getElementById("dropdown2").classList.toggle("show");
                    }
                </script>

                <div class="section-header text-color">
                    Obras para ler
                </div>
                <div class="movies-slide carousel-nav-center owl-carousel">
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item" data-target="section-obra1">
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
                            alt="#">
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
                            alt="#">
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
                        <img src="https://img.olx.com.br/images/19/199402202691144.jpg" alt="#">
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
                        <img src="https://www.europanet.com.br/image_gen/resizeimg.php?cod_produto=130008&h=420" alt="">
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
                            alt="#">
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
        </section>

        <section id="historico" class="content2">
            <div class="container-gp">

                <button class="dropdown-button" onclick="toggleDropdown3()">
                    <h2 class="text-color dropdown-p">

                        Organizar
                        <i class='bx bxs-up-arrow-alt'></i>
                        <i class='bx bxs-down-arrow-alt'></i>

                        <ul class="dropdown-content" id="dropdown3">
                            <li onclick="ordenar('az')">A - Z</li>
                            <li onclick="ordenar('za')">Z - A</li>
                            <li onclick="ordenar('ultimo')">Último Adicionado</li>
                            <li onclick="ordenar('primeiro')">Primeiro Adicionado</li>
                        </ul>
                    </h2>
                </button>

                <script>
                    // Função para alternar a visibilidade do dropdown
                    function toggleDropdown3() {
                        document.getElementById("dropdown3").classList.toggle("show");
                    }
                </script>

                <div class="section-header text-color">
                    Obras acessadas
                </div>
                <div class="movies-slide carousel-nav-center owl-carousel">
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item" data-target="section-obra1">
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
                            alt="#">
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
                            alt="#">
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
                        <img src="https://img.olx.com.br/images/19/199402202691144.jpg" alt="#">
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
                        <img src="https://www.europanet.com.br/image_gen/resizeimg.php?cod_produto=130008&h=420" alt="">
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
                            alt="#">
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
        </section>

        <section id="minhas-obras" class="content2">

            <div class="container-gp">

                <button class="dropdown-button" onclick="toggleDropdown4()">
                    <h2 class="text-color dropdown-p">

                        Organizar
                        <i class='bx bxs-up-arrow-alt'></i>
                        <i class='bx bxs-down-arrow-alt'></i>

                        <ul class="dropdown-content" id="dropdown4">
                            <li onclick="ordenar('az')">A - Z</li>
                            <li onclick="ordenar('za')">Z - A</li>
                            <li onclick="ordenar('ultimo')">Último Adicionado</li>
                            <li onclick="ordenar('primeiro')">Primeiro Adicionado</li>
                        </ul>
                    </h2>
                </button>

                <script>
                    // Função para alternar a visibilidade do dropdown
                    function toggleDropdown4() {
                        document.getElementById("dropdown4").classList.toggle("show");
                    }
                </script>

                <div class="section-header text-color">
                    Obras publicadas
                </div>
                <div class="movies-slide carousel-nav-center owl-carousel">
                    <!-- MOVIE ITEM -->
                    <a href="#" class="movie-item" data-target="section-obra1">
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
                            alt="#">
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
                            alt="#">
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
                        <img src="https://img.olx.com.br/images/19/199402202691144.jpg" alt="#">
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
                        <img src="https://www.europanet.com.br/image_gen/resizeimg.php?cod_produto=130008&h=420" alt="">
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
                            alt="#">
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
                            <h1 class="text-color">Quer publicar no The Glark?</h1>
                        </div>
                        <div class="Mtexto">
                            <p class="text-color">Preencha nosso formulário de submissão e envie sua obra. Nossa equipe
                                irá revisar seu trabalho com atenção e fornecer feedback em até 7 dias úteis. Estamos
                                ansiosos para conhecer suas histórias e ilustrações!</p>
                        </div>
                        <div class="Mroxo">
                            <h2 class="Mroxo-text">Faça parte do nosso catálogo!</h2>
                        </div>
                    </div>
                </div><!-- FINAL DA PRIMEIRA PARTE -->
            </div>
            <!-- INICIO DO FORMULÁRIO -->
            <form action="valida_obras.php" method="post" enctype="multipart/form-data">
                <div class="containerF">
                    <div class="containerF2">
                        <h1 class="text-color">Mande sua obra!</h1>
                        <!-- Quadrado para informações de contato -->
                        <div class="info-box">
                            <h2 class="text-color">Informações para contato</h2>
                            <div class="input-group">
                                <input type="text" placeholder="Nome" name="nome">
                                <input type="text" placeholder="Sobrenome" name="sobrenome">
                            </div>
                            <div class="input-group">
                                <input type="email" placeholder="Email" name="email">
                                <input type="tel" placeholder="Telefone" name="telefone">
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" id="politica" name="checkbox_status">
                                <label for="politica" class="text-color">Estou ciente e concordo com a Politica de
                                    Privacidade da plataforma The Glark.</label>
                            </div>
                        </div>
                        <!-- Quadrado para informações da obra -->
                        <div class="info-box">
                            <h2 class="text-color">Informações da obra</h2>
                            <input type="text" placeholder="Título da obra" name="nm_obra">
                            <textarea placeholder="Sinopse" name="ds_sinopse"></textarea>
                            <!-- Select status -->
                            <select name="nmstatus" id="idstatus">
                                <option value="status" disabled selected>Status</option>
                                <option value="andamento">Em andamento</option>
                                <option value="finalizado">Finalizado</option>
                            </select>
                            <!-- Checkboxs generos -->
                            <div class="gene">
                                <p id="lbGene" class="text-color">Gêneros</p>
                                <div class="generos-group">
                                    <input type="checkbox" name="obras[]" id="idacao" value="acao">
                                    <label for="acao" class="text-color">Ação</label>
                                    <input type="checkbox" name="obras[]" id="idaven" value="aventura">
                                    <label for="aventura" class="text-color">Aventura</label>
                                    <input type="checkbox" name="obras[]" id="idcome" value="comedia">
                                    <label for="comedia" class="text-color">Comédia</label>
                                    <input type="checkbox" name="obras[]" id="iddram" value="drama">
                                    <label for="drama" class="text-color">Drama</label>
                                    <input type="checkbox" name="obras[]" id="idesco" value="escolar">
                                    <label for="escolar" class="text-color">Escolar</label>
                                    <input type="checkbox" name="obras[]" id="idespo" value="esporte">
                                    <label for="esporte" class="text-color">Esporte</label>
                                    <input type="checkbox" name="obras[]" id="idfant" value="fantasia">
                                    <label for="fantasia" class="text-color">Fantasia</label>
                                    <input type="checkbox" name="obras[]" id="idficc" value="ficcient">
                                    <label for="ficacao-cientifica" class="text-color">Ficção Científica</label>
                                    <input type="checkbox" name="obras[]" id="idhist" value="historia">
                                    <label for="historico" class="text-color">Histórico</label><br>
                                    <input type="checkbox" name="obras[]" id="idisek" value="isekai">
                                    <label for="isekai" class="text-color">Isekai</label>
                                    <input type="checkbox" name="obras[]" id="idlgbt" value="lgbt">
                                    <label for="lgbt" class="text-color">LGBT</label>
                                    <input type="checkbox" name="obras[]" id="idmist" value="misterio">
                                    <label for="misterio" class="text-color">Mistério</label>
                                    <input type="checkbox" name="obras[]" id="idroma" value="romance">
                                    <label for="romance" class="text-color">Romance</label>
                                    <input type="checkbox" name="obras[]" id="idslic" value="slice">
                                    <label for="slice-of-life" class="text-color">Slice of Life</label>
                                    <input type="checkbox" name="obras[]" id="idsobr" value="sobrenat">
                                    <label for="sobrenatural" class="text-color">Sobrenatural</label>
                                    <input type="checkbox" name="obras[]" id="idterr" value="terror">
                                    <label for="terror" class="text-color">Terror</label>
                                </div>
                            </div>
                            <div class="capa">
                                <p class="text-color" id="lbCapa">Imagem de Capa:</p>
                                <div class="capa-upload">
                                    <!-- Input para o upload da capa -->
                                    <input type="file" id="imageInput" accept="image/*" onchange="loadImage(event)" name="capa">
                                    <!-- <div class="image-rect" id="imageRect"></div>
                                    <div class="image-square" id="imageSquare"></div> -->
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" name="checkbox_image" id="idconc">
                                    <label for="idconc" class="text-color">Afirmo que a imagem de capa selecionada
                                        é autoral.</label>
                                </div>
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" name="checkbox_autoral" id="idafir">
                                <label for="idafir" class="text-color">Afirmo que a obra em questão é
                                    autoral.</label>
                            </div>
                        </div>
                        <!-- Botão de envio no final do formulário -->
                        <div class="btn-enviar">
                            <button type="submit" class="btn price__btn">Enviar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <!-- # FIM DA PUBLICAÇÃO DE OBRAS -->


    <!-- # PLANOS MENSAIS -->
    <main id="section-planos-mensais" class="home-section content-section">
        <div class="princial-plans-container">
            <div class="plans-container">
            <!-- Plano Gratuito -->
            <div class="plan-card">
              <h2 class="text-color">Plano Gratuito</h2>
              <p class="plan-price text-color">R$00.00</p>
              <p class="plan-description text-color">Experimente nosso sistema gratuitamente!</p>
              <ul class="benefits">
                <li class="text-color"><i>✔</i> Catálogo limitado</li>
                <li class="text-color"><i>✔</i> Acesso em 48 horas</li>
                <li class="text-color"><i>✔</i> Máximo de 1 tela</li>
                <li class="inactive text-color"><i>❌</i> Sem Propagandas</li>
                <li class="inactive text-color"><i>❌</i> Sorteios Especiais</li>
              </ul>
              <a href="#" class="subscribe-button text-color">Assine agora!</a>
            </div>
        
            <!-- Plano Simples -->
            <div class="plan-card">
              <h2>Plano Simples</h2>
              <p class="plan-price text-color">R$17.99</p>
              <p class="plan-description text-color">Ideal para leitores tranquilos desfrutarem de nossas funções básicas!</p>
              <ul class="benefits">
                <li class="text-color"><i>✔</i> Catálogo ilimitado</li>
                <li class="text-color"><i>✔</i> Acesso em 24 horas</li>
                <li class="text-color"><i>✔</i> Máximo de 2 telas</li>
                <li class="text-color"><i>✔</i> Sem Propagandas</li>
                <li class="inactive text-color"><i>❌</i> Sorteios Especiais</li>
              </ul>
              <a href="#" class="subscribe-button text-color">Assine agora!</a>
            </div>
        
            <!-- Plano Premium -->
            <div class="plan-card">
              <h2 class="text-color">Plano Premium</h2>
              <p class="plan-price text-color">R$34.99</p>
              <p class="plan-description text-color">Para que os leitores engajados aproveitem o máximo da sua aventura!</p>
              <ul class="benefits">
                <li class="text-color"><i>✔</i> Catálogo ilimitado</li>
                <li class="text-color"><i>✔</i> Acesso antecipado</li>
                <li class="text-color"><i>✔</i> Máximo de 4 telas</li>
                <li class="text-color"><i>✔</i> Sem Propagandas</li>
                <li class="text-color"><i>✔</i> Sorteios Especiais</li>
              </ul>
              <a href="#" class="subscribe-button text-color">Assine agora!</a>
            </div>
          </div>
        </div>
    </main>
    <!-- # FIM PLANOS MENSAIS -->

    <!-- # PAGAMENTO CARTÃO -->
    <main id="section-cartao" class="home-section content-section">
        <a data-target="section-planos-mensais"><i class='bx bxs-left-arrow-circle'></i></a>

        <div class="container">

            <div class="card-container">

                <div class="front">
                    <div class="image">
                        <img src="../assets/images/chip.png" alt="#">
                        <img src="../assets/images/visa.png" alt="#">
                    </div>
                    <div class="card-number-box">################</div>
                    <div class="flexbox">
                        <div class="box">
                            <span>titular do cartão</span>
                            <div class="card-holder-name">Nome Completo</div>
                        </div>
                        <div class="box">
                            <span>Validade</span>
                            <div class="expiration">
                                <span class="exp-month">mês</span>
                                <span class="exp-year">ano</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="back">
                    <div class="stripe"></div>
                    <div class="box">
                        <span>cvv</span>
                        <div class="cvv-box"></div>
                        <img src="image/visa.png" alt="">
                    </div>
                </div>

            </div>

            <form action="">
                <div class="inputBox">
                    <span>Número do Cartão</span>
                    <input type="number" id="number" data-maxlength="16" class="card-number-input">
                </div>
                <div class="inputBox">
                    <span>titular do cartão</span>
                    <input type="text" maxlength="65" class="card-holder-input">
                </div>
                <div class="flexbox">
                    <div class="inputBox">
                        <span>Mês</span>
                        <select name="" id="" class="month-input">
                            <option value="month" selected disabled>mês</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    <div class="inputBox">
                        <span>Ano</span>
                        <select name="" id="" class="year-input">
                            <option value="year" selected disabled>ano</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                            <option value="2028">2031</option>
                            <option value="2029">2032</option>
                            <option value="2030">2033</option>
                        </select>
                    </div>
                    <div class="inputBox">
                        <span>cvv</span>
                        <input type="number" id="number" data-maxlength="4" class="cvv-input">
                    </div>
                </div>
                <input type="submit" value="Cadastrar" class="submit-btn">
            </form>

        </div>

        <script>
            // SCRIPT CARTAO DE CREDITO

            // Seleciona todos os inputs com o atributo 'data-maxlength'
            const inputs = document.querySelectorAll('input[type="number"][data-maxlength]');

            inputs.forEach(input => {
                input.addEventListener("input", () => {
                    const maxLength = parseInt(input.getAttribute("data-maxlength"), 10); // Obtém o valor do atributo
                    if (input.value.length > maxLength) {
                        input.value = input.value.slice(0, maxLength); // Limita os caracteres
                    }
                });
            });

            document.querySelector('.card-number-input').oninput = () => {
                document.querySelector('.card-number-box').innerText = document.querySelector('.card-number-input').value;
            }

            document.querySelector('.card-holder-input').oninput = () => {
                document.querySelector('.card-holder-name').innerText = document.querySelector('.card-holder-input').value;
            }

            document.querySelector('.month-input').oninput = () => {
                document.querySelector('.exp-month').innerText = document.querySelector('.month-input').value;
            }

            document.querySelector('.year-input').oninput = () => {
                document.querySelector('.exp-year').innerText = document.querySelector('.year-input').value;
            }

            document.querySelector('.cvv-input').onmouseenter = () => {
                document.querySelector('.front').style.transform = 'perspective(1000px) rotateY(-180deg)';
                document.querySelector('.back').style.transform = 'perspective(1000px) rotateY(0deg)';
            }

            document.querySelector('.cvv-input').onmouseleave = () => {
                document.querySelector('.front').style.transform = 'perspective(1000px) rotateY(0deg)';
                document.querySelector('.back').style.transform = 'perspective(1000px) rotateY(180deg)';
            }

            document.querySelector('.cvv-input').oninput = () => {
                document.querySelector('.cvv-box').innerText = document.querySelector('.cvv-input').value;
            }

            // FIM SCRIPT CARTAO DE CREDTOI
        </script>
    </main>
    <!-- # FIM PAGAMENTO CARTÃO -->

    <!-- # SUPORTE -->
    <main id="section-suporte" class="home-section content-section">
        <div class="principal-support-container">
        <div class="faq-section">
        <h1 class="text-color tittle-support">Perguntas Frequentes</h1>
        <div class="faq-item">
            <div class="faq-question text-color">O que é o nosso serviço?</div>
            <div class="faq-answer text-color">
            Texto texto texto
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question text-color">Quanto custa?</div>
            <div class="faq-answer text-color">
            Texto texto texto
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question text-color">Onde posso acessar?</div>
            <div class="faq-answer text-color">
                Texto texto texto
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question text-color">?</div>
            <div class="faq-answer text-color">
                Texto texto texto
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question text-color">?</div>
            <div class="faq-answer text-color">
                Texto texto texto
            </div>
        </div>
    </div>

    <div class="contact-section">
        <h2 class="text-color tittle-support">Precisa de ajuda? Nos contate por:</h2>
        <div class="contact-item text-color">Email: theglark@gmail.com</div>
        <div class="contact-item text-color">Telefone: +55 13 94565-4235</div>
    </div>

    <footer class="social-footer">
        <h3 class="text-color tittle-support">Siga-nos nas redes sociais:</h3>
        <div class="social-icons">
            <a href="https://www.facebook.com" target="_blank" aria-label="Facebook">
                <i class="fab fa-facebook"></i>
            </a>
            <a href="https://www.instagram.com" target="_blank" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.linkedin.com" target="_blank" aria-label="LinkedIn">
                <i class="fab fa-linkedin"></i>
            </a>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.faq-question').forEach(item => {
            item.addEventListener('click', () => {
                const parent = item.parentElement;
                parent.classList.toggle('active');
            });
        });
    </script>
        </div>
    </main>
    <!-- # FIM SUPORTE -->

    <!-- # CONFIGURAÇÕES -->
    <main id="section-config" class="home-section content-section">
        <img src="https://www.protecaomaxima.com.br/imgs/em_desenvolvimento.jpg" alt="" class="manutenção">
    </main>
    <!-- # FIM CONFIGURAÇÕES -->


    <!-- # PERFIR GIBI PÚBLICO -->
    <main id="section-obra1" class="home-section content-section">
        <a data-target="section-inicio"><i class='bx bxs-left-arrow-circle'></i></a>
        <div class="container">
            <img src="../capitulo2/o-menino-nemo-na-terra-dos-sonhos-1_page-0001.jpg" alt="Capa do Mangá"
                class="manga-cover">
            <div class="manga-info">
                <h2 class="text-color">O Menino Nemo</h2>

                <div class="info-item">
                    <p class="info-label text-color">Gêneros:</p>
                    <p class="info-value text-color">Ação, Aventura, Romance</p>
                </div>
                <div class="info-item">
                    <p class="info-label text-color">Tipo:</p>
                    <p class="info-value text-color">Colorido</p>
                </div>
                <div class="info-item">
                    <p class="info-label text-color">Ano:</p>
                    <p class="info-value text-color">1995</p>
                </div>
                <div class="info-item">
                    <p class="info-label text-color">Status:</p>
                    <p class="info-value text-color">Encerrado</p>
                </div>
                <div class="info-item">
                    <p class="info-label text-color">Lançamento:</p>
                    <p class="info-value text-color">08 de Outubro de 1995</p>
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
                <img src="../capitulo2/o-menino-nemo-na-terra-dos-sonhos-1_page-0001.jpg" alt="Capa do Capítulo"
                    class="chapter-cover">
                <div>
                    <span class="chapter-number">Capítulo 01</span>
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
    <!-- # FIM PERFIL GIBI PÚBLICO  -->














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