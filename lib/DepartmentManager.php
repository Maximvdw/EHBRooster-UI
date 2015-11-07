<?php
class DepartmentManager{
    public $db = null;
    
    function __construct($config){
        $this->db = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
        if ($this->db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        $this->db->set_charset("utf8");
    }
    
    function getDepartmentById($id){
        if (!($stmt = $this->db->prepare("SELECT * FROM departments WHERE id = ?"))) {
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
    
    function getDepartmentByCode($id){
        if (!($stmt = $this->db->prepare("SELECT * FROM departments WHERE code = ?"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("s", $id)) {
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
    
    function getDepartments(){
        if (!($stmt = $this->db->prepare("SELECT * FROM departments"))) {
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
}
?>