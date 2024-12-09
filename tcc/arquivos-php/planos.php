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

                    <a href="biblioteca.php" class="sidebar__link">
                        <i class='bx bxs-star text-color'></i>
                        <span class="sidebar__link-name text-color">Biblioteca</span>
                        <span class="sidebar__link-floating text-color">Biblioteca</span>
                    </a>

                    <a href="publique.php" class="sidebar__link">
                        <i class='bx bx-book-reader text-color'></i>
                        <span class="sidebar__link-name text-color">Publique Obras</span>
                        <span class="sidebar__link-floating text-color">Publique Obras</span>
                    </a>

                    <a href="#" class="sidebar__link active-link">
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

                    <a href="suporte.php" class="sidebar__link">
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

                <a href="perfil.php">
                    <i class='bx bxs-chevron-right-circle text-color'></i>
                </a>
            </div>
        </nav>
    </div>

    <!--=============== MAIN ===============-->


    <!-- # PLANOS MENSAIS -->
    <main id="section-planos-mensais" class=" main-pd home-section content-section active">
        <section class="section__container price__container">
            <h2 class="section__header text-color">PLANOS DE ASSINATURA</h2>
            <p class="section__subheader text-color">
                Assine nossos planos mensais e tenha acesso ilimitado a uma coleção de quadrinhos autênticos e exclusivos!
            </p>
            <div class="price__grid ">
                <div class="price__card troca-cor">
                    <div class="price__card__content">
                        <h4>Básico</h4>
                        <h3>R$17.99</h3>
                        <p>
                            <i class="ri-checkbox-circle-line"></i>
                            Catálogo Ilimitado
                        </p>
                        <p>
                            <i class="ri-checkbox-circle-line"></i>
                            Acesso em 24 horas
                        </p>
                        <p>
                            <i class="ri-checkbox-circle-line"></i>
                            Máximo 2 telas
                        </p>
                        <p>
                            <i class="ri-checkbox-circle-line"></i>
                            Sem Propagandas
                        </p>


                    </div>
                    <a data-target="section-cartao" class="btn price__btn">Iniciar Assinatura!</a>
                </div>
                <div class="price__card troca-cor">
                    <div class="price__card__content">
                        <h4>Premium</h4>
                        <h3>R$ 34.99</h3>
                        <p>
                            <i class="ri-checkbox-circle-line"></i>
                            Catálogo Ilimitado
                        </p>
                        <p>
                            <i class="ri-checkbox-circle-line"></i>
                            Acesso Antecipado
                        </p>
                        <p>
                            <i class="ri-checkbox-circle-line"></i>
                            Máximo 4 tela
                        </p>
                        <p>
                            <i class="ri-checkbox-circle-line"></i>
                            Sem Propagandas
                        </p>
                        <p>
                            <i class="ri-checkbox-circle-line"></i>
                            Sorteios Especiais
                        </p>
                    </div>
                    <a data-target="section-cartao" class="btn price__btn">Iniciar Assinatura!</a>
                </div>
            </div>
        </section>

    </main>
    <!-- # FIM PLANOS MENSAIS -->

    <!-- # PAGAMENTO CARTÃO -->
    <main id="section-cartao" class="home-section content-section">
        <a data-target="section-planos-mensais"><i class='bx bxs-left-arrow-circle'></i></a>
        <div class="container">

            <div class="card-container">

                <div class="front">
                    <div class="image">
                        <img src="https://cdn-icons-png.flaticon.com/512/680/680284.png" alt="#">
                        <img src="https://logosmarcas.net/wp-content/uploads/2020/09/Mastercard-Logo.png" alt="#">
                    </div>
                    <div class="card-number-box">#### #### #### ####</div>
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
                        <img src="https://logosmarcas.net/wp-content/uploads/2020/09/Mastercard-Logo.png" alt="">
                    </div>
                </div>

            </div>

            <form action="">
                <div class="inputBox">
                    <span>Número do Cartão</span>
                    <input type="text" id="number" data-maxlength="19" class="card-number-input">
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
                            <!-- rest of the months -->
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
                            <option value="2031">2031</option>
                            <option value="2032">2032</option>
                            <option value="2033">2033</option>
                            <!-- rest of the years -->
                        </select>
                    </div>
                    <div class="inputBox">
                        <span>cvv</span>
                        <input type="text" id="cvv" data-maxlength="3" class="cvv-input">
                    </div>
                </div>
                <input type="submit" value="Cadastrar" class="submit-btn">
            </form>

        </div>

        <script>
            // Atualização do formato do número do cartão
            const inputCartao = document.querySelector('.card-number-input');
            inputCartao.addEventListener('input', (e) => {
                let valor = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/g, '').slice(0, 16);
                e.target.value = valor.match(/.{1,4}/g)?.join(' ') || '';
                document.querySelector('.card-number-box').innerText = e.target.value || '#### #### #### ####';
            });

            // Limitar CVV a 3 dígitos
            const inputCvv = document.querySelector('.cvv-input');
            inputCvv.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 3);
                document.querySelector('.cvv-box').innerText = e.target.value || '';
            });

            // Limitar nome do titular
            const inputNome = document.querySelector('.card-holder-input');
            inputNome.addEventListener('input', (e) => {
                const maxLength = 40; // Limite máximo de caracteres
                let nome = e.target.value.slice(0, maxLength);
                e.target.value = nome;

                // Adicionar quebra de linha para nomes longos
                const nomeComQuebra = nome.replace(/(.{24})/g, '$1\n');
                document.querySelector('.card-holder-name').innerText = nomeComQuebra;
            });

            // Atualizar validade
            document.querySelector('.month-input').oninput = () => {
                document.querySelector('.exp-month').innerText = document.querySelector('.month-input').value;
            };

            document.querySelector('.year-input').oninput = () => {
                document.querySelector('.exp-year').innerText = document.querySelector('.year-input').value;
            };

            // Efeitos para o CVV
            document.querySelector('.cvv-input').onmouseenter = () => {
                document.querySelector('.front').style.transform = 'perspective(1000px) rotateY(-180deg)';
                document.querySelector('.back').style.transform = 'perspective(1000px) rotateY(0deg)';
            };

            document.querySelector('.cvv-input').onmouseleave = () => {
                document.querySelector('.front').style.transform = 'perspective(1000px) rotateY(0deg)';
                document.querySelector('.back').style.transform = 'perspective(1000px) rotateY(180deg)';
            };
        </script>

    </main>
    <!-- # FIM PAGAMENTO CARTÃO -->


    <!-- # PERFIR GIBI PÚBLICO -->
    <main id="section-obra1" class="home-section content-section">

        <div class="star-rating">
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
    <!--  FIM SCRIPT -->



</body>

</html>