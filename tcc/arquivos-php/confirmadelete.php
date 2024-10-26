<?php
    session_start();
    
    if(!isset($_SESSION['logado'])){
        header ("Location: index.html");
    }
    else{
     ?> 
    <div class="prin">
    <div class="form">  
     <h1>Deseja Deletar seu usuário?</h1>
     <br>
        <a href="delete.php" class="a1"> Sim </a>
     <br>
        <a href="home.php"  class="a1">Não! Quero voltar ao Menu!</a>
    </div>
    </div>
    <?php
    }
    ?>