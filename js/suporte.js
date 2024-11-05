let isLoggedIn = false; // Variável para verificar se o usuário está logado
let userName = ''; // Variável para armazenar o nome do usuário

const homeBtn = document.getElementById('homeBtn');
const loginBtn = document.getElementById('loginBtn');

if (homeBtn) {
    homeBtn.addEventListener('click', function() {
        window.location.href = 'main.html';
    });
}

// Função para verificar o estado de login
function checkLogin() {
    const welcomeMessage = document.getElementById('welcomeMessage');
    
    if (isLoggedIn) {
        welcomeMessage.textContent = `Seja bem-vindo, ${userName}!`;
        welcomeMessage.style.display = 'block';
    } else {
        welcomeMessage.style.display = 'none';
    }
}

// Função para verificar se o usuário está logado
function init() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_login.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            isLoggedIn = response.loggedIn;
            userName = response.username || '';
            checkLogin();
        }
    };
    xhr.send();
}

// Inicializa a aplicação
init();
