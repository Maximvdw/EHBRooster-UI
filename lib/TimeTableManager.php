<?php
class TimeTableManager{
    public $db = null;
    
    function __construct($config){
        $this->db = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
        if ($this->db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        $this->db->set_charset("utf8");
    }
    
    function getStartTimeStamp(){
        $query = "SELECT startTimeStamp FROM timetable;";
        if (!($stmt = $this->db->prepare($query))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }

        if (!$stmt->execute()) {
            //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        
        $timeStamp = 0;
        $queryResult = $stmt->get_result();
        while($row = $queryResult->fetch_array(MYSQLI_ASSOC)){ 
            $timeStamp = $row['startTimeStamp'];
        }
        return $timeStamp;
    }
    
    function getLastSync(){
        $query = "SELECT lastSync FROM timetable;";
        if (!($stmt = $this->db->prepare($query))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }

        if (!$stmt->execute()) {
            //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        
        $timeStamp = 0;
        $queryResult = $stmt->get_result();
        while($row = $queryResult->fetch_array(MYSQLI_ASSOC)){ 
            $timeStamp = $row['lastSync'];
        }
        return $timeStamp;
    }
    
    function getWeekTimeStamp($week){
        $query = "SELECT startTimeStamp FROM timetable;";
        if (!($stmt = $this->db->prepare($query))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }

        if (!$stmt->execute()) {
            //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        
        $timeStamp = 0;
        $queryResult = $stmt->get_result();
        while($row = $queryResult->fetch_array(MYSQLI_ASSOC)){ 
            $timeStamp = $row['startTimeStamp'] + (($week - 1) * 7 * 24 * 60 * 60);
        }
        return $timeStamp;
    }
    function getWeekActivitiesBySubjects($subjects = array(),$week){
        $result = $this->getWeekActivitiesBySubjectsName($subjects,$week);
        return $result;
    }
    
    function getWeekActivitiesBySubjectsName($subjects = array(),$week){
        if (sizeof($subjects) == 0)
            return false;
        $query = "SELECT *,GROUP_CONCAT(DISTINCT lector) lectors FROM activities a JOIN activities_subjects a_s ON (a_s.activities_id = a.id) JOIN subjects s ON (s.id = a_s.subjects_id) JOIN days d ON (d.id = a.dayId) JOIN weeks w ON (w.id = d.weekId) WHERE w.weekInYear = ? AND (s.subjectId = '".mysqli_real_escape_string($this->db,$subjects[0])."'";
        for ($i = 1 ; $i < sizeof($subjects) ; $i++){
            $subject = $subjects[$i];
            $query .= " OR s.subjectId='".mysqli_real_escape_string($this->db,$subject)."'";
        }
        $query .= ") GROUP BY a.name,a.beginTimeUnix ORDER BY a.beginTimeUnix ASC";
        if (!($stmt = $this->db->prepare($query))) {
            //echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }
        
        if (!$stmt->bind_param("i", $week)) {
            return false;
        }
        if (!$stmt->execute()) {
            //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        
        $result = array();
        $queryResult = $stmt->get_result();
        while($row = $queryResult->fetch_array(MYSQLI_ASSOC)){ 
            array_push($result,$row);
        }
        return $result;
    }
    
    function getWeekActivitiesByLector($lector,$week){
        $query = "SELECT *,GROUP_CONCAT(DISTINCT lector) lectors FROM activities a JOIN days d ON (d.id = a.dayId) JOIN weeks w ON (w.id = d.weekId) WHERE w.weekInYear = ? AND (a.lector = ?) GROUP BY a.lector,a.beginTimeUnix,a.endTimeUnix ORDER BY a.beginTimeUnix ASC";
        if (!($stmt = $this->db->prepare($query))) {
            //echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }
        
        if (!$stmt->bind_param("is", $week,$lector)) {
            return false;
        }
        if (!$stmt->execute()) {
            //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        
        $result = array();
        $queryResult = $stmt->get_result();
        while($row = $queryResult->fetch_array(MYSQLI_ASSOC)){ 
            array_push($result,$row);
        }
        return $result;
    }
    
    function getAllActivitiesByLector($lector,$week){
        $query = "SELECT *,GROUP_CONCAT(DISTINCT lector) lectors FROM activities a WHERE (a.lector = ?) GROUP BY a.lector,a.beginTimeUnix ORDER BY a.beginTimeUnix ASC";
        if (!($stmt = $this->db->prepare($query))) {
            //echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }
        
        if (!$stmt->bind_param("s", $lector)) {
            return false;
        }
        if (!$stmt->execute()) {
            //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        
        $result = array();
        $queryResult = $stmt->get_result();
        while($row = $queryResult->fetch_array(MYSQLI_ASSOC)){ 
            array_push($result,$row);
        }
        return $result;
    }
    
    function getAllActivitiesBySubjects($subjects = array()){
        $query = "SELECT *,GROUP_CONCAT(DISTINCT lector) FROM activities a JOIN activities_subjects a_s ON (a_s.activities_id = a.id) JOIN subjects s ON (s.id = a_s.subjects_id) JOIN days d ON (d.id = a.dayId) JOIN weeks w ON (w.id = d.weekId) WHERE (s.subjectId = '".mysqli_real_escape_string($this->db,$subjects[0])."'";
        for ($i = 1 ; $i < sizeof($subjects) ; $i++){
            $subject = $subjects[$i];
            $query .= " OR s.subjectId='".mysqli_real_escape_string($this->db,$subject)."'";
        }
        $query .= ") GROUP BY a.name,a.beginTimeUnix ORDER BY a.beginTimeUnix ASC";
        
        if (!($stmt = $this->db->prepare($query))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        
        if (!$stmt->execute()) {
            //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        
        $result = array();
        $queryResult = $stmt->get_result();
        while($row = $queryResult->fetch_array(MYSQLI_ASSOC)){ 
            array_push($result,$row);
        }
        return $result;
    }
    
    function getAllActivitiesByGroupId($groupId){
        if (!($stmt = $this->db->prepare("SELECT *,GROUP_CONCAT(DISTINCT lector) FROM activities a JOIN activities_subjects a_s ON (a_s.activities_id = a.id) JOIN subjects s ON (a_s.subjects_id = s.id) WHERE s.groupId = ? GROUP BY s.subjectName,a.beginTimeUnix,a.groups ORDER BY a.beginTimeUnix ASC;"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("i", $groupId)) {
            //echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        if (!$stmt->execute()) {
            //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        
        $result = array();
        $queryResult = $stmt->get_result();
        while($row = $queryResult->fetch_array(MYSQLI_ASSOC)){ 
            array_push($result,$row);
        }
        return $result;
    }
}
?>