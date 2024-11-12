let isLoggedIn = false;
let isAdmin = false;
let userId = null;

const ordersList = document.getElementById('ordersList');

document.getElementById('homeBtn').addEventListener('click', function() {
    window.location.href = 'main.html';
});

function checkLogin() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_login.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            isLoggedIn = response.loggedIn;
            if (!isLoggedIn) {
                alert("Você precisa estar logado para ver seus pedidos.");
                window.location.href = 'main.html';
            } else {
                loadOrders();
            }
        }
    };
    xhr.send();
}

function loadOrders() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_orders.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            ordersList.innerHTML = '';

            if (response.success && response.orders.length > 0) {
                response.orders.forEach(order => {
                    const orderDiv = document.createElement('div');
                    orderDiv.className = 'order-item';

                    const total = typeof order.total === 'number' ? order.total : parseFloat(order.total);

                    orderDiv.innerHTML = `
                        <div class="order-details">
                            <p><strong>ID do Pedido:</strong> ${order.id}</p>
                            <p><strong>Total:</strong> R$ ${total.toFixed(2).replace('.', ',')}</p>
                            <p><strong>Data do Pedido:</strong> ${new Date(order.data_pedido).toLocaleString('pt-BR')}</p>
                            <p><strong>Endereço de Entrega:</strong> ${order.endereco_entrega || 'Não especificado'}</p>
                            <p><strong>Método de Pagamento:</strong> ${order.metodo_pagamento}</p>
                            <p><strong>Status:</strong> ${order.status}</p>
                        </div>
                        ${isAdmin && order.status !== 'avaliado' ? `
                            <button class="status-btn" onclick="changeOrderStatus(${order.id}, 'pendente')">Pendente</button>
                            <button class="status-btn" onclick="changeOrderStatus(${order.id}, 'concluido')">Concluído</button>
                            <button class="status-btn" onclick="changeOrderStatus(${order.id}, 'cancelado')">Cancelado</button>
                        ` : ''}
                        ${order.status === 'concluido' ? `
                            <button class="status-btn" onclick="avaliarPedido(${order.id})">Avaliar Pedido</button>
                        ` : ''}
                    `;
                    ordersList.appendChild(orderDiv);
                });
            } else {
                ordersList.innerHTML = '<p>Nenhum pedido encontrado.</p>';
            }
        }
    };
    xhr.send();
}


// Função para redirecionar à página de avaliação
function avaliarPedido(orderId) {
    window.location.href = `pesquisa.html?orderId=${orderId}`;
}
function checkLogin() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_login.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            isLoggedIn = response.loggedIn;
            isAdmin = response.userType === "admin"; // Verifica se é admin
            userId = response.userId;

            if (!isLoggedIn) {
                alert("Você precisa estar logado para ver seus pedidos.");
                window.location.href = 'main.html';
            } else {
                loadOrders();
            }
        }
    };
    xhr.send();
}

function changeOrderStatus(orderId, newStatus) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_order_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert(`Status do pedido atualizado para: ${newStatus}`);
                loadOrders();
            } else {
                alert("Erro ao atualizar o status do pedido.");
            }
        }
    };
    xhr.send(JSON.stringify({ id: orderId, status: newStatus }));
}

checkLogin();
