<?php
session_start();

// Verifique se o usuário tem uma assinatura ativa
$isSigned = $_SESSION['user_signed'] ?? true; // Suponha que o estado da assinatura vem da sessão ou do banco de dados

// Lista das imagens de mangás
$images = ["manga1.jpg", "manga2.jpg", "manga3.jpg"];

function renderMangaImage($imageUrl, $isSigned) {
    $class = $isSigned ? 'available' : 'unavailable'; // Defina a classe com base na assinatura
    echo "<img src='$imageUrl' alt='#' class='img-escura $class'>";
}

foreach ($images as $image) {
    renderMangaImage($image, $isSigned);
}
?>

