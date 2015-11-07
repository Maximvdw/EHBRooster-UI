<?php
$page['menu'] = "";
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

        <title>EHB Rooster - Changelog</title>

        <?php include('head.php'); ?>
    </head>
    <body>
        <?php include('header.php'); ?>
        
         <!-- Begin page content -->
        <div class="container">
            <h1>Changelog voor EHBRooster</h1>
            <h2>v1.0.0 (07/11/2015)</h2>
            <ol>
                <li>Opleiding: Office Management toegevoegd</li>
                <li>Stabiliteitswijzigingen en daemon memory leak fix</li>
            </ol>
            <hr>
            <h2>v0.7.0 BETA (21/10/2015)</h2>
            <ol>
                <li>Opleiding: Idea & Innovation Management toegevoegd</li>
                <li>Opleiding: Journalistiek toegevoegd</li>
                <li>Opleiding: Verpleegkunde toegevoegd</li>
                <li>Opleiding: Hotelmanagement toegevoegd</li>
                <li>Aanpassing PDT registratie</li>
                <li>Synchronisaties worden nog steeds 1 uur van elkaar uitgevoerd, maar door de toenemende duurtijd kan het nu 2 uur zijn tussen werkelijke aanpassingen.</li>
            </ol>
            <hr>
            <h2>v0.6.0 BETA (14/10/2015)</h2>
            <ol>
                <li>Onderverdeling tussen trajecten en vakken bij PDT studenten</li>
                <li>Opleidingen 'Sociaal werk' toegevoegd</li>
                <li>Opleidingen 'Animatiefilm' toegevoegd</li>
            </ol>
            <hr>
            <h2>v0.5.0 BETA (04/10/2015)</h2>
            <ol>
                <li>Bugfixes uurroosters</li>
                <li>Docenten knop toegevoegd</li>
            </ol>
            <hr>
            <h2>v0.4.0 BETA (30/09/2015)</h2>
            <ol>
                <li>Mobiele weergave verbeterd</li>
                <li>Je kan nu op je GSM tussen weken swypen</li>
                <li>Webcal knop toegevoegd op mobiele weergave (enkel iOS)</li>
                <li>Jaren 1,2,3 Audiovisuele Kunsten toegevoegd</li>
            </ol>
            <hr>
            <h2>v0.3.0 BETA (27/09/2015)</h2>
            <ol>
                <li>De knoppen "Volgende week" en "Vorige week" zijn vervangen door pijltjes. Je kan er nu blijven op drukken om verder/terug te gaan. "Deze week" brengt je nog steeds terug naar de huidige week</li>
                <img class="manual-image" src="http://i.mvdw-software.com/27-09-2015_22-08-22.png"></img>
                <li>Verkeerde lessenroosters voor "Drama" zijn opgelost</li>
                <li>Fixed layout problemen bij conflicten</li>
                <li>De 3 jaar podiumtechnieken toegevoegd</li>
                <li>NOTA: De lessenroosters voor Audiovisuele Kunsten zijn nog steeds niet opgedeeld in "Beeld,..."</li>
            </ol>
            <hr>
            <h2>v0.2.0 BETA (26/09/2015)</h2>
            <ol>
                <li>Indien je op dezelfde pc terug naar EHBrooster.be gaat krijg je je laats bezochte uurrooster te zien</li>
                <img class="manual-image" src="http://i.mvdw-software.com/26-09-2015_20-57-10.png"></img>
                <li>Opleidingsonderdelen: Drama Regie, Drama Acteren en Audiovisuele kunsten zijn toegevoegd</li>
                <li>Om de lijst niet te lang te maken na het toevoegen van RITCS heb je bij modeltrajecten de keuze
                of je wil filteren op departement. Momenteel zijn enkel enkele opleidingen voor RITCS en DT toegevoegd.</li>
                <img class="manual-image" src="http://i.mvdw-software.com/26-09-2015_21-01-56.png"></img>
                <li>Bij de lessenroosters is de naam van de agenda onder de weeknummer gezet.</li>
                <li>Bij het klikken op een vak in je lessenrooster krijg je nu een venstertje met meer informatie. Vooral bedoeld voor mobiele gebruikers.</li>
                <img class="manual-image" src="http://i.mvdw-software.com/26-09-2015_21-04-07.png"></img>
                <li>Optie toegevoegd om de Webcal:// link te koppieren. Aangezien Google Calendar soms moeilijk doet over het cachen van lessenroosters. Hier vind je een link naar meer info over hoe dit te gebruiken: <a href="http://www.ehbrooster.be/handleiding.php#manueel-office365">www.ehbrooster.be/handleiding.php#manueel-office365</a></li>
                <img class="manual-image" src="http://i.mvdw-software.com/26-09-2015_21-06-26.png"></img>
                <li>Vakken met dezelfde naam, die op hetzelfde moment vallen maar door verschillende docenten worden gegeven worden op de site versie onder 1 vak gezet maar met beide docenten</li>
                <img class="manual-image" src="http://i.mvdw-software.com/26-09-2015_21-10-15.png"></img>
                <li>Sync. proces geoptimaliseerd.</li>
                <li>De synchronisatie gebeurt nu elke 5 uur omdat het onnodig is dit sneller te doen</li>
            </ol>
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>