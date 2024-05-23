<?php

// Upload configs.
define('UPLOAD_DIR', 'uploads');
define('UPLOAD_MAX_FILE_SIZE', 10485760); // 10MB.
define('UPLOAD_ALLOWED_MIME_TYPES', 'image/jpeg,image/png,image/gif');

include 'functions.php';

$pdo = pdo_connect_mysql();

$productSaved = FALSE;

if (isset($_POST['submit'])) {
    // Read posted values.
    $productName = isset($_POST['name']) ? $_POST['name'] : '';
    $productQuantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
    $productDescription = isset($_POST['description']) ? $_POST['description'] : '';

    // Validate posted values.
    $errors = [];
    if (empty($productName)) {
        $errors[] = 'Please provide a product name.';
    }

    if ($productQuantity == 0) {
        $errors[] = 'Please provide the quantity.';
    }

    if (empty($productDescription)) {
        $errors[] = 'Please provide a description.';
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
                                $filenamesToSave[] = $uploadedFilePath;
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
                    description
                ) VALUES (
                    ?, ?, ?
                )';

        // Prepare the SQL statement for execution - ONLY ONCE.
        $statement = $pdo->prepare($sql);

        // Bind variables for the parameter markers (?) in the SQL statement that was passed to prepare().
        $statement->bindParam(1, $productName, PDO::PARAM_STR);
        $statement->bindParam(2, $productQuantity, PDO::PARAM_INT);
        $statement->bindParam(3, $productDescription, PDO::PARAM_STR);

        // Execute the prepared SQL statement.
        $statement->execute();

        // Read the id of the inserted product.
        $lastInsertId = $pdo->lastInsertId();

        // Close the prepared statement.
        $statement->closeCursor();

        // Save a record for each uploaded file.
        foreach ($filenamesToSave as $filename) {
            $sql = 'INSERT INTO products_images (
                        product_id,
                        filename
                    ) VALUES (
                        ?, ?
                    )';

            $statement = $pdo->prepare($sql);

            $statement->bindParam(1, $lastInsertId, PDO::PARAM_INT);
            $statement->bindParam(2, $filename, PDO::PARAM_STR);

            $statement->execute();

            $statement->closeCursor();
        }

        // Close the previously opened database connection.
        $pdo = null;

        $productSaved = TRUE;

        // Reset the posted values, so that the default ones are now showed in the form.
        $productName = $productQuantity = $productDescription = NULL;
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

    <title>Save product details</title>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js" type="text/javascript"></script>
    <style type="text/css">
        body {
            padding: 30px;
        }

        .form-container {
            margin-left: 80px;
        }

        .form-container .messages {
            margin-bottom: 15px;
        }

        .form-container input[type="text"],
        .form-container input[type="number"] {
            display: block;
            margin-bottom: 15px;
            width: 150px;
        }

        .form-container input[type="file"] {
            margin-bottom: 15px;
        }

        .form-container label {
            display: inline-block;
            float: left;
            width: 100px;
        }

        .form-container button {
            display: block;
            padding: 5px 10px;
            background-color: #8daf15;
            color: #fff;
            border: none;
        }

        .form-container .link-to-product-details {
            margin-top: 20px;
            display: inline-block;
        }
    </style>

</head>
<body>

    <div class="form-container">
        <h2>Add a product</h2>

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
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo isset($productName) ? $productName : ''; ?>">

            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" min="0" value="<?php echo isset($productQuantity) ? $productQuantity : '0'; ?>">

            <label for="description">Description</label>
            <input type="text" id="description" name="description" value="<?php echo isset($productDescription) ? $productDescription : ''; ?>">

            <label for="file">Images</label>
            <input type="file" id="file" name="file[]" multiple>

            <button type="submit" id="submit" name="submit" class="button">
                Submit
            </button>
        </form>

        <?php
        if ($productSaved) {
            ?>
            <a href="getProduct.php?id=<?php echo $lastInsertId; ?>" class="link-to-product-details">
                Click me to see the saved product details in <b>getProduct.php</b> (product id: <b><?php echo $lastInsertId; ?></b>)
            </a>
            <?php
        }
        ?>
    </div>

</body>
</html>
