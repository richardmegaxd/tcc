<?php
// Diretório onde as páginas do gibi estão armazenadas
$directory = '../capitulo2/'; // Mude para o diretório específico

// Verifica se o diretório existe
if (is_dir($directory)) {
    // Obtém todas as imagens com extensão .jpg, .jpeg, .png ou .gif
    $images = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

    // Retorna a lista de arquivos no formato JSON
    echo json_encode($images);
} else {
    echo json_encode([]);
}
?>
