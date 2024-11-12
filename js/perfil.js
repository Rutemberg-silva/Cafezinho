let isLoggedIn = false;
let userName = '';
let currentPage = 1;
const usersPerPage = 10;
let totalPages = 1;
let users = [];

// Função para verificar o estado de login
function checkLogin() {
    const welcomeMessage = document.getElementById('welcomeMessage');
    const logoutBtn = document.getElementById('logoutBtn');

    if (isLoggedIn) {
        welcomeMessage.textContent = `Seja bem-vindo, ${userName}!`;
        welcomeMessage.style.display = 'block';
        logoutBtn.style.display = 'inline-block';
    } else {
        welcomeMessage.style.display = 'none';
        logoutBtn.style.display = 'none';
    }
}

// Função para inicializar a página
function init() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_login.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            isLoggedIn = response.loggedIn;
            userName = response.username || '';
            checkLogin();
            loadUsers(currentPage);
        }
    };
    xhr.send();
}

// Função para abrir o modal de edição de usuário
function openEditUserModal(userId) {
    const user = users.find(u => u.id == userId); 

    if (user) {
        const editUserModal = document.getElementById('editUserModal');
        document.getElementById('editNome').value = user.nome;
        document.getElementById('editEmail').value = user.email;
        document.getElementById('editTipo').value = user.tipo_usuario || 'cliente';
        document.getElementById('editEndereco').value = user.endereco || '';
        document.getElementById('editTelefone').value = user.telefone || '';

        // Armazena o ID do usuário no modal
        editUserModal.setAttribute('data-user-id', user.id);

        editUserModal.style.display = 'block';
    } else {
        console.error('Usuário não encontrado.');
    }
}


// Função para carregar os dados dos usuários
function loadUsers(page) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `get_user.php?page=${page}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            users = response.users;
            totalPages = response.totalPages;
            const userTableBody = document.querySelector('#userTable tbody');
            userTableBody.innerHTML = '';

            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.nome}</td>
                    <td>${user.email}</td>
                    <td>${user.endereco}</td>
                    <td>${user.telefone}</td>
                    <td>${user.tipo_usuario || 'N/A'}</td>
                    <td>
                        <button class="editUserBtn" data-id="${user.id}" onclick="openEditUserModal(${user.id})">Editar</button>
                        <button class="deleteUserBtn" data-id="${user.id}">Excluir</button>
                        <button class="resetPasswordBtn" data-id="${user.id}">Redefinir Senha</button>
                    </td>
                `;
                userTableBody.appendChild(row);
            });

            document.getElementById('pageNumber').textContent = page;
            document.getElementById('prevPage').disabled = page === 1;
            document.getElementById('nextPage').disabled = page >= totalPages;
        }
    };
    xhr.send();
}

// Funções para navegação de páginas
document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        loadUsers(currentPage);
    }
});

document.getElementById('nextPage').addEventListener('click', () => {
    if (currentPage < totalPages) {
        currentPage++;
        loadUsers(currentPage);
    }
});

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

// Edição de usuário
document.getElementById('editUserForm').addEventListener('submit', (event) => {
    event.preventDefault();

    // Pega o ID do usuário armazenado no modal
    const userId = document.getElementById('editUserModal').getAttribute('data-user-id');
    const nome = document.getElementById('editNome').value;
    const email = document.getElementById('editEmail').value;
    const tipo_usuario = document.getElementById('editTipo').value;
    const endereco = document.getElementById('editEndereco').value;
    const telefone = document.getElementById('editTelefone').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'edit_user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert('Usuário editado com sucesso!');
                loadUsers(currentPage); // Recarrega a lista de usuários
                document.getElementById('editUserModal').style.display = 'none'; // Fecha o modal
            } else {
                alert('Erro ao editar usuário: ' + response.message);
            }
        } else {
            alert('Erro ao editar usuário.');
        }
    };

    // Envia os dados de edição
    xhr.send(`id=${userId}&nome=${nome}&email=${email}&tipo_usuario=${tipo_usuario}&endereco=${endereco}&telefone=${telefone}`);
});

// Cancelar edição
document.getElementById('cancelEdit').addEventListener('click', () => {
    document.getElementById('editUserModal').style.display = 'none';
});

// Excluir usuário
document.addEventListener('click', (event) => {
    if (event.target.classList.contains('deleteUserBtn')) {
        const userId = event.target.getAttribute('data-id');
        if (confirm('Você tem certeza que deseja excluir este usuário?')) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_user.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Usuário excluído com sucesso!');
                    loadUsers(currentPage);
                } else {
                    alert('Erro ao excluir usuário.');
                }
            };
            xhr.send(`id=${userId}`);
        }
    }
});

// Redefinir senha
document.addEventListener('click', (event) => {
    if (event.target.classList.contains('resetPasswordBtn')) {
        const userId = event.target.getAttribute('data-id');

        if (confirm('Você tem certeza que deseja redefinir a senha deste usuário?')) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'reset_password.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Senha redefinida com sucesso!');
                } else {
                    alert('Erro ao redefinir a senha.');
                }
            };
            xhr.send(`id=${userId}`);
        }
    }
});

// Inicializa a página
init();
