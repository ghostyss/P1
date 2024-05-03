<?php

class login_levels {

    private $id;
    private $level_name;
    private $level_level;
    private $level_disabled;
    private $level_redirect;
    private $welcome_email;

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

    public function setlevel_name($alevel_name) {
        $this->level_name = $alevel_name;
        $this->db->add("login_levels", "level_name", "('$alevel_name')");
        $this->setid($this->db->lastInsertId());
    }

    //vars values return 
    public function getlevel_name() {
        return $this->level_name;
    }

    //update property
    public function updatelevel_name($myId, $alevel_name) {
        $this->level_name = $alevel_name;
        $this->db->updateRow("login_levels", "level_name", $alevel_name, "id", $myId);
    }

    public function setlevel_level($alevel_level) {
        $this->level_level = $alevel_level;
        $this->db->add("login_levels", "level_level", "('$alevel_level')");
        $this->setid($this->db->lastInsertId());
    }

    //vars values return 
    public function getlevel_level() {
        return $this->level_level;
    }

    //update property
    public function updatelevel_level($myId, $alevel_level) {
        $this->level_level = $alevel_level;
        $this->db->updateRow("login_levels", "level_level", $alevel_level, "id", $myId);
    }

    public function setlevel_disabled($alevel_disabled) {
        $this->level_disabled = $alevel_disabled;
        $this->db->add("login_levels", "level_disabled", "('$alevel_disabled')");
        $this->setid($this->db->lastInsertId());
    }

    //vars values return 
    public function getlevel_disabled() {
        return $this->level_disabled;
    }

    //update property
    public function updatelevel_disabled($myId, $alevel_disabled) {
        $this->level_disabled = $alevel_disabled;
        $this->db->updateRow("login_levels", "level_disabled", $alevel_disabled, "id", $myId);
    }

    public function setwelcome_email($awelcome_email) {
        $this->welcome_email = $awelcome_email;
        $this->db->add("login_levels", "welcome_email", "('$awelcome_email')");
        $this->setid($this->db->lastInsertId());
    }

    //vars values return 
    public function getwelcome_email() {
        return $this->welcome_email;
    }

    //update property
    public function updatewelcome_email($myId, $awelcome_email) {
        $this->welcome_email = $awelcome_email;
        $this->db->updateRow("login_levels", "welcome_email", $awelcome_email, "id", $myId);
    }

    public function setlevel_redirect($alevel_redirect) {
        $this->level_redirect = $alevel_redirect;
        $this->db->add("login_levels", "level_redirect", "('$alevel_redirect')");
        $this->setid($this->db->lastInsertId());
    }

    //vars values return 
    public function getlevel_redirect() {
        return $this->level_redirect;
    }

    //update property
    public function updatelevel_redirect($myId, $alevel_redirect) {
        $this->level_redirect = $alevel_redirect;
        $this->db->updateRow("login_levels", "level_redirect", $alevel_redirect, "id", $myId);
    }

    //return a object form DB by ID
    public function getlogin_levelsById($unId) {
        $dbResult = $this->db->getRowByID("login_levels", "id", $unId);
        return $dbResult;
    }

    public function getlogin_levelsBylevel_level($unId) {
        $dbResult = $this->db->getRowByID("login_levels", "level_level", $unId);
        return $dbResult;
    }

    public function getlogin_levelsBylevel_name($unId) {
        $dbResult = $this->db->getRowByID("login_levels", 'level_name', $unId);
        return $dbResult;
    }

    //return array with all ids
    public function getAlllogin_levelss() {
        $dbResult = $this->db->getAllRows("login_levels");
        return $dbResult;
    }

}

?>
