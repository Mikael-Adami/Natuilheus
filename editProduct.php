<?php
// Upload configs.
define('UPLOAD_DIR', 'uploads');
define('UPLOAD_MAX_FILE_SIZE', 10485760); // 10MB.
define('UPLOAD_ALLOWED_MIME_TYPES', 'image/jpeg,image/png,image/gif');
include 'functions.php';

$pdo = pdo_connect_mysql();

// Initialize variables
$product = null;
$images = [];
$errors = [];
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Function to fetch product details and images by ID
function fetchProductDetails($pdo, $productId) {
    $sql = 'SELECT * FROM products WHERE id = ?';
    $statement = $pdo->prepare($sql);
    $statement->execute([$productId]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function fetchProductImages($pdo, $productId) {
    $sql = 'SELECT * FROM products_images WHERE product_id = ?';
    $statement = $pdo->prepare($sql);
    $statement->execute([$productId]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Handle form submission for updating product details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    $productName = isset($_POST['name']) ? $_POST['name'] : '';
    $productQuantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $productDescription = isset($_POST['description']) ? $_POST['description'] : '';

    if (!$productId || !is_numeric($productId)) {
        $errors[] = 'Invalid product ID.';
    } else {
        // Fetch current product details to compare
        $currentProduct = fetchProductDetails($pdo, $productId);

        // Update product details in the database
        if ($currentProduct['name'] !== $productName || $currentProduct['quantity'] !== $productQuantity || $currentProduct['description'] !== $productDescription) {
            $sql = 'UPDATE products SET name = ?, quantity = ?, description = ? WHERE id = ?';
            $statement = $pdo->prepare($sql);
            $statement->execute([$productName, $productQuantity, $productDescription, $productId]);

            if ($statement->rowCount() === 0) {
                $errors[] = 'Failed to update product details. Debug info: ' . json_encode($statement->errorInfo());
            } else {
                $updated = true;
            }
        } else {
            $updated = true;
        }

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Sanitize file name
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

            // Check if file type is allowed
            $allowedMimeTypes = explode(',', UPLOAD_ALLOWED_MIME_TYPES);
            if (in_array($fileType, $allowedMimeTypes)) {
                // Directory to save the uploaded file
                $uploadFileDir = UPLOAD_DIR . '/';
                $dest_path = $uploadFileDir . $newFileName;

                // Remove existing image if any
                $images = fetchProductImages($pdo, $productId);
                foreach ($images as $image) {
                    $imagePath = UPLOAD_DIR . '/' . $image['filename'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $sql = 'DELETE FROM products_images WHERE product_id = ?';
                $statement = $pdo->prepare($sql);
                $statement->execute([$productId]);

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Insert new image info into database
                    $sql = 'INSERT INTO products_images (product_id, filename) VALUES (?, ?)';
                    $statement = $pdo->prepare($sql);
                    $statement->execute([$productId, $newFileName]);
                } else {
                    $errors[] = 'There was an error moving the uploaded file.';
                }
            } else {
                $errors[] = 'Upload failed. Allowed file types: ' . UPLOAD_ALLOWED_MIME_TYPES;
            }
        }

        // Redirect to updated product details page if no errors
        if (empty($errors) && isset($updated)) {
            header("Location: {$_SERVER['PHP_SELF']}?id=$productId");
            exit();
        }
    }
}

// Handle product deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    if ($productId && is_numeric($productId)) {
        // Delete product images from server
        $images = fetchProductImages($pdo, $productId);
        foreach ($images as $image) {
            $imagePath = UPLOAD_DIR . '/' . $image['filename'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        // Delete product record from database
        $sql = 'DELETE FROM products WHERE id = ?';
        $statement = $pdo->prepare($sql);
        $statement->execute([$productId]);
        // Delete product images records from database
        $sql = 'DELETE FROM products_images WHERE product_id = ?';
        $statement = $pdo->prepare($sql);
        $statement->execute([$productId]);
        // Redirect to product listing page or any other page
        header("Location: listProducts.php");
        exit();
    } else {
        $errors[] = 'Invalid product ID.';
    }
}

// Fetch product details and images based on ID or search
if (!empty($search) && is_numeric($search)) {
    // Fetch by ID
    $productId = $search;
    $product = fetchProductDetails($pdo, $productId);
    if ($product) {
        $images = fetchProductImages($pdo, $productId);
    } else {
        $errors[] = 'No product found with the provided ID.';
    }
}

$pdo = null;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
    <meta charset="UTF-8" />
    <title>Product details</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/style.css" class="">
</head>
<body style="padding: 30px;">
<div class="page-container">
    <h2>Product details</h2>

    <?php
    // Display errors
    if (!empty($errors)) {
        echo '<div class="error-message">' . implode('<br>', $errors) . '</div>';
    }

    // Display product details if found
    if ($product) {
        ?>
        <!-- Product Details -->
        <div class="product-details">
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <table>
                    <tr>
                        <td class="label">Name</td>
                        <td><input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>"></td>
                    </tr>
                    <tr>
                        <td class="label">Quantity</td>
                        <td><input type="text" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>"></td>
                    </tr>
                    <tr>
                        <td class="label">Description</td>
                        <td><textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea></td>
                    </tr>
                    <tr>
                        <td class="label">Image</td>
                        <td><input type="file" name="image"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="update_product" value="Update Product">
                            <input type="submit" name="delete_product" value="Delete Product" onclick="return confirm('Are you sure you want to delete this product?');" style="background-color: #ff3333; border-color: #ff3333;">
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <?php
        // Display images if available
        if (!empty($images)) {
            ?>
            <!-- Product Images -->
            <div class="product-images">
                <?php foreach ($images as $image): ?>
                    <?php
                    $imageFilename = htmlspecialchars($image['filename']);
                    $imagePath = strpos($imageFilename, UPLOAD_DIR) === 0 ? $imageFilename : UPLOAD_DIR . '/' . $imageFilename;
                    ?>
                    <?php if (file_exists($imagePath)): ?>
                        <img src="<?php echo $imagePath; ?>" alt="Product Image">
                    <?php else: ?>
                        <p>Image not found: <?php echo $imagePath; ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php
        } else {
            // No images found message
            ?>
            <div class="product-images">
                <p>No images available for this product.</p>
            </div>
            <?php
        }
    } else {
        // No product found message or search form
        ?>
        <div class="search-form">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by product ID" value="<?php echo htmlspecialchars($search); ?>">
                <input type="submit" value="Search">
            </form>
        </div>
        <?php
        if (!empty($search) && !is_numeric($search)) {
            // Display error for invalid search input
            echo '<div class="error-message">Invalid search. Please provide a valid product ID.</div>';
        }
    }
    ?>
</div>
</body>
</html>