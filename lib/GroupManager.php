<?php
class GroupManager{
    public $db = null;
    
    function __construct($config){
        $this->db = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
        if ($this->db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        $this->db->set_charset("utf8");
    }
    
    function getGroupByGroupId($groupId){
        if (!($stmt = $this->db->prepare("SELECT * FROM groups WHERE groupId = ?"))) {
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
        if (sizeof($result) == 0)
            return $result;
        return $result[0];
    }
    
    function getGroupById($id){
        if (!($stmt = $this->db->prepare("SELECT * FROM groups WHERE id = ?"))) {
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
    
    function getGroups(){
        if (!($stmt = $this->db->prepare("SELECT * FROM groups"))) {
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
    
    function getGroupsByEducationId($eduationId){
        if (!($stmt = $this->db->prepare("SELECT * FROM groups WHERE educationId = ?"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("i", $eduationId)) {
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