<?php
    session_start();
    
    if(!isset($_SESSION['logado'])){
        header ("Location: index.html");
    }
    else{
     ?> 
     <!DOCTYPE html>
     <html lang="en">
     <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/styleLogin.css">
     </head>
     <body>
        
     </body>
     </html>
     <div id="section-delete" class="logo">
     <img src="../assets/images/log5.png" width="50%" height="auto" loading="lazy" alt="Logotipo The Glarck" class="card-icon">
     <h1>The Glark</h1><br>
     </div> 
     <h2>Deseja Deletar seu usuário?</h2>
     <br>
        <a href="delete.php" class="voltar delete" > <b>Sim</b> </a>
     <br>
        <a href="home.php" class="voltar delete"> <b>Não! Voltar ao Menu!</b> </a>  
    <?php
    }
    ?>