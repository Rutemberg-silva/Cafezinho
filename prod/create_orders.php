<?php
include 'db_connect.php';
session_start();

// Função para obter o preço do produto
function obterPrecoDoProduto($produto_id, $conn) {
    $stmt = $conn->prepare("SELECT preco FROM produtos WHERE id = ?");
    $stmt->bind_param('i', $produto_id);
    $stmt->execute();
    $stmt->bind_result($preco);
    $stmt->fetch();
    $stmt->close();
    return $preco;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['user_id'];
    $data = json_decode(file_get_contents('php://input'), true); // Decodifica os dados da solicitação

    // Verifica se 'produtos' e 'total' estão definidos
    if (isset($data['produtos']) && isset($data['total'])) {
        $produtos = $data['produtos']; // Recebe os produtos como um array de IDs e quantidades
        $total = $data['total'];

        // Inicia a transação
        $conn->begin_transaction();

        try {
            // Insere o pedido
            $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, total, data_pedido) VALUES (?, ?, NOW())");
            $stmt->bind_param('id', $usuario_id, $total);
            $stmt->execute();
            $pedido_id = $stmt->insert_id;

            // Insere os itens do pedido e calcula o total
            foreach ($produtos as $produto) {
                $produto_id = $produto['produto_id']; // Certifique-se de que está pegando o `produto_id` do produto
                $quantidade = $produto['quantidade'];

                // Obtém o preço do produto
                $preco = obterPrecoDoProduto($produto_id, $conn);
                if ($preco !== null) {
                    // Insere o item do pedido
                    $stmt = $conn->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param('iiid', $pedido_id, $produto_id, $quantidade, $preco);
                    
                    if (!$stmt->execute()) {
                        // Mensagem de erro se a inserção falhar
                        echo json_encode(['success' => false, 'message' => 'Erro ao inserir item do pedido: ' . $stmt->error]);
                        return; // Interrompe a execução se ocorrer um erro
                    }
                } else {
                    // Mensagem se o preço não for encontrado
                    echo json_encode(['success' => false, 'message' => 'Preço do produto não encontrado para o ID: ' . $produto_id]);
                    return; // Interrompe a execução se o preço não for encontrado
                }
            }

            // Comita a transação
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Pedido criado com sucesso!']);
        } catch (Exception $e) {
            // Em caso de erro, reverte a transação
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Erro ao criar pedido: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados de produtos ou total não fornecidos.']);
    }
}
?>
