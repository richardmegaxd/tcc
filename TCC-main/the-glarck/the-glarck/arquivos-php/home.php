<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="stylesheet" href="../home-assets/css/style.css">
    <link rel="stylesheet" href="../home-assets/css/lightslider.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != true) {
        header("Location: login.php");
        exit;
    }
    ?>

    
    <nav id="sidebar">
        <div id="sidebar_content">
            <div id="user">
                <img src="../assets/images/EU.jpg" id="user_avatar" alt="Avatar">

                <p id="user_infos">
                    <span class="item-description">
                        Marcelo Azevedo
                    </span>
                    <span class="item-description">
                        O MIOR
                    </span>
                </p>
            </div>

            <ul id="side_items">

                <li class="side-item">
                    <a href="#">
                        <ion-icon name="contact"></ion-icon>
                        <span class="item-description">
                            Perfil
                        </span>
                    </a>
                </li>

                <li class="side-item">
                    <a href="#">
                        <ion-icon name="star"></ion-icon>
                        <span class="item-description">
                            Favoritos
                        </span>
                    </a>
                </li>

                
                <li class="side-item">
                    <a href="#">
                        <ion-icon name="bookmarks"></ion-icon>
                        <span class="item-description">
                            Publique Obras
                        </span>
                    </a>
                </li>
                
                <li class="side-item">
                    <a href="#">
                        <ion-icon name="help-circle"></ion-icon>
                        <span class="item-description">
                            Suporte
                        </span>
                    </a>
                </li>

                <li class="side-item">
                    <a href="#">
                        <ion-icon name="construct"></ion-icon>
                        <span class="item-description">
                            Configurações
                        </span>
                    </a>
                </li>
            </ul>

            <button id="open_btn">
                <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <div id="logout">
            <button id="logout_btn">
                <ion-icon name="log-in"></ion-icon>
                <span class="item-description">
                    Logout
                </span>
            </button>
        </div>
    </nav>


    

    <main>

    <div class="search">
            <img src="../assets/images/log5.png" alt="logo" width="100">
            
                <input type="text" placeholder="Pesquisar"/>
                <i class="fas fa-search"></i>
            
        </div>

    <section id="main">


        <h1 class="para-voce-heading">PARA VOCÊ</h1>

        <ul id="autoWidth" class="cs-hidden">
            <li class="item-a">
                <div class="para-voce-box">
                    <img src="https://images.cdn2.buscalibre.com/fit-in/360x360/1e/d8/1ed855c74716504217169a34db6131c3.jpg" />
                </div>
            </li>

            <li class="item-b">
                <div class="para-voce-box">
                    <img src="https://cdn.awsli.com.br/2500x2500/2517/2517513/produto/247524248c63509423b.jpg" />
                </div>
            </li>

            <li class="item-c">
                <div class="para-voce-box">
                    <img src="https://m.media-amazon.com/images/I/813qloZY95L._AC_UF1000,1000_QL80_.jpg" />
                </div>
            </li>

            <li class="item-d">
                <div class="para-voce-box">
                    <img src="https://www.suika.com.br/9685/manga-black-clover-volume-24.jpg" />
                </div>
            </li>

            <li class="item-e">
                <div class="para-voce-box">
                    <img src="https://pbs.twimg.com/media/E_PhZqrXMAE6kR6?format=jpg&name=4096x4096" />
                </div>
            </li>
        </ul>

    </section>

    <section id="continue-lendo">
        <h2 class="continue-lendo-heading">CONTINUE LENDO</h2>
        <ul id="autoWidth2" class="cs-hidden">
            <li class="item-a">
                <div class="continue-lendo-box">
                    <div class="continue-lendo-b-img">
                        <img src="https://trecobox.com.br/wp-content/uploads/2020/03/shingeki-no-kyojin-manga-capa-31.jpg">
                    </div>
                    <div class="continue-lendo-b-text">
                        <strong><img class="logo-titulo" src="https://upload.wikimedia.org/wikipedia/commons/e/ec/Shingeki%21_Kyojin_ch%C5%ABgakk%C5%8D_logo.png" alt=""></strong>
                    </div>
                </div>
            </li>

            <li class="item-b">
                <div class="continue-lendo-box">
                    <div class="continue-lendo-b-img">
                        <img src="https://www.analiseit.com/wp-content/uploads/2018/04/Capa-Manga-Yakusoku-no-Neverland-Volume-8-Revelada.jpg">
                    </div>
                    <div class="continue-lendo-b-text">
                        <strong><img class="logo-titulo" src="https://i.pinimg.com/736x/7a/75/6e/7a756e76be9ab8f95c06dfbf76a3529f.jpg" alt=""></strong>
                    </div>
                </div>
            </li>

            <li class="item-c">
                <div class="continue-lendo-box">
                    <div class="continue-lendo-b-img">
                        <img src="https://www.jbchost.com.br/editorajbc/wp-content/uploads/2017/04/The_Seven_Deadly_Sins_23_p.jpg">
                    </div>
                    <div class="continue-lendo-b-text">
                        <strong><img class="logo-titulo" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRfqKyn7f7e6GOkygmggo2fRlc3aDOFzApDoeGa2G_4-QuB60vZnSpQHETjeMgST5BkSxw&usqp=CAU" alt=""></strong>
                    </div>
                </div>
            </li>

            <li class="item-d">
                <div class="continue-lendo-box">
                    <div class="continue-lendo-b-img">
                        <img src="https://http2.mlstatic.com/D_NQ_NP_845437-MLB49667159016_042022-O.webp">
                    </div>
                    <div class="continue-lendo-b-text">
                        <strong><img class="logo-titulo" src="https://freepnglogo.com/images/all_img/1707838647demon-slayer-logo-transparent.png" alt=""></strong>
                    </div>
                </div>
            </li>

            <li class="item-e">
                <div class="continue-lendo-box">
                    <div class="continue-lendo-b-img">
                        <img src="https://m.media-amazon.com/images/I/51PxvL11XkL.jpg">
                    </div>
                    <div class="continue-lendo-b-text">
                        <strong><img class="logo-titulo" src="https://upload.wikimedia.org/wikipedia/commons/e/ed/Death_Note_Logo.png" alt=""></strong>
                    </div>
                </div>
            </li>

            <li class="item-f">
                <div class="continue-lendo-box">
                    <div class="continue-lendo-b-img">
                        <img src="https://www.jbchost.com.br/editorajbc/wp-content/uploads/2018/03/FairyTail-63-Capa_p.jpg">
                    </div>
                    <div class="continue-lendo-b-text">
                        <strong><img class="logo-titulo" src="https://logos-world.net/wp-content/uploads/2020/08/Fairy-Tail-Logo.png" alt=""></strong>
                    </div>
                </div>
            </li>

            <li class="item-g">
                <div class="continue-lendo-box">
                    <div class="continue-lendo-b-img">
                        <img src="https://m.media-amazon.com/images/I/71kF+AttRZL._AC_UF1000,1000_QL80_.jpg">
                    </div>
                    <div class="continue-lendo-b-text">
                        <strong><img class="logo-titulo" src="https://upload.wikimedia.org/wikipedia/commons/f/f1/Berserk_anime_logo.png" alt=""></strong>
                    </div>
                </div>
            </li>

            <li class="item-h">
                <div class="continue-lendo-box">
                    <div class="continue-lendo-b-img">
                        <img src="https://www.jbchost.com.br/editorajbc/wp-content/uploads/2006/07/capa_yugioh_01_g.jpg">
                    </div>
                    <div class="continue-lendo-b-text">
                        <strong><img class="logo-titulo" src="https://upload.wikimedia.org/wikipedia/commons/2/21/Yu-Gi-Oh%21.png" alt=""></strong>
                    </div>
                </div>
            </li>


        </ul>
    </section>

    <div class="lancamentos-heading">
        <h2>LANÇAMENTOS</h2>
    </div>

    <section id="lancamentos-list">


        <div class="lancamentos-box">
            <div class="lancamentos-img">
                <div class="quality">NOVO</div>
                <img src="https://animenew.com.br/wp-content/uploads/2024/01/GD9UweLbMAAjNKG-jpg.webp">
            </div>
        </div>

        <div class="lancamentos-box">
            <div class="lancamentos-img">
                <div class="quality">NOVO</div>
                <img src="https://i.pinimg.com/736x/a0/9b/2e/a09b2eca90d800a22c62d50fbeb8ea48.jpg">
            </div>
        </div>

        <div class="lancamentos-box">
            <div class="lancamentos-img">
                <div class="quality">NOVO</div>
                <img src="https://m.media-amazon.com/images/I/71vroUCZshL._AC_UF1000,1000_QL80_.jpg">
            </div>
        </div>

        <div class="lancamentos-box">
            <div class="lancamentos-img">
                <div class="quality">NOVO</div>
                <img src="https://cinema10.com.br/upload/series/series_2589_school_Easy-Resize.com.jpg?default=poster">
            </div>
        </div>

        <div class="lancamentos-box">
            <div class="lancamentos-img">
                <div class="quality">NOVO</div>
                <img src="https://i.pinimg.com/564x/c3/07/7d/c3077d6bd761696c310e1ea5db9677e9.jpg">
            </div>
        </div>

        <div class="lancamentos-box">
            <div class="lancamentos-img">
                <div class="quality">NOVO</div>
                <img src="https://static1.srcdn.com/wordpress/wp-content/uploads/2024/01/sakamoto-days-volume-7.jpg">
            </div>
        </div>

        <div class="lancamentos-box">
            <div class="lancamentos-img">
                <div class="quality">NOVO</div>
                <img src="https://entretenimento.r7.com/resizer/NU4xq2CrUAsBu9IRIe9gHohIKZ4=/arc-photo-newr7/arc2-prod/public/G7OMLBCVPNG33OT42VRQQKHMSM.png">
            </div>
        </div>

        <div class="lancamentos-box">
            <div class="lancamentos-img">
                <div class="quality">NOVO</div>
                <img src="https://www.newpop.com.br/wp-content/uploads/2020/03/NewPOP_Grimms01.jpg">
            </div>
        </div>

    </section>
    

    <footer>

    </footer>
    </main>

    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="../home-assets/Js/JQuery3.3.1.js"></script>
    <script src="../home-assets/Js/lightslider.js"></script>
    <script src="../home-assets/Js/script2.js"></script>
</body>

</html>