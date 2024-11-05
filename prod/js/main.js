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
        loginBtn.style.display = 'none'; // Oculta o botão de login
        createAccountBtn.style.display = 'none'; // Oculta o botão 'Criar Conta'
        cartBtn.style.display = 'inline';
        listaDesejos.style.display = 'inline';
        meusPedidos.style.display = 'inline'; // Exibe o menu Meus Pedidos

        // Verifica se o usuário é admin
        if (userType === 'admin') {
            manageUsers.style.display = 'block'; // Exibe o menu de gerenciamento de usuários
            manageProducts.style.display = 'block'; // Exibe o menu de gerenciamento de produtos
        } else {
            manageUsers.style.display = 'none';
            manageProducts.style.display = 'none';
        }
    } else {
        welcomeMessage.style.display = 'none';
        logoutBtn.style.display = 'none';
        manageUsers.style.display = 'none';
        manageProducts.style.display = 'none';
        loginBtn.style.display = 'inline-block'; // Exibe o botão de login
        cartBtn.style.display = 'none';
        listaDesejos.style.display = 'none';
        meusPedidos.style.display = 'none'; // Oculta o menu Meus Pedidos
    }
}

// Função para inicializar a página
function init() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_login.php', true); // Chamada para verificar login
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            isLoggedIn = response.loggedIn;
            userName = response.username || '';
            userType = response.userType || ''; // Obtém o tipo de usuário
            checkLogin();
        }
    };
    xhr.send();
}

// Função para realizar logout
function logout() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'logout.php', true); // Chamada para realizar logout
    xhr.onload = function() {
        if (xhr.status === 200) {
            window.location.reload(); // Recarrega a página após logout
        }
    };
    xhr.send();
}

// Adiciona o listener para o botão de logout
document.getElementById('logoutBtn').addEventListener('click', logout);

// Adiciona evento de clique ao botão 'Criar Conta'
const createAccountBtn = document.getElementById('createAccountBtn');
if (createAccountBtn) {
    createAccountBtn.addEventListener('click', function() {
        window.location.href = 'cadastro.html'; // Redireciona para a página de cadastro
    });
}

document.getElementById('cartBtn').addEventListener('click', function() {
    window.location.href = 'carrinho_compras.html'; // Redireciona para a página de carrinho
});

// Adiciona evento de clique ao botão de login
document.getElementById('loginBtn').addEventListener('click', function() {
    window.location.href = 'login.html'; // Redireciona para a página de login
});

// Adiciona evento de clique ao link "Meus Pedidos"
const meusPedidos = document.getElementById('meusPedidos');
meusPedidos.addEventListener('click', function() {
    window.location.href = 'meus_pedidos.html'; // Redireciona para a página de Meus Pedidos
});

// Inicializa a aplicação
init();
