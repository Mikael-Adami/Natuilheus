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
</head>
<body>
    <form method="post" action="loginAdmin.php">
        <h2>Login</h2>
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
