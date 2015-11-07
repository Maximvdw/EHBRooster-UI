<?php
$page['menu'] = "about";
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

        <title>EHB Rooster - Terms of Service</title>

        <?php include('head.php'); ?>
    </head>
    <body>
        <?php include('header.php'); ?>
        
         <!-- Begin page content -->
        <div class="container">
            <h2>Terms of Service</h2>
            <p>
            Bij het gebruik van deze site ga je akkoord met volgende terms and conditions. Deze zijn van toepassing op zowel
            de site, geexporteerde kalenders en eventuele 3de partij uitbreidingen die gebruik maken van de API.
            </p>
            <ul>
                <li>EHBRooster.be haalt zijn gegevens niet rechtstreeks uit de officiele lessenroosters. Deze worden
                elk 2-3 uur afgehaalt via de officiele site voor de uurroosters.</li>
                <li>EHBRooster.be en eigenaar (Maxim Van de Wynckel) zijn niet verantwoordelijk voor eventuele fouten in de
                lessenroosters. Hoewel deze om de 2-3 uur worden gesynchronizeerd kan er een kans bestaan dat bepaalde
                gegevens foutief zijn.</li>
                <li>Voor PDT lessenroosters wordt er gevraagd naar een voornaam,achternaam en email adres. Deze zijn
                noodzakelijk om te zorgen dat uw persoonlijk rooster bewaard blijft en eventueel in de toekomst kan
                worden aangepast.</li>
                <li>Lessen worden voor studenten samengevoegd in het lessenrooster wanneer de naam van de les en begin tijd overeenkomen.
                Voor docenten worden lessen samengevoegd wanneer de lector van het vak en de begin tijd overeenkomen. Deze maatregel is
                toegepast omdat sommige vakken door meerdere docenten worden gegeven maar dit wel het zelfde vak blijft.</li>
                <li>Misbruik van de site (poging tot hacking, ...) kan leiden tot afscherming van het lessenrooster voor de
                desbetreffende gebruiker.</li>
                <li>Bij onderhoud aan het paneel zal bij een downtime langer dan 10 minuten dit worden aangekondigd op onze
                facebook pagina.</li>
            </ul>
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>