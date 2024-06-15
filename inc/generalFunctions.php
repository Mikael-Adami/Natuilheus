<?php

//conectar a database
function pdo_connect_mysql() {
    // Update the details below with your MySQL details
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = 'root';
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
                    <a href="../index.php#about">Sobre</a>
                </li>
                <li>
                    <a href="products.php">Produtos</a>
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
    <div class="rodape1c">
        <img src="images/logonobg.svg" alt="logo-natuilheus">
    </div>
    <div class="rodape2c">
        <h2>Contato</h2>
     
        <div>
            <span class="material-icons">phone</span>
            <span style="letter-spacing: 0.2px;margin-left:0.75%;">(73)999984706</span>
        </div>
        <div>
            <span class="material-icons">email</span>
            <span style="letter-spacing: 0.5px; margin-left:1%;">natuilheus@gmail.com</span>
        </div>
    </div>
    <div class="rodape3c">
    <div class="rodapesuperior">
        <h2>Siga-nos</h2>
            <div style="margin-bottom: 5px;">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/>
                </svg>
                <svg class="w-[24px] h-[24px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path fill="currentColor" fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd"/>
                </svg>

                <svg class="w-[24px] h-[24px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z" clip-rule="evenodd"/>
                    <path fill="currentColor" d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z"/>
                </svg>

            </div> 
        </div>
        <address class="digitalize">
        Feito por <a class="l1" href="mailto:digitalizesistemas@gmail.com">Digitalize Sistemas</a>
        </address>
    </div>
</footer>
EOT;
}
?>

 