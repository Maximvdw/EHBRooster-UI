<?php
$page['menu'] = "help";
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

        <title>EHB Rooster - Handleiding</title>

        <!-- Bootstrap core CSS -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/style.css" rel="stylesheet">
        
        <?php include_once("analyticstracking.php") ?>
    </head>
    <body>
        <?php include('header.php'); ?>
        
         <!-- Begin page content -->
        <div class="container">
            <h2>Student</h2>
            <p>
            Als student heb je de keuze om een modeltraject of persoonlijk deeltraject samen te stellen.
            Indien je een modeltraject volgt zonder vakken die weggevallen of bijgekomen zijn dan kan je op de knop <b>Modeltraject</b>
            drukken.
            </p>
            <h3>Modeltraject</h3>
            <p>
            Na het klikken op modeltraject krijg je een scherm waar je jouw modeltraject kan selecteren. Deze staan alfabetish geordend en
            hebben de naam die overeenkomt met "Groepen" uit het officiële lessenrooster.
            </p>
            <p>
            <i>
            Voorbeeld: Ik zit in mijn 3de jaar Bachelor Digi-X met specializatie Software Engineering. Dus kies ik "3BaDig-X-SWE". Indien je bijvoorbeeld
            in je eerste jaar Digi-X zit in groep B zal het "1BaTi_B" zijn.
            </i>
            </p>
            <h3>Persoonlijk deeltraject</h3>
            <p>
            Indien je een persoonlijk deeltraject volgt (PDT) moet je eerst je <b>Voornaam, naam, email</b> ingeven alvorens je je vakken selecteert.
            </p>
            <p>
            Nadat je op de knop <b>Verder</b> hebt gedrukt zal je naar het volgend scherm worden geleid waar je jou vakken kan selecteren.
            Net zoals bij modeltrajecten staan deze alfabetish geordend.
            </p>
            <p>
            Je kan meerdere vakken selecteren over verschillende modeltrajecten. Je kan duidelijk zien welk vak bij welk jaar hoort.
            </p>
            <hr>
            <h2>Abonneren</h2>
            <p>
            Met abonneren kan je een lessenrooster converteren of linken aan je persoonlijke agenda. Zo kan je de kalendar synchroniseren met Google Calendar, Outlook of
            een andere applicatie die ICS kalenders ondersteund.
            </p>
            <h3 id="manueel-office365">Manueel - Office 365</h3>
            <ol>
                <li>Ga naar: <a href="https://portal.office.com/Home">https://portal.office.com/Home</a></li>
                <li>Klik op `Calendar`</li>
                <li>Navigeer naar `Nieuw ... < Agenda Toevoegen < Van internet`</li>
                <img class="manual-image" src="http://i.mvdw-software.com/26-09-2015_20-23-25.png"></img>
                <li>Aan de zijkant opent zich een balk</li>
                <li>Open een nieuw tablad en ga naar jou agenda op EHBRooster</li>
                <li>Druk op `Exporteren`</li>
                <li>Selecteer `webcal:// Kalender (online)`</li>
                <li>Koppier de link naar je klembord</li>
                <img class="manual-image" src="http://i.mvdw-software.com/26-09-2015_20-44-19.png"></img>
                <li>Ga terug naar het Office365 tabblad</li>
                <li>Plak de link en geef je agenda een naam</li>
                <img class="manual-image" src="http://i.mvdw-software.com/26-09-2015_20-45-13.png"></img>
                <li>Druk op `Opslaan`</li>
                <li>Normaal zou binnen enkele minuten je rooster in je outlook moeten staan</li>
                <li>Het rooster word automatisch geupdate indien er iets wijzigt, al kan dit wel soms 24 uur duren</li>
                <img class="manual-image" src="http://i.mvdw-software.com/26-09-2015_20-55-31.png"></img>
            </ol>
            <hr>
            <h2>Docenten</h2>
            <p>
            Voor de docenten is er ook een knop voorzien die toelaat om de vakken te tonen die deze docent geeft. Aan docenten word gevraagd om zeker in het begin niet
            blindelings te vertrouwen op deze uurroosters. Deze worden opgesteld uit de vakken van alle groepen die zijn toegevoegd voor studenten. Aangezien een
            docent aan meerdere groepen les kan geven kan het dus zijn dat deze voor sommige docenten niet volledig zijn.
            </p>
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>