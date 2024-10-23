<?php
    session_start();

    if(($_SESSION['logado']==true)){

    $idUsuario = $_SESSION['id_usuario'];

    $conexao = mysqli_connect("localhost", "root", "", "bd_glark");

    $operacao = "DELETE FROM tb_usuario WHERE cd_usuario = '$idUsuario'"; //efetua a seleção no banco de dados e atribui a uma variável

    mysqli_query($conexao, $operacao);

    session_destroy();
    }else{
    header ("Location: index.html");
    }
    ?>
    <header>
    <h3>Me Ajude a Lembrar</h3>
    </header>
    <div class="logout">
    <h1>Usuário Deletado com Sucesso!</h1>
    <button onclick ="location.href='index.php'" type="button">Voltar ao menu inicial</a>
    </div>
    </div>
    </div>