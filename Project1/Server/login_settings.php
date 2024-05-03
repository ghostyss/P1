<?php

class login_settings {

    private $id;
    private $option_name;
    private $option_value;
    private $db;

    function __construct($dbname = '') {
        
    }

    public function setDB($conn) {
        $this->db = $conn;
    }

    public function DisconnectDb() {
        return $this->db->disconnect();
    }

    private function setid($ID) {
        $this->id = $ID;
    }

    public function getid() {
        return $this->id;
    }

    public function setoption_name($aoption_name) {
        $aoption_name = addslashes($aoption_name);
        $this->option_name = $aoption_name;
        $this->db->add("login_settings", "option_name", "('$aoption_name')");
        $this->setid($this->db->lastInsertId());
    }

    //vars values return
    public function getoption_name() {
        return $this->option_name;
    }

    //update property
    public function updateoption_name($myId, $aoption_name) {
        $aoption_name = addslashes($aoption_name);
        $this->option_name = $aoption_name;
        $this->db->updateRow("login_settings", "option_name", $aoption_name, "id", $myId);
    }

    public function setoption_value($aoption_value) {
        $aoption_value = addslashes($aoption_value);
        $this->option_value = $aoption_value;
        $this->db->add("login_settings", "option_value", "('$aoption_value')");
        $this->setid($this->db->lastInsertId());
    }

    //vars values return
    public function getoption_value() {
        return $this->option_value;
    }

    //update property
    public function updateoption_value($myId, $aoption_value) {
        $aoption_value = addslashes($aoption_value);
        $this->option_value = $aoption_value;
        $this->db->updateRow("login_settings", "option_value", $aoption_value, "id", $myId);
    }

    public function getlogin_settingsById($unId) {
        $dbResult = $this->db->getRowByID("login_settings", "id", $unId);
        return $dbResult;
    }

    //return array with all ids
    public function getAlllogin_settingss() {
        $dbResult = $this->db->getAllRows("login_settings");
        return $dbResult;
    }

}

#  --- [ END Class ] ---
?>
