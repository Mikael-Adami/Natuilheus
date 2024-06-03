<?php

// Upload configs.
define('UPLOAD_DIR', '../uploads');
define('UPLOAD_MAX_FILE_SIZE', 10485760); // 10MB.
define('UPLOAD_ALLOWED_MIME_TYPES', 'image/jpeg,image/png,image/gif');

include 'generalFunctions.php';

$pdo = pdo_connect_mysql();

$productSaved = FALSE;

if (isset($_POST['submit'])) {
    // Read posted values.
    $productName = isset($_POST['name']) ? $_POST['name'] : '';
    $productQuantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
    $productDescription = isset($_POST['description']) ? $_POST['description'] : '';
    $productPrice = isset($_POST['price']) ? $_POST['price'] : 0;
    $productWeight = isset($_POST['weight']) ? $_POST['weight'] : '';
    
    // Validate posted values.
    $errors = [];
    if (empty($productName)) {
        $errors[] = 'Insira um nome para o produto.';
    }

    if ($productQuantity == 0) {
        $errors[] = 'Insira a quantidade em unidades do produto.';
    }

    if (empty($productDescription)) {
        $errors[] = 'Insira a descrição do produto.';
    }

    if (empty($productPrice)) {
        $errors[] = 'Insira o preço do produto em R$.';
    }

    if (empty($productWeight)) {
        $errors[] = 'Insira o peso do produto.';
    }

    // Create "uploads" directory if it doesn't exist.
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    // List of file names to be filled in by the upload script below and to be saved in the db table "products_images" afterwards.
    $filenamesToSave = [];

    $allowedMimeTypes = explode(',', UPLOAD_ALLOWED_MIME_TYPES);

    // Upload files.
    if (!empty($_FILES)) {
        if (isset($_FILES['file']['error'])) {
            foreach ($_FILES['file']['error'] as $uploadedFileKey => $uploadedFileError) {
                if ($uploadedFileError === UPLOAD_ERR_NO_FILE) {
                    $errors[] = 'You did not provide any files.';
                } elseif ($uploadedFileError === UPLOAD_ERR_OK) {
                    $uploadedFileName = basename($_FILES['file']['name'][$uploadedFileKey]);

                    if ($_FILES['file']['size'][$uploadedFileKey] <= UPLOAD_MAX_FILE_SIZE) {
                        $uploadedFileType = $_FILES['file']['type'][$uploadedFileKey];
                        $uploadedFileTempName = $_FILES['file']['tmp_name'][$uploadedFileKey];

                        $uploadedFilePath = rtrim(UPLOAD_DIR, '/') . '/' . $uploadedFileName;

                        if (in_array($uploadedFileType, $allowedMimeTypes)) {
                            if (!move_uploaded_file($uploadedFileTempName, $uploadedFilePath)) {
                                $errors[] = 'The file "' . $uploadedFileName . '" could not be uploaded.';
                            } else {
                                // Read file data to save as BLOB
                                $imageBlob = file_get_contents($uploadedFilePath);

                                // Save filename and BLOB to array for batch insert
                                $filenamesToSave[] = [
                                    'filename' => $uploadedFileName,
                                    'image_blob' => $imageBlob
                                ];
                            }
                        } else {
                            $errors[] = 'The extension of the file "' . $uploadedFileName . '" is not valid. Allowed extensions: JPG, JPEG, PNG, or GIF.';
                        }
                    } else {
                        $errors[] = 'The size of the file "' . $uploadedFileName . '" must be of max. ' . (UPLOAD_MAX_FILE_SIZE / 1024) . ' KB';
                    }
                }
            }
        }
    }

    // Save product and images.
    if (empty($errors)) {
        // The SQL statement to be prepared.
        $sql = 'INSERT INTO products (
                    name,
                    quantity,
                    description,
                    price,
                    weight
                ) VALUES (
                    ?, ?, ?, ?, ?
                )';

        // Prepare the SQL statement for execution - ONLY ONCE.
        $statement = $pdo->prepare($sql);

        // Bind variables for the parameter markers (?) in the SQL statement that was passed to prepare().
        $statement->bindParam(1, $productName, PDO::PARAM_STR);
        $statement->bindParam(2, $productQuantity, PDO::PARAM_INT);
        $statement->bindParam(3, $productDescription, PDO::PARAM_STR);
        $statement->bindParam(4, $productPrice, PDO::PARAM_STR);
        $statement->bindParam(5, $productWeight, PDO::PARAM_STR);

        // Execute the prepared SQL statement.
        $statement->execute();

        // Read the id of the inserted product.
        $lastInsertId = $pdo->lastInsertId();

        // Close the prepared statement.
        $statement->closeCursor();

        // Save images as BLOBs in products_images table.
        foreach ($filenamesToSave as $file) {
            $sql = 'INSERT INTO products_images (
                        product_id,
                        filename,
                        image_blob
                    ) VALUES (
                        ?, ?, ?
                    )';

            $statement = $pdo->prepare($sql);

            $statement->bindParam(1, $lastInsertId, PDO::PARAM_INT);
            $statement->bindParam(2, $file['filename'], PDO::PARAM_STR);
            $statement->bindParam(3, $file['image_blob'], PDO::PARAM_LOB); // Use PARAM_LOB for BLOBs

            $statement->execute();

            $statement->closeCursor();
        }

        // Close the previously opened database connection.
        $pdo = null;

        $productSaved = TRUE;

        // Reset the posted values, so that the default ones are now showed in the form.
        $productName = $productQuantity = $productDescription = $productPrice = $productWeight = NULL;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
    <meta charset="UTF-8" />
    <!-- The above 3 meta tags must come first in the head -->

    <title>Salvar informações do produto</title>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../css/style.css" class="">
</head>
<body class="addprincipal">
    <header class="headeradmin">
        <a href="index.php" style="letter-spacing: 1.5px;">NATU ILHÉUS</a>
        <a href="../index.php" class="inpageback-button">Voltar</a>
    </header>
    <div class="form-container">
        <h2>Adicionar produto</h2>

        <div class="messages">
            <?php
            if (isset($errors)) {
                echo implode('<br/>', $errors);
            } elseif ($productSaved) {
                echo 'The product details were successfully saved.';
            }
            ?>
        </div>

        <form action="addProduct.php" method="post" enctype="multipart/form-data">
            <label for="name">Nome</label>
            <input type="text" id="name" name="name" value="<?php echo isset($productName) ? $productName : ''; ?>">

            <label for="quantity">Quantidade</label>
            <input type="number" id="quantity" name="quantity" min="0" value="<?php echo isset($productQuantity) ? $productQuantity : '0'; ?>">

            <label for="description">Descrição</label>
            <input type="text" id="description" name="description" value="<?php echo isset($productDescription) ? $productDescription : ''; ?>">

            <label for="price">Preço (em R$)</label>
            <input type="text" id="price" name="price" value="<?php echo isset($productPrice) ? $productPrice : ''; ?>">

            <label for="weight">Peso</label>
            <input type="text" id="weight" name="weight" value="<?php echo isset($productWeight) ? $productWeight : ''; ?>">

            <label for="file">Imagem</label>
            <input type="file" id="file" name="file[]" multiple>

            <button type="submit" id="submit" name="submit" class="button">
                Enviar
            </button>
        </form>

        <?php
        if ($productSaved) {
            ?>
            <a href="editProduct.php?search=<?php echo $lastInsertId; ?>" class="link-to-product-details">
                Click me to see the saved product details in <b>editProduct.php</b> (product id: <b><?php echo $lastInsertId; ?></b>)
            </a>
            <?php
        }
        ?>
    </div>

</body>
</html>