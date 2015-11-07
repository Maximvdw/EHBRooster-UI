<?php
$page['menu'] = "lessenrooster";
include_once('config.php');
include_once('lib/SubjectManager.php');

if ($config['maintenance'] == true){
    include('maintenance.php');
    die();
}

$subjectManager = new SubjectManager($config);

$lectors = $subjectManager->getLectors();
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
            <div class="alert alert-info">
                <p>
                    Op basis van alle vakken van alle opleidingen zal uw persoonlijk lessenrooster worden opgesteld voor de
                    vakken waar uw les aan geeft.
                </p>
            </div>
            <p class="lead">Selecteer uzelf in de lijst:</p>
            <form action="rooster.php" method="get">
                <div class="input-group form-group-lg">
                    <input type="hidden" name="type" value="lector">
                    <select onchange="$('#submitBtn').removeAttr('disabled')" name="id" class="form-control">
                        <option value="" disabled selected>Selecteer uzelf ...</option>
                        <?php
                        for ($i = 0 ; $i < sizeof($lectors); $i++){
                            $lector = $lectors[$i];
                        ?>
                        <option value="<?=$lector['lector']?>"><?=$lector['lector']?></option>
                        <?php
                        }
                        ?>
                    </select>
                   <span class="input-group-btn">
                        <button id="submitBtn" type="submit" class="btn-lg btn btn-success" disabled>Verder</button>
                   </span>
                </div>
            </form>
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>