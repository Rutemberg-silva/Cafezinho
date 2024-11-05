let isLoggedIn = false;
let userName = '';
let totalPrice = 0;
const cartItemsDiv = document.getElementById('cartItems');
const payBtn = document.getElementById('payBtn');
const totalPriceElem = document.getElementById('totalPrice');
const paymentOptions = document.getElementById('paymentOptions');
const homeBtn = document.getElementById('homeBtn');
const deliveryOption = document.getElementById('deliveryOption');
const deliveryAddress = document.getElementById('deliveryAddress');

// Redireciona para a página principal
if (homeBtn) {
    homeBtn.addEventListener('click', function() {
        window.location.href = 'main.html';
    });
}

// Verifica o estado de login
function checkLogin() {
    const welcomeMessage = document.getElementById('welcomeMessage');
    if (isLoggedIn) {
        welcomeMessage.textContent = `Seja bem-vindo, ${userName}!`;
        welcomeMessage.style.display = 'block';
    } else {
        welcomeMessage.style.display = 'none';
    }
}

// Inicializa o estado de login e carrega os itens do carrinho
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
                loadCartItems(); // Carrega os itens do carrinho se o usuário estiver logado
            }
        }
    };
    xhr.send();
}

// Carrega os itens do carrinho
function loadCartItems() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_cart_items.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                cartItemsDiv.innerHTML = ''; // Limpa os itens carregados previamente
                response.cartItems.forEach(item => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'cart-item';
                    itemDiv.innerHTML = `
                        <img src="${item.imagem}" alt="${item.nome_produto}" class="cart-item-image">
                        <p>${item.nome_produto} - R$ ${item.preco}</p>
                        <input type="checkbox" class="select-item" data-preco="${item.preco}" data-id="${item.id}">
                    `;
                    cartItemsDiv.appendChild(itemDiv);
                });
                updateTotalPrice(); // Atualiza o preço total após carregar os itens
                console.log("Itens do carrinho carregados:", response.cartItems.length); // Verifica quantos itens foram carregados
            }
        }
    };
    xhr.send();
}

// Atualiza o total dos produtos selecionados
function updateTotalPrice() {
    totalPrice = 0;
    const selectedItems = document.querySelectorAll('.select-item:checked');
    selectedItems.forEach(item => {
        totalPrice += parseFloat(item.dataset.preco.replace(',', '.')); // Adiciona o preço do item
    });
    totalPriceElem.textContent = `Total: R$ ${totalPrice.toFixed(2).replace('.', ',')}`; // Exibe o preço total
    payBtn.disabled = selectedItems.length === 0; // Habilita o botão de pagamento se houver itens selecionados
    console.log("Preço total atualizado: R$", totalPrice.toFixed(2)); // Verifica o preço total
}

// Eventos para os checkboxes
cartItemsDiv.addEventListener('change', updateTotalPrice);

// Evento para o botão de remover selecionados
document.getElementById('removeBtn').addEventListener('click', function() {
    const selectedItems = Array.from(document.querySelectorAll('.select-item:checked')).map(item => item.dataset.id);
    
    if (selectedItems.length === 0) {
        alert("Selecione pelo menos um item para remover.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'remove_selected_items.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            alert(response.message);
            loadCartItems();
        }
    };

    const data = JSON.stringify({ selectedItems });
    xhr.send(data);
});

// Evento para o botão "Pagar"
payBtn.addEventListener('click', function() {
    paymentOptions.style.display = 'block'; // Mostrar opções de pagamento
});

// Mostra ou oculta o endereço de entrega com base na opção escolhida
deliveryOption.addEventListener('change', function() {
    deliveryAddress.style.display = this.value === 'delivery' ? 'block' : 'none';
});

// Evento de finalizar compra
document.getElementById('finalizeBtn').addEventListener('click', function() {
    const selectedItems = Array.from(document.querySelectorAll('.select-item:checked')).map(item => item.dataset.id);
    const paymentMethod = document.getElementById('paymentMethod').value;
    const deliveryOptionValue = deliveryOption.value;
    
    if (selectedItems.length === 0) {
        alert("Selecione pelo menos um item para continuar.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'finalize_order.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            alert("Obrigado pela compra!");
            window.location.href = 'main.html';
        }
    };

    const data = JSON.stringify({ selectedItems, paymentMethod, deliveryOption: deliveryOptionValue });
    xhr.send(data);
});

// Inicializa a aplicação
init();
