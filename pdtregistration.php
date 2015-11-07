<?php
$page['menu'] = "lessenrooster";

include_once('config.php');
include_once('lib/GroupManager.php');
include_once('lib/TimeTableManager.php');
include_once('lib/PDTManager.php');
if ($config['maintenance'] == true){
    include('maintenance.php');
    die();
}

$pdtManager = new PDTManager($config);
$groupManager = new GroupManager($config);

$groups = $groupManager->getGroups();
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

        <title>EHB Rooster - Student (PDT)</title>

        <?php include('head.php'); ?>
    </head>
    <body>
        <?php include('header.php'); ?>
        
         <!-- Begin page content -->
        <div class="container">
            <div class="page-header">
                <a href="/"><img class="logo" src="images/logo.png"></img></a>
            </div>
            <p>Vul uw persoonlijke gegevens in. Deze worden gebruikt om uw vakken te bewaren.</p>
            <p class="lead">Persoonlijke gegevens:</p>
            <form action="pdt.php" method="post">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label for="inputFirstName">Voornaam</label>
                    <input name="firstname" type="text" class="form-control" id="inputFirstName" placeholder="Voornaam">
                </div>
                <div class="form-group">
                    <label for="inputSurname">Achternaam</label>
                    <input name="surname" type="text" class="form-control" id="inputSurname" placeholder="Achternaam">
                </div>
                <div class="form-group">
                    <label for="inputEmail">E-mail</label>
                    <input name="email" type="email" class="form-control" id="inputEmail" placeholder="voornaam.achternaam@student.ehb.be">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Vakken selecteren</button>
                </div>
            </form>
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>