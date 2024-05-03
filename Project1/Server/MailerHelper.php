<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer6/src/Exception.php';
require_once 'PHPMailer6/src/PHPMailer.php';
require_once 'PHPMailer6/src/SMTP.php';
if (!class_exists('GetClassHelper')) {
    include_once ('Server/GetClassHelper.php');
}

class MailerHelper {

    private $Subject;
    private $BodyContent;
    private $Mail;
    private $dbname;
    private $ForReplace;
    private $Optionals;
    private $conn;

    function __construct($dbname) {
        $this->dbname = $dbname;
        $this->conn = new GetClassHelper($dbname);
        //print_r($this->conn);
        $this->Mail = new PHPMailer(true);
        date_default_timezone_set('America/New_York');
        $general_config_obj = $this->conn->GetClass('general_config');
        $general_config = $general_config_obj->getgeneral_configById(1);
        $gc_data = json_decode($general_config->getofficeinfo(), true);
        $ena_disa = $gc_data['ZimbraServer'];
        $general_config = $this->GetConfig($ena_disa);
        $this->Mail->SMTPDebug = 0;
        $this->Mail->isSMTP();
        if ($general_config['Port'] == '2525' || $general_config['Port'] == '465') {
            $this->Mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
        }
        $this->Mail->Host = $general_config['Host'];
        $this->Mail->SMTPAuth = true;
        $this->Mail->Username = $general_config['User'];
        $this->Mail->Password = $general_config['Password'];
        $this->Mail->SMTPSecure = $general_config['Secure'];
        $this->Mail->Port = $general_config['Port'];
        $this->Mail->isHTML(true);
        $this->ForReplace = false;
    }

    public function GetConfig($Local) {
        if ($Local == 'disabled') {
            /*$general_config_obj = $this->conn->GetClass('general_config');
            $general_config = $general_config_obj->getgeneral_configById(1);
            $general_config = json_decode($general_config->getofficeinfo(), true);
            $general_config = json_decode($general_config['EmailData'], true);*/
            /**/
            $emailcnf_obj = $this->conn->GetClass('emailcnf');
            $emailcnf = $emailcnf_obj->getemailcnfById(1);
            if($emailcnf){
                $general_config['Host'] = $emailcnf->gethost();
                $general_config['Port'] = $emailcnf->getport();
                $general_config['Secure'] = $emailcnf->getsecure();
                $general_config['User'] = $emailcnf->getusername();
                $general_config['Password'] = $emailcnf->getpass();
            }
            /**/
            $this->Mail->setFrom($general_config['User'], $this->dbname);
        } else {
            $zserver_obj = $this->conn->GetClass('zserver');
            $zserver = $zserver_obj->getzserverById(1);
            $general_config = array();
            $general_config['Host'] = str_replace(array('https://', 'http://'), '', $zserver->getzurl());
            $this->Mail->setFrom("admin@" . $zserver->getdomain(), $this->dbname);
            $jsonconfig = json_decode($zserver->getjsonconfig(), true);
            $general_config['User'] = $jsonconfig['admin'];
            $general_config['Password'] = $jsonconfig['pass'];
            $SMTPSecure = 'tls';
            $Port = 587;
            if (is_array($jsonconfig) && isset($jsonconfig['port'])) {
                $SMTPSecure = 'ssl';
                $Port = $jsonconfig['port'];
            }
            $general_config['Secure'] = $SMTPSecure;
            $general_config['Port'] = $Port;
        }
        return $general_config;
    }

    public function SetTemplate($NameTemplate) {
        $emailmessage_obj = $this->conn->GetClass('emailmessage');
        $emailmessage = $emailmessage_obj->getAllemailmessageForColumnValue('name', '"' . $NameTemplate . '"');
        if ($emailmessage) {
            $emailmessage = $emailmessage[0];
            $this->BodyContent = $emailmessage->getbody();
        }
    }

    public function ActiveReplace($data) {
        $this->ForReplace = true;
        if ($data) {
            $this->Optionals = $data;
        }
    }

    public function ActiveICal($Body, $path) {
        $this->Mail->isHTML(false);
        $this->Mail->ContentType = 'text/calendar';
        //$this->Mail->addStringAttachment($Body,'Event.ics','7bit','text/calendar; charset=utf-8; method=REQUEST');
        if ($Body) {
            $this->Mail->Ical = $Body;
        }
    }

