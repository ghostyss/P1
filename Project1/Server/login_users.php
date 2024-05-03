<?php

class login_users {

    private $user_id;
    private $user_level;
    private $restricted;
    private $username;
    private $firstname;
    private $lastname;
    private $nameu;
    private $email;
    private $email2;
    private $password;
    private $timestamp;
    private $phone;
    private $mobile;
    private $fax;
    private $passz;
    private $zid;
    private $zcalid;
    private $ztaskid;
    private $passreset;
    private $twilio_phone;
    private $db;
    private $web;
    private $pic;
    private $idq;

    function __construct($dbname = '') {
        
    }

    public function setDB($conn) {
        $this->db = $conn;
    }

    public function DisconnectDb() {
        return $this->db->disconnect();
    }

    private function setidlogin_users($ID) {
        $this->user_id = $ID;
    }

    public function getuser_id() {
        return $this->user_id;
    }

    public function setuser_level($auser_level) {
        $auser_level = addslashes($auser_level);
        $this->user_level = $auser_level;
        $this->db->add("login_users", "user_level", "('$auser_level')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getuser_level() {
        return $this->user_level;
    }

    //update property
    public function updateuser_level($myId, $auser_level) {
        $auser_level = addslashes($auser_level);
        $this->user_level = $auser_level;
        $this->db->updateRow("login_users", "user_level", $auser_level, "user_id", $myId);
    }

    public function setrestricted($arestricted) {
        $arestricted = addslashes($arestricted);
        $this->restricted = $arestricted;
        $this->db->add("login_users", "restricted", "('$arestricted')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getrestricted() {
        return $this->restricted;
    }

    //update property
    public function updaterestricted($myId, $arestricted) {
        $arestricted = addslashes($arestricted);
        $this->restricted = $arestricted;
        $this->db->updateRow("login_users", "restricted", $arestricted, "user_id", $myId);
    }

    public function setusername($ausername) {
        $ausername = addslashes($ausername);
        $this->username = $ausername;
        $this->db->add("login_users", "username", "('$ausername')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getusername() {
        return $this->username;
    }

    //update property
    public function updateusername($myId, $ausername) {
        $ausername = addslashes($ausername);
        $this->username = $ausername;
        $this->db->updateRow("login_users", "username", $ausername, "user_id", $myId);
    }

    public function setfirstname($afirstname) {
        $afirstname = addslashes($afirstname);
        $this->firstname = $afirstname;
        $this->db->add("login_users", "firstname", "('$afirstname')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return
    public function getweb() {
        return $this->web;
    }

    //update property
    public function updateweb($myId, $aweb) {
        $aweb = addslashes($aweb);
        $this->web = $aweb;
        $this->db->updateRow("login_users", "web", $aweb, "user_id", $myId);
    }

    public function getpic() {
        return $this->pic;
    }

    //update property
    public function updatepic($myId, $apic) {
        $apic = addslashes($apic);
        $this->pic = $apic;
        $this->db->updateRow("login_users", "pic", $apic, "user_id", $myId);
    }

    public function getidq() {
        return $this->idq;
    }

    //update property
    public function updateidq($myId, $aidq) {
        $aidq = addslashes($aidq);
        $this->idq = $aidq;
        $this->db->updateRow("login_users", "idq", $aidq, "user_id", $myId);
    }

    //vars values return 
    public function getfirstname() {
        return $this->firstname;
    }

    //update property
    public function updatefirstname($myId, $afirstname) {
        $afirstname = addslashes($afirstname);
        $this->firstname = $afirstname;
        $this->db->updateRow("login_users", "firstname", $afirstname, "user_id", $myId);
    }

    public function setlastname($alastname) {
        $alastname = addslashes($alastname);
        $this->lastname = $alastname;
        $this->db->add("login_users", "lastname", "('$alastname')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getlastname() {
        return $this->lastname;
    }

    //update property
    public function updatelastname($myId, $alastname) {
        $alastname = addslashes($alastname);
        $this->lastname = $alastname;
        $this->db->updateRow("login_users", "lastname", $alastname, "user_id", $myId);
    }

    public function setnameu($aname) {
        $aname = addslashes($aname);
        $this->nameu = $aname;
        $this->db->add("login_users", "nameu", "('$aname')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getnameu() {
        return $this->nameu;
    }

    public function getname() {
        return $this->nameu;
    }

    //update property
    public function updatenameu($myId, $aname) {
        $aname = addslashes($aname);
        $this->nameu = $aname;
        $this->db->updateRow("login_users", "nameu", $aname, "user_id", $myId);
    }

    public function setemail($aemail) {
        $aemail = addslashes($aemail);
        $this->email = $aemail;
        $this->db->add("login_users", "email", "('$aemail')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getemail() {
        return $this->email;
    }

    //update property
    public function updateemail($myId, $aemail) {
        $aemail = addslashes($aemail);
        $this->email = $aemail;
        $this->db->updateRow("login_users", "email", $aemail, "user_id", $myId);
    }

    public function setemail2($aemail2) {
        $aemail2 = addslashes($aemail2);
        $this->email2 = $aemail2;
        $this->db->add("login_users", "email2", "('$aemail2')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getemail2() {
        return $this->email2;
    }

    //update property
    public function updateemail2($myId, $aemail2) {
        $aemail2 = addslashes($aemail2);
        $this->email2 = $aemail2;
        $this->db->updateRow("login_users", "email2", $aemail2, "user_id", $myId);
    }

    public function setpassword($apassword) {
        $apassword = addslashes($apassword);
        $this->password = $apassword;
        $this->db->add("login_users", "password", "('$apassword')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getpassword() {
        return $this->password;
    }

    //update property
    public function updatepassword($myId, $apassword) {
        $apassword = addslashes($apassword);
        $this->password = $apassword;
        $this->db->updateRow("login_users", "password", $apassword, "user_id", $myId);
    }

    public function settimestamp($atimestamp) {
        $atimestamp = addslashes($atimestamp);
        $this->timestamp = $atimestamp;
        $this->db->add("login_users", "timestamp", "('$atimestamp')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function gettimestamp() {
        return $this->timestamp;
    }

    //update property
    public function updatetimestamp($myId, $atimestamp) {
        $atimestamp = addslashes($atimestamp);
        $this->timestamp = $atimestamp;
        $this->db->updateRow("login_users", "timestamp", $atimestamp, "user_id", $myId);
    }

    public function setphone($aphone) {
        $aphone = addslashes($aphone);
        $this->phone = $aphone;
        $this->db->add("login_users", "phone", "('$aphone')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getphone() {
        return $this->phone;
    }

    //update property
    public function updatephone($myId, $aphone) {
        $aphone = addslashes($aphone);
        $this->phone = $aphone;
        $this->db->updateRow("login_users", "phone", $aphone, "user_id", $myId);
    }

    public function setmobile($amobile) {
        $amobile = addslashes($amobile);
        $this->mobile = $amobile;
        $this->db->add("login_users", "mobile", "('$amobile')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getmobile() {
        return $this->mobile;
    }

    //update property
    public function updatemobile($myId, $amobile) {
        $amobile = addslashes($amobile);
        $this->mobile = $amobile;
        $this->db->updateRow("login_users", "mobile", $amobile, "user_id", $myId);
    }

    public function setfax($afax) {
        $afax = addslashes($afax);
        $this->fax = $afax;
        $this->db->add("login_users", "fax", "('$afax')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getfax() {
        return $this->fax;
    }

    //update property
    public function updatefax($myId, $afax) {
        $afax = addslashes($afax);
        $this->fax = $afax;
        $this->db->updateRow("login_users", "fax", $afax, "user_id", $myId);
    }

    public function setpassz($apassz) {
        $apassz = addslashes($apassz);
        $this->passz = $apassz;
        $this->db->add("login_users", "passz", "('$apassz')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getpassz() {
        return $this->passz;
    }

    //update property
    public function updatepassz($myId, $apassz) {
        $apassz = addslashes($apassz);
        $this->passz = $apassz;
        $this->db->updateRow("login_users", "passz", $apassz, "user_id", $myId);
    }

    public function setzid($azid) {
        $azid = addslashes($azid);
        $this->zid = $azid;
        $this->db->add("login_users", "zid", "('$azid')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getzid() {
        return $this->zid;
    }

    //update property
    public function updatezid($myId, $azid) {
        $azid = addslashes($azid);
        $this->zid = $azid;
        $this->db->updateRow("login_users", "zid", $azid, "user_id", $myId);
    }

    public function setzcalid($azcalid) {
        $azcalid = addslashes($azcalid);
        $this->zcalid = $azcalid;
        $this->db->add("login_users", "zcalid", "('$azcalid')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getzcalid() {
        return $this->zcalid;
    }

    //update property
    public function updatezcalid($myId, $azcalid) {
        $azcalid = addslashes($azcalid);
        $this->zcalid = $azcalid;
        $this->db->updateRow("login_users", "zcalid", $azcalid, "user_id", $myId);
    }

    public function setztaskid($aztaskid) {
        $aztaskid = addslashes($aztaskid);
        $this->ztaskid = $aztaskid;
        $this->db->add("login_users", "ztaskid", "('$aztaskid')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getztaskid() {
        return $this->ztaskid;
    }

    //update property
    public function updateztaskid($myId, $aztaskid) {
        $aztaskid = addslashes($aztaskid);
        $this->ztaskid = $aztaskid;
        $this->db->updateRow("login_users", "ztaskid", $aztaskid, "user_id", $myId);
    }

    public function setpassreset($apassreset) {
        $apassreset = addslashes($apassreset);
        $this->passreset = $apassreset;
        $this->db->add("login_users", "passreset", "('$apassreset')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function getpassreset() {
        return $this->passreset;
    }

    //update property
    public function updatepassreset($myId, $apassreset) {
        $apassreset = addslashes($apassreset);
        $this->passreset = $apassreset;
        $this->db->updateRow("login_users", "passreset", $apassreset, "user_id", $myId);
    }

    public function settwilio_phone($atwilio_phone) {
        $atwilio_phone = addslashes($atwilio_phone);
        $this->twilio_phone = $atwilio_phone;
        $this->db->add("login_users", "twilio_phone", "('$atwilio_phone')");
        $this->setidlogin_users($this->db->lastInsertId());
    }

    //vars values return 
    public function gettwilio_phone() {
        return $this->twilio_phone;
    }

    //update property
    public function updatetwilio_phone($myId, $atwilio_phone) {
        $atwilio_phone = addslashes($atwilio_phone);
        $this->twilio_phone = $atwilio_phone;
        $this->db->updateRow("login_users", "twilio_phone", $atwilio_phone, "user_id", $myId);
    }

    public function getlogin_usersById($unId) {
        $dbResult = $this->db->getRowByID("login_users", "user_id", $unId);
        return $dbResult;
    }

    //return array with all idlogin_userss
    public function getAlllogin_users() {
        $dbResult = $this->db->getAllRows("login_users");
        return $dbResult;
    }

    public function getAlllogin_usersForColumnValue($ID_column, $value_wanted) {
        $dbResult = $this->db->getListRowsByID("login_users", $ID_column, $value_wanted);

        return $dbResult;
    }

    public function deletelogin_users($myId) {
        $this->db->deleteRow("login_users", "user_id", $myId);
        // $this->setIdevent("");
    }

    public function getlogin_usersByusername($unId) {
        $dbResult = $this->db->getRowByID("login_users", "username", $unId);
        return $dbResult;
    }

    public function getlogin_usersByemail($unId) {
        //echo $unId;
        $dbResult = $this->db->getRowByID("login_users", "email", "'" . $unId . "'");
        return $dbResult;
    }

    public function getlogin_usersByToken($unId) {
        //echo $unId;
        $dbResult = $this->db->getRowByID("login_users", "passreset", "'" . $unId . "'");
        return $dbResult;
    }

    public function getlogin_usersByTokenkey($unId) {
        //echo $unId;
        $dbResult = $this->db->getRowByID("login_users", "passresetkey", "'" . $unId . "'");
        return $dbResult;
    }

}

#  --- [ END Class ] ---
?>    
