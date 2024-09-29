let currentPage = 1;
const totalPages = 3; // Defina o número total de páginas
let scrollMode = false; // Modo padrão de navegação por setas

document.addEventListener("DOMContentLoaded", function () {
    displayPage(currentPage);
});

function toggleMode() {
    scrollMode = !scrollMode;
    const toggleButton = document.querySelector('.toggle-mode');
    const arrowNav = document.getElementById('arrow-navigation');
    
    if (scrollMode) {
        // Modo de rolagem ativado
        toggleButton.textContent = "Mudar para Modo de Navegação por Setas";
        document.querySelectorAll("img").forEach(img => img.style.display = "block");
        arrowNav.style.display = "none";
    } else {
        // Modo de setas ativado
        toggleButton.textContent = "Mudar para Modo de Rolagem";
        document.querySelectorAll("img").forEach(img => img.style.display = "none");
        arrowNav.style.display = "flex";
        displayPage(currentPage); // Exibe apenas a página atual
    }
}

function displayPage(pageNumber) {
    // Oculta todas as imagens
    document.querySelectorAll("img").forEach(img => img.style.display = "none");
    // Exibe a página atual
    document.getElementById(`page${pageNumber}`).style.display = "block";
}

function nextPage() {
    if (currentPage < totalPages) {
        currentPage++;
        displayPage(currentPage);
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayPage(currentPage);
    }
}
