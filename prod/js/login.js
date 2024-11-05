document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    // Obtém os valores dos campos de entrada
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Cria um objeto para enviar os dados
    const formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);

    // Cria um objeto XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'process_login.php', true);

    // Definir o que acontece ao carregar a resposta
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.responseText); // Loga a resposta no console para debugging
            try {
                // Tenta fazer o parsing da resposta JSON
                const response = JSON.parse(xhr.responseText);

                // Verifica se o login foi bem-sucedido
                if (response.success) {
                    alert('Login realizado com sucesso!');
                    window.location.href = 'main.html'; // Redireciona para a página principal
                } else {
                    // Exibe a mensagem de erro retornada pelo servidor
                    alert('Erro de login: ' + response.message);
                }
            } catch (error) {
                // Caso ocorra um erro ao fazer o parsing do JSON
                console.error("Erro ao fazer o parsing do JSON: " + error);
            }
        } else {
            // Caso ocorra um erro com a requisição
            console.error("Erro ao realizar login: " + xhr.status);
        }
    };

    // Envia a requisição com os dados do formulário
    xhr.send(formData);
});
