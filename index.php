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

            <input type="radio" name="bt-radio" id="radio1">
            <input type="radio" name="bt-radio" id="radio2">
            <input type="radio" name="bt-radio" id="radio3">

            <div class="slide primeiro">
            <img src="images/teste2.jpg" alt="imagemdepublicidade">
                
            </div>

            <div class="slide segundo">
            <img src="images/teste1.jpg" alt="imagemdepublicidade">
            </div>
            
            <div class="slide terceiro">
                <img src="images/teste3.jpg" alt="imagemdepublicidade">
            </div>

            <nav class="navegacao-auto">
            
            <div class="bt-auton1"></div>
            <div class="bt-auton2"></div>
            <div class="bt-auton3"></div>
            
            </nav>

            <div class="navegacao-manual">

                <label for="radio1" class="bt-manual"></label>
                <label for="radio2" class="bt-manual"></label>
                <label for="radio3" class="bt-manual"></label>

            </div>

        </div>
        
    </section>
</main>
<script src="js/script.js"></script>
</body>

</html>