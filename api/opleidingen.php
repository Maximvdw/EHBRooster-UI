<?php
include_once('../config.php');
include_once('../lib/GroupManager.php');
include_once('../lib/SubjectManager.php');
include_once('../lib/TimeTableManager.php');
include_once('../lib/PDTManager.php');
include_once('../lib/EducationManager.php');
include_once('../lib/DepartmentManager.php');

$groupManager = new GroupManager($config);
$subjectManager = new SubjectManager($config);
$timeTableManager = new TimeTableManager($config);
$pdtManager = new PDTManager($config);
$educationManager = new EducationManager($config);
$departmentManager = new DepartmentManager($config);

$output = array();

// Convert the database education object to static readable array
function educationToArray($education){
    $return = array();
    if (sizeof($education) == 0)
        return $return;
    $return['opleiding_id'] = $education['id'];
    $return['naam'] = $education['name'];
    return $return;
}


if (isset($_GET['id'])){
    // Get education by id
    $id = $_GET['id'];
    $education = $educationManager->getEducationById($id);
    $output = educationToArray($education);
}else if (isset($_GET['departement_id'])){
    // Get all educations from department_id
    $id = $_GET['departement_id'];
    $educations = $educationManager->getEducationsByDepartmentId($id);
    if ($educations != false)
        foreach ($educations as $education){
            array_push($output,educationToArray($education));
        }
}else{
    // Get all educations
    $educations = $educationManager->getEducations();
    if ($educations != false)
        foreach ($educations as $education){
            array_push($output,educationToArray($education));
        }
}

die(json_encode($output)); // Return
?>