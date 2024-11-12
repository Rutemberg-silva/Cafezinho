const homeBtn = document.getElementById('homeBtn');

if (homeBtn) {
    homeBtn.addEventListener('click', function() {
        window.location.href = 'main.html';
    });
}

// Variáveis globais
let isLoggedIn = false;
let userName = '';
let userType = '';
let selectedProduct = null;

// Função para verificar se o usuário está logado
function checkLogin() {
    fetch('check_login.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                isLoggedIn = true;
                userName = data.username;
                userType = data.userType;
            }
            updateUI();
        })
        .catch(error => console.error('Erro ao verificar login:', error));
}

// Atualiza a interface do usuário
function updateUI() {
    const welcomeMessage = document.getElementById('welcomeMessage');
    const loginBtn = document.getElementById('loginBtn');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const addToWishlistBtn = document.getElementById('addToWishlistBtn');
    const cartBtn = document.getElementById('cartBtn');

    if (isLoggedIn) {
        welcomeMessage.textContent = `Seja bem-vindo, ${userName}!`;
        welcomeMessage.style.display = 'block';
        loginBtn.style.display = 'none';
        cartBtn.style.display = 'inline';
    } else {
        welcomeMessage.style.display = 'none';
        loginBtn.style.display = 'inline-block';
        addToCartBtn.style.display = 'none';
        addToWishlistBtn.style.display = 'none';
        cartBtn.style.display = 'none';
    }
}

if (cartBtn) {
    cartBtn.addEventListener('click', function() {
        window.location.href = 'carrinho_compras.html';
    });
}

// Redirecionamento para login
document.getElementById('loginBtn').addEventListener('click', function() {
    window.location.href = 'login.html';
});

document.addEventListener('DOMContentLoaded', function() {
    checkLogin();

    const productList = document.getElementById('productList');
    const descricaoProduto = document.getElementById('descricaoProduto');
    const sugestoesProduto = document.getElementById('sugestoesProduto');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const addToWishlistBtn = document.getElementById('addToWishlistBtn');

    // Carregar produtos
    function loadProducts() {
        fetch('get_products.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.products.forEach(product => {
                        const productItem = document.createElement('div');
                        productItem.classList.add('product-item');
                        productItem.innerHTML = `
                            <img class="product-image-nosso" src="${product.imagem}" alt="${product.nome}">
                            <p>${product.nome}</p>
                            <p>R$ ${parseFloat(product.preco).toFixed(2)}</p>
                        `;
                        productItem.addEventListener('click', () => {
                            selectedProduct = product;
                            descricaoProduto.textContent = product.descricao;
                            sugestoesProduto.textContent = product.sugestoes || "Sem sugestões disponíveis";
                            addToCartBtn.style.display = isLoggedIn ? 'block' : 'none';
                            addToWishlistBtn.style.display = isLoggedIn ? 'block' : 'none';
                        });
                        productList.appendChild(productItem);
                    });
                } else {
                    console.error('Erro ao carregar produtos:', data.message);
                }
            })
            .catch(error => console.error('Erro:', error));
    }
    // Função para adicionar à lista de desejos
    addToWishlistBtn.addEventListener('click', () => {
        if (selectedProduct && isLoggedIn) {
            fetch('add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    product_id: selectedProduct.id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Produto adicionado à lista de desejos!');
                } else {
                    alert(data.message || 'Erro ao adicionar à lista de desejos');
                }
            })
            .catch(error => console.error('Erro ao adicionar à lista de desejos:', error));
        } else {
            alert('Por favor, faça login para adicionar produtos à lista de desejos.');
        }
    });

    // Função para adicionar ao carrinho
    addToCartBtn.addEventListener('click', () => {
        if (selectedProduct && isLoggedIn) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    productId: selectedProduct.id,
                    nomeProduto: selectedProduct.nome,
                    preco: selectedProduct.preco,
                    imagem: selectedProduct.imagem,
                    quantidade: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Produto adicionado ao carrinho!');
                } else {
                    alert(data.message || 'Erro ao adicionar ao carrinho');
                }
            })
            .catch(error => console.error('Erro ao adicionar ao carrinho:', error));
        } else {
            alert('Por favor, faça login para adicionar produtos ao carrinho.');
        }
    });

    loadProducts();
});
