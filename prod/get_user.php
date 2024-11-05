<?php
include 'db_connect.php';

// Obter página e número de usuários por página para a paginação
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$usersPerPage = 10;
$offset = ($page - 1) * $usersPerPage;

// Buscar os usuários com limite para paginação
$sql = "SELECT id, nome, email, endereco,telefone, tipo_usuario, data_criacao FROM usuarios LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $usersPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Contar o número total de usuários para a paginação
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];

// Retornar o total de páginas e os dados dos usuários
echo json_encode([
    'users' => $users,
    'totalPages' => ceil($totalUsers / $usersPerPage)
]);

$conn->close();
?>
