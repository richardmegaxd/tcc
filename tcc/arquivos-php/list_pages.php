<?php
// Diretório base onde as obras estão armazenadas
$directory = '../obras/mangas';

// Verifica se o diretório existe
if (is_dir($directory)) {
    // Obtém todas as imagens com extensão .jpg, .jpeg, .png ou .gif
    $images = glob($directory . "/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

    // Retorna os caminhos das imagens no formato JSON
    echo json_encode($images);
} else {
    echo json_encode([]); // Retorna um array vazio se o diretório não existir
}
?>
