<?php
$page['menu'] = "lessenrooster";

include_once('config.php');
include_once('lib/GroupManager.php');
include_once('lib/SubjectManager.php');
include_once('lib/EducationManager.php');
include_once('lib/TimeTableManager.php');
include_once('lib/PDTManager.php');
include_once('lib/DepartmentManager.php');
if ($config['maintenance'] == true){
    include('maintenance.php');
    die();
}

$departmentManager = new DepartmentManager($config);
$pdtManager = new PDTManager($config);
$groupManager = new GroupManager($config);
$subjectManager = new SubjectManager($config);
$educationManager = new EducationManager($config);


$educations = $educationManager->getEducations();
$groups = $groupManager->getGroups();
$departments = $departmentManager->getDepartments();

$pdtStudent = array();
if (isset($_GET['id'])){
    $id = $_GET['id'];
    $pdtStudent = $pdtManager->getPDTStudentByKey($id)[0];
}

// POST actions (forms)
if (isset($_POST['action'])){
    $action = $_POST['action'];
    if ($action == "create"){
        // Create PDT Student
        if (isset($_POST['firstname']) && isset($_POST['surname']) && isset($_POST['email'])){
            $firstname = $_POST['firstname'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $key = $pdtManager->registerPDT($firstname,$surname,$email);
            if ($key == false){
                header('Location: pdtregistration.php');
                die();
            }else{
                header('Location: pdt.php?id='.$key);
                die();
            }
        }else{
            header('Location: pdtregistration.php');
            die();
        }
    }else if ($action == "setsubjects"){
        // Set subjects
        if (isset($_POST['subjects'])){
            $subjects = $_POST['subjects'];
            $pdtManager->setPDTSubjects($pdtStudent['id'],$subjects);
            header('Location: rooster.php?type=pdt&id='.$id);
            die();
        }
    }
}

$pdtSubjects = $subjectManager->getSubjectsByPdtKey($id);
$pdtSubjectIds = array();
foreach ($pdtSubjects as $pdtSubject){
    array_push($pdtSubjectIds,$pdtSubject['subjectId']);
}
if (sizeof($pdtStudent) == 0){
    header('Location: pdtregistration.php');
    die();
}


if (isset($_POST['trajects'])){
    $trajectFilter = $_POST['trajects']; 
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

        <title>EHB Rooster - Student (PDT)</title>

        <?php include('head.php'); ?>
        
        <link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css">
        <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
        <script type="text/javascript" src="js/bootstrap-multiselect-collapsible-groups.js"></script>
        <style>
        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            max-width: 500px;
        }
        </style>
    </head>
    <body>
        <?php include('header.php'); ?>
        
         <!-- Begin page content -->
        <div class="container">
            <div class="page-header">
                <a href="/"><img class="logo" src="images/logo.png"></img></a>
            </div>
            <p><?=$pdtStudent['firstName'].' '.$pdtStudent['surName']?> <?=isset($trajectFilter) ? "selecteer je vakken voor dit academiejaar" : "selecteer de jaren/opleidingen waar je in zit"?></p>
            <?php
            if (!isset($trajectFilter)){
            ?>
            <p class="lead">Selecteer trajecten:</p>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#pdtTrajects').multiselect({
                        buttonWidth: '400px',
                        dropRight: true
                    });
                });
            </script>
            <form action="pdt.php?id=<?=isset($id) ? $id: ""?>" method="post">
                <div class="btn-group" role="group">
                   <input type="hidden" name="action" value="settrajects">
                   <select name="trajects[]" id="pdtTrajects" multiple="multiple">
                        <?php
                        for ($i = 0 ; $i < sizeof($educations); $i++){
                            $education = $educations[$i];
                            $edugroups = $groupManager->getGroupsByEducationId($education['id']);
                        ?>
                        <optgroup class="department_group department_<?=$education['departmentId']?>" label="<?=$education['name']?>">
                        <?php
                            for ($j = 0 ; $j < sizeof($edugroups); $j++){
                                $group = $edugroups[$j];
                        ?>
                        <option value="<?=$group['groupId']?>"><?=$group['alias']?></option>
                        <?php
                            }
                        ?>
                        </optgroup>
                        <?php
                        }
                        ?>
                    </select>
                    <button id="submitBtn" type="submit" class="btn btn-success">Verder</button>
                </div>
            </form>
            <?php }else{ ?>
            <p class="lead">Selecteer uw vakken voor dit academiejaar:</p>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#pdtSubjects').multiselect({
                        buttonWidth: '400px',
                        dropRight: true
                    });
                });
            </script>
            <form action="pdt.php?id=<?=isset($id) ? $id: ""?>" method="post">
                <div class="btn-group" role="group">
                   <input type="hidden" name="action" value="setsubjects">
                   <select name="subjects[]" id="pdtSubjects" multiple="multiple">
                        <?php
                        foreach ($groups as &$group){
                            if (sizeof($trajectFilter) != 0)
                                if (!in_array($group['groupId'],$trajectFilter)){
                                    continue;
                                }
                            $subjects = $subjectManager->getSubjectsByGroupId($group['groupId']);
                        ?>
                        <optgroup class="department_group department_<?=$group['departmentId']?>" label="<?=$group['alias']?>">
                            <?php
                            foreach ($subjects as $subject){
                            ?>
                            <option value="<?=$subject['subjectId']?>"<?=in_array($subject['subjectId'],$pdtSubjectIds) ? " selected" : ""?>><?=$subject['subjectName']?></option>
                            <?php
                            }
                            ?>
                        </optgroup>
                        <?php
                        }
                        ?>
                    </select>
                    <button id="submitBtn" type="submit" class="btn btn-success">Verder</button>
                </div>
            </form>
            <?php } ?>
            <script>
            $(document).ready(function() {
                if (<?=sizeof($pdtSubjectIds)?> != 0){
                    $('#modal_remember').modal('show');
                }
            });
            </script>
            <div class="modal fade" id="modal_remember">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Kennen we je al?</h4>
                        </div>
                        <div class="modal-body">
                            <p>Hey <?=$pdtStudent['firstName'].' '.$pdtStudent['surName']?>! Kan het zijn dat je al je vakken hebt samengesteld? Klik op 'Bekijk mijn rooster' om direct naar je rooster te gaan.</p>
                            <p>Het is aangeraden om je rooster te bookmarken, zo hoef je niet telkens je naam en email in te geven.</p>
                        </div>
                        <div class="modal-footer">
                            <a href="rooster.php?type=pdt&id=<?=$id?>" id="link_button" class="btn btn-primary">Bekijk mijn rooster</a>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>