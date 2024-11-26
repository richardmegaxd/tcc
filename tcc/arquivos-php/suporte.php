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

                    <a href="#" class="sidebar__link active-link" >
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

                <a href="perfil.php" >
                    <i class='bx bxs-chevron-right-circle text-color'></i>
                </a>
            </div>
        </nav>
    </div>

    <!--=============== MAIN ===============-->

   <!-- # SUPORTE -->
   <main id="section-suporte" class="home-section content-section active">
        <div class="principal-support-container">
        <div class="faq-section">
        <h1 class="text-color tittle-support">Perguntas Frequentes</h1>
        <div class="faq-item">
            <div class="faq-question">Como funciona a assinatura mensal?</div>
            <div class="faq-answer">
            Com a assinatura mensal, você terá acesso ilimitado ao nosso catálogo de quadrinhos, incluindo obras brasileiras e estrangeiras, com novos títulos adicionados regularmente. A assinatura é renovada automaticamente todos os meses, e você pode cancelá-la a qualquer momento diretamente nas configurações da sua conta. Assim que você cancela, mantém acesso até o final do período pago.  
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Quais métodos de pagamento são aceitos?</div>
            <div class="faq-answer">
            Aceitamos os seguintes métodos de pagamento: - Cartões de crédito e débito das principais bandeiras; - Pix (pagamento único para renovação automática); - PayPal (em breve).  
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Posso acessar os quadrinhos offline?</div>
            <div class="faq-answer">
            Atualmente, não é possível acessar os quadrinhos offline, pois nosso sistema é baseado na web e requer uma conexão com a internet para carregar o catálogo. Esse recurso está em nossos planos futuros para um aplicativo dedicado, onde será possível fazer downloads para leitura offline. Enquanto isso, você pode acessar todos os títulos a qualquer momento com uma conexão estável.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Como envio minha obra para avaliação?</div>
            <div class="faq-answer">
            Estamos sempre em busca de novos talentos! Para enviar sua obra, acesse a aba “Publicar sua Obra”, preencha o formulário com as informações requeridas e confirme o envio. Após, nossa equipe avaliará a obra em até 15 dias úteis. Você será notificado por e-mail sobre o resultado. Importante: Somente serão aceitos materiais que respeitem nossos critérios editoriais e de qualidade.  
            </div>
        </div>
        <div class="faq-item ">
            <div class="faq-question">O que fazer se encontrar problemas técnicos no site?</div>
            <div class="faq-answer">
            Caso enfrente problemas como erros ao carregar quadrinhos, dificuldade de acesso à conta ou falhas no pagamento, verifique primeiro sua conexão com a internet, limpe o cache do navegador ou atualize o aplicativo para a última versão. Se o problema persistir, envie uma mensagem detalhada para nosso suporte através do e-mail ou telefone abaixo. Nossa equipe de suporte técnico responderá em até 48 horas úteis.
            </div>
        </div>
    </div>

    <div class="contact-section">
        <h2 class="tittle-support">Precisa de ajuda? Nos contate por:</h2>
        <div class="contact-item">Email: theglark@gmail.com</div>
        <div class="contact-item">Telefone: +55 13 94565-4235</div>
    
        
        <h3 class="tittle-support">Siga-nos nas redes sociais:</h3>
        <div class="social-icons">

            <a href="#" class="social-link" style="background-color: #3b5998">
            <ion-icon name="logo-facebook"></ion-icon>

            <a href="#" class="social-link"
                  style="background-image: radial-gradient(circle at -20.71% 50%, #afde72 0, #bbd966 5.56%, #c6d259 11.11%, #cfc94e 16.67%, #d8c042 22.22%, #dfb537 27.78%, #e6a92e 33.33%, #eb9c26 38.89%, #f08c21 44.44%, #f37b1f 50%, #f56822 55.56%, #f85329 61.11%, #f93c32 66.67%, #fa193d 72.22%, #f90049 77.78%, #f80057 83.33%, #f40066 88.89%, #ef0076 94.44%, #e70087 100%);">
                  <ion-icon name="logo-instagram"></ion-icon>
            </a>

            <a href="#" class="social-link" style="background-color: #1565c0">
                  <ion-icon name="logo-linkedin"></ion-icon>
            </a>
        </div>
        </div>

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
    <!-- # FIM PERFIR GIBI PÚBLICO  -->


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
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <!--  FIM SCRIPT -->



</body>

</html>