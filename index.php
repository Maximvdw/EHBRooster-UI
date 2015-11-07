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

        <title>EHB Rooster</title>

        <?php include('head.php'); ?>
    </head>
    <body>
        <?php include('header.php'); ?>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=1410594242557995";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

         <!-- Begin page content -->
        <div class="container">
            <div class="page-header">
                <img class="logo" src="images/logo.png"></img>
            </div>
            <?php
            // Check if cookie present
            if (isset($_COOKIE['ROOSTER_URL']) && isset($_COOKIE['ROOSTER_NAME'])){
                
            ?>
            <div class="alert alert-info alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4>Lessenrooster <?=$_COOKIE['ROOSTER_NAME']?></h4>
                <p>Je hebt al eens een keertje een lessenrooster geselecteerd. Wil je deze openen?</p>
                <p>
                    <a href="<?=$_COOKIE['ROOSTER_URL']?>" class="btn btn-primary">Ga naar rooster</a>
                </p>
            </div>
            <?php
            }
            ?>
            <p class="disclaimer">EHBRooster.be is een gebruiksvriendelijk uurrooster gemaakt door Maxim Van de Wynckel.</p>
            <p class="disclaimer">Met deze wizard kan u uw persoonlijk uurrooster opstellen om het vervolgens te exporteren of bookmarken.</p>
            <p class="lead">Bent u een Student of Docent?</p>
            
            <a class="btn btn-default btn-lg" href="student.php">Student</a>
            <!-- Docenten: Use at your own risk -->
            <a class="btn btn-default btn-lg" href="docent.php">Docent</a>
            
        </div>
        <div class="fb-page" data-href="https://www.facebook.com/ehbrooster" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/ehbrooster"><a href="https://www.facebook.com/ehbrooster">EHBRooster.be</a></blockquote></div></div>
        <?php include('footer.php'); ?>
    </body>
</html>