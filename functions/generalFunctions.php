<?php

//conectar a database
function pdo_connect_mysql() {
    // Update the details below with your MySQL details
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'shoppingcart';
    try {
    	return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	// If there is an error with the connection, stop the script and display the error.
    	exit('Falha ao conectar a database!');
    }
}

//template_header
function template_header() {
    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <body>
            <header>
            <div class="information">
            <div>
                <span class="material-icons">call</span>
                <span style="letter-spacing: 1px;" >(73)999984706</span>
            </div>
            <div>
                <span class="material-icons">mail</span>
                <span style="letter-spacing: 1px;" >NATUILHEUS@GMAIL.COM</span>
            </div>
            <div>
                <span class="material-icons">local_shipping</span>
                <span style="letter-spacing: 1px;">ENTREGAMOS EM TODO PAÍS</span>
            </div>
        </div>
        <nav class="header">
            <a href="index.php" style="letter-spacing: 1.5px;">NATU ILHÉUS</a>
            <ul class="paginas">
                <li>
                    <a href="about.php">Sobre</a>
                </li>
                <li>
                    <a href="#products">Produtos</a>
                </li>
                <li>
                    <a href="contact.php">Contato</a>
                </li>
                <li>
                    <a href="cart.php"><span class="material-icons">shopping_cart</span>[0]</a>
                </li>
            </ul>
        </nav>
            </header>
            <main>
    EOT;
    }

//footer template
function template_footer(){
    $year = date('Y');
    echo <<<EOT
        <footer>
            <div class="information">
                <p>&copy; $year, Natu Ilheus</p>
            </div>
        </footer>
        <script src="js/script.js"></script>
    </body>
    </html>
EOT;
}
?>

 