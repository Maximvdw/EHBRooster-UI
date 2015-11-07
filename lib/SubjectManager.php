<?php
class SubjectManager{
    public $db = null;
    
    function __construct($config){
        $this->db = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
        if ($this->db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        $this->db->set_charset("utf8");
    }
    
    function getLectors(){
        if (!($stmt = $this->db->prepare("SELECT DISTINCT lector FROM activities WHERE lector != '' AND lector IS NOT NULL ORDER BY lector ASC"))) {
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
    
    function getSubjectsByLector($lector){
        if (!($stmt = $this->db->prepare("SELECT DISTINCT s.* FROM subjects s JOIN activities_subjects a_s ON (a_s.subjects_id = s.id) JOIN activities a ON (a_s.activities_id = a.id) WHERE a.lector = ? AND a.lector != '' AND a.lector IS NOT NULL GROUP BY s.subjectName,a.beginTimeUnix,a.groups"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("s", $lector)) {
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

    function getSubjects(){
        if (!($stmt = $this->db->prepare("SELECT * FROM subjects"))) {
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
    
    function getSubjectsByPdtKey($pdtKey){
        if (!($stmt = $this->db->prepare("SELECT * FROM subjects s JOIN pdt_subjects ps ON (ps.subject = s.subjectId) JOIN pdt p ON (p.id = ps.pdt_id) WHERE p.pdtId= ?"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("s", $pdtKey)) {
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
    
    function getSubjectsByGroupId($groupId){
        if (!($stmt = $this->db->prepare("SELECT * FROM subjects s JOIN groups_subjects sg ON (sg.subjects_id = s.id) JOIN groups g ON (g.id = sg.groups_id) WHERE g.groupId=?;"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("s", $groupId)) {
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