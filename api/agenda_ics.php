<?php
include_once('../config.php');
include_once('../lib/GroupManager.php');
include_once('../lib/TimeTableManager.php');
include_once('../lib/PDTManager.php');
include_once('../lib/SubjectManager.php');

$groupManager = new GroupManager($config);
$timeTableManager = new TimeTableManager($config);
$subjectManager = new SubjectManager($config);
$pdtManager = new PDTManager($config);


$subjects = array(); // Subjects to use


if (isset($_GET['type'])){
    $type = $_GET['type'];
    if ($type == "group"){
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            if (strpos($id,"#") === false)
                $id = "#".$id;
            $group = $groupManager->getGroupByGroupId($id);
            $agendaName=$group['alias'];
            $subjectObjects = $subjectManager->getSubjectsByGroupId($id);
            for ($i = 0 ; $i< sizeof($subjectObjects); $i++){
                array_push($subjects,$subjectObjects[$i]['subjectId']);
            }
            $activities = $timeTableManager->getAllActivitiesBySubjects($subjects);
        }else{
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
            $activities = $timeTableManager->getAllActivitiesBySubjects($subjects);
        }else{
            die();
        }
    }else if ($type == "lector"){
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $agendaName = $id;
            $activities = $timeTableManager->getAllActivitiesByLector($id);
        }else{
            die();
        }
    }else{
        die();
    }
}else{
    die();
}

if (!isset($_GET['text'])){
    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: inline; filename='.$agendaName.'.ics');
}

date_default_timezone_set('Europe/Brussels');
function dateToCal($timestamp) {
    $dateTimeZoneBrussels = new DateTimeZone("Europe/Brussels");
    $dateTimeZoneGMT = new DateTimeZone("Europe/London");
    $dateTimeBrussels = new DateTime();
    $dateTimeBrussels->setTimezone($dateTimeZoneBrussels);
    $dateTimeBrussels->setTimestamp($timestamp);
    $dateTimeGMT = new DateTime();
    $dateTimeGMT->setTimezone($dateTimeZoneGMT);
    $dateTimeGMT->setTimestamp($timestamp);
    $offset = 3600- $dateTimeZoneGMT->getOffset($dateTimeBrussels);
    return date('Ymd\THis', $timestamp + $offset);
}
// Escapes a string of characters
function escapeString($string) {
    return preg_replace('/([\,;])/','\\\$1', $string);
}


?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
NAME:Lessenrooster <?=$agendaName.PHP_EOL?>
DESCRIPTION:Lessenrooster <?=$agendaName.PHP_EOL?>
X-WR-CALNAME:Lessenrooster <?=$agendaName.PHP_EOL?>
X-WR-CALDESC:Lessenrooster <?=$agendaName.PHP_EOL?>
TIMEZONE-ID:Europe/Brussels
X-WR-TIMEZONE:Europe/Brussels
REFRESH-INTERVAL;VALUE=DURATION:PT1H
X-PUBLISHED-TTL:PT1H
CALSCALE:GREGORIAN
<?php for ($i = 0 ; $i < sizeof($activities) ; $i++){ 
$activity = $activities[$i];
$dateend = $activity['endTimeUnix'];
$datestart = $activity['beginTimeUnix'];
$address = $activity['classRoom'];
$description = "Lector: ".$activity['lector']."\\nWeken: ".$activity['weeks']."\\nStudiegroepen: ".$activity['groups'];
$uri = "";
$summary = $activity['name'];
?>
BEGIN:VEVENT
DTEND:<?= dateToCal($dateend) .PHP_EOL ?>
UID:<?= uniqid().PHP_EOL ?>
DTSTAMP:<?= dateToCal(time()).PHP_EOL ?>
LOCATION:<?= escapeString($address).PHP_EOL ?>
DESCRIPTION:<?= escapeString($description).PHP_EOL ?>
URL;VALUE=URI:<?= escapeString($uri).PHP_EOL ?>
SUMMARY:<?= escapeString($summary).PHP_EOL ?>
DTSTART:<?= dateToCal($datestart) .PHP_EOL ?>
END:VEVENT
<?php
}
?>
END:VCALENDAR