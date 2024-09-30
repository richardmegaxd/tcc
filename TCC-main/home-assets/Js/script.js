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
    const searchBtn = document.querySelector(".bx-search")

    closeBtn.addEventListener("click", function () {
        sidebar.classList.toggle("open")
        menuBtnChange()
    })

    searchBtn.addEventListener("click", function () {
        sidebar.classList.toggle("open")
        menuBtnChange()
    })

    function menuBtnChange() {
        if (sidebar.classList.contains("open")) {
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right")
        } else {
            closeBtn.classList.replace("bx-menu-alt-right", "bx-menu")
        }
    }
}
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










let currentPage = 1;
let totalPages = 0;
let imagePaths = [];
let scrollMode = false; // Modo padrão de navegação por setas

document.addEventListener("DOMContentLoaded", function () {
    // Carregar as páginas do servidor
    fetch('list_pages.php')
        .then(response => response.json())
        .then(data => {
            // Filtrar imagens da pasta 'capitulo1'
            imagePaths = data.filter(path => path.includes('capitulo1'));
            totalPages = imagePaths.length;
            createImageElements();
            displayPage(currentPage);
            updateButtons();
        });

    // Carregar o tema salvo no localStorage
    loadSavedTheme();
});

function createImageElements() {
    const imageContainer = document.getElementById('image-container');
    imageContainer.innerHTML = '';

    imagePaths.forEach((imagePath, index) => {
        const img = document.createElement('img');
        img.src = imagePath;
        img.id = `page${index + 1}`;
        img.alt = `Página ${index + 1}`;
        img.style.display = 'none'; // Escondendo todas as imagens inicialmente
        imageContainer.appendChild(img);
    });
}

function toggleMode() {
    scrollMode = !scrollMode;
    const toggleButton = document.querySelector('.toggle-mode');
    const arrowNav = document.getElementById('arrow-navigation');

    if (scrollMode) {
        // Modo de rolagem ativado
        // toggleButton.textContent = "Mudar para Modo de Navegação por Setas";
        // Exibir todas as imagens da pasta 'capitulo1'
        document.querySelectorAll("img").forEach(img => {
            if (img.src.includes('capitulo1')) {
                img.style.display = "block"; // Exibe imagens da pasta 'capitulo1'
            }
        });
        arrowNav.style.display = "none";
    } else {
        // Modo de setas ativado
        // toggleButton.textContent = "Mudar para Modo de Rolagem";
        // Ocultar todas as imagens que têm o id começando com 'page' e estão na pasta 'capitulo1'
        document.querySelectorAll("img").forEach(img => {
            if (img.src.includes('capitulo1') && img.id.startsWith('page')) {
                img.style.display = "none"; // Oculta imagens da pasta 'capitulo1'
            }
        });
        displayPage(currentPage); // Exibe apenas a página atual
        arrowNav.style.display = "flex";
    }
}

function displayPage(pageNumber) {
    // Oculta todas as imagens que têm o id começando com 'page' e estão na pasta 'capitulo1'
    document.querySelectorAll("img").forEach(img => {
        if (img.src.includes('capitulo1') && img.id.startsWith('page')) {
            img.style.display = "none"; // Oculta imagens da pasta 'capitulo1'
        }
    });

    // Exibe a página atual
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








let backgroundImageEnabled = true; // Controla a exibição da imagem de fundo

// Função para verificar o tema atual no localStorage ou definir o tema escuro como padrão
function getCurrentTheme() {
    // Se não houver tema salvo no localStorage, retorna 'dark' como padrão
    return localStorage.getItem('theme') || 'dark';
}

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

// Aplica o tema salvo ou o tema escuro por padrão ao carregar a página
window.addEventListener('DOMContentLoaded', () => {
    const savedTheme = getCurrentTheme(); // Verifica se há tema salvo no localStorage
    applyTheme(savedTheme); // Aplica o tema (escuro por padrão se não houver tema salvo)
});
