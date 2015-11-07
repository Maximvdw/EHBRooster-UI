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
function groupToArray($group){
    $return = array();
    if (sizeof($group) == 0)
        return $return;
    $return['opleidingsgroep_id'] = $group['id'];
    $return['naam'] = $group['groupOriginalName'];
    $return['departement_id'] = $group['departmentId'];
    $return['opleiding_id'] = $group['educationId'];
    return $return;
}


if (isset($_GET['id'])){
    // Get group by id
    $id = $_GET['id'];
    $group = $groupManager->getGroupById($id);
    $output = groupToArray($group);
}else if (isset($_GET['departement_id'])){
    // Get all groups from department_id
    $id = $_GET['departement_id'];
    $groups = $groupManager->getGroupsByDepartmentId($id);
    if ($educations != false)
        foreach ($educations as $group){
            array_push($output,groupToArray($group));
        }
}else if (isset($_GET['departement_id'])){
    // Get all groups from department_id
    $id = $_GET['departement_id'];
    $groups = $groupManager->getGroupsByDepartmentId($id);
    if ($groups != false)
        foreach ($groups as $group){
            array_push($output,groupToArray($group));
        }
}else{
    // Get all groups
    $groups = $groupManager->getGroups();
    if ($groups != false)
        foreach ($groups as $group){
            array_push($output,groupToArray($group));
        }
}

die(json_encode($output)); // Return
?>