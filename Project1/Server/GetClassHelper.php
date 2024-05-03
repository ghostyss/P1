<?php

class GetClassHelper {

    private $Connection;
    public $dbname;

    function __construct($dbname = '') {
        if ($dbname) {
            $this->dbname = $dbname;
        } else {
            if (!class_exists('dbname')) {
                include_once($_SERVER['DOCUMENT_ROOT'] . '/mrt/Server/dbname.php');
            }
            $m = new dbname();
            //print_r($m->getdbname());
            $this->dbname = $m->getdbname();
        }
        if (!class_exists('Databasecustomer')) {
            include_once('Databasecustomer.php');
        }
        $this->Connection = new Databasecustomer($this->dbname);
    }

    public function GetClass($NameClass) {
        try {
            if (!class_exists($NameClass)) {
                include_once ($NameClass . '.php');
            }
            if($NameClass == 'lead'){
                $NameClass = 'mylead';
            }
            $Class = new $NameClass($this->dbname);
            if(method_exists($Class,'setDB')){
                $Class->setDB($this->Connection);
            }
            return $Class;
        } catch (Exception $e) {
            die($e->ErrorInfo);
            //return json_encode(array('Code' => '500', 'Msj' => $e->ErrorInfo));
        }
    }

}

#  --- [ END Class ] ---
?>    
