<?php
$page['menu'] = "api";
?>
<html lang="nl">
    <head>
        <!-- Maxim Van de Wynckel (Maximvdw) -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="EHB Rooster">
        <meta name="author" content="Maxim Van de Wynckel">
        <link rel="icon" href="favicon.ico">

        <title>EHB Rooster - API</title>

        <?php include('head.php'); ?>
    </head>
    <body>
        <?php include('header.php'); ?>
        
         <!-- Begin page content -->
        <div class="container">
            <h1>EHBRooster.be JSON API</h1>
            <p>
            EHBRooster.be beschikt over een JSON REST API. De gegevens van deze API worden opgehaalt uit de lokaal gecachte lessenroosters
            die van de officiele lessenroosters worden opgevraagd.
            </p>
            <p>
            De gegevens worden gemiddeld om de 2 uur nagekeken op eventuele wijzigingen. Met de JSON API kan je d.m.v. GET requests deze
            gegevens opvragen alsook de gegevens over de laatste synchronisatie.
            </p>
            
            <h2>Departmenten</h2>
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>