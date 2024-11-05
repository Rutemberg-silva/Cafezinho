// Variáveis globais
let isLoggedIn = false; // Define se o usuário está logado
let userName = ''; // Armazena o nome do usuário
let userType = ''; // Armazena o tipo do usuário

// Função para verificar se o usuário está logado
function checkLogin() {
    const welcomeMessage = document.getElementById('welcomeMessage');
    const logoutBtn = document.getElementById('logoutBtn');
    const manageUsers = document.getElementById('manageUsers');
    const manageProducts = document.getElementById('manageProducts');
    const loginBtn = document.getElementById('loginBtn');
    const createAccountBtn = document.getElementById('createAccountBtn');
    const cartBtn = document.getElementById('cartBtn');
    const meusPedidos = document.getElementById('meusPedidos');

    if (isLoggedIn) {
        welcomeMessage.textContent = `Seja bem-vindo, ${userName}!`;
        welcomeMessage.style.display = 'block';
        logoutBtn.style.display = 'block';
        loginBtn.style.display = 'none';
        createAccountBtn.style.display = 'none';
        cartBtn.style.display = 'inline';
        meusPedidos.style.display = 'inline';

        if (userType === 'admin') {
            manageUsers.style.display = 'block';
            manageProducts.style.display = 'block';
        } else {
            manageUsers.style.display = 'none';
            manageProducts.style.display = 'none';
        }
    } else {
        welcomeMessage.style.display = 'none';
        logoutBtn.style.display = 'none';
        manageUsers.style.display = 'none';
        manageProducts.style.display = 'none';
        loginBtn.style.display = 'inline-block';
        cartBtn.style.display = 'none';
        meusPedidos.style.display = 'none';
    }
}

// Função para carregar produtos
function loadProducts() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'product_main.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('productFrame').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Função para inicializar a página
function init() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_login.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            isLoggedIn = response.loggedIn;
            userName = response.username || '';
            userType = response.userType || '';
            checkLogin();
        }
    };
    xhr.send();

    loadProducts(); // Carrega os produtos ao iniciar
}

if (cartBtn) {
    cartBtn.addEventListener('click', function() {
        window.location.href = 'carrinho_compras.html';
    });
}
if (loginBtn) {
    loginBtn.addEventListener('click', function() {
        window.location.href = 'login.html';
    });
}
if (createAccountBtn) {
    createAccountBtn.addEventListener('click', function() {
        window.location.href = 'cadastro.html';
    });
}
// Logout
function logout() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'logout.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            window.location.href = 'login.html';
        }
    };
    xhr.send();
}

document.getElementById('logoutBtn').addEventListener('click', logout);

// Inicializa a aplicação
init();
