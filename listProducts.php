<?php include 'functions.php';
$pdo = pdo_connect_mysql(); // Fetch all products
$sql = "SELECT * FROM products";
$statement = $pdo->query($sql);
$products = $statement->fetchAll(PDO::FETCH_ASSOC);
// Close connection
$pdo = null;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Lista de Produtos</title>
<link rel="stylesheet" href="css/style.css" class="">
</head>
<body>
<a href="homeAdmin.php" class="back-button">Voltar</a>
<div class="page-container">
  <h2>Lista de Produtos</h2>
  <?php if (empty($products)): ?>
    <p>Nenhum produto encontrado.</p>
  <?php else: ?>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Quantidade</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Peso</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product): ?>
            <tr>
              <td><?php echo $product['id']; ?></td>
              <td><?php echo htmlspecialchars($product['name']); ?></td>
              <td><?php echo $product['quantity']; ?></td>
              <td><?php echo htmlspecialchars($product['description']); ?></td>
              <td>R$<?php echo $product['price']; ?></td>
              <td><?php echo htmlspecialchars($product['weight']); ?></td>
              <td>
                <a href="editProduct.php?search=<?php echo $product['id']; ?>">Editar</a>
                </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
