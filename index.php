<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: functions/loginAdmin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style>

    </style>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="principal">
    <header class="headeradmin">
        <a href="index.php" style="letter-spacing: 1.5px;">NATU ILHÉUS</a>
    </header>
    <div class="container">
            <a href="functions/listProducts.php" class="home-button button1">Listar Produtos</a>
            <a href="functions/addProduct.php" class="home-button button2">Adicionar Produto</a>
            <a href="functions/editProduct.php" class="home-button button3">Editar Produto</a>
            <a href="functions/logoutAdmin.php" class="back-button">Sair</a>
    </div>
</body>
</html>