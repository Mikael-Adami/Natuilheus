<?php
session_start();

// Incluir arquivo de conexão e funções gerais
include 'inc/generalFunctions.php';
$pdo = pdo_connect_mysql();

// Função para adicionar produto ao carrinho
function add_to_cart($product_id, $quantity) {
    global $pdo;
    // Buscar informações do produto no banco de dados
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se o produto existe no banco de dados
    if ($product && $quantity > 0) {
        // Inicializar ou atualizar o carrinho na sessão
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }
    }
}

// Processar adição de produto ao carrinho se o formulário for submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    add_to_cart($product_id, 1); // Adiciona 1 unidade por padrão
    header('Location: cart.php');
    exit;
}

// Remover produto do carrinho se a ação for 'remove'
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: cart.php');
    exit;
}

// Atualizar quantidades no carrinho se o formulário de atualização for submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'quantity-') !== false && is_numeric($value)) {
            $product_id = str_replace('quantity-', '', $key);
            $quantity = intval($value);
            if (isset($_SESSION['cart'][$product_id]) && $quantity > 0) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } elseif (isset($_SESSION['cart'][$product_id]) && $quantity == 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
    header('Location: cart.php');
    exit;
}

// Função para buscar imagem do produto
function get_product_image($product_id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT filename FROM products_images WHERE product_id = ? LIMIT 1');
    $stmt->execute([$product_id]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);
    return $image ? 'downloads/' . $image['filename'] : 'images/placeholder.png';
}

// Calcular o total do carrinho
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}


?>
<div class="cart content-wrapper">
    <h1>Carrinho de Compras</h1>
    <form action="cart.php" method="post">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Produto</td>
                    <td>Preço</td>
                    <td>Quantidade</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($_SESSION['cart'])): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Seu carrinho está vazio</td>
                </tr>
                <?php else: ?>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td class="img">
                        <img src="<?php echo get_product_image($item['id']); ?>" alt="Product Image" style="width:50px; height:50px;">
                    </td>
                    <td>
                        <?=$item['name']?>
                        <br>
                        <a href="cart.php?action=remove&id=<?=$item['id']?>" class="remove">Remover</a>
                    </td>
                    <td class="price">R$<?=$item['price']?></td>
                    <td class="quantity">
                        <input type="number" name="quantity-<?=$item['id']?>" value="<?=$item['quantity']?>" min="1" max="99" required>
                    </td>
                    <td class="price">R$<?=$item['price'] * $item['quantity']?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Subtotal</span>
            <span class="price">R$<?=$total?></span>
        </div>
        <div class="buttons">
            <input type="submit" value="Atualizar Carrinho" name="update">
        </div>
    </form>
</div>
