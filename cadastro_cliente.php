<?php
session_start();
require_once 'conexao.php';
require_once 'menu.php';

if ($_SESSION['perfil']!= 1){
    echo "Acesso Negado!";
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $sql = "INSERT INTO cliente(nome_cliente, endereco, telefone, email) VALUES(:nome_cliente, :endereco, :telefone, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_cliente', $nome);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);

    if($stmt->execute()){
        echo "<script>alert('Cliente cadastrado com sucesso!')</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar o cliente!')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
    <script src="validacoes.js"></script>
</head>
<body>
    <h2>Cadastrar Cliente</h2>
    <form action="cadastro_cliente.php" method="POST">
        <label for="nome">Nome: </label>
        <input type="text" id="nome" name="nome" required onkeyup="validarNome()">

        <label for="endereco">Endereço: </label>
        <input type="text" id="endereco" name="endereco" required >

        <label for="telefone">Telefone: </label>
        <input type="text" id="telefone" name="telefone" required onkeyup="validarTelefone()">

        <label for="email">E-mail: </label>
        <input type="email" id="email" name="email" required >

        </select>
        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>
    </form>

    <div class="logout">
        <form action="logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
    </div>

    <a href="principal.php">Voltar</a>
</body>
</html>