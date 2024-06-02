<?php
session_start();
include 'inc/generalFunctions.php';
$pdo = pdo_connect_mysql();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Natu Ilhéus</title>
    <link rel="shortcut icon" href="images/iconlogo.jpg">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://kit.fontawesome.com/7c1502f0ee.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
<?php
    include('inc/header.php');
?>
<main>

    <section class="principal">
        <div class="textocontainer1">
            <h1>TRAZENDO O MELHOR PARA VOCÊ</h1>
            <p><a href="#products">Ver Produtos</a></p>
        </div>
    </section>

    <section class="publicidade">

        <div class="slider">

            <div class="slide fade" style="display: flex;">
            <img src="images/teste2.jpg" alt="imagemdepublicidade" style="width: 100%;">
                
            </div>

            <div class="slide fade">
            <img src="images/teste1.jpg" alt="imagemdepublicidade" style="width: 100%;">
            </div>
            
            <div class="slide fade">
                <img src="images/teste3.jpg" alt="imagemdepublicidade" style="width: 100%;">
            </div>

            <div class="navegacao-manual">

                <span class="bt-manual active" onclick="slideAtual(1)"></span>
                <span class="bt-manual" onclick="slideAtual(2)"></span>
                <span class="bt-manual" onclick="slideAtual(3)"></span>

            </div>

        </div>
        
    </section>

    <section id="about" class="sobre">
        <h1>Sobre Nós</h1>
        <p>To expand Paleonola’s reach throughout the US, they partnered with Cuker for digital marketing. Our goal was to grow national brand awareness, boost market share, and increase household penetration utilizing digital platforms. We also needed to support sales through the natural foods channel and increase end distribution points.</p>
        <div class="textosobre">
            <div class="arrumacaosobre">
                <h3>Nosso Desejo</h3>
                <p>
                We launched an integrated digital marketing campaign, incorporating a strategic marketing calendar to keep up with competition online. We utilized social, search and an effective email marketing strategy to grow awareness and increase engagement for Paleonola. We helped develop an aspirational lifestyle around the Paleonola brand. By exposing the brand to a new audience, we were able to drive trial and increase online and in-store sales.
                </p>
            </div>
            <img src="images/ceo.jpg" style="width:25%; height:100%;border-radius: 5%;">
        </div>
    </section>
    <?php
        include('inc/footer.php');
    ?>
</main>
<script src="js/script.js"></script>
</body>

</html>