<?php
include_once ($_SERVER['DOCUMENT_ROOT'] . '/mrt/security/classes/conf.php');
//print_r($_SERVER['DOCUMENT_ROOT'] . 'mrt/security/classes/conf.php');
Class Databasecustomer {

    private $mydb;
    public $error;

    function __construct($dbname = "") {
        //include('/var/www/html/mrt/security/classes/config.php');
        //print_r(getcwd());
        $config = new conf($dbname);
        $gaSql = $config->GetArray();
        /*$gaSql['user'] = $dbUser;
        $gaSql['password'] = $dbPass;
        $gaSql['db'] = $dbname;
        $gaSql['server'] = $host;*/
        //print_r($gaSql);
        if (!($gaSql['link'] = new mysqli($gaSql['server'], $gaSql['user'], $gaSql['password']))) {
            $this->error = 'Could not open connection to server';
            die('Could not open connection to server');
        }
        //print_r($gaSql['link']);
        if (!($gaSql['link']->select_db($gaSql['db']))) {
            die('Could not select database ' . $gaSql['db']);
        }
        $this->mydb = $gaSql['link'];
        return $this;
    }
    
    public function lastInsertId(){
        return $this->GetLastId();
    }
    
    public function GetLastId() {
        return $this->mydb->insert_id;
    }

    public function execute_sql($sql) {
        try {
            $rResult = mysqli_query($this->mydb, $sql) or die(mysqli_error($this->mydb));
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "" . "<br/>";
            die();
        }
        return $rResult;
    }

    public function getconnectionid() {
        $sql = 'SELECT CONNECTION_ID()';
        $result = mysqli_query($this->mydb, $sql) or die(mysqli_error($this->mydb));
        $result = $result->fetch_assoc();
        return $result;
    }

    public function disconnect() {
        $id = $this->getconnectionid();
        $id = $id['CONNECTION_ID()'];
        $sql = 'KILL ' . $id;
        $result = mysqli_query($this->mydb, $sql);
        return $result;
    }

    public function add($nametable, $campos, $variables) {
        try {
            $this->execute_sql("INSERT INTO $nametable ($campos) VALUES $variables");
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$nametable,$campos,$variables" . "<br/>";
            die();
        }
    }

    public function getRowByID($table, $ID_column, $value_wanted) {
        try {
            $rResult = $this->execute_sql("SELECT * FROM $table WHERE $ID_column = ($value_wanted) LIMIT 1");
            $obj = $rResult->fetch_object($table);
            return $obj;
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table,$ID_column,$value_wanted" . "<br/>";
            die();
        }
    }
    
    public function getRowByIDRows($table, $ID_column, $value_wanted, $ListRows) {
        try {
            $rResult = $this->execute_sql("SELECT $ListRows FROM $table WHERE $ID_column = ($value_wanted) LIMIT 1");
            $obj = $rResult->fetch_object($table);
            return $obj;
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table,$ID_column,$value_wanted" . "<br/>";
            die();
        }
    }

    public function getListRowsByID($table, $ID_column, $value_wanted) {
        try {
            $result = $this->execute_sql("SELECT * FROM $table WHERE $ID_column = ($value_wanted) ");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table,$ID_column,$value_wanted" . "<br/>";
            die();
        }
    }

    public function getListRowsByIDLike($table, $ID_column, $value_wanted) {
        try {
            $result = $this->execute_sql("SELECT * FROM $table WHERE $ID_column LIKE '$value_wanted' ");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table,$ID_column,$value_wanted" . "<br/>";
            die();
        }
    }

    public function getListRowsByIDLike2($table, $ID_column, $value_wanted, $ID_column2, $value_wanted2) {
        try {
            $result = $this->execute_sql("SELECT * FROM $table WHERE $ID_column LIKE ($value_wanted) AND $ID_column LIKE (%$value_wanted%)");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table,$ID_column,$value_wanted" . "<br/>";
            die();
        }
    }

    public function getListRowsByID2($table, $ID_column, $value_wanted, $ID_column2, $value_wanted2) {
        try {
            $result = $this->execute_sql("SELECT * FROM $table WHERE $ID_column = ($value_wanted) AND  $ID_column2 = ($value_wanted2)");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table,$ID_column,$value_wanted" . "<br/>";
            die();
        }
    }

    public function getListRowsByID3($table, $ID_column, $value_wanted, $ID_column2, $value_wanted2, $ID_column3, $value_wanted3) {
        try {
            $result = $this->execute_sql("SELECT * FROM $table WHERE $ID_column = ($value_wanted) AND  $ID_column2 = ($value_wanted2) AND  $ID_column3 = ($value_wanted3)");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table,$ID_column,$value_wanted" . "<br/>";
            die();
        }
    }

    public function ConvertRows($rResult, $table) {
        $ret = array();
        while ($aRow = $rResult->fetch_object($table)) {
            $ret[] = $aRow;
        }
        return $ret;
    }

    public function getRowQ($query) {
        try {
            $result = $this->execute_sql($query);
            return $result;
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$query" . "<br/>";
            die();
        }
    }

    public function getAllRows($table) {
        try {
            $result = $this->execute_sql("SELECT * FROM $table");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table" . "<br/>";
            die();
        }
    }

    public function getListRowsByIDContains($table, $ID_column, $value_wanted) {
        try {
            $result = $this->execute_sql("SELECT * FROM $table WHERE $ID_column LIKE ($value_wanted) ");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table,$ID_column,$value_wanted" . "<br/>";
            die();
        }
    }

    public function getaListRows($table, $listRows) {
        try {
            $result = $this->execute_sql("SELECT $listRows FROM $table");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table" . "<br/>";
            die();
        }
    }

    public function getColumnsByIDContains($table, $ID_column, $value_wanted, $listRows) {
        try {
            $result = $this->execute_sql("SELECT $listRows FROM $table WHERE $ID_column LIKE ($value_wanted) ");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table,$ID_column,$value_wanted" . "<br/>";
            die();
        }
    }

    public function getColumnsByIDContains2($table, $ID_column, $value_wanted, $ID_column2, $value_wanted2, $listRows) {
        try {
            $result = $this->execute_sql("SELECT $listRows FROM $table WHERE $ID_column LIKE ($value_wanted) AND $ID_column2 LIKE ($value_wanted2) ");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table,$ID_column,$value_wanted" . "<br/>";
            die();
        }
    }

    public function deleteRow($nametable, $ID_campo, $id) {
        $this->execute_sql("DELETE FROM $nametable WHERE $ID_campo IN ($id)");
    }

    public function updateRow($nametable, $campo, $variable, $ID_campo, $id) {
        try {
            $this->execute_sql("Update $nametable Set $campo='$variable' WHERE ((($ID_campo)='$id'))");
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$nametable,$campo,$variable" . "<br/>";
            die();
        }
    }
    
    public function updateRowCompress($nametable, $campo, $variable, $ID_campo, $id) {
        try {
            $this->execute_sql("Update $nametable Set $campo=$variable WHERE ((($ID_campo)='$id'))");
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$nametable,$campo,$variable" . "<br/>";
            die();
        }
    }

    public function updateRowBlob($nametable, $campo, $variable, $ID_campo, $id) {
        try {
            $this->execute_sql("Update $nametable Set $campo=LOAD_FILE('$variable') WHERE ((($ID_campo)='$id'))");
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$nametable,$campo,$variable" . "<br/>";
            die();
        }
    }

    public function getAllRowsOrderbyWithLimit($table, $limit, $orderby) {
        try {
            $result = $this->execute_sql("SELECT * FROM $table ORDER BY $table" . '.' . "$orderby DESC LIMIT $limit");
            return $this->ConvertRows($result, $table);
        } catch (Exception $e) {
            print "Error!: " . $e->getMessage() . "$table" . "<br/>";
            die();
        }
    }

}
