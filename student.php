<?php
$page['menu'] = "lessenrooster";
include_once('config.php');
if ($config['maintenance'] == true){
    include('maintenance.php');
    die();
}
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

        <title>EHB Rooster - Student</title>

        <?php include('head.php'); ?>
    </head>
    <body>
        <?php include('header.php'); ?>
        
         <!-- Begin page content -->
        <div class="container">
            <div class="page-header">
                <a href="/"><img class="logo" src="images/logo.png"></img></a>
            </div>
            <p class="lead">Volgt u een modeltraject of persoonlijk deeltraject?</p>
            <a class="btn btn-default btn-lg" href="modeltraject.php">Modeltraject</a>
            <a class="btn btn-default btn-lg" href="pdtregistration.php">Persoonlijk deeltraject (PDT)</a>
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>