<?php
class EducationManager{
    public $db = null;
    
    function __construct($config){
        $this->db = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
        if ($this->db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        $this->db->set_charset("utf8");
    }
    
    function getEducationById($id){
        if (!($stmt = $this->db->prepare("SELECT * FROM educations WHERE id = ?"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("i", $id)) {
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
        if (sizeof($result) == 0)
            return $result;
        return $result[0];
    }
    
    function getEducations(){
        if (!($stmt = $this->db->prepare("SELECT * FROM educations"))) {
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
    
    function getEducationsByDepartmentId($departmentId){
        if (!($stmt = $this->db->prepare("SELECT * FROM educations WHERE departmentId = ?"))) {
            //echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }
        if (!$stmt->bind_param("i", $departmentId)) {
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
    
    function getEducationsByDepartmentCode($departmentCode){
        if (!($stmt = $this->db->prepare("SELECT e.* FROM educations e JOIN departments d ON (d.id = e.departmentId) WHERE d.code = ?"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("s", $departmentCode)) {
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