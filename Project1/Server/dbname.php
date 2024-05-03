<?php

class dbname {

    private $mydbname;

    function __construct() {
        $curpageURL = $_SERVER["SERVER_NAME"];
        $this->mydbname = $curpageURL;
    }

    public function getdbname() {
        $db = array();
        $db = explode('.', $this->mydbname);
        if($db[0] == 'localhost'){
            return 'RoomP';
        }else{
            return $db[0];
        }
    }

}

?>
