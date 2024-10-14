<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
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
// URL base da API
$base_url = "https://api.mangadex.org";

// Função para buscar um mangá sem necessidade de autenticação
function buscar_manga($title) {
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

    <!-- # INÍCIO -->
    <main id="section-inicio" class="home-section content-section active">

        <section id="main">


            <h1 class="para-voce-heading"><i class='bx bxs-castle'></i> Destaques </h1>

            <ul id="autoWidth" class="cs-hidden">
                <li class="item-a">
                    <div class="para-voce-box">
                        <img src="https://www.europanet.com.br/image_gen/resizeimg.php?cod_produto=130008&h=420" />
                    </div>
                </li>

                <li class="item-b">
                    <div class="para-voce-box">
                        <img src="https://spawnbrasil.com.br/wp-content/uploads/2020/02/spawn-112-capa-editora-abril-por-guia-dos-quadrinhos.jpg" />
                    </div>
                </li>

                <li class="item-c">
                    <div class="para-voce-box">
                        <img src="https://f.i.uol.com.br/fotografia/2021/10/05/1633460103615c9f879caa1_1633460103_3x2_md.jpg" />
                    </div>
                </li>

                <li class="item-d">
                    <div class="para-voce-box">
                        <img src="https://img.olx.com.br/images/19/199402202691144.jpg" />
                    </div>
                </li>

                <li class="item-e">
                    <div class="para-voce-box">
                        <img src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/anime-manga-style-album-cover-1.0-design-template-0fc6d256e9ff17603475dfb129b132f0_screen.jpg?ts=1664026643" />
                    </div>
                </li>
            </ul>

        </section>

        <section id="continue-lendo">
            <h2 class="continue-lendo-heading"><i class='bx bxs-book-alt'></i> CONTINUE LENDO</h2>
            <ul id="autoWidth2" class="cs-hidden">
                <li class="item-a">
                    <div class="continue-lendo-box">
                        <picture class="continue-lendo-b-img">
                            <img src="https://static.hq-now.com/hqs/hqs/uploads/picture/image/897179/Oedipe_TheWitch_000a.jpg">
                        </picture>

                    </div>
                </li>

                <li class="item-b">
                    <div class="continue-lendo-box">
                        <picture class="continue-lendo-b-img">
                            <a href="../manga-page/index.html" data-target="section-obra">
                                <img src="https://www.nautiljon.com/images/manga/00/03/fukigen_na_mononokean_5230.webp">
                            </a>
                        </picture>

                    </div>
                </li>

                <li class="item-c">
                    <div class="continue-lendo-box">
                        <picture class="continue-lendo-b-img">
                            <img src="https://hyperioncomics.com.br/wp-content/uploads/2022/10/0-300x459.jpg">
                        </picture>

                    </div>
                </li>

                <li class="item-d">
                    <div class="continue-lendo-box">
                        <picture class="continue-lendo-b-img">
                            <img src="https://imgv2-1-f.scribdassets.com/img/word_document/605138375/original/99852f7170/1719936830?v=1">
                        </picture>

                    </div>
                </li>

                <li class="item-e">
                    <div class="continue-lendo-box">
                        <picture class="continue-lendo-b-img">
                            <img src="https://i.pinimg.com/474x/08/a7/18/08a7185ecfd01971106503f9d4be3961.jpg">
                        </picture>

                    </div>
                </li>

                <li class="item-f">
                    <div class="continue-lendo-box">
                        <picture class="continue-lendo-b-img">
                            <img src="https://cdn.kobo.com/book-images/9b82874e-df48-4981-a011-e4c3156ba65c/353/569/90/False/submechanophobia-an-afk-book-five-nights-at-freddy-s-tales-from-the-pizzaplex-4.jpg">
                        </picture>

                    </div>
                </li>

                <li class="item-g">
                    <div class="continue-lendo-box">
                        <picture class="continue-lendo-b-img">
                            <img src="https://m.media-amazon.com/images/I/91i217MtWbL._AC_UF1000,1000_QL80_.jpg">
                        </picture>

                    </div>
                </li>

                <li class="item-h">
                    <div class="continue-lendo-box">
                        <picture class="continue-lendo-b-img">
                            <img src="https://devir.com.br/diadoquadrinhogratis/assets/img/capas/conrad-digital2023_1.jpg">
                        </picture>
                    </div>
                </li>


            </ul>
        </section>

        <section>

            <div class="lancamentos-heading">
                <h2><i class='bx bxl-sketch'></i> LANÇAMENTOS</h2>
            </div>



            <div id="lancamentos-list">

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <div class="quality">NOVO</div>
                        <img src="https://zinebrasil.wordpress.com/wp-content/uploads/2015/04/capa-capitao-brasil1.jpg?w=584">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <div class="quality">NOVO</div>
                        <img src="https://devir.com.br/diadoquadrinhogratis/assets/img/capas/conrad-digital2023_3.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <div class="quality">NOVO</div>
                        <img src="https://devir.com.br/diadoquadrinhogratis/assets/img/capas/devir-digital2023_5.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <div class="quality">NOVO</div>
                        <img src="https://www.jbchost.com.br/editorajbc/wp-content/uploads/2023/11/9horas-master-edition-capa.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <div class="quality">NOVO</div>
                        <img src="https://editoradraco.com/wp-content/uploads/2023/03/Retratosbrutos-CC-capa-500x718.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <div class="quality">NOVO</div>
                        <img src="https://i.pinimg.com/236x/ce/44/04/ce44046067f7d3121f4d81fed5f9b146.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <div class="quality">NOVO</div>
                        <img src="https://acdn.mitiendanube.com/stores/141/982/products/contosorixas1-0b0a04e13e6fbb2bb915661866635852-480-0.jpg">
                    </div>
                </div>

                <div class="lancamentos-box">
                    <div class="lancamentos-img">
                        <div class="quality">NOVO</div>
                        <img src="https://www.newpop.com.br/wp-content/uploads/2020/03/NewPOP_Grimms01.jpg">
                    </div>
                </div>

            </div>

        </section>

    </main>
    <!-- # FIM INÍCIO  -->


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
    <!-- # FIM CONFIGURAÇÕES -->




    <!-- # PERFIL DO MANGÁ -->
    <main id="section-obra" class="home-section content-section">

        <div class="container">
            <img src="https://www.nautiljon.com/images/manga/00/03/fukigen_na_mononokean_5230.webp" alt="Capa do Mangá"
                class="manga-cover">
            <div class="manga-info">
                <h2>Fukigen</h2>

                <div class="info-item">
                    <p class="info-label">Gêneros:</p>
                    <p class="info-value">Ação, Aventura, Romance</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Tipo:</p>
                    <p class="info-value">Preto e Branco</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Ano:</p>
                    <p class="info-value">2024</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Status:</p>
                    <p class="info-value">Ativo</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Lançamento:</p>
                    <p class="info-value">08 de Outubro de 2015</p>
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
                <img width="60" height="60" src="https://img.icons8.com/color/48/comics-magazine.png" alt="comics-magazine" />
                <h2>CAPÍTULOS</h2>

            </div>
            <a class="chapter" data-target="section-leitura-manga">
                <img src="https://cm.blazefast.co/25/b6/25b661d784cc6ac96e1726c5e45f9666.jpg" alt="Capa do Capítulo"
                    class="chapter-cover">
                <div>
                    <span class="chapter-number">Capítulo 26</span>
                </div>

            </a>
            <a class="chapter" data-target="section-leitura-manga">
                <img src="https://i.ebayimg.com/images/g/lVUAAOSwfZxguF6M/s-l1200.jpg"
                    alt="Capa do Capítulo" class="chapter-cover">
                <div>
                    <span class="chapter-number">Capítulo 25</span>

                </div>
            </a>
            <a class="chapter" data-target="section-leitura-manga">
                <img src="https://i0.wp.com/www.otakupt.com/wp-content/uploads/2021/03/Fukigen-na-Mononokean-volume-17-cover.jpg?resize=696%2C990&ssl=1" alt="Capa do Capítulo"
                    class="chapter-cover">
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
                <label for="comment">Deixe seu comentário:</label>
                <textarea id="comment" name="comment" rows="4" placeholder="Digite seu comentário"></textarea>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </main>
    <!-- # FIM PERFIL MANGÁ -->


    <!-- # LEITURA DO MANGÁ -->
    <main id="section-leitura-manga" class="home-section content-section">
           
        <a data-target="section-obra"><i class='bx bxs-left-arrow-circle'></i></a>

        <div class="btn-troca">
            <button class="toggle-mode" onclick="toggleMode()"><i class='bx bxs-binoculars'></i></button>
        </div>

        <div id="viewer" class="container">

            <div id="image-container" class="mt-4">
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