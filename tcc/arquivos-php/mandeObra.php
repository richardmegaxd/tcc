<head>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styleLogin.css">
</head>

<form action="publicar_obra.php" method="POST" enctype="multipart/form-data">
    <label for="titulo">Título do Manga</label>
    <input type="text" id="titulo" name="titulo" required>

    <label for="descricao">Descrição</label>
    <textarea id="descricao" name="descricao" required></textarea>

    <label for="imagem_capa">Imagem de Capa</label>
    <input type="file" id="imagem_capa" name="imagem_capa">

    <label for="paginas_manga">Páginas do Manga (Selecione várias)</label>
    <input type="file" id="paginas_manga[]" name="paginas_manga[]" multiple>

    <input type="submit" value="Publicar Manga">
</form>
