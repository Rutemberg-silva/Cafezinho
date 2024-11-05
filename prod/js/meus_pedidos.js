document.getElementById('statusFilter').addEventListener('change', loadOrders);

function loadOrders() {
    const status = document.getElementById('statusFilter').value;
    fetch(`get_order.php?status=${status}`)
        .then(response => response.json())
        .then(data => {
            const ordersList = document.getElementById('ordersList');
            ordersList.innerHTML = '';

            if (data.success && data.orders.length > 0) {
                data.orders.forEach(order => {
                    const orderDiv = document.createElement('div');
                    orderDiv.className = 'order-item';
                    orderDiv.innerHTML = `
                        <p>Pedido ID: ${order.id}</p>
                        <p>Produtos: ${order.produtos}</p>
                        <p>Total: R$${order.total.toFixed(2)}</p>
                        <p>Status: ${order.status}</p>
                    `;
                    ordersList.appendChild(orderDiv);
                });
            } else {
                ordersList.innerHTML = `<p>Nenhum pedido encontrado para o status "${status}".</p>`;
            }
        })
        .catch(error => console.error('Erro ao carregar pedidos:', error));
}

loadOrders();  // Chama ao carregar a página para exibir pedidos pendentes por padrão
