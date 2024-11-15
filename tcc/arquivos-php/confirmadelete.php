<?php
session_start();

if (!isset($_SESSION['logado'])) {
   header("Location: index.html");
} else {
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

      <header>
         <div class="logo">
            <img src="../assets/images/log5.png" width="50%" height="auto" loading="lazy" alt="Logotipo The Glarck" class="card-icon">
         </div>
         <h1>Deseja deletar seu usuário?</h1>
      </header>

      <form action="delete.php">

         <button type="submit"> Sim </button>

         <a href="home.php" > <b>Não! Voltar ao Menu!</b> </a>

      </form>

   </body>

   </html>

   <?php
}
?>