<?php
$page['menu'] = "about";

include_once('config.php');
include_once('lib/StatisticsManager.php');
include_once('lib/TimeTableManager.php');

$timeTableManager = new TimeTableManager($config);
$statManager = new StatisticsManager($config);

// Statistics
$currentTimeStamp = time();
$lastSync = $timeTableManager->getLastSync();
$syncs = $statManager->getSyncs(2000);

function dateGMT($timestamp) {
    $dateTimeZoneBrussels = new DateTimeZone("Europe/Brussels");
    $dateTimeZoneGMT = new DateTimeZone("Europe/London");
    $dateTimeBrussels = new DateTime();
    $dateTimeBrussels->setTimezone($dateTimeZoneBrussels);
    $dateTimeBrussels->setTimestamp($timestamp);
    $dateTimeGMT = new DateTime();
    $dateTimeGMT->setTimezone($dateTimeZoneGMT);
    $dateTimeGMT->setTimestamp($timestamp);
    $offset = 3600+ $dateTimeZoneGMT->getOffset($dateTimeBrussels);
    return $timestamp+$offset;
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

        <title>EHB Rooster - Status</title>

        <?php include('head.php'); ?>
        <script src="amcharts/amcharts.js"></script>
        <script src="amcharts/serial.js"></script>
        <script src="amcharts/themes/light.js"></script>
    </head>
    <body>
        <?php include('header.php'); ?>
        
         <!-- Begin page content -->
        <div class="container">
            <h2>EHBRooster.be Statistieken</h2>
            
            <div class="alert alert-<?=($currentTimeStamp-$lastSync) < (60*60*1) ? "success" : ((($currentTimeStamp-$lastSync) < (60*120) ) ?  "warning" : "danger")?>">
                <h3><span class="glyphicon glyphicon-refresh"></span>  Synchronisatie (<?=($currentTimeStamp-$lastSync) < (60*60*1) ? "Up-to-date" : ((($currentTimeStamp-$lastSync) < (60*120) ) ?  "Bezig..." : "Crash")?>)</h3>
                <p>
                <b>Laatste synchronisatie: </b><?=gmdate("d M y H:i:s",dateGMT($lastSync))?>
                </p>
            </div>

            <h3>Tijdsduur synchronisaties</h3>
            <p>In deze grafiek zie je de tijdsduur in seconden voor elke synchronisatie.</p>
            <p>Merk op dat de tijdsduur elke week langer duurt. Dit komt omdat de synchronisatie telkens 20 weken op voorand zal ophalen. Elke week is dit dus een extra week er bij.</p>
            <p>De grootste factor die de tijdsduur bepaald is de pagina laad snelheid van rooster.ehb.be. Synchronisaties worden een uur nadat de vorige sync. is voltooid uitgevoerd.</p>
            <div id="chartdiv"></div>					
            <style>
            #chartdiv {
                width	: 100%;
                height	: 500px;
            }									
                                
            </style>												
            <script>
            $(document).ready(function(){ 
            var chart = AmCharts.makeChart("chartdiv", {
                "type": "serial",
                "theme": "light",
                "marginRight": 80,
                "dataDateFormat": "YYYY-MM-DD, JJ:NN:SS",
                "autoMarginOffset": 20,
                "valueAxes": [{
                    "id": "v1",
                    "axisAlpha": 0,
                    "position": "left"
                }],
                "balloon": {
                    "borderThickness": 1,
                    "shadowAlpha": 0
                },
                "graphs": [{
                    "id": "g1",
                    "bullet": "round",
                    "bulletBorderAlpha": 1,
                    "bulletColor": "#FFFFFF",
                    "bulletSize": 5,
                    "hideBulletsCount": 50,
                    "lineThickness": 3,
                    "title": "red line",
                    "useLineColorForBulletBorder": true,
                    "valueField": "value",
                    "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]]</span><br>[[value]] sec.</div>"
                }],
                "chartScrollbar": {
                    "graph": "g1",
                    "oppositeAxis":false,
                    "offset":30,
                    "scrollbarHeight": 80,
                    "backgroundAlpha": 0,
                    "selectedBackgroundAlpha": 0.1,
                    "selectedBackgroundColor": "#888888",
                    "graphFillAlpha": 0,
                    "graphLineAlpha": 0.5,
                    "selectedGraphFillAlpha": 0,
                    "selectedGraphLineAlpha": 1,
                    "autoGridCount":true,
                    "color":"#AAAAAA"
                },
                "chartCursor": {
                    "pan": true,
                    "valueLineEnabled": true,
                    "valueLineBalloonEnabled": true,
                    "cursorAlpha":0,
                    "valueLineAlpha":0.2
                },
                "categoryField": "datetime",
                "categoryAxis": {
                    "parseDates": true,
                    "dashLength": 1,
                    "minorGridEnabled": true,
                    "minPeriod": "ss",
                },
                "export": {
                    "enabled": true
                },
                "dataProvider": [
                <?php
                for ($i = 0 ; $i < sizeof($syncs); $i++){
                ?>
                    {
                        "datetime": "<?=gmdate("Y-m-d, H:i:s",(dateGMT($syncs[$i]['timeStamp'] / 1000)))?>",
                        "value": <?=($syncs[$i]['duration'] / 1000)?>
                    }
                <?php
                    if($i < sizeof($syncs)){
                        echo ",";
                    }
                }
                ?>
                ]
            });
            chart.addListener("rendered", zoomChart);

            zoomChart();

            function zoomChart() {
                chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
            }
        });
        </script>
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>