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
                <a href="#">
                    <i class="bx bx-grid-alt"></i>
                    <span class="link_name">Início</span>
                </a>
                <span class="tooltip">Início</span>
            </li>
            <li>
                <a href="#">
                    <i class="bx bx-user"></i>
                    <span class="link_name">Perfil</span>
                </a>
                <span class="tooltip">Perfil</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-star'></i>
                    <span class="link_name">Favoritos</span>
                </a>
                <span class="tooltip">Favoritos</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-book-reader'></i>
                    <span class="link_name">Publique Obras</span>
                </a>
                <span class="tooltip">Publique Obras</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-money'></i>
                    <span class="link_name">Planos Mensais</span>
                </a>
                <span class="tooltip">Planos Mensais</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-help-circle'></i>
                    <span class="link_name">Suporte</span>
                </a>
                <span class="tooltip">Suporte</span>
            </li>
            <li>
                <a href="#">
                    <i class="bx bx-cog"></i>
                    <span class="link_name">Configurações</span>
                </a>
                <span class="tooltip">Configurações</span>
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


    <main class="home-section">


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
                        <picture  class="continue-lendo-b-img">
                            <a href="../manga-page/index.html">
                                <img src="https://www.nautiljon.com/images/manga/00/03/fukigen_na_mononokean_5230.webp">
                            </a>  
                        </picture>
                
                    </div>
                </li>

                <li class="item-c">
                    <div class="continue-lendo-box">
                        <picture  class="continue-lendo-b-img">
                            <img src="https://hyperioncomics.com.br/wp-content/uploads/2022/10/0-300x459.jpg">
                        </picture>
                        
                    </div>
                </li>

                <li class="item-d">
                    <div class="continue-lendo-box">
                        <picture  class="continue-lendo-b-img">
                            <img src="https://imgv2-1-f.scribdassets.com/img/word_document/605138375/original/99852f7170/1719936830?v=1">
                        </picture>
                       
                    </div>
                </li>

                <li class="item-e">
                    <div class="continue-lendo-box">
                        <picture  class="continue-lendo-b-img">
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

        <footer>

        </footer>
    </main>

    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="../home-assets/Js/JQuery3.3.1.js"></script>
    <script src="../home-assets/Js/lightslider.js"></script>
    <script src="../home-assets/Js/script.js"></script>
</body>

</html>