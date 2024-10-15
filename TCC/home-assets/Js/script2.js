// CARROSSEL
$(document).ready(function () {
    $('#autoWidth,#autoWidth2').lightSlider({
        autoWidth: true,
        loop: true,
        onSliderLoad: function () {
            $('#autoWidth,#autoWidth2').removeClass('cS-hidden');
        }
    });
});
// FIM CARROSSEL


// MENU LATERAL
window.onload = function () {
    const sidebar = document.querySelector(".sidebar");
    const closeBtn = document.querySelector("#btn");
    const searchBtn = document.querySelector(".bx-search");

    closeBtn.addEventListener("click", function () {
        sidebar.classList.toggle("open");
        menuBtnChange();
    });

    searchBtn.addEventListener("click", function () {
        sidebar.classList.toggle("open");
        menuBtnChange();
    });

    function menuBtnChange() {
        if (sidebar.classList.contains("open")) {
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else {
            closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
    }
};
// FIM MENU LATERAL



// ALTERAÇÃO DE TELAS
document.querySelectorAll('a[data-target]').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('data-target');
        
        document.querySelectorAll('.content-section').forEach(section => {
            section.classList.remove('active');
        });

        document.getElementById(targetId).classList.add('active');
    });
});

// FIM ALTERAÇÃO DE TELAS




// AVALIAÇÃO DO MANGÁ
const starRating = document.querySelector('.star-rating');
const stars = starRating.querySelectorAll('i');

let rating = 0;

stars.forEach((star, index) => {
  star.addEventListener('click', () => {
    rating = index + 1;
    stars.forEach((star, i) => {
      if (i < rating) {
        star.classList.add('active');
      } else {
        star.classList.remove('active');
      }
    });
  });
});
// FIM AVALIAÇÃO DO MANGÁ









// SCRIPT PÁGINA DE LEITURA
let currentPage = 1;
let totalPages = 0;
let imagePaths = [];
let scrollMode = false; // Modo padrão de navegação por setas
let backgroundImageEnabled = true; // Controla a exibição da imagem de fundo

document.addEventListener("DOMContentLoaded", function () {
    // Carregar as páginas do servidor
    fetch('list_pages.php')
        .then(response => response.json())
        .then(data => {
            // Filtrar imagens da pasta 'capitulo1'
            imagePaths = data.filter(path => path.includes('capitulo1'));
            totalPages = imagePaths.length;
            createImageElements();  // Cria os elementos de imagem no contêiner
            restoreModeAndTheme();  // Restaura o modo de leitura e o tema
        });

    // Carregar o tema salvo no localStorage ou usar o tema escuro por padrão
    const savedTheme = getCurrentTheme(); // Verifica o tema salvo (escuro por padrão)
    applyTheme(savedTheme); // Aplica o tema salvo
});

function createImageElements() {
    const imageContainer = document.getElementById('image-container');
    imageContainer.innerHTML = ''; // Limpa o contêiner de imagens antes de adicionar novas

    imagePaths.forEach((imagePath, index) => {
        const img = document.createElement('img');
        img.src = imagePath;
        img.id = `page${index + 1}`;
        img.alt = `Página ${index + 1}`;
        img.style.display = 'none'; // Esconde as imagens inicialmente
        imageContainer.appendChild(img);
    });
}

// Função para restaurar o modo de leitura e o tema
function restoreModeAndTheme() {
    // Carregar o modo de leitura salvo no localStorage ou usar modo de setas por padrão
    const savedMode = getReadingMode(); // Verifica o modo de leitura salvo
    scrollMode = savedMode === 'scroll'; // Define o modo de leitura salvo

    // Aplicar o modo de leitura corretamente
    applyReadingMode();
}

function toggleMode() {
    scrollMode = !scrollMode;
    const newMode = scrollMode ? 'scroll' : 'click';
    localStorage.setItem('readingMode', newMode); // Salva o modo de leitura no localStorage
    applyReadingMode(); // Aplica o novo modo de leitura
}

// Função para aplicar o modo de leitura e controlar o botão
function applyReadingMode() {
    const toggleButton = document.querySelector('.toggle-mode');
    const arrowNav = document.getElementById('arrow-navigation');
    const backToTopBtn = document.getElementById('backToTopBtn');

    if (scrollMode) {
        document.querySelectorAll("img").forEach(img => {
            if (img.src.includes('capitulo1')) {
                img.style.display = "block"; // Exibe todas as imagens da pasta 'capitulo1'
            }
        });
        arrowNav.style.display = "none"; // Oculta a navegação por setas
        // toggleButton.textContent = "Mudar para Modo de Navegação por Setas"; // Texto do botão

        // Habilitar o botão "Voltar ao Topo"
        window.addEventListener('scroll', toggleBackToTopButton);

    } else {
        document.querySelectorAll("img").forEach(img => {
            if (img.src.includes('capitulo1') && img.id.startsWith('page')) {
                img.style.display = "none"; // Oculta todas as imagens da pasta 'capitulo1'
            }
        });
        displayPage(currentPage); // Exibe a página atual
        arrowNav.style.display = "flex"; // Exibe a navegação por setas
        // toggleButton.textContent = "Mudar para Modo de Leitura por Rolagem"; // Texto do botão

        // Desabilitar o botão "Voltar ao Topo" no modo de setas
        backToTopBtn.style.display = "none"; // Certifique-se de que ele esteja escondido
        window.removeEventListener('scroll', toggleBackToTopButton); // Remove o evento de scroll
    }
}

function displayPage(pageNumber) {
    document.querySelectorAll("img").forEach(img => {
        if (img.src.includes('capitulo1') && img.id.startsWith('page')) {
            img.style.display = "none"; // Oculta todas as imagens
        }
    });
    const currentImg = document.getElementById(`page${pageNumber}`);
    if (currentImg) {
        currentImg.style.display = "block"; // Exibe a página atual
    }
}

function nextPage() {
    if (currentPage < totalPages) {
        currentPage++;
        displayPage(currentPage);
        updateButtons();
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayPage(currentPage);
        updateButtons();
    }
}

function updateButtons() {
    document.getElementById('prevButton').disabled = (currentPage === 1);
    document.getElementById('nextButton').disabled = (currentPage === totalPages);
}

// Função para verificar o tema atual no localStorage ou definir o tema escuro como padrão
function getCurrentTheme() {
    return localStorage.getItem('theme') || 'dark';
}

// Função para aplicar o tema com base na seleção
function applyTheme(theme) {
    const themeIcon = document.querySelector('#themeIconContainer i'); // O ícone dentro do contêiner

    if (theme === 'light') {
        // Alterar cor dos ícones e fontes para preto
        document.querySelectorAll('.icon').forEach(icon => icon.style.color = '#000000');
        document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, label').forEach(el => el.style.color = '#000000');

        // Verifica se a imagem de fundo deve ser removida
        if (backgroundImageEnabled) {
            document.body.style.backgroundImage = 'none';
        }

        // Troca o ícone para lua
        themeIcon.classList.remove('bx-sun');
        themeIcon.classList.add('bx-moon');
    } else {
        // Reverter cor dos ícones e fontes para branco
        document.querySelectorAll('.icon').forEach(icon => icon.style.color = '#ffffff');
        document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, label').forEach(el => el.style.color = '#ffffff');

        // Restaura a imagem de fundo se estiver habilitada
        if (backgroundImageEnabled) {
            document.body.style.backgroundImage = 'url("../assets/images/fundo3.jpg")'; // Substitua pela URL da sua imagem
        }

        // Troca o ícone para sol
        themeIcon.classList.remove('bx-moon');
        themeIcon.classList.add('bx-sun');
    }
}

function toggleTheme() {
    const currentTheme = getCurrentTheme();
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark'; // Alterna o tema

    applyTheme(newTheme); // Aplica o novo tema
    localStorage.setItem('theme', newTheme); // Salva a preferência do tema no localStorage
}

function getReadingMode() {
    return localStorage.getItem('readingMode') || 'click';
}



// Função para rolar ao topo da página
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Função que mostra ou esconde o botão ao rolar a página
function toggleBackToTopButton() {
    const backToTopBtn = document.getElementById('backToTopBtn');

    if (scrollMode && window.pageYOffset > 200) {
        // Mostra o botão se o usuário rolou mais de 200px e estiver no modo de rolagem
        backToTopBtn.style.display = "block";
    } else {
        // Esconde o botão se o modo de rolagem não está ativo ou o scroll é menor que 200px
        backToTopBtn.style.display = "none";
    }
}
//FIM SCRIPT PÁGINA DE LEITURA


























// Seletor para o menu lateral e o botão de alternância
const sidebar = document.querySelector(".sidebar");
const toggleMenuButton = document.getElementById("toggleMenu");

// Função para alternar a exibição do menu lateral
function toggleSidebar() {
    if (sidebar.style.display === "none" || sidebar.style.display === "") {
        sidebar.style.display = "block"; // Mostra o menu lateral
    } else {
        sidebar.style.display = "none"; // Esconde o menu lateral
    }
}

// Adiciona o evento de clique ao botão
toggleMenuButton.addEventListener("click", toggleSidebar);