    public function ReplaceData($optionals = array()) {
        $office_obj = $this->conn->GetClass('office');
        $office = $office_obj->getofficeById(1);
        if (is_object($office)) {
            $this->BodyContent = str_replace('#office_name#', $office->getname(), $this->BodyContent);
            $this->BodyContent = str_replace('#office_address#', $office->getaddress(), $this->BodyContent);
            $this->BodyContent = str_replace('#office_state#', $office->getstate(), $this->BodyContent);
            $this->BodyContent = str_replace('#office_city#', $office->getcity(), $this->BodyContent);
            $this->BodyContent = str_replace('#office_phone#', $office->getphone(), $this->BodyContent);
            $this->BodyContent = str_replace('#office_managername#', $office->getmanagername(), $this->BodyContent);
            $this->BodyContent = str_replace('#office_phone1#', $office->getphone1(), $this->BodyContent);
            $this->BodyContent = str_replace('#office_fax#', $office->getfax(), $this->BodyContent);
            $this->BodyContent = str_replace('#office_type#', $office->gettype(), $this->BodyContent);
            $this->BodyContent = str_replace('#office_managerphone#', $office->getmanagerphone(), $this->BodyContent);
            $this->BodyContent = str_replace('#office_nameinletterstocustomers#', $office->getnameinletterstocustomers(), $this->BodyContent);
            $this->BodyContent = str_replace('#nameoffi#', $office->getnameinletterstocustomers(), $this->BodyContent);
            $this->BodyContent = str_replace('#address#', $office->getaddress(), $this->BodyContent);
            $this->BodyContent = str_replace('#state#', $office->getstate(), $this->BodyContent);
            $this->BodyContent = str_replace('#city#', $office->getcity(), $this->BodyContent);
            $this->BodyContent = str_replace('#zip#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#phone#', $office->getphone(), $this->BodyContent);
        } else {
            $this->BodyContent = str_replace('#office_name#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#office_address#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#office_state#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#office_city#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#office_phone#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#office_managername#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#office_phone1#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#office_fax#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#office_type#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#office_managerphone#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#office_nameinletterstocustomers#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#nameoffi#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#address#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#state#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#city#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#zip#', '', $this->BodyContent);
            $this->BodyContent = str_replace('#phone#', '', $this->BodyContent);
        }
        if ($optionals) {
            foreach ($optionals as $key => $replace) {
                $this->BodyContent = str_replace($key, $replace, $this->BodyContent);
            }
        }
        $this->BodyContent = str_replace('#date_now#', date('F d, Y'), $this->BodyContent);
        $this->BodyContent = str_replace('#time_now#', date('H:s:i'), $this->BodyContent);
    }

    public function SenderEmail($From = '', $To = '', $Subject = '', $Body = '', $Path = '', $Cc = '', $Bcc = '') {
        if (trim($Subject)) {
            $this->SetSubjectTitle($Subject);
        } else {
            return json_encode(array('Code' => '500', 'Msj' => 'Subject is Required'));
        }
        //print_r($To);
        if (trim($To)) {
            $this->SetTo($To);
        } else {
            return json_encode(array('Code' => '500', 'Msj' => 'Email To is Required'));
        }
        if (trim($From)) {
            $this->SetFromName($From, '');
        }
        if (trim($Body)) {
            $this->Mail->Body = $Body;
        } else {
            if ($this->BodyContent) {
                $this->Mail->Body = $this->BodyContent;
            }
        }
        if ($this->ForReplace) {
            $this->BodyContent = $this->Mail->Body;
            if ($this->Optionals) {
                $this->ReplaceData($this->Optionals);
            } else {
                $this->ReplaceData();
            }
            $this->Mail->Body = $this->BodyContent;
        }
        if (!trim($this->Mail->Body)) {
            $this->Mail->Body = 'Email from : ' . $From;
        }
        if ($Path) {
            //$Path = '../'.$Path;
            $this->SetPath($Path);
        }
        if ($Cc) {
            $this->SetCC($Cc);
        }
        if ($Bcc) {
            $this->SetBCC($Bcc);
        }
        try {
            $this->Mail->send();
            return json_encode(array('Code' => '200', 'Msj' => 'Message Send'));
        } catch (Exception $e) {
            return json_encode(array('Code' => '500', 'Msj' => $this->Mail->ErrorInfo));
        }
    }

    public function SetFromName($From, $Name = '') {
        $this->Mail->setFrom($From, $Name);
    }

    public function SetSubjectTitle($Subject) {
        $this->Mail->Subject = $Subject;
    }

    public function SetTo($To) {
        if (is_array($To)) {
            foreach ($To as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->Mail->AddAddress($email, '');
                }
            }
        } else {
            $To = explode(',', $To);
            foreach ($To as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->Mail->AddAddress($email, '');
                }
            }
        }
    }

    public function SetPath($Path) {
        if (is_array($Path)) {
            foreach ($Path as $Pat) {
                if ($Pat) {
                    $Pat = $_SERVER['DOCUMENT_ROOT'] . '/mrt/' . $Pat;
                    if (file_exists($Pat)) {
                        $this->Mail->AddAttachment($Pat);
                    }
                }
            }
        } else {
            $Path = explode(',', $Path);
            foreach ($Path as $Pat) {
                if ($Pat) {
                    $Pat = $_SERVER['DOCUMENT_ROOT'] . '/mrt/' . $Pat;
                    //print_r($Pat);
                    if (file_exists($Pat)) {
                        $this->Mail->AddAttachment($Pat);
                    }
                }
            }
        }
    }

    public function SetCC($CC) {
        if (is_array($CC)) {
            foreach ($CC as $c) {
                if ($c) {
                    $this->Mail->AddCC($c);
                }
            }
        } else {
            $CC = explode(',', $CC);
            foreach ($CC as $c) {
                if ($c) {
                    $this->Mail->AddCC($c);
                }
            }
        }
    }

    public function SetBCC($BCC) {
        if (is_array($BCC)) {
            foreach ($BCC as $bc) {
                if ($bc) {
                    $this->Mail->AddBCC($bc);
                }
            }
        } else {
            $BCC = explode(',', $BCC);
            foreach ($BCC as $bc) {
                if ($bc) {
                    $this->Mail->AddBCC($bc);
                }
            }
        }
    }
}

#  --- [ END Class ] ---
?>    
