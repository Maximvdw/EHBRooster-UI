<?php
class StatisticsManager{
    public $db = null;
    
    function __construct($config){
        $this->db = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
        if ($this->db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        $this->db->set_charset("utf8");
    }
    
    function getSyncs($count){
        if (!($stmt = $this->db->prepare("SELECT * FROM sync LIMIT ?"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("i", $count)) {
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
        return $result;
    }
}
?>