<?php
$page['menu'] = "lessenrooster";

include_once('config.php');
include_once('lib/GroupManager.php');
include_once('lib/EducationManager.php');
include_once('lib/TimeTableManager.php');
include_once('lib/DepartmentManager.php');
if ($config['maintenance'] == true){
    include('maintenance.php');
    die();
}

$departmentManager = new DepartmentManager($config);
$groupManager = new GroupManager($config);
$educationManager = new EducationManager($config);


$educations = $educationManager->getEducations();
$departments = $departmentManager->getDepartments();

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

        <title>EHB Rooster - Student</title>

        <?php include('head.php'); ?>
    </head>
    <body>
        <?php include('header.php'); ?>
        
         <!-- Begin page content -->
        <div class="container">
            <div class="page-header">
                <a href="/"><img class="logo" src="images/logo.png"></img></a>
            </div>
            <p>Indien uw modeltraject niet in onderstaande lijst of deze staat onder een foute richting gelieve mij te <a href="mailto:maxim.van.de.wynckel@student.ehb.be">contacteren</a>.</p>
            <form action="rooster.php" method="get">
                <p class="lead">Filter op departement:</p>
                <div class="form-group-lg">
                    <script>
                    function filterDepartments(departmentId){
                        if (departmentId == ""){
                            // Show all
                            var department_groups = $('.department_group');
                            department_groups.each(function() {
                              $( this ).show();
                            });
                        }else{
                            // First make sure all department groups are hidden
                            var department_groups = $('.department_group');
                            department_groups.each(function() {
                              $( this ).hide();
                            });
                            // Now show the one we need
                            $('.department_' + departmentId).show();
                        }
                    }
                    </script>
                    <select onchange="filterDepartments(this.value);" class="form-control">
                        <option value="" selected>Alle departementen</option>
                        <?php
                        for ($i = 0 ; $i < sizeof($departments); $i++){
                        ?>
                        <option value="<?=$departments[$i]['id']?>">[<?=$departments[$i]['code']?>] <?=$departments[$i]['name']?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <br>
                <p class="lead">Selecteer uw modeltraject:</p>
                <div class="input-group form-group-lg">
                    <input type="hidden" name="type" value="group">
                    <select onchange="$('#submitBtn').removeAttr('disabled')" name="id" placeholder="Model traject aan de EhB" class="form-control">
                        <option value="" disabled selected>Selecteer een traject ...</option>
                        <?php
                        for ($i = 0 ; $i < sizeof($educations); $i++){
                            $education = $educations[$i];
                            $groups = $groupManager->getGroupsByEducationId($education['id']);
                        ?>
                        <optgroup class="department_group department_<?=$education['departmentId']?>" label="<?=$education['name']?>">
                        <?php
                            for ($j = 0 ; $j < sizeof($groups); $j++){
                                $group = $groups[$j];
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
                   <span class="input-group-btn">
                        <button id="submitBtn" type="submit" class="btn-lg btn btn-success" disabled>Verder</button>
                   </span>
                </div>
            </form>
        </div>

        <?php include('footer.php'); ?>
    </body>
</html>