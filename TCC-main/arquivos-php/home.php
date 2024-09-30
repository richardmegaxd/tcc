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
                <i class="bx bx-search"></i>
                <input type="text">
                <span class="tooltip">Pesquisa</span>
            </li>
            <li>
                <a href="#" data-target="section-inicio">
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
                        <img src="https://f.i.uol.com.br/fotografia/2021/10/05/1633460103615c9f879caa1_1633460103_3x2_md.jpg" /></a>
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

    <!-- # PEERFIL USUÁRIO  -->
    <main id="section-perfil" class="home-section content-section">
        <img src="https://www.protecaomaxima.com.br/imgs/em_desenvolvimento.jpg" alt="" class="manutenção">
    </main>
    <!-- # FIM PEERFIL USUÁRIO  -->

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
            <button class="toggle-mode" onclick="toggleMode()">Trocar Modo de Leitura</button>
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
    </main>
    <!-- # FIM LEITURA DO MANGÁ -->












    <footer class="footer" id="footer">

    <div class="footer-top">
      <div class="container">

        <div class="footer-manga">

          <a href="#" class="logo">The GLARK</a>

          <p class="footer-text">
            Dê vida às suas histórias favoritas de forma legal e segura.
          </p>

          <ul class="contact-list">

            <li class="contact-item">
              <div class="contact-icon">
                <ion-icon name="location"></ion-icon>
              </div>

              <address class="item-text">
                Endereço : São Vicente - SP
              </address>
            </li>

            <li class="contact-item">
              <div class="contact-icon">
                <ion-icon name="headset"></ion-icon>
              </div>

              <a href="tel:+241245654235" class="item-text">Telefone : +55 (13)94565-4235</a>
            </li>

            <li class="contact-item">
              <div class="contact-icon">
                <ion-icon name="mail-open"></ion-icon>
              </div>

              <a href="mailto:info@exemple.com" class="item-text">Email : theglark@gmail.com </a>
            </li>

          </ul>

        </div>

        <ul class="footer-list">

          <li>
            <p class="footer-list-title">Navegação</p>
          </li>

          <li>
            <a href="#" class="footer-link">Obras Populares</a>
          </li>

          <li>
            <a href="#" class="footer-link">Lançamentos</a>
          </li>

          <li>
            <a href="#" class="footer-link">Notícias</a>
          </li>


        </ul>

        <ul class="footer-list">

          <li>
            <p class="footer-list-title">PRECISA DE AJUDA?</p>
          </li>

          <li>
            <a href="#" class="footer-link">Termos e Condições</a>
          </li>

          <li>
            <a href="#" class="footer-link">Política de Privacidade</a>
          </li>

          <li>
            <a href="#" class="footer-link">Política de reembolso</a>
          </li>

        </ul>

        <div class="footer-wrapper">

          <div class="social-wrapper">

            <p class="footer-list-title">SIGA-NOS</p>

            <ul class="social-list">

              <li>
                <a href="#" class="social-link" style="background-color: #3b5998">
                  <ion-icon name="logo-facebook"></ion-icon>
                </a>
              </li>

              <li>
                <a href="#" class="social-link"
                  style="background-image: radial-gradient(circle at -20.71% 50%, #afde72 0, #bbd966 5.56%, #c6d259 11.11%, #cfc94e 16.67%, #d8c042 22.22%, #dfb537 27.78%, #e6a92e 33.33%, #eb9c26 38.89%, #f08c21 44.44%, #f37b1f 50%, #f56822 55.56%, #f85329 61.11%, #f93c32 66.67%, #fa193d 72.22%, #f90049 77.78%, #f80057 83.33%, #f40066 88.89%, #ef0076 94.44%, #e70087 100%);">
                  <ion-icon name="logo-instagram"></ion-icon>
                </a>
              </li>

              <li>
                <a href="#" class="social-link" style="background-color: #1565c0">
                  <ion-icon name="logo-linkedin"></ion-icon>
                </a>
              </li>

            </ul>

          </div>

          <div class="footer-duvida">

            <p class="footer-list-title">Quer saber quando o próximo capítulo vai ser lançado?</p>

            <form action="" class="footer-duvida">
              <input type="email" name="email_address" aria-label="email" placeholder="Digite seu e-mail" required
                class="email-field">

              <button type="submit" class="footer-btn" aria-label="submit">
                <ion-icon name="rocket"></ion-icon>
              </button>
            </form>

          </div>

        </div>

      </div>
    </div>


    <!-- 
    - #BACK TO TOP
  -->

    <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
      <ion-icon name="caret-up"></ion-icon>
    </a>





    
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="../home-assets/Js/JQuery3.3.1.js"></script>
    <script src="../home-assets/Js/lightslider.js"></script>
    <script src="../home-assets/Js/script.js"></script>
</body>

</html>