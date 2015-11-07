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

        <title>EHB Rooster - Over</title>

        <?php include('head.php'); ?>
    </head>
    <body>
        <?php include('header.php'); ?>
        
         <!-- Begin page content -->
        <div class="container">
            <h2>Over EHBRooster.be</h2>
            <p>
            EHBRooster.be werd gemaakt op 23 September 2015 nadat bekend werd dat Erasmus Hogeschool Brussel was overgestapt naar 
            SPlus om de lessenroosters samen te stellen.
            </p>
            <p>
            Wegens de incompatibiliteit met iOS devices, slechte performantie, geen mogelijkheid tot bookmarking, geen mogelijkheid tot exporteren en bovendien
            een slechte lay-out voor de uurroosters heb ik besloten deze site op te richten.
            </p>
            <h2>Over mezelf</h2>
            <p>
            Mijn naam is Maxim Van de Wynckel (Maximvdw). Tot eind van mijn 2de jaar Digi-X aan de Erasmus Hogeschool Brussel gebruikte ik de tool
            'Vub2GCal' om mijn lessenrooster te bekijken. Helaas kon dit niet meer na de overstap naar hun eigen SPlus systeem dus heb ik wat
            tijd gestoken om deze converter te maken zodat ik later geen tijd verlies om mijn lessenrooster raad te plegen.
            </p>
            <h2>For the geeks</h2>
            <p>
            De huidige front-end van het Erasmus lessenrooster laat de wensen over:
            </p>
            <ul>
                <li>Het is traag (zowel bij navigatie als het laden van de roosters)</li>
                <li>Het bevat fouten (sommige opleidingen laten niet alle vakken zien)</li>
                <li>Het bevat schrijffouten (Gegevens en namen van vakken worden vaak verkeerd geschreven)</li>
                <li>Het is onduidelijk met veel knoppen ,selecties ,...</li>
                <li>Het heeft veel doorverwijzingen (soms wel 35+ page loads in de achtergrond)<li>
                <li>Het is niet data friendly (+- 3MB om te navigeren)</li>
                <li>Geen export mogelijkheden</li>
                <li>Slechte lay out voor lessenroosters</li>
                <li>Onnodige gegevens in de voorgrond</li>
                <li>Geen mogelijkheid tot het bookmarken van lessenroosters</li>
                <li>Cookie gebaseerde navigatie</li>
            </ul>
            <p>
            EHBRooster.be werkt in twee delen:
            </p>
            <ul>
                <li>Deamon (Java)</li>
                <li>Front end (HTML,PHP)</li>
            </ul>
            <h3>Deamon</h3>
            <p>
            De deamon die geschreven is in Java zal eerst alle modeltrajecten en vervolgens de vakken van deze trajecten opvragen.
            Vervolgens gaat hij per vak/per week de lessenroosters ophalen.
            </p>
            <p>
            Doordat SPlus met een cookie gebaseerde navigatie werkt en er steeds tokens van de vorige pagina moeten worden meegegeven is
            dit proces intensiever dan voordien toen de lessenroosters nog bij de VUB stonden.
            </p>
            <p>
            Nadat de timetable is gevuld met nieuwe gegevens worden deze gecontrolleerd tegenover de huidige database. Indien er wijzigingen zijn worden
            deze aangepast aan de main database.
            </p>
            <p>
            Er werd door mij gekozen om Java gebruiken voor de deamon zodat we toegang hadden tot een sterke programmeer taal die bovendien beschikte over
            een makkelijk te gebruiken Entity Framework om de conversie naar de database te vergemakkelijken.
            </p>
            <a class="btn btn-lg btn-info" href="status.php"><span class="glyphicon glyphicon-heart"></span> Deamon status</a>
            <br>
            <h3>Front end</h3>
            <p>De front end is wat je nu ziet. Deze is geschreven in PHP, HTML (en CSS,JS). Deze haalt alle gegevens van de lessenroosters op uit de MySQL
            database waar de deamon alles naartoe saved.
            </p>
            <p>
            Ook zal de front end de lessenroosters exporteren en PDT schema's opstellen.
            </p>
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>