<?php

class conf {

    public $User;
    public $Pass;
    public $Host;
    public $Db;

    function __construct($dbname = "") {
        if (!$dbname) {
            $curpageURL = $_SERVER["SERVER_NAME"];
            $a = explode('.', $curpageURL);
            if ($a[0] == 'test') {
                $this->Db = 'king';
            } else {
                $this->Db = $a[0];
            }
        } else {
            $this->Db = $dbname;
        }
        $this->User = 'developer';
        $this->Pass = 'Emlr29861152%%';
        $this->Host = '127.0.0.1';
    }

    function GetArray() {
        $ArrayConf = array();
        $ArrayConf['user'] = $this->User;
        $ArrayConf['password'] = $this->Pass;
        $ArrayConf['db'] = $this->Db;
        $ArrayConf['server'] = $this->Host;
        return $ArrayConf;
    }
}
