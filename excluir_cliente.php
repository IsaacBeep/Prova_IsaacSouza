<?php
session_start();
require_once 'conexao.php';
require_once 'menu.php';

if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

$cliente = null;

//BUSCA TODOS OS USUARIOS CADASTRADOS EM ORDEM ALFABETICA
$sql = "SELECT * FROM cliente ORDER BY nome_cliente ASC";
$stmt = $pdo->prepare($sql);
$stmt-> execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);


//SE UM ID FOR PASSADO VIA GET, EXCLUI O USUARIO
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_cliente = $_GET['id'];

    //EXCLUI O USUARIO DO BANCO DE DADOS
    $sql = "DELETE FROM cliente WHERE id_cliente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT);

    if ($stmt->execute()){
        echo "<script>alert('Cliente excluído com sucesso!');window.location.href='excluir_cliente.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir o Cliente!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=], initial-scale=1.0">
    <title>Excluir Cliente</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
</head>
<body>
    <h2>Excluir Cliente</h2>
    <?php if (!empty($clientes)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Endereco</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>

            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?=htmlspecialchars($cliente['id_cliente']) ?></td>
                    <td><?=htmlspecialchars($cliente['nome_cliente']) ?></td>
                    <td><?=htmlspecialchars($cliente['endereco']) ?></td>
                    <td><?=htmlspecialchars($cliente['telefone']) ?></td>
                    <td><?=htmlspecialchars($cliente['email']) ?></td>
                    <td>
                        <a href="excluir_cliente.php?id=<?= htmlspecialchars($cliente['id_cliente']) ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum cliente encontrado.</p>
    <?php endif; ?>

    <div class="logout">
        <form action="logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
    </div>
    
    <a href="principal.php">Voltar</a>
</body>
</html>