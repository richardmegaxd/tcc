/*=============== SHOW SIDEBAR ===============*/
const showSidebar = (toggleId, sidebarId, mainId) => {
    const toggle = document.getElementById(toggleId),
        sidebar = document.getElementById(sidebarId),
        main = document.getElementById(mainId);

    if (toggle && sidebar && main) {
        toggle.addEventListener('click', () => {
            /* Show sidebar */
            sidebar.classList.toggle('show-sidebar');
            /* Add padding main */
            main.classList.toggle('main-pd');
        });
    }
};
showSidebar('header-toggle', 'sidebar', 'main');

/*=============== LINK ACTIVE ===============*/
const sidebarLink = document.querySelectorAll('.sidebar__link');

document.querySelector('.sidebar__list').addEventListener('click', function (e) {
    const target = e.target.closest('.sidebar__link');
    if (target) {
        sidebarLink.forEach(l => l.classList.remove('active-link'));
        target.classList.add('active-link');
    }
});





// ALTERAÇÃO DE TELAS
document.querySelectorAll('a[data-target]').forEach(link => {
    link.addEventListener('click', function (e) {
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
            star.classList.toggle('active', i < rating);
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
            // Filtrar imagens da pasta 'capitulo2'
            imagePaths = data.filter(path => path.includes('capitulo2'));
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
            if (img.src.includes('capitulo2')) {
                img.style.display = "block"; // Exibe todas as imagens da pasta 'capitulo2'
            }
        });
        arrowNav.style.display = "none"; // Oculta a navegação por setas
        // toggleButton.textContent = "Mudar para Modo de Navegação por Setas"; // Texto do botão

        // Habilitar o botão "Voltar ao Topo"
        window.addEventListener('scroll', toggleBackToTopButton);

    } else {
        document.querySelectorAll("img").forEach(img => {
            if (img.src.includes('capitulo2') && img.id.startsWith('page')) {
                img.style.display = "none"; // Oculta todas as imagens da pasta 'capitulo2'
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
        if (img.src.includes('capitulo2') && img.id.startsWith('page')) {
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

function getReadingMode() {
    return localStorage.getItem('readingMode') || 'click';
}

// Função para verificar o tema atual no localStorage ou definir o tema escuro como padrão
function getCurrentTheme() {
    return localStorage.getItem('theme') || 'dark'; // O padrão será 'dark'
}

// Função para aplicar o tema com base na seleção
function applyTheme(theme) {
    const themeIcon = document.querySelector('#themeIconContainer i');

    if (theme === 'light') {
        document.body.style.backgroundColor = '#fff'; // Cor de fundo sólida
        document.body.style.backgroundImage = ''; // Remove a imagem de fundo
        document.querySelectorAll('.text-color').forEach(el => el.style.color = 'rgb(67, 21, 96)'); // Cor do texto
        document.querySelectorAll('.fundo-color').forEach(el => el.style.backgroundImage = "none" ); // remove imagem de fundo do sidebar   
        document.querySelectorAll('.fundo-color').forEach(el => el.style.backgroundColor = "#fff" ); // remove imagem de fundo do sidebar   
        themeIcon.classList.remove('bx-sun');
        themeIcon.classList.add('bx-moon');
    } else {
        document.body.style.backgroundImage = 'url("../assets/images/fundo3.jpg")'; // Imagem de fundo escura
        document.body.style.backgroundColor = ''; // Remove a cor de fundo sólida
        document.querySelectorAll('.text-color').forEach(el => el.style.color = '#fff'); // Cor do texto
        document.querySelectorAll('.fundo-color').forEach(el => el.style.backgroundImage = 'url("../assets/images/fundo3.jpg")');  // Cor do texto
        themeIcon.classList.remove('bx-moon');
        themeIcon.classList.add('bx-sun');
    }
}


// Função para alternar tema
function toggleTheme() {
    const currentTheme = getCurrentTheme();
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark'; // Alterna o tema

    applyTheme(newTheme); // Aplica o novo tema
    localStorage.setItem('theme', newTheme); // Salva a preferência do tema no localStorage
}

// Ao carregar a página, aplica o tema salvo
document.addEventListener("DOMContentLoaded", function () {
    const savedTheme = getCurrentTheme(); // Verifica o tema salvo
    applyTheme(savedTheme); // Aplica o tema salvo
});








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
