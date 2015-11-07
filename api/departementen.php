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

// Convert the database department object to static readable array
function departmentToArray($department){
    $return = array();
    if (sizeof($department) == 0)
        return $return;
    $return['departement_id'] = $department['id'];
    $return['code'] = $department['code'];
    $return['naam'] = $department['name'];
    return $return;
}


if (isset($_GET['id'])){
    // Get department by id
    $id = $_GET['id'];
    $department = $departmentManager->getDepartmentById($id);
    $output = departmentToArray($department);
}else if (isset($_GET['code'])){
    // Get department by code
    $code = $_GET['code'];
    $department = $departmentManager->getDepartmentByCode($code);
    $output = departmentToArray($department);
}else{
    // Get all departments
    $departments = $departmentManager->getDepartments();
    if ($departments != false)
        foreach ($departments as $department){
            array_push($output,departmentToArray($department));
        }
}

die(json_encode($output)); // Return
?>