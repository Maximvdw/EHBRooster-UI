<?php
class PDTManager{
    public $db = null;
    
    function __construct($config){
        $this->db = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
        if ($this->db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        $this->db->set_charset("utf8");
    }
 
    function getPDTKeyFromEmail($email){
        if (!($stmt = $this->db->prepare("SELECT * FROM pdt WHERE email=?;"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("s", $email)) {
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
        return $result[0]['pdtId'];
    }
    
    function registerPDT($firstname,$surname,$email){
        $prevKey = $this->getPDTKeyFromEmail($email);
        if ($prevKey != null){
            return $prevKey;
        }
        if (!($stmt = $this->db->prepare("INSERT INTO pdt (firstName,surName,email,pdtId) VALUES(?,?,?,?)"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }
        $key = md5($firstname.$surname.$email.time());
        if (!$stmt->bind_param("ssss", $firstname,$surname,$email,$key)) {
            echo "Binding parameters failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }
        
        return $key;
    }
    function resetPDTSubjects($pdtId){
        $query = "DELETE FROM pdt_subjects WHERE pdt_id = ?";
        if (!($stmt = $this->db->prepare($query))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        
        if (!$stmt->bind_param("i", $pdtId)) {
            return false;
        }
        if (!$stmt->execute()) {
            //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        return true;  
    }
    function setPDTSubjects($pdtId,$subjects){
        $this->resetPDTSubjects($pdtId);
        foreach ($subjects as $subject){
            $query = "INSERT INTO pdt_subjects (pdt_id,subject) VALUES(?,?)";
            if (!($stmt = $this->db->prepare($query))) {
                //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                return false;
            }
            
            if (!$stmt->bind_param("is", $pdtId,$subject)) {
                return false;
            }
            if (!$stmt->execute()) {
                //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return false;
            }
        }
        return true;
    }
    
    function getPDTStudentByKey($key){
        if (!($stmt = $this->db->prepare("SELECT * FROM pdt WHERE pdtId=?;"))) {
            //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        if (!$stmt->bind_param("s", $key)) {
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