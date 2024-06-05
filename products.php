<?php
include 'inc/generalFunctions.php';
$pdo = pdo_connect_mysql();

// Cria o diretório downloads caso ele não exista
$downloads_dir = 'downloads';
if (!is_dir($downloads_dir)) {
    mkdir($downloads_dir, 0777, true);
}

// Função para baixar e salvar as imagens
function save_images($pdo, $product_id, $downloads_dir) {
    $stmt = $pdo->prepare('SELECT filename, image_blob FROM products_images WHERE product_id = ?');
    $stmt->execute([$product_id]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($images as $image) {
        $filename = $image['filename'];
        $file_path = $downloads_dir . '/' . $filename;
        if (!file_exists($file_path)) {
            $image_data = $image['image_blob'];
            if ($image_data) {
                file_put_contents($file_path, $image_data);
            } else {
                // Caso não tenha o BLOB, simula a presença do arquivo
                file_put_contents($file_path, ''); // Simulando a criação do arquivo.
            }
        }
    }
}

// Consulta SQL para buscar todos os produtos
$sql = "SELECT p.id, p.name, p.description, p.price, pi.filename AS image
        FROM products p
        LEFT JOIN products_images pi ON p.id = pi.product_id";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Baixa e salva as imagens de todos os produtos
foreach ($products as $product) {
    save_images($pdo, $product['id'], $downloads_dir);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/card.css">
    <link rel="shortcut icon" href="images/iconlogo.jpg">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7c1502f0ee.js" crossorigin="anonymous"></script>
</head>
<body class="principalprodutos">
<?=template_header()?>
    <div class="containerdecorrecao">
    <div class="container">
        <h1 style="text-align: center; font-size: 25px;">Lista de Produtos</h1>
        <div class="cards-container">
            <?php foreach ($products as $product): ?>
                <div class="card">
                    <div class="card-img">
                        <?php if ($product['image']): ?>
                            <img src="downloads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                        <?php else: ?>
                            <div class="placeholder-image">Imagem não disponível</div>
                        <?php endif; ?>
                    </div>
                    <div class="card-title"><?php echo htmlspecialchars($product['name']); ?></div>
                    <div class="card-subtitle"><?php echo htmlspecialchars($product['description']); ?></div>
                    <hr class="card-divider">
                    <div class="card-footer">
                        <div class="card-price"><span>R$</span> <?php echo htmlspecialchars($product['price']); ?></div>
                        <form action="cart.php" method="post" class="card-btn-form">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="quantity" value="1"> <!-- Sempre adiciona uma unidade -->
                            <button type="submit" name="add_to_cart" class="card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="m397.78 316h-205.13a15 15 0 0 1 -14.65-11.67l-34.54-150.48a15 15 0 0 1 14.62-18.36h274.27a15 15 0 0 1 14.65 18.36l-34.6 150.48a15 15 0 0 1 -14.62 11.67zm-193.19-30h181.25l27.67-120.48h-236.6z"></path><path d="m222 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z"></path><path d="m368.42 450a57.48 57.48 0 1 1 57.48-57.48 57.54 57.54 0 0 1 -57.48 57.48zm0-84.95a27.48 27.48 0 1 0 27.48 27.47 27.5 27.5 0 0 0 -27.48-27.47z"></path><path d="m158.08 165.49a15 15 0 0 1 -14.23-10.26l-25.71-77.23h-47.44a15 15 0 1 1 0-30h58.3a15 15 0 0 1 14.23 10.26l29.13 87.49a15 15 0 0 1 -14.23 19.74z"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    </div>
</body>
</html>
