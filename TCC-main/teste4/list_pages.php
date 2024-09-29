<?php
// Diretório onde as páginas estão armazenadas
$directory = 'capitulo1/';
$images = glob($directory . "*.jpg"); // Busca por arquivos .jpg no diretório

// Retorna a lista de arquivos no formato JSON
echo json_encode($images);
?>
