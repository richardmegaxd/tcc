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
    <div class="sidebar show-sidebar fundo-color" id="sidebar">
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

                    <a href="#" class="sidebar__link active-link"  >
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

                <img onclick="location.href='perfil.php';" class="sidebar__perfil" src="<?php echo $resultado['ds_foto_perfil']; ?>" alt="Foto de Perfil" />
                <?php
                echo "<p class='sidebar__email'> $nome <br> $apelido</p>"
                ?>

                <a href="perfil.php" >
                    <i class='bx bxs-chevron-right-circle text-color'></i>
                </a>
            </div>
        </nav>
    </div>

    <!--=============== MAIN ===============-->


    <!-- # PUBLICAÇÃO DE OBRAS -->
    <main id="section-publicacao-obras" class=" main-pd home-section content-section active">
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