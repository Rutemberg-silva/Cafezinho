const homeBtn = document.getElementById('homeBtn');

// Botão de voltar para a página principal
if (homeBtn) {
    homeBtn.addEventListener('click', function() {
        window.location.href = 'main.html';
    });
}

document.getElementById('registrationForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita o envio padrão do formulário
    
    // Captura os dados do formulário
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const address = document.getElementById('address').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();

    // Verifica se as senhas coincidem
    if (password !== confirmPassword) {
        alert("As senhas não coincidem!");
        return;
    }

    // Verifica se o e-mail é válido
    if (!validateEmail(email)) {
        alert("Por favor, insira um e-mail válido!");
        return;
    }

    // Envia os dados para o backend usando XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'register_user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Usuário cadastrado com sucesso!");
                window.location.href = 'login.html'; // Redireciona para a página de login
            } else {
                alert("Erro ao cadastrar usuário: " + response.message);
            }
        } else {
            console.error("Erro ao cadastrar usuário: " + xhr.status);
        }
    };

    xhr.onerror = function() {
        console.error("Erro na requisição.");
    };

    // Envia os dados do formulário
    xhr.send(`name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&address=${encodeURIComponent(address)}&phone=${encodeURIComponent(phone)}&password=${encodeURIComponent(password)}`);
});

// Função para validar e-mail
function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex básico para validação de e-mail
    return regex.test(email);
}
