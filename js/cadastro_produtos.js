const homeBtn = document.getElementById('homeBtn');

if (homeBtn) {
    homeBtn.addEventListener('click', function() {
        window.location.href = 'main.html';
    });
}

document.addEventListener("DOMContentLoaded", () => {
    loadProducts(); // Carrega produtos ao abrir a página

    // Envio do formulário para cadastrar novo produto
    document.getElementById("productForm").addEventListener("submit", (e) => {
        e.preventDefault();
        addProduct();
    });
});

let products = []; // Array global para armazenar os produtos carregados

// Função para carregar todos os produtos
function loadProducts() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "get_products.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    products = response.products;
                    console.log("Produtos carregados:", products); 
                    displayProducts(products);
                } else {
                    console.error("Erro: A resposta não foi bem-sucedida.");
                }
            } catch (e) {
                console.error("Erro ao parsear o JSON:", e);
            }
        } else {
            console.error("Erro na requisição:", xhr.status);
        }
    };
    xhr.send();
}

// Função para exibir produtos na lista
function displayProducts(products) {
    const productContainer = document.querySelector(".product-list");
    productContainer.innerHTML = ""; 

    products.forEach(product => {
        const preco = parseFloat(product.preco); // Converte para número
        const formattedPreco = isNaN(preco) ? "0.00" : preco.toFixed(2); // Formata ou usa "0.00" se NaN

        const productItem = document.createElement("div");
        productItem.className = "product-item";
        productItem.innerHTML = `
            <div class="product-info">
                <img src="${product.imagem}" alt="${product.nome}" class="product-image-cad">
                <div class="product-details">
                    <h4>${product.nome}</h4>
                    <p>Preço: R$ ${formattedPreco}</p>
                    <p>Descrição: ${product.descricao}</p>
                    <p>Sugestões: ${product.sugestoes}</p>
                    <button class="edit-btn" onclick="editProduct(${product.id})">Editar</button>
                    <button class="delete-btn" onclick="deleteProduct(${product.id})">Excluir</button>
                    <button class="change-image-btn" style="display: none;">Alterar Imagem</button>
                </div>
            </div>
        `;
        productContainer.appendChild(productItem);
    });
}


// Função para adicionar produto
function addProduct() {
    const formData = new FormData();
    formData.append("nome", document.getElementById("productName").value);
    formData.append("preco", document.getElementById("productPrice").value);
    formData.append("descricao", document.getElementById("productDescription").value);
    formData.append("sugestoes", document.getElementById("productSuggestions").value);
    formData.append("imagem", document.getElementById("productImage").files[0]);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "add_product.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Produto cadastrado com sucesso!");
                loadProducts(); // Recarrega a lista de produtos
                document.getElementById("productForm").reset(); // Limpa o formulário
            } else {
                alert("Erro ao cadastrar produto.");
            }
        }
    };
    xhr.send(formData);
}

// Função para editar produto
function editProduct(productId) {
    const product = products.find(p => Number(p.id) === Number(productId));
    if (!product) return alert("Produto não encontrado.");

    // Preenche o formulário com os dados do produto
    document.getElementById("productName").value = product.nome;
    document.getElementById("productPrice").value = product.preco;
    document.getElementById("productDescription").value = product.descricao;
    document.getElementById("productSuggestions").value = product.sugestoes || "";
    
    // Manipula a ação de envio para salvar alterações
    document.getElementById("productForm").onsubmit = (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append("id", productId);
        formData.append("nome", document.getElementById("productName").value);
        formData.append("preco", document.getElementById("productPrice").value);
        formData.append("descricao", document.getElementById("productDescription").value);
        formData.append("sugestoes", document.getElementById("productSuggestions").value);
        formData.append("imagem", document.getElementById("productImage").files[0]);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_product.php", true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert("Produto atualizado com sucesso!");
                    loadProducts(); // Recarrega a lista de produtos
                    document.getElementById("productForm").reset();
                    document.getElementById("productForm").onsubmit = addProduct; // Restaura a função original
                } else {
                    alert("Erro ao atualizar produto.");
                }
            }
        };
        xhr.send(formData);
    };
}

// Função para excluir produto
function deleteProduct(productId) {
    if (confirm("Deseja realmente excluir este produto?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_product.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert("Produto excluído com sucesso!");
                    loadProducts(); // Recarrega a lista de produtos
                } else {
                    alert("Erro ao excluir produto.");
                }
            }
        };
        xhr.send(`id=${productId}`);
    }
}
