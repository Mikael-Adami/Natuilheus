<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Defina as credenciais do usuário
    $username = 'admin';
    $password = '123';

    // Verifique as credenciais
    if ($_POST['username'] == $username && $_POST['password'] == $password) {
        $_SESSION['loggedin'] = true;
        header('Location: ../index.php');
        exit;
    } else {
        $error = 'Credenciais inválidas!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../images/iconlogo.jpg">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://kit.fontawesome.com/7c1502f0ee.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"> 
</head>
<body class="loginadmin">
    <header class="headeradmin">
        <a href="index.php" style="letter-spacing: 1.5px;">NATU ILHÉUS</a>
    </header>
    <div class="containeradmin">
        <form class="formulariologinadmin" method="post" action="loginAdmin.php">
            <h2>Login</h2>
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button class="botaologinadmin" type="submit">Entrar</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
