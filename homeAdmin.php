<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: loginAdmin.php');
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
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
    </style>
    <link rel="stylesheet" href="css/style.css" class="">
</head>
<body>
    <a href="logoutAdmin.php" class="back-button">Logout</a>
    <div class="container">
        <a href="listProducts.php" class="home-button button1">Listar Produtos</a>
        <a href="addProduct.php" class="home-button button2">Adicionar Produto</a>
        <a href="editProduct.php" class="home-button button3">Editar Produto</a>
    </div>
</body>
</html>