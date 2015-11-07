<?php
include_once('config.php');
include_once('lib/GroupManager.php');
include_once('lib/SubjectManager.php');
include_once('lib/TimeTableManager.php');
include('lib/phpqrcode/qrlib.php'); 
include_once('lib/PDTManager.php');

$groupManager = new GroupManager($config);
$subjectManager = new SubjectManager($config);
$timeTableManager = new TimeTableManager($config);
$pdtManager = new PDTManager($config);

$subjects = array(); // Subjects to use

if (isset($_GET['type'])){
    $type = $_GET['type'];
    if ($type == "group"){
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $group = $groupManager->getGroupByGroupId($id);
            $subjectObjects = $subjectManager->getSubjectsByGroupId($id);
            for ($i = 0 ; $i< sizeof($subjectObjects); $i++){
                array_push($subjects,$subjectObjects[$i]['subjectId']);
            }
        }else{
            die();
        }
    }else if ($type == "pdt"){
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $subjectObjects = $subjectManager->getSubjectsByPdtKey($id);
            $pdtStudent = $pdtManager->getPDTStudentByKey($id)[0];
            for ($i = 0 ; $i< sizeof($subjectObjects); $i++){
                array_push($subjects,$subjectObjects[$i]['subjectId']);
            }
        }else{
            die();
        }
    }else if ($type == "lector"){
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $subjectObjects = $subjectManager->getSubjectsByLector($id);
            for ($i = 0 ; $i< sizeof($subjectObjects); $i++){
                array_push($subjects,$subjectObjects[$i]['subjectId']);
            }
        }else{
            die();
        }
    }else{
        die();
    }
}else{
    die();
}

// QR CODE
ob_start("callback"); 
 
// here DB request or some processing 
if (isset($_GET['webcal'])){
    $codeText = 'webcal://'.$config['url'].'/api/agenda_ics.php?type='.$type.'&id='.$id; 
}else{
    $codeText = 'http://'.$config['url'].'/rooster.php?type='.$type.'&id='.$id; 
}

// end of processing here 
ob_end_clean(); 
 
// outputs image directly into browser, as PNG stream 
QRcode::png($codeText);
?>