<?php
include_once('config.php');
include_once('lib/GroupManager.php');
include_once('lib/SubjectManager.php');
include_once('lib/TimeTableManager.php');
include('lib/phpqrcode/qrlib.php'); 
include_once('lib/PDTManager.php');
if ($config['maintenance'] == true){
    include('maintenance.php');
    die();
}

$groupManager = new GroupManager($config);
$subjectManager = new SubjectManager($config);
$timeTableManager = new TimeTableManager($config);
$pdtManager = new PDTManager($config);

$subjects = array(); // Subjects to use

$startTimeStamp = $timeTableManager->getStartTimeStamp();
$lastSync = $timeTableManager->getLastSync();
$currentTimeStamp = time();
$currentDay = date('w',$currentTimeStamp);
$currentWeek = 1;
for ($i = 1 ; $i <= 52 ; $i++){
    $weekStartTimeStamp = $startTimeStamp + (($i-1) * 7 * 24 * 60 *60);
    $weekEndTimeStamp = $weekStartTimeStamp + (7*24*60*60);
    if ($currentTimeStamp >= $weekStartTimeStamp && $currentTimeStamp < $weekEndTimeStamp){
        $currentWeek = $i;
        break;
    }
}

$week = $currentWeek;

$agendaName = "";


if (isset($_GET['week'])){
    $weekStr = $_GET['week'];
    if (strPos($weekStr,"n") === false && strPos($weekStr,"p") === false){
        $week = $weekStr;
    }else if (strPos($weekStr,"n") !== false){
        $week = $currentWeek + intval(substr($weekStr,1));
    }else if (strPos($weekStr,"p") !== false){
        $week = $currentWeek - intval(substr($weekStr,1));
    }
}

if (isset($_GET['type'])){
    $type = $_GET['type'];
    if ($type == "group"){
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $group = $groupManager->getGroupByGroupId($id);
            $agendaName=$group['alias'];
            $subjectObjects = $subjectManager->getSubjectsByGroupId($id);
            for ($i = 0 ; $i< sizeof($subjectObjects); $i++){
                array_push($subjects,$subjectObjects[$i]['subjectId']);
            }
            $activities = $timeTableManager->getWeekActivitiesBySubjects($subjects,$week);
        }else{
            header('Location: /');
            die();
        }
    }else if ($type == "pdt"){
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $subjectObjects = $subjectManager->getSubjectsByPdtKey($id);
            $pdtStudent = $pdtManager->getPDTStudentByKey($id)[0];
            $agendaName = $pdtStudent['firstName'].' '.$pdtStudent['surName'];
            for ($i = 0 ; $i< sizeof($subjectObjects); $i++){
                array_push($subjects,$subjectObjects[$i]['subjectId']);
            }
            $activities = $timeTableManager->getWeekActivitiesBySubjects($subjects,$week);
        }else{
            header('Location: /');
            die();
        }
    }else if ($type == "lector"){
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $agendaName = $id;
            $tempActivities = $timeTableManager->getWeekActivitiesByLector($id,$week);
            $activities = array();
            if ($tempActivities != false)
                foreach($tempActivities as $activity){
                    if (strpos($activity['lectors'],$id) !== false)
                        array_push($activities,$activity);
                }
        }else{
            header('Location: /');
            die();
        }
    }else{
        header('Location: /');
        die();
    }
}else{
    header('Location: /');
    die();
}

// Save selectie
setcookie("ROOSTER_URL", $_SERVER['REQUEST_URI']);
setcookie("ROOSTER_NAME",$agendaName);

if ($activities == false){
    $activities = array();
}

$weekTimeStamp = $startTimeStamp + (($week - 1) * 7 * 24 * 60 * 60);

$seperatedActivities = array(1 => array(), 2 =>array(), 3 =>array(), 4 =>array(), 5 =>array(), 6 =>array(), 7 =>array());
for ($i = 0 ; $i < sizeof($activities) ; $i++){
    array_push($seperatedActivities[$activities[$i]['dayInWeek']],$activities[$i]);
}

$maxDuration = 40;
for ($i = 0 ; $i < sizeof($activities) ; $i++){
    if (date('H',$activities[$i]['endTimeUnix']) > 18){
        $maxDuration = 52;
    }
}
// QR CODE

$dateTimeZoneBrussels = new DateTimeZone("Europe/Brussels");
$dateTimeZoneGMT = new DateTimeZone("Europe/London");
$dateTimeBrussels = new DateTime();
$dateTimeBrussels->setTimezone($dateTimeZoneBrussels);
$dateTimeBrussels->setTimestamp($weekTimeStamp);
$dateTimeGMT = new DateTime();
$dateTimeGMT->setTimezone($dateTimeZoneGMT);
$dateTimeGMT->setTimestamp($weekTimeStamp);
$offset = 3600- $dateTimeZoneGMT->getOffset($dateTimeBrussels);

$fullWeek = false;
if (sizeof($seperatedActivities[6]) != 0 || sizeof($seperatedActivities[7]) != 0){
    $fullWeek = true;
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
        <meta charset="UTF-8">

        <title>EHB Rooster - <?=$agendaName?></title>

        <!-- Bootstrap core CSS -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/style.css?v3" rel="stylesheet">
        <link href="bootstrap/css/bootstrap-datepicker3.css" rel="stylesheet">
        
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery-mobile.js"></script>`
        
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="bootstrap/js/bootstrap-datepicker.js"></script>
        
        <?php include_once("analyticstracking.php") ?>
    </head>
    <body class="rooster-body">
        <div class="rooster-header row">
            <div class="col-md-1 mobilehide">
                <a href="/"><img class="logo-small" src="images/logo.png"></img></a>
            </div>
            <div class="btn-group" data-toggle="buttons" role="group" aria-label="weeks">
                <button class="btn btn-default" onclick="if (this.disabled == false) window.location = 'rooster.php?type=<?=$type?>&id=<?=urlencode($id)?>&week=<?=$week-1?>'" autocomplete="off"<?=$week == 1 ? " disabled" : ""?>><span style="margin-top: 3px; margin-bottom: 3px"  class="glyphicon glyphicon-backward"></span></button>     
                <label class="btn btn-default<?=$currentWeek == $week ? " active" : ""?>">
                    <input onchange="if (this.disabled == false) window.location = 'rooster.php?type=<?=$type?>&id=<?=urlencode($id)?>'" type="radio" autocomplete="off"<?=$currentWeek == $week ? " active" : ""?>>Deze week</button>
                </label>
                <button class="btn btn-default" onclick="if (this.disabled == false) window.location = 'rooster.php?type=<?=$type?>&id=<?=urlencode($id)?>&week=<?=$week+1?>'" autocomplete="off"<?=$week == 52 ? " disabled" : ""?>><span style="margin-top: 3px; margin-bottom: 3px" class="glyphicon glyphicon-forward"></span></button>
                <label id="weekPicker" class="btn btn-warning mobilehide">
                    <script>
                    $('#weekPicker').datepicker({
                        format: "dd/mm/yyyy",
                        weekStart: 1,
                        todayBtn: "linked",
                        calendarWeeks: true,
                        autoclose: true,
                        todayHighlight: true
                    }).on("hide", function(e) {
                        var selectedTime = e.date.getTime() / 1000;
                        var startTime = <?=$startTimeStamp?> - 1;
                        if (selectedTime < startTime){
                            alert("Foutieve invoer!");
                            return;
                        }
                        var week = 1;
                        for (var i = 1 ; i < 53; i ++){
                            var weekTimeStart = startTime + ((i-1) * 7 * 24 *60 *60);
                            var weekTimeEnd = weekTimeStart + (7*24*60*60);
                            if (selectedTime > weekTimeStart && weekTimeEnd > selectedTime){
                                week = i;
                            }
                        }
                        window.location = "rooster.php?type=<?=$type?>&id=<?=urlencode($id)?>&week=" + week;
                    });
                    </script>
                    <input onchange="" type="radio" autocomplete="off"><span class="glyphicon glyphicon-th-large"></span> Week</button>
                </label>
            </div>
            <a class="mobileshow btn btn-primary" href="<?=("webcal://".$config['url']."/type/".$type."/id/".urlencode(($type == "group" ? urlencode(substr($id,1)) : $id))."/agenda.ics")?>"><span style="margin-top: 3px; margin-bottom: 3px" class="glyphicon glyphicon-calendar"></span></a>
          
            <div class="btn-group" role="group" class="mobilehide">
                <button type="button" class="mobilehide btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-plus"></span><span class="mobilehide">  Abonneren</span>
                    <span class="caret"></span>
                </button>
                <script>
                function openWebcal(link){
                    $('#link_textbox').val(link);
                    $('#link_button').attr("href",link);
                    $('#modal_download').modal('show');
                }
                </script>
                <ul class="mobilehide dropdown-menu">
                    <li><a href="https://www.google.com/calendar/render?cid=<?=urlencode("http://".$config['url']."/type/".$type."/id/".($type == "group" ? urlencode(substr($id,1)) : $id)."/agenda.ics")?>">Google Calendar <small>(online)</small></a></li>
                    <li><a href="https://bay03.calendar.live.com/calendar/import.aspx?rru=addsubscription&url=<?=urlencode("http://".$config['url']."/api/agenda_ics.php?type=".$type."&id=".urlencode($id))?>&name=Lessenrooster">Outlook Calendar <small>(online)</small></a></li>
                    <li><a style="cursor: pointer;" onclick="openWebcal('<?=("webcal://".$config['url']."/type/".$type."/id/".urlencode(($type == "group" ? urlencode(substr($id,1)) : $id))."/agenda.ics")?>');">webcal:// Kalender <small>(online)</small></a></li>
                    <li><a href="<?=("http://".$config['url']."/api/agenda_ics.php?type=".$type."&id=".urlencode($id))?>">Download *.ICS <small>(offline)</small></a></li>
                </ul>
            </div>
        </div>
        <div class="rooster-agenda-header">
            <?php $day = 1; $timeStart = $weekTimeStamp + (($day-1) * 24 * 60 *60); ?>
            <?php $day = 7; $timeEnd = $weekTimeStamp + (($day-1) * 24 * 60 *60); ?>
            <h3>Week <?=$week?> <small>(<?=date("j M y",$timeStart + $offset)?> - <?=date("d M y",$timeEnd + $offset)?>) <span class="mobilehide" style="font-size: 12px;">Last sync <?=date("j M y H:i:s",$lastSync)?></span></small></h3><br>
            <h4 style="margin-top: -35px;">Agenda voor <?=$agendaName?></h4>
            
        </div>
        <div class="rooster-container">
            <div id="table-container">
                <table id="rooster" class="table rooster-tabel">
                    <thead>
                        <tr>
                            <?php
                            $colSpan = array();
                            ?>
                            <th class="left-side-column">
                            
                            </th>
                            <?php $day = 1; $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                            $dailyActivities = $seperatedActivities[$day];
                            $colSpan[$day] = 1;
                            $columnPresent = false;
                            $currentSubject = array();
                            for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                $dailyActivities[$j]['conflicts'] = 0;
                            }
                            $dayTimeTableArray = array();
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                array_push($dayTimeTableArray,array());
                            }
                            $uur = 8;
                            $min = 0;
                            // Get overlapping events
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                                $time += $uur * 60 * 60;
                                $time += $min * 60;
                                for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                    $dayActivity = $dailyActivities[$j];
                                    $start = $dayActivity['beginTimeUnix'];
                                    $end = $dayActivity['endTimeUnix'];
                                    if ($start <= $time && $end > $time){
                                        array_push($dayTimeTableArray[$tp],$j);
                                    }
                                }
                                if ($tp % 4 == 0){
                                    $uur += 1;
                                    $min = 0;
                                }else{
                                    $min += 15;
                                }
                            }
                            for ($i = 0 ; $i < sizeof($dayTimeTableArray) ; $i++){
                                // Calculate max col span
                                if ($colSpan[$day] < sizeof($dayTimeTableArray[$i])){
                                    $colSpan[$day] = sizeof($dayTimeTableArray[$i]);
                                }
                                
                                for($j = 0; $j < sizeof($dayTimeTableArray[$i]); $j++){
                                    if ($dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] < sizeof($dayTimeTableArray[$i]) - 1)
                                        $dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] =  sizeof($dayTimeTableArray[$i]) - 1;
                                }
                            }
                            $seperatedActivities[$day] = $dailyActivities;
                            ?>
                            <th colspan="<?=$colSpan[$day]?>" class="day-column<?=$day == $currentDay && $week == $currentWeek ? " currentday-column" : ""?>">
                            Ma<span class="mobilehide">andag</span> <small class="mobilehide" style="font-weight: normal;">(<?=date('j M Y', $time+ $offset)?>)</small>
                            </th>
                            <?php $day = 2; $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                            $dailyActivities = $seperatedActivities[$day];
                            $colSpan[$day] = 1;
                            $columnPresent = false;
                            $currentSubject = array();
                            for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                $dailyActivities[$j]['conflicts'] = 0;
                            }
                            $dayTimeTableArray = array();
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                array_push($dayTimeTableArray,array());
                            }
                            $uur = 8;
                            $min = 0;
                            // Get overlapping events
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                                $time += $uur * 60 * 60;
                                $time += $min * 60;
                                for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                    $dayActivity = $dailyActivities[$j];
                                    $start = $dayActivity['beginTimeUnix'];
                                    $end = $dayActivity['endTimeUnix'];
                                    if ($start <= $time && $end > $time){
                                        array_push($dayTimeTableArray[$tp],$j);
                                    }
                                }
                                if ($tp % 4 == 0){
                                    $uur += 1;
                                    $min = 0;
                                }else{
                                    $min += 15;
                                }
                            }
                            for ($i = 0 ; $i < sizeof($dayTimeTableArray) ; $i++){
                                // Calculate max col span
                                if ($colSpan[$day] < sizeof($dayTimeTableArray[$i])){
                                    $colSpan[$day] = sizeof($dayTimeTableArray[$i]);
                                }
                                
                                for($j = 0; $j < sizeof($dayTimeTableArray[$i]); $j++){
                                    if ($dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] < sizeof($dayTimeTableArray[$i]) - 1)
                                        $dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] =  sizeof($dayTimeTableArray[$i]) - 1;
                                }
                            }
                            $seperatedActivities[$day] = $dailyActivities;
                            ?>
                            <th colspan="<?=$colSpan[$day]?>" class="day-column<?=$day == $currentDay && $week == $currentWeek ? " currentday-column" : ""?>">
                            Di<span class="mobilehide">nsdag</span> <small class="mobilehide" style="font-weight: normal;">(<?=date('j M Y', $time+ $offset)?>)</small>
                            </th>
                            <?php $day = 3; $time = $weekTimeStamp + (($day-1) * 24 * 60 *60); 
                            $dailyActivities = $seperatedActivities[$day];
                            $colSpan[$day] = 1;
                            $columnPresent = false;
                            $currentSubject = array();
                            for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                $dailyActivities[$j]['conflicts'] = 0;
                            }
                            $dayTimeTableArray = array();
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                array_push($dayTimeTableArray,array());
                            }
                            $uur = 8;
                            $min = 0;
                            // Get overlapping events
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                                $time += $uur * 60 * 60;
                                $time += $min * 60;
                                for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                    $dayActivity = $dailyActivities[$j];
                                    $start = $dayActivity['beginTimeUnix'];
                                    $end = $dayActivity['endTimeUnix'];
                                    if ($start <= $time && $end > $time){
                                        array_push($dayTimeTableArray[$tp],$j);
                                    }
                                }
                                if ($tp % 4 == 0){
                                    $uur += 1;
                                    $min = 0;
                                }else{
                                    $min += 15;
                                }
                            }
                            for ($i = 0 ; $i < sizeof($dayTimeTableArray) ; $i++){
                                // Calculate max col span
                                if ($colSpan[$day] < sizeof($dayTimeTableArray[$i])){
                                    $colSpan[$day] = sizeof($dayTimeTableArray[$i]);
                                }
                                
                                for($j = 0; $j < sizeof($dayTimeTableArray[$i]); $j++){
                                    if ($dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] < sizeof($dayTimeTableArray[$i]) - 1)
                                        $dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] =  sizeof($dayTimeTableArray[$i]) - 1;
                                }
                            }
                            $seperatedActivities[$day] = $dailyActivities;
                            ?>
                            <th colspan="<?=$colSpan[$day]?>" class="day-column<?=$day == $currentDay && $week == $currentWeek ? " currentday-column" : ""?>">
                            Woe<span class="mobilehide">nsdag</span> <small class="mobilehide" style="font-weight: normal;">(<?=date('j M Y', $time+ $offset)?>)</small>
                            </th>
                            <?php $day = 4; $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                            $dailyActivities = $seperatedActivities[$day];
                            $colSpan[$day] = 1;
                            $columnPresent = false;
                            $currentSubject = array();
                            for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                $dailyActivities[$j]['conflicts'] = 0;
                            }
                            $dayTimeTableArray = array();
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                array_push($dayTimeTableArray,array());
                            }
                            $uur = 8;
                            $min = 0;
                            // Get overlapping events
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                                $time += $uur * 60 * 60;
                                $time += $min * 60;
                                for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                    $dayActivity = $dailyActivities[$j];
                                    $start = $dayActivity['beginTimeUnix'];
                                    $end = $dayActivity['endTimeUnix'];
                                    if ($start <= $time && $end > $time){
                                        array_push($dayTimeTableArray[$tp],$j);
                                    }
                                }
                                if ($tp % 4 == 0){
                                    $uur += 1;
                                    $min = 0;
                                }else{
                                    $min += 15;
                                }
                            }
                            for ($i = 0 ; $i < sizeof($dayTimeTableArray) ; $i++){
                                // Calculate max col span
                                if ($colSpan[$day] < sizeof($dayTimeTableArray[$i])){
                                    $colSpan[$day] = sizeof($dayTimeTableArray[$i]);
                                }
                                
                                for($j = 0; $j < sizeof($dayTimeTableArray[$i]); $j++){
                                    if ($dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] < sizeof($dayTimeTableArray[$i]) - 1)
                                        $dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] =  sizeof($dayTimeTableArray[$i]) - 1;
                                }
                            }
                            $seperatedActivities[$day] = $dailyActivities;
                            ?>
                            <th colspan="<?=$colSpan[$day]?>" class="day-column<?=$day == $currentDay && $week == $currentWeek ? " currentday-column" : ""?>">
                            Do<span class="mobilehide">nderdag</span> <small class="mobilehide" style="font-weight: normal;">(<?=date('j M Y', $time+ $offset)?>)</small>
                            </th>
                            <?php $day = 5; $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                            $dailyActivities = $seperatedActivities[$day];
                            $colSpan[$day] = 1;
                            $columnPresent = false;
                            $currentSubject = array();
                            for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                $dailyActivities[$j]['conflicts'] = 0;
                            }
                            $dayTimeTableArray = array();
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                array_push($dayTimeTableArray,array());
                            }
                            $uur = 8;
                            $min = 0;
                            // Get overlapping events
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                                $time += $uur * 60 * 60;
                                $time += $min * 60;
                                for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                    $dayActivity = $dailyActivities[$j];
                                    $start = $dayActivity['beginTimeUnix'];
                                    $end = $dayActivity['endTimeUnix'];
                                    if ($start <= $time && $end > $time){
                                        array_push($dayTimeTableArray[$tp],$j);
                                    }
                                }
                                if ($tp % 4 == 0){
                                    $uur += 1;
                                    $min = 0;
                                }else{
                                    $min += 15;
                                }
                            }
                            for ($i = 0 ; $i < sizeof($dayTimeTableArray) ; $i++){
                                // Calculate max col span
                                if ($colSpan[$day] < sizeof($dayTimeTableArray[$i])){
                                    $colSpan[$day] = sizeof($dayTimeTableArray[$i]);
                                }
                                
                                for($j = 0; $j < sizeof($dayTimeTableArray[$i]); $j++){
                                    if ($dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] < sizeof($dayTimeTableArray[$i]) - 1)
                                        $dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] =  sizeof($dayTimeTableArray[$i]) - 1;
                                }
                            }
                            $seperatedActivities[$day] = $dailyActivities;
                            ?>
                            <th colspan="<?=$colSpan[$day]?>" class="day-column<?=$day == $currentDay && $week == $currentWeek ? " currentday-column" : ""?>">
                            Vr<span class="mobilehide">ijdag</span> <small class="mobilehide" style="font-weight: normal;">(<?=date('j M Y', $time+ $offset)?>)</small>
                            </th>
                            
                            <?php if($fullWeek){ ?>
                            
                            <?php $day = 6; $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                            $dailyActivities = $seperatedActivities[$day];
                            $colSpan[$day] = 1;
                            $columnPresent = false;
                            $currentSubject = array();
                            for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                $dailyActivities[$j]['conflicts'] = 0;
                            }
                            $dayTimeTableArray = array();
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                array_push($dayTimeTableArray,array());
                            }
                            $uur = 8;
                            $min = 0;
                            // Get overlapping events
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                                $time += $uur * 60 * 60;
                                $time += $min * 60;
                                for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                    $dayActivity = $dailyActivities[$j];
                                    $start = $dayActivity['beginTimeUnix'];
                                    $end = $dayActivity['endTimeUnix'];
                                    if ($start <= $time && $end > $time){
                                        array_push($dayTimeTableArray[$tp],$j);
                                    }
                                }
                                if ($tp % 4 == 0){
                                    $uur += 1;
                                    $min = 0;
                                }else{
                                    $min += 15;
                                }
                            }
                            for ($i = 0 ; $i < sizeof($dayTimeTableArray) ; $i++){
                                // Calculate max col span
                                if ($colSpan[$day] < sizeof($dayTimeTableArray[$i])){
                                    $colSpan[$day] = sizeof($dayTimeTableArray[$i]);
                                }
                                
                                for($j = 0; $j < sizeof($dayTimeTableArray[$i]); $j++){
                                    if ($dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] < sizeof($dayTimeTableArray[$i]) - 1)
                                        $dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] =  sizeof($dayTimeTableArray[$i]) - 1;
                                }
                            }
                            $seperatedActivities[$day] = $dailyActivities;
                            ?>
                            <th colspan="<?=$colSpan[$day]?>" class="day-column<?=$day == $currentDay && $week == $currentWeek ? " currentday-column" : ""?>">
                            Za<span class="mobilehide">terdag</span> <small class="mobilehide" style="font-weight: normal;">(<?=date('j M Y', $time+ $offset)?>)</small>
                            </th>
                            
                            <?php $day = 7; $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                            $dailyActivities = $seperatedActivities[$day];
                            $colSpan[$day] = 1;
                            $columnPresent = false;
                            $currentSubject = array();
                            for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                $dailyActivities[$j]['conflicts'] = 0;
                            }
                            $dayTimeTableArray = array();
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                array_push($dayTimeTableArray,array());
                            }
                            $uur = 8;
                            $min = 0;
                            // Get overlapping events
                            for ($tp = 0 ; $tp < $maxDuration; $tp++){
                                $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                                $time += $uur * 60 * 60;
                                $time += $min * 60;
                                for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                    $dayActivity = $dailyActivities[$j];
                                    $start = $dayActivity['beginTimeUnix'];
                                    $end = $dayActivity['endTimeUnix'];
                                    if ($start <= $time && $end > $time){
                                        array_push($dayTimeTableArray[$tp],$j);
                                    }
                                }
                                if ($tp % 4 == 0){
                                    $uur += 1;
                                    $min = 0;
                                }else{
                                    $min += 15;
                                }
                            }
                            for ($i = 0 ; $i < sizeof($dayTimeTableArray) ; $i++){
                                // Calculate max col span
                                if ($colSpan[$day] < sizeof($dayTimeTableArray[$i])){
                                    $colSpan[$day] = sizeof($dayTimeTableArray[$i]);
                                }
                                
                                for($j = 0; $j < sizeof($dayTimeTableArray[$i]); $j++){
                                    if ($dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] < sizeof($dayTimeTableArray[$i]) - 1)
                                        $dailyActivities[$dayTimeTableArray[$i][$j]]['conflicts'] =  sizeof($dayTimeTableArray[$i]) - 1;
                                }
                            }
                            $seperatedActivities[$day] = $dailyActivities;
                            ?>
                            <th colspan="<?=$colSpan[$day]?>" class="day-column<?=$day == $currentDay && $week == $currentWeek ? " currentday-column" : ""?>">
                            Zo<span class="mobilehide">ndag</span> <small class="mobilehide" style="font-weight: normal;">(<?=date('j M Y', $time+ $offset)?>)</small>
                            </th>
                            
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $uur = 8;
                        $min = 0;
                        for ($i = 1 ; $i <= $maxDuration; $i++){
                        ?>
                        <tr class="rooster-row-min-<?=$min?> rooster-row-hour-<?=$uur?>">
                            <th class="left-side-column">
                                <span><?=sprintf("%02d", $uur).":".sprintf("%02d", $min)?></span>
                            </th>
                            <?php
                            for ($day = 1 ; $day <= ($fullWeek ? 7 : 5) ; $day++){ 
                            ?>
                            
                            
                            <?php
                            $dailyActivities = $seperatedActivities[$day];
                            $time = $weekTimeStamp + (($day-1) * 24 * 60 *60);
                            $time += $uur * 60 * 60;
                            $time += $min * 60;
                            if (sizeof($dailyActivities) == 0){
                            ?>
                            <td colspan="<?=$colSpan[$day]?>" class="day-<?=$day?> day-column<?=$day == $currentDay && $week == $currentWeek ? " currentday-column" : ""?>">
                            
                            </td>
                            <?php }else{
                                $columnsPlaced = 0;
                                for ($j = 0 ; $j < sizeof($dailyActivities) ; $j ++){
                                    $start = $dailyActivities[$j]['beginTimeUnix'];
                                    $end = $dailyActivities[$j]['endTimeUnix'];
                                    if ($start == $time){
                            ?>
                                <td onclick="showSubject('<?=addslashes($dailyActivities[$j]['name'])?>','<?=addslashes($dailyActivities[$j]['classRoom'])?>','<?=addslashes($dailyActivities[$j]['lectors'])?>','<?=addslashes($dailyActivities[$j]['beginTime'])?>','<?=$dailyActivities[$j]['endTime']?>','<?=$dailyActivities[$j]['weeks']?>','<?=$dailyActivities[$j]['groups']?>');" colspan="<?=$colSpan[$day] / ($dailyActivities[$j]['conflicts']+1)?>" rowspan="<?=($end-$start) / (15 * 60)?>" class="day-<?=$day?> day-column subject-cell<?=$day == $currentDay && $week == $currentWeek ? " currentday-column" : ""?>">
                                    <b><?=$dailyActivities[$j]['name']?></b>
                                    <div class="mobilehide subject-cell-info"<?=($dailyActivities[$j]['endTimeUnix'] - $dailyActivities[$j]['beginTimeUnix']) <= 2700 ? " style='display: none;'" : ""?>>
                                        <span style="text-decoration: underline;">Lokaal: </span><?=$dailyActivities[$j]['classRoom']?><br>
                                        <span style="text-decoration: underline;">Lector<?=$dailyActivities[$j]['lectors'] != $dailyActivities[$j]['lector'] ? "s" : ""?>: </span><?=$dailyActivities[$j]['lectors']?><br>
                                        <span style="text-decoration: underline;">Begin: </span><?=$dailyActivities[$j]['beginTime']?><br>
                                        <span style="text-decoration: underline;">Einde: </span><?=$dailyActivities[$j]['endTime']?><br>
                                    </div>
                                </td>
                            <?php
                                        $columnsPlaced += $colSpan[$day] - $dailyActivities[$j]['conflicts'];
                                    }else if ($time > $start && $time < $end){
                                        // Do nothing
                                        $columnsPlaced += $colSpan[$day] - $dailyActivities[$j]['conflicts'];
                                    }
                                }
                                if ($columnsPlaced != $colSpan[$day]){
                            ?>
                                <td colspan="<?=$colSpan[$day]-$columnsPlaced?>" class="day-<?=$day?> day-column<?=$day == $currentDay && $week == $currentWeek ? " currentday-column" : ""?>">
                                
                                </td>
                            <?php 
                                }
                            } 
                            ?>

                            <?php } ?>
                        </tr>
                        <?php
                            if ($i % 4 == 0){
                                $uur += 1;
                                $min = 0;
                            }else{
                                $min += 15;
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <div id="bottom_anchor"></div>
            </div>
        </div>
        <style>
        .modal_info{
            font-weight: bold;
        }
        </style>
        <div class="modal fade" id="modal_download">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Webcal agenda</h4>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="link_textbox" class="form-control form-control-lg" readonly>
                    </div>
                    <div class="modal-footer">
                        <a href="" id="link_button" class="btn btn-primary">Uitvoeren</a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal fade" id="modal_subject">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modal_title">Enable Javascript!</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-1 modal_info">
                            Lokaal
                            </div>
                            <div id="modal_classroom" class="col-md-9">
                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 modal_info">
                            Lector
                            </div>
                            <div id="modal_lector" class="col-md-9">
                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 modal_info">
                            Begin
                            </div>
                            <div id="modal_begin" class="col-md-9">
                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 modal_info">
                            Einde
                            </div>
                            <div id="modal_end" class="col-md-9">
                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 modal_info">
                            Weken
                            </div>
                            <div id="modal_weeks" class="col-md-9">
                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 modal_info">
                            Groepen
                            </div>
                            <div id="modal_groups" class="col-md-9">
                            
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <script>
        function showSubject(name,classRoom,lector,beginTime,endTime,weeks,groups){
            $('#modal_title').text(name);
            $('#modal_classroom').text(classRoom);
            $('#modal_lector').text(lector);
            $('#modal_begin').text(beginTime);
            $('#modal_end').text(endTime);
            $('#modal_weeks').text(weeks);
            $('#modal_groups').text(groups);
            $('#modal_subject').modal('show');
        }


        $(function(){
             $(window).resize(function(){
                    if($(this).width() < 991){
                        $('.rooster-row-min-15 .left-side-column span').css("display","none");
                        $('.rooster-row-min-45 .left-side-column span').css("display","none");
                        $('.rooster-row-min-15 td').css("height","7px");
                        $('.rooster-row-min-15 th').css("height","7px");
                        $('.rooster-row-min-45 td').css("height","7px");
                        $('.rooster-row-min-45 th').css("height","7px");
                    }else{
                        $('.rooster-row-min-15 .left-side-column span').removeAttr("style");
                        $('.rooster-row-min-45 .left-side-column span').removeAttr("style");
                        $('.rooster-row-min-15 td').css("height","15px");
                        $('.rooster-row-min-15 th').css("height","15px");
                        $('.rooster-row-min-45 td').css("height","15px");
                        $('.rooster-row-min-45 th').css("height","15px");
                    }
                    moveScroll();
              })
              .resize();//trigger resize on page load
              
              $('.rooster-container').swipe( {
                //Generic swipe handler for all directions
                swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
                    if (distance < 150)
                        return;
                    if (direction == "left"){
                        window.location = "rooster.php?type=<?=$type?>&id=<?=urlencode($id)?>&week=<?=$week + 1?>";
                    }else if (direction == "right"){
                        window.location = "rooster.php?type=<?=$type?>&id=<?=urlencode($id)?>&week=<?=$week - 1?>";
                    }
                },
                click:function (event, target) {
                    $(target).click();
                },
                //Default is 75px, set to 0 for demo so any distance triggers swipe
               threshold:0, allowPageScroll: "vertical",
               excludedElements:$.fn.swipe.defaults.excludedElements+", td"
            });
            
            function moveScroll(){
                var scroll = $(window).scrollTop();
                var anchor_top = $("#rooster").offset().top;
                var anchor_bottom = $("#bottom_anchor").offset().top;
                if (scroll>1 && scroll<anchor_bottom) {
                    clone_table = $("#clone");
                    if(clone_table.length == 0){
                        clone_table = $("#rooster").clone();
                        clone_table.attr('id', 'clone');
                        if($(window).width() < 991){
                            clone_table.css({position:'fixed',
                                 'pointer-events': 'none',
                                 top:97});
                        }else{
                            clone_table.css({position:'fixed',
                                 'pointer-events': 'none',
                                 top:146});
                        }
                        
                        clone_table.width($("#rooster").width());
                        $("#table-container").append(clone_table);
                        $("#clone").css({visibility:'hidden'});
                        $("#clone thead").css({'visibility':'visible','pointer-events':'auto'});
                    }else{
                        if($(window).width() < 991){
                            clone_table.css({position:'fixed',
                                 'pointer-events': 'none',
                                 top:97});
                            clone_table.width($("#rooster").width());
                        }else{
                            clone_table.css({position:'fixed',
                                 'pointer-events': 'none',
                                 top:146});
                            clone_table.width($("#rooster").width());
                        }
                    }
                } else {
                    $("#clone").remove();
                }
            }
            $(window).scroll(moveScroll); 
        });
        </script>
    </body>
</html>