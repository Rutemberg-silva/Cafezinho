let isLoggedIn = false;
let userId = null;

const reviewList = document.getElementById('reviewList');
const reviewFormContainer = document.getElementById('reviewFormContainer');
const reviewForm = document.getElementById('reviewForm');

// Função para verificar o estado de login e carregar as avaliações
function init() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_login.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            isLoggedIn = response.loggedIn;
            userId = response.userId || null;
            
            if (isLoggedIn) {
                loadReviews();
                checkIfCanReview();
            } else {
                alert("Você precisa estar logado para deixar uma avaliação.");
                window.location.href = 'main.html';
            }
        }
    };
    xhr.send();
}

// Carregar todas as avaliações
function loadReviews() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_reviews.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            reviewList.innerHTML = '';
            
            if (response.success && response.reviews.length > 0) {
                response.reviews.forEach(review => {
                    const reviewItem = document.createElement('div');
                    reviewItem.className = 'review-item';
                    reviewItem.innerHTML = `
                        <p><strong>Usuário:</strong> ${review.nome}</p>
                        <p><strong>Avaliação:</strong> ${review.avaliacao} / 5</p>
                        <p><strong>Comentário:</strong> ${review.comentario}</p>
                        <p><strong>Data:</strong> ${new Date(review.data_avaliacao).toLocaleString('pt-BR')}</p>
                    `;
                    reviewList.appendChild(reviewItem);
                });
            } else {
                reviewList.innerHTML = '<p>Nenhuma avaliação encontrada.</p>';
            }
        }
    };
    xhr.send();
}


// Verificar se o usuário pode deixar uma avaliação para um pedido específico
function checkIfCanReview() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `check_review_permission.php?user_id=${userId}`, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.canReview) {
                reviewFormContainer.style.display = 'block';
            }
        }
    };
    xhr.send();
}

// Enviar a avaliação
reviewForm.addEventListener('submit', function(event) {
    event.preventDefault();
    
    const rating = document.getElementById('rating').value;
    const comment = document.getElementById('comment').value;
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'submit_review.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Avaliação enviada com sucesso!");
                reviewForm.reset();
                loadReviews();
            } else {
                alert("Erro ao enviar a avaliação.");
            }
        }
    };
    xhr.send(JSON.stringify({
        user_id: userId,
        rating: rating,
        comment: comment
    }));
});

// Inicializar a aplicação
init();
