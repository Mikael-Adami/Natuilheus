<?php

include 'generalFunctions.php';
$pdo = pdo_connect_mysql();

// Consulta SQL para buscar todos os produtos
$sql = "SELECT p.id, p.name, p.weight, p.price, pi.filename AS image
        FROM products p
        LEFT JOIN products_images pi ON p.id = pi.product_id";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="../css/card.css">
    <script src="https://kit.fontawesome.com/7c1502f0ee.js" crossorigin="anonymous"></script>
<body>
    <div class="container">
        <h1>Lista de Produtos</h1>
        <div class="cards-container">
            <?php foreach ($products as $product): ?>
                <div class="card">
                    <div class="card-img">
                        <?php if ($product['image']): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                        <?php else: ?>
                            <div class="placeholder-image">Imagem não disponível</div>
                        <?php endif; ?>
                    </div>
                    <div class="card-title"><?php echo htmlspecialchars($product['name']); ?></div>
                    <div class="card-subtitle"><?php echo htmlspecialchars($product['weight']); ?></div>
                    <hr class="card-divider">
                    <div class="card-footer">
                        <div class="card-price"><span>R$</span> <?php echo htmlspecialchars($product['price']); ?></div>
                        <a href="editProduct.php?id=<?php echo $product['id']; ?>" class="card-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="m397.78 316h-205.13a15 15 0 0 1 -14.65-11.67l-34.54-150.48a15 15 0 0 1 14.62-18.36h274.27a15 15 0 0 1 14.65 18.36l-34.6 150.48a15 15 0 0 1 -14.62 11.67zm-193.19-30h181.25l27.67-120.48h-236.6z"></path><path d="m222 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z"></path><path d="m368.42 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z"></path><path d="m158.08 165.49a15 15 0 0 1 -14.23-10.26l-25.71-77.23h-47.44a15 15 0 1 1 0-30h58.3a15 15 0 0 1 14.23 10.26l29.13 87.49a15 15 0 0 1 -14.23 19.74z"></path></svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>




