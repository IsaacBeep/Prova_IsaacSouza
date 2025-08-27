<?php

session_start();
require_once 'conexao.php';
require_once 'menu.php';

// VERFICIA SE O USUARIO DE ERMISSÃO DE ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// INICIALIZA As VARIAVEIS
$cliente = null;

// SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO NOME OU ID
if ($_SERVER["REQUEST_METHOD"] == "POST")
    if (!empty($_POST['busca_cliente'])) {
    $busca = trim($_POST['busca_cliente']);
    
    // VERIFICA SE É UM NÚMERO (ID) OU UM NOME
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);  
    }
    
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    // SE O USUARIO FOR ENCONTRADO, EXIBE UM ALERTA
    if (!$cliente) {
        echo "<script>alert('Cliente não encontrado.');window.location.href='buscar_cliente.php';</script>";
    }
} 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Cliente</title>
    <link rel="stylesheet" href="styles.css">
    <script src="validacoes.js"></script>

    <!-- CERTIFIQUE-SE DE QUE O JAVASCRIPT ESTA SENDO CARREGADO CORRETAMENTE -->
    <script src="scripts.js"></script>

</head>
<body>
    <h2>Alterar Cliente</h2>

    <!-- FORMULARIO PARA BUSCAR USUARIOS -->
    <form action="alterar_cliente.php" method="POST">
        <label for="busca_cliente">Buscar por ID ou Nome do cliente:</label>
        <input type="text" id="busca_cliente" name="busca_cliente" required onkeyup="buscarSugestoes()">

        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>
    </form>

    <?php if($cliente): ?>
        <form action="processa_alteracao_cliente.php" method="POST">
            <input type="hidden" name="id_cliente" value="<?=htmlspecialchars($cliente['id_cliente'])?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?=htmlspecialchars($cliente['nome_cliente'])?>" required onkeyup="validarNome()">

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" value="<?=htmlspecialchars($cliente['endereco'])?>" required onkeyup="validarEndereco()">

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?=htmlspecialchars($cliente['telefone'])?>" required onkeyup="validarTelefone()">

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?=htmlspecialchars($cliente['email'])?>" required onkeyup="validarEmail()">
            
            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>
        </form>
    <?php endif; ?>
    <a href="principal.php">Voltar</a>
</body>
</html>