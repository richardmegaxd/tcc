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
                    <a href="home.php" class="sidebar__link ">
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

                    <a href="config.php" class="sidebar__link" >
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

            <div class="sidebar__account " data-target="section-perfil">
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

                <img class="sidebar__perfil " src="<?php echo $resultado['ds_foto_perfil']; ?>" alt="Foto de Perfil" />
                <?php
                echo "<p class='sidebar__email'> $nome <br> $apelido</p>"
                ?>

                <a href="#" >
                    <i class='bx bxs-chevron-right-circle text-color '></i>
                </a>
            </div>
        </nav>
    </div>

    <!--=============== MAIN ===============-->
    <main id="section-perfil" class="home-section content-section active">
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
                                <li><a class="oof" href="edit/edit.php">Editar Perfil</a></li>
                            <?php endif; ?>
                            <li><a class="oof2" href="delete/confirmadelete.php">Deletar Perfil</a></li>
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
                <div class="topo-perfil text-color">
                    <h2 class="tab active text-color" data-target="obras">Obras</h2>
                    <h2 class="tab text-color" data-target="recomendados">Recomendados</h2>
                    <!-- <h2 class="tab text-color" data-target="comentarios">Comentários</h2> -->
                </div>
                
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

                <div class="obras">
                    <h1>Nenhuma obra publicada</h1>
                </div>

        </section>

        <section id="recomendados" class="content">

        <div class="container-gp">
                <div class="movies-slide carousel-nav-center owl-carousel">
                    <!-- MOVIE ITEM -->
                    <a href="obra.php" class="movie-item">
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
    <!-- # FIM INÍCIO  -->
                <div class="star-rating">
                </div>

  
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