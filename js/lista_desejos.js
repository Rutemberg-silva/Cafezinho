let isLoggedIn = false;
let userName = '';

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
    const cartBtn = document.getElementById('cartBtn');
    if (isLoggedIn) {
        cartBtn.style.display = 'inline';
        welcomeMessage.textContent = `Seja bem-vindo, ${userName}!`;
        welcomeMessage.style.display = 'block';
    } else {
        welcomeMessage.style.display = 'none';
        cartBtn.style.display = 'none';
    }
}

// Função para verificar se o usuário está logado e carregar a lista de desejos
function init() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_login.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            isLoggedIn = response.loggedIn;
            userName = response.username || '';
            checkLogin();

            if (isLoggedIn) {
                loadWishlist(); // Carregar lista de desejos
            } else {
                alert('Por favor, faça login para acessar sua lista de desejos.');
                window.location.href = 'login.html';
            }
        }
    };
    xhr.send();
}

if (cartBtn) {
    cartBtn.addEventListener('click', function() {
        window.location.href = 'carrinho_compras.html';
    });
}

// Função para carregar a lista de desejos
function loadWishlist() {
    fetch('get_wishlist.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const wishlistContainer = document.querySelector('.wishlist');
                wishlistContainer.innerHTML = ''; // Limpa a lista antes de renderizar

                data.wishlist.forEach(item => {
                    const wishlistItem = document.createElement('div');
                    wishlistItem.classList.add('wishlist-item');
                    wishlistItem.innerHTML = `
                        <img src="${item.imagem}" alt="${item.nome}">
                        <p>${item.nome} - R$ ${parseFloat(item.preco).toFixed(2)}</p>
                        <button class="add-to-cart-btn" data-id="${item.product_id}" data-nome="${item.nome}" data-preco="${item.preco}" data-imagem="${item.imagem}">Mover para Carrinho</button>
                        <button class="remove-btn" data-id="${item.product_id}">Remover da Lista</button>
                    `;
                    wishlistContainer.appendChild(wishlistItem);
                });

                // Adiciona eventos para mover para o carrinho e remover
                document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                    button.addEventListener('click', () => moveToCart(button.getAttribute('data-id'), button.getAttribute('data-nome'), button.getAttribute('data-preco'), button.getAttribute('data-imagem')));
                });
                document.querySelectorAll('.remove-btn').forEach(button => {
                    button.addEventListener('click', () => removeFromWishlist(button.getAttribute('data-id')));
                });
            } else {
                alert(data.message || 'Erro ao carregar a lista de desejos');
            }
        })
        .catch(error => console.error('Erro ao carregar a lista de desejos:', error));
}

// Função para mover item para o carrinho
function moveToCart(productId, nomeProduto, preco, imagem) {
    getUserId()
        .then(userId => {
            const dataToSend = {
                product_id: productId,
                nome_produto: nomeProduto,
                preco: parseFloat(preco),
                imagem: imagem,
                quantidade: 1,
                user_id: userId
            };

            return fetch('move_car_list.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dataToSend)
            });
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                loadWishlist(); // Recarregar a lista de desejos
            } else {
                alert(data.message || 'Erro ao mover para o carrinho');
            }
        })
        .catch(error => console.error('Erro ao mover para o carrinho:', error));
}

// Função para obter o ID do usuário logado
function getUserId() {
    return fetch('get_user_id.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.user_id;
            } else {
                throw new Error('Usuário não autenticado');
            }
        });
}

// Função para remover item da lista de desejos
function removeFromWishlist(productId) {
    getUserId()
        .then(userId => {
            return fetch('remove_from_wishlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId, user_id: userId })
            });
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produto removido da lista de desejos!');
                loadWishlist(); // Recarregar a lista de desejos
            } else {
                alert(data.message || 'Erro ao remover da lista de desejos');
            }
        })
        .catch(error => console.error('Erro ao remover da lista de desejos:', error));
}

// Inicializa a aplicação
init();
