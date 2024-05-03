<?php

error_reporting(E_ERROR);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    header("Location: index.php");
}
include_once('security/classes/check.class.php');
include_once ('Server/jsend.class.php');
include_once('Server/twilio-php-master/Services/Twilio.php');
include_once 'Server/twilio-php-master/Services/Twilio/Capability.php';
include_once ('Server/developer/fpdf.php');
require_once("Server/jcryption.php");
date_default_timezone_set('America/New_York');

if (!isset($_SESSION)) {
    session_start();
}
//protect("*");
$action = isset($_POST['action']) ? $_POST['action'] : "";
//echo $action;
if ($action != "") {
    call_user_func($action, $_POST['input']);
}

function result() {
    //global $myService;
    if (isset($_POST['input'])) {
        $myVal = $_POST['input'];
        //getting message head
        $keyOperation = substr($myVal, 0, 2);
        //data size
        $datalen = strlen($myVal);
        //taking the head out
        $dataresult = substr($myVal, 2, $datalen);
    }
    switch ($keyOperation) {
        case "01":
            CDOperations($dataresult);
            break;
        case "02":
            GetDataCDHUD($dataresult);
            break;
        case "03":
            SaveBankTransaction($dataresult);
            break;
        case "04":
            GetIDLinesMarks($dataresult);
            break;
        case "05":
            t_selected($dataresult);
            break;
        case "06":
            AlertsNumberTransaction($dataresult);
            break;
        case "07":
            GetPropertyInformation($dataresult);
            break;
        case "08":
            SaveAutoSave($dataresult);
            break;
        case "09":
            UploadDocument($dataresult);
            break;
        case "10":
            ChangeFileName($dataresult);
            break;
        case "11":
            PreviewDoc($dataresult);
            break;
        case "12":
            SendMailPanel($dataresult);
            break;
        case "13":
            PreviewDivide($dataresult);
            break;
        case "14":
            SendMailDivide($dataresult);
            break;
        case "15":
            deleteFile($dataresult);
            break;
        case "16":
            changesection($dataresult);
            break;
        case "17":
            TaskAjax($dataresult);
            break;
        case "18":
            UpdateTaskAjax($dataresult);
            break;
        case "19":
            deletetask_panel($dataresult);
            break;
        case "20":
            ReturnParties($dataresult);
            break;
        case "21":
            SaveAutoSaveParty($dataresult);
            break;
        case "22":
            SaveSalesPriceDialog($dataresult);
            break;
        case "23":
            SaveLoanAmountDialog($dataresult);
            break;
        case "24":
            PrepaidInterest($dataresult);
            break;
        case "25":
            SaveTaxes($dataresult);
            break;
        case "26":
            SavePayyoffs($dataresult);
            break;
        case "27":
            GetSaveLineDeposits($dataresult);
            break;
        case "28":
            DisbursmentData($dataresult);
            break;
        case "29":
            DeleteVoids($dataresult);
            break;
        case "30":
            CreatePartie($dataresult);
            break;
        case "31":
            SaveRequirementList($dataresult);
            break;
        case "32":
            GetSaveTask($dataresult);
            break;
        case "33":
            GetExcrowLetter($dataresult);
            break;
        case "34":
            ChangeUnderwriter($dataresult);
            break;
        case "35":
            ChangeStatusT($dataresult);
            break;
        case "36":
            SaveLoanTerm($dataresult);
            break;
        case "37":
            PrintCD($dataresult);
            break;
        case "38":
            MergeFiles($dataresult);
            break;
        case "39":
            mergeDocuments($dataresult);
            break;
        case "40":
            AddDeleteAlert($dataresult);
            break;
        case "41":
            DeleteParty($dataresult);
            break;
        case "42":
            DisbursmentPdf($dataresult);
            break;
        case "43":
            UpdateOrCreateCheck($dataresult);
            break;
        case "44":
            PreviewCheckOne($dataresult);
            break;
        case "45":
            CheckAllTransaction($dataresult);
            break;
        case "46":
            AddressVerification($dataresult);
            break;
        case "47":
            GetAllContacts($dataresult);
            break;
        case "48":
            EmailPartySend($dataresult);
            break;
        case "49":
            smsToMobile($dataresult);
            break;
        case "50":
            CreateLink($dataresult);
            break;
        case "51":
            BankUpdNewDelete($dataresult);
            break;
        case "52":
            BankDetails($dataresult);
            break;
        case "53":
            ReChargeCredit($dataresult);
            break;
        case "54":
            RecoveryPassword($dataresult);
            break;
        case "55":
            UpdateCreateEmailtemplate($dataresult);
            break;
        case "56":
            GetAllEvents($dataresult);
            break;
        case "57":
            GetEvents($dataresult);
            break;
        case "58":
            SaveEmailConfig($dataresult);
            break;
        case "59":
            UploadPaymentErec($dataresult);
            break;
        case "60":
            ChangeStatusErec($dataresult);
            break;
        case "61":
            WFGJackets($dataresult);
            break;
        case "62":
            WFGCPLapi($dataresult);
            break;
        case "63":
            RequestCancelation($dataresult);
            break;
        case "64":
            RefreshTokenTwilio($dataresult);
            break;
        case "65":
            Refresh1003($dataresult);
            break;
        case "66":
            RefreshFees($dataresult);
            break;
        case "67":
            RefreshIncome($dataresult);
            break;
        case "68":
            importloanapp($dataresult);
            break;
        case "69":
            fill1003fortransaction($dataresult);
            break;
        case "70":
            GetDataLoanEstimate($dataresult);
            break;
        case "71":
            SaveDataLoanEstimate($dataresult);
            break;
        case "72":
            getprogresstransactions($dataresult);
            break;
        case "73":
            GetDataTransactionCMR($dataresult);
            break;
        case "74":
            GetXMLCMR($dataresult);
            break;
        case "75":
            SaveAuth($dataresult);
            break;
    }
}

function GetClassPsToDb($dbname = '') {
    include_once ('Server/GetClassHelper.php');
    $GetClass = new GetClassHelper($dbname);
    return $GetClass;
}

//------------- general functions -----------------
function IsZServer() {
    $GetClass = GetClassPsToDb();
    $genconf = $GetClass->GetClass('general_config');
    $generalconfig = $genconf->getgeneral_configById(1);
    $gc_data = json_decode($generalconfig->getofficeinfo(), true);
    $ena_disa = $gc_data['ZimbraServer'];
    return $ena_disa;
}

function hasLevel($user_level, $level_require) {
    // $level_require = 100; is user internal
    // $level_require = 105; is user internal 1-5
    // $level_require = 200; is user external 11,12,13
    $array_levels = array(
        'a:3:{i:0;s:1:"3";i:1;s:1:"1";i:2;s:1:"2";}',
        'a:2:{i:0;s:1:"2";i:1;s:1:"3";}',
        'a:1:{i:0;s:1:"3";}',
        'a:1:{i:0;s:1:"4";}',
        'a:1:{i:0;s:1:"5";}',
        'a:1:{i:0;s:1:"6";}',
        'a:1:{i:0;s:1:"7";}',
        'a:1:{i:0;s:1:"8";}',
        'a:1:{i:0;s:1:"9";}',
        'a:1:{i:0;s:2:"10";}',
        'a:1:{i:0;s:2:"11";}',
        'a:1:{i:0;s:2:"12";}',
        'a:1:{i:0;s:2:"13";}');
    if ($level_require == 100) {
        $is_internal = false;
        for ($index_level = 0; $index_level < 9; $index_level++) {
            if ($user_level == $array_levels[$index_level]) {
                $is_internal = true;
            }
        }
        return $is_internal;
    } else if ($level_require == 200) {
        $is_urecording = false;
        for ($index_level = 11; $index_level < 13; $index_level++) {
            if ($user_level == $array_levels[$index_level - 1]) {
                $is_urecording = true;
            }
        }
        return $is_urecording;
    } else if ($level_require == 105) {
        $is_user = false;
        for ($index_level = 1; $index_level < 6; $index_level++) {
            if ($user_level == $array_levels[$index_level - 1]) {
                $is_user = true;
            }
        }
        return $is_user;
    } else {
        if ($user_level == $array_levels[$level_require - 1]) {
            return true;
        } else {
            return false;
        }
    }
}

function postZimbraProxy($data) {
    $GetClass = GetClassPsToDb();

    $fdata = "";
    foreach ($data as $key => $val) {
        $fdata .= "$key=" . urlencode($val) . "&";
    }

    $zserver = $GetClass->GetClass('zserver');
    $server = $zserver->getzserverById(1);
    $url = '';
    if (is_object($server)) {
        $url = $server->getzproxy();
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    $strJson = trim(curl_exec($ch));
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $strJson;
}

//------------- end general functions -------------
function quickbook_e_d() {
    $GetClass = GetClassPsToDb();

    $genconf = $GetClass->GetClass('general_config');
    $generalconfig = $genconf->getgeneral_configById(1);
    $gc_data = json_decode($generalconfig->getechosign(), true);
    $ena_disa = $gc_data['quickbook'];
    return $ena_disa;
}

function sendGeneralEmail($subject, $from = "", $to, $body, $path = "", $cc = "", $bcc = "") {
    $GetClass = GetClassPsToDb();
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $Mailer = GetClass('MailerHelper', $dbname);
    /* $From = '', $To = '', $Subject = '', $Body = '', $Path = '', $Cc = '', $Bcc = '' */
    $emails = array('gus@titlehost.com', 'ivan@titlehost.com');
    if ($to == 'notification') {
        $to = implode(',', $emails);
    }
    $Response = $Mailer->SenderEmail($from, $to, $subject, $body, $path, $cc, $bcc);
    //print_r($path);
    $Response = json_decode($Response);
    if ($Response->Code == 200) {
        return $Response->Msj;
    } else {
        print_r($Mailer);
        return 'Error : ' . $Response->Msj;
    }
    //return senderGeneralEmail($subject, $from, $to, $body, $path, $cc, $bcc);
}

/**/

function GetClass($MyClass, $dbname = '') {
    if (class_exists($MyClass)) {
        if ($dbname) {
            return new $MyClass($dbname);
        } else {
            return new $MyClass();
        }
    } else {
        include_once ('Server/' . $MyClass . '.php');
        if ($dbname) {
            return new $MyClass($dbname);
        } else {
            return new $MyClass();
        }
    }
}

function CreaContactForCheck($First, $Last, $Company) {
    $GetClass = GetClassPsToDb();

    $contact_obj = $GetClass->GetClass('contact');
    $FirstName = trim(ucwords(strtolower($First)));
    $LastName = trim(ucwords(strtolower($Last)));
    $CompanyName = trim(ucwords(strtolower($Company)));
    $ContactExist = '';

    if ($CompanyName) {
        $contact = $contact_obj->getAllcontactForColumnValue('company', '"' . $CompanyName . '"');
    }
    if ($contact) {
        $ContactExist = $contact[0];
    } else {
        if ($LastName && $FirstName) {
            $contact = $contact_obj->getAllcontactForColumnValue('surname', '"' . $LastName . '"');
            foreach ($contact as $cont) {
                if ($cont->getfirstname() == $FirstName) {
                    $ContactExist = $cont;
                }
            }
        }
    }
    if (trim($FirstName . $LastName)) {
        $array['TypeContact'] = 'Person';
    } else {
        $array['TypeContact'] = 'Company';
    }
    if ($ContactExist) {
        if ($ContactExist->getidq()) {
            return $ContactExist->getidcontact();
        } else {
            $idqcontact = CreateContactQB($array['TypeContact'], $FirstName, $LastName, $CompanyName);
            if (is_numeric($idqcontact)) {
                $contact_obj->updateidq($ContactExist->getidcontact(), $idqcontact);
            }
            return $ContactExist->getidcontact();
        }
    } else {
        if (trim($FirstName . $Middle . $LastName)) {
            $CompleteName = $FirstName . ' ' . $Middle . ' ' . $LastName;
        } else {
            $CompleteName = $CompanyName;
        }
        if ($CompanyName) {
            $contact_obj->setcompany($CompanyName);
            $contact_obj->updatesurname($contact_obj->getidcontact(), $LastName);
        } else {
            $contact_obj->setsurname($LastName);
            $contact_obj->updatecompany($contact_obj->getidcontact(), $CompanyName);
        }
        $contact_obj->updatemiddlename($contact_obj->getidcontact(), $Middle);
        $contact_obj->updatename($contact_obj->getidcontact(), $CompleteName);
        $contact_obj->updatename1($contact_obj->getidcontact(), $CompleteName);
        $contact_obj->updatename2($contact_obj->getidcontact(), $CompleteName);
        $contact_obj->updatefirstname($contact_obj->getidcontact(), $FirstName);
        //$idqcontact = CreateContactQB($array['TypeContact'], $FirstName, $LastName, $CompanyName);
        if (is_numeric($idqcontact)) {
            $contact_obj->updateidq($contact_obj->getidcontact(), $idqcontact);
        }
        return $contact_obj->getidcontact();
    }
}

function CreateContactQB($TypeContact, $FirstName, $LastName, $CompanyName) {
    $GetClass = GetClassPsToDb();
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    if (quickbook_e_d() == 'enabled') {
        $QBFunctions_obj = $GetClass->GetClass('QBFunctions');
        if ($TypeContact == 'Person') {
            $name_contact = $LastName . ' ' . $FirstName;
            $Title = 'Mr(s)';
        } else {
            $name_contact = $CompanyName;
            $Title = 'Inc.';
        }
        $params = array(
            'title' => $Title,
            'family_name' => cleanStringQB($name_contact),
            'display_name' => cleanStringQB($name_contact),
            'company_name' => cleanStringQB($CompanyName)
        );
        $response = $QBFunctions_obj->CreateCustomerQB($params);
        if (is_numeric($response)) {
            return $response;
        } else {
            sendGeneralEmail('Error on ' . $dbname, "", 'notification', 'QB is Not Connect on ' . $dbname);
        }
    } else {
        return 'disabled';
        //sendGeneralEmail('Error on ' . $dbname, "", 'notification', 'QB is Disabled on ' . $dbname);
    }
}

function cleanStringQB($str) {
    $str = str_replace(array("\n", "\t"), '', $str);
    return str_replace(array('"', ':'), array("'", ''), $str);
}

/**/

// 01
function CDOperations($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $cdhud_page2_obj = $GetClass->GetClass('cdhud_page2');
        $cdhud_page3_obj = $GetClass->GetClass('cdhud_page3');
        $cdhud_page245_obj = $GetClass->GetClass('cdhud_page245');
        $purchase_obj = $GetClass->GetClass('purchase');
        $deposit_obj = $GetClass->GetClass('deposit');
        $contact_obj = $GetClass->GetClass('contact');
        $transaction_obj = $GetClass->GetClass('transaction');
        $bank_obj = $GetClass->GetClass('bank');
        if ($array['idtransaction']) {
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            $ArrayReturn = array();
            if ($array['Function'] == 'GetDataLine') {
                if (!$cdhudTransaction) {
                    $ArrayReturn['Data'] = 'Empty';
                } else {
                    $cdhudTransaction = $cdhudTransaction[0];
                    $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                    /* Review data Sales Price */
                    $SalesPrice = $cdhudTransaction->getSalesPrice();
                    if ($SalesPrice) {
                        $SalesPrice = json_decode($SalesPrice, true);
                        if ($SalesPrice['CheckStampDeed'] && $array['Line'] == 'E-02') {
                            /* $ArrayReturn['Data'] = $cdhudTransaction->getSalesPrice();
                              $ArrayReturn['Function'] = $array['Function'];
                              $ArrayReturn['Page'] = 'SalesPrice';
                              $ArrayReturn['Line'] = 'SalesPrice';
                              echo json_encode($ArrayReturn);
                              exit(); */
                        }
                    }
                    /**/
                    /* Review if is Recomm */
                    if ($cdhud_page245) {
                        $cdhud_page245 = $cdhud_page245[0];
                        $REComm = $cdhud_page245->getREComm();
                        if ($REComm) {
                            $REComm = json_decode($REComm, true);
                            if ($REComm['LineCommission1'] == $array['Line'] || $REComm['LineCommission2'] == $array['Line']) {
                                $ArrayReturn['Data'] = $cdhud_page245->getREComm();
                                $ArrayReturn['Function'] = $array['Function'];
                                $ArrayReturn['Page'] = 'ReComm';
                                $ArrayReturn['Line'] = 'ReComm';
                                echo json_encode($ArrayReturn);
                                exit();
                            }
                        }
                        $TitleIns = $cdhud_page245->getTitleIns();
                        if ($TitleIns) {
                            $TitleIns = json_decode($TitleIns, true);
                            if ($TitleIns['selectins2'] == $array['Line'] || $TitleIns['selectins2Split'] == $array['Line'] || $TitleIns['selectins3'] == $array['Line'] || $TitleIns['selectins3Split'] == $array['Line'] || $TitleIns['selectins5'] == $array['Line'] || $TitleIns['selectins4'] == $array['Line']) {
                                $ArrayReturn['Data'] = $cdhud_page245->getTitleIns();
                                $ArrayReturn['Function'] = $array['Function'];
                                $ArrayReturn['Page'] = 'TitleIns';
                                $ArrayReturn['Line'] = 'TitleIns';
                                echo json_encode($ArrayReturn);
                                exit();
                            }
                        }
                    }
                    /**/
                    if ($array['Page'] == 'page2' || $array['Page'] == 'page2E') {
                        $cdhud_page2 = $cdhud_page2_obj->getAllcdhud_page2ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                        if ($cdhud_page2) {
                            $cdhud_page2 = $cdhud_page2[0];
                            $get = 'get' . str_replace('-', '', $array['Line']);
                            if ($cdhud_page2->$get()) {
                                $ArrayReturn['Data'] = $cdhud_page2->$get();
                            } else {
                                $ArrayReturn['Data'] = 'Empty';
                            }
                        } else {
                            $ArrayReturn['Data'] = 'Empty';
                        }
                    }
                    if ($array['Page'] == 'ReComm') {
                        if ($cdhud_page245) {
                            $JsonREComm = $cdhud_page245->getREComm();
                            /**/
                            $JsonRECommDeduct = $cdhud_page245->getReccomDeduct();
                            if ($JsonRECommDeduct) {
                                $JsonRECommDeducts = array();
                                $JsonRECommDeducts['Deducts'] = json_decode($JsonRECommDeduct, true);
                                //print_r($JsonRECommDeducts);
                                if ($JsonREComm) {
                                    $JsonREComm = json_encode(array_merge(json_decode($JsonREComm, true), $JsonRECommDeducts));
                                } else {
                                    $JsonREComm = json_encode($JsonRECommDeducts);
                                }
                            }
                            /**/
                            if ($JsonREComm) {
                                $ArrayReturn['Data'] = $JsonREComm;
                            } else {
                                $ArrayReturn['Data'] = 'Empty';
                            }
                        } else {
                            $ArrayReturn['Data'] = 'Empty';
                        }
                    }
                    if ($array['Page'] == 'TitleIns') {
                        if ($cdhud_page245) {
                            $JsonTitleIns = $cdhud_page245->getTitleIns();
                            if ($JsonTitleIns) {
                                $ArrayReturn['Data'] = $JsonTitleIns;
                            } else {
                                $ArrayReturn['Data'] = 'Empty';
                            }
                        } else {
                            $ArrayReturn['Data'] = 'Empty';
                        }
                    }
                    if ($array['Page'] == 'Page3' || $array['Page'] == 'Page3_2' || $array['Page'] == 'Page3_3') {
                        $cdhud_page3 = $cdhud_page3_obj->getAllcdhud_page3ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                        if ($cdhud_page3) {
                            $cdhud_page3 = $cdhud_page3[0];
                            $get = 'get' . str_replace('-', '', $array['Line']);
                            if ($cdhud_page3->$get()) {
                                $ArrayReturn['Data'] = $cdhud_page3->$get();
                            } else {
                                $ArrayReturn['Data'] = 'Empty';
                            }
                        } else {
                            $ArrayReturn['Data'] = 'Empty';
                        }
                    }
                }
                $ArrayReturn['Function'] = $array['Function'];
                $ArrayReturn['Page'] = $array['Page'];
                $ArrayReturn['Line'] = $array['Line'];
                echo json_encode($ArrayReturn);
            } else {
                if ($array['Function'] == 'ClearLine') {
                    $IdCDLine = $array['Line'];
                    if (!$cdhudTransaction) {
                        $ArrayReturn['Data'] = 'Empty';
                    } else {
                        $cdhudTransaction = $cdhudTransaction['0'];
                        $cdhud_page2 = $cdhud_page2_obj->getAllcdhud_page2ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                        $update = 'update' . str_replace('-', '', $IdCDLine);
                        if ($cdhud_page2) {
                            $cdhud_page2 = $cdhud_page2[0];
                            $cdhud_page2_obj->$update($cdhud_page2->getidcdhud_page2(), '');
                        }
                    }
                    /* Delete Checks */
                    $AllPurchasesTransaction = $purchase_obj->getAllpurchaseForColumnvalue('idtransaction', $array['idtransaction']);
                    if ($AllPurchasesTransaction) {
                        foreach ($AllPurchasesTransaction as $purchase) {
                            if ($purchase->gethudline() == $IdCDLine) {
                                $idpurchase = $purchase->getidpurchase();
                            }
                        }
                        if ($idpurchase) {
                            $purchase_obj->deletepurchase($idpurchase);
                        }
                    }
                    /**/
                    /* delete deduct and proceds */
                    $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                    if ($cdhud_page245) {
                        $cdhud_page245 = $cdhud_page245[0];
                        $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                        $JsonDeducts = $cdhud_page245->getDeducts();
                        $JsonProceds = $cdhud_page245->getProceds();
                        if ($JsonDeducts) {
                            $JsonDeducts = json_decode($JsonDeducts, true);
                            $NewJsonDeduct = array();
                            foreach ($JsonDeducts as $CodeLine => $datade) {
                                if ($CodeLine != $IdCDLine) {
                                    $NewJsonDeduct[$CodeLine] = $datade;
                                }
                            }
                        }
                        if ($JsonProceds) {
                            $JsonProceds = json_decode($JsonProceds, true);
                            $NewJsonProceds = array();
                            foreach ($JsonProceds as $CodeLine => $datapre) {
                                if ($CodeLine != $IdCDLine) {
                                    $NewJsonProceds[$CodeLine] = $datapre;
                                }
                            }
                        }
                        $cdhud_page245_obj->updateDeducts($idcdhud_page245, json_encode($NewJsonDeduct));
                        $cdhud_page245_obj->updateProceds($idcdhud_page245, json_encode($NewJsonProceds));
                    }
                    /**/
                    $ArrayReturn['Function'] = 'ClearLine';
                    $ArrayReturn['Page'] = 'page2';
                    $ArrayReturn['Line'] = $array['Line'];
                    $ArrayReturn['Data'] = json_encode($array);
                    echo json_encode($ArrayReturn);
                } else {
                    /* Save Data */
                    if ($cdhudTransaction) {
                        $cdhudTransaction = $cdhudTransaction[0];
                    } else {
                        $cdhud_obj->setidtransaction($array['idtransaction']);
                        if ($array['BankSelect']) {
                            $cdhud_obj->updatebankaccount($cdhud_obj->getidcdhud(), $array['BankSelect']);
                        }
                        $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                        $cdhudTransaction = $cdhudTransaction[0];
                    }
                    /* Check */
                    $AllPurchasesTransaction = $purchase_obj->getAllpurchaseForColumnvalue('idtransaction', $array['idtransaction']);
                    $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                    if ($array['Page2Line']) {
                        if ($array['IsCheck']) {
                            $idpurchase = '';
                            if ($AllPurchasesTransaction) {
                                foreach ($AllPurchasesTransaction as $purchase) {
                                    if ($purchase->gethudline() == $array['Page2Line']) {
                                        $idpurchase = $purchase->getidpurchase();
                                    }
                                }
                            }
                            if (!$idpurchase) {
                                $purchase_obj->setidtransaction($array['idtransaction']);
                                $idpurchase = $purchase_obj->getidpurchase();
                                $purchase_obj->updatehudline($idpurchase, $array['Page2Line']);
                            }
                            /* Amount */
                            $AmountLine = 0;
                            if ($array['AmountD21']) {
                                $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $array['AmountD21']);
                            }
                            if ($array['AmountD22']) {
                                $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $array['AmountD22']);
                            }
                            if ($array['AmountD23']) {
                                $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $array['AmountD23']);
                            }
                            if ($array['AmountD24']) {
                                $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $array['AmountD24']);
                            }
                            if ($array['AmountD25']) {
                                $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $array['AmountD25']);
                            }
                            /**/
                            if (!$array['IdContact1']) {
                                $array['IdContact1'] = CreaContactForCheck($array['FirstName1'], $array['LastName1'], $array['CompanyName1']);
                            }
                            $Line = array('TypeContact' => $array['TypeContact'], 'Amount' => $AmountLine, 'IdContact' => $array['IdContact1']);
                            $Lines = array(json_encode($Line));
                            $account = json_encode($Lines);
                            /* Update Data Purchase */
                            $purchase_obj->updateaccount($idpurchase, $account);
                            $purchase_obj->updateexpensedate($idpurchase, date('Y-m-d'));
                            $purchase_obj->updateidlogin($idpurchase, $idlogin);
                            $purchase_obj->updatedescription($idpurchase, $array['Description']);
                            $purchase_obj->updateamount($idpurchase, $AmountLine);
                            $purchase_obj->updatebankaccount($idpurchase, $cdhudTransaction->getbankaccount());
                            /**/
                        } else {
                            if ($AllPurchasesTransaction) {
                                $idpurchase = '';
                                foreach ($AllPurchasesTransaction as $purchase) {
                                    if ($purchase->gethudline() == $array['Page2Line']) {
                                        $idpurchase = $purchase->getidpurchase();
                                    }
                                }
                                if ($idpurchase) {
                                    $purchase_obj->deletepurchase($idpurchase);
                                }
                            }
                        }
                    }
                    if ($array['isCheckE01']) {
                        $idpurchase = '';
                        if ($AllPurchasesTransaction) {
                            foreach ($AllPurchasesTransaction as $purchase) {
                                if ($purchase->gethudline() == $array['Page2ELine']) {
                                    $idpurchase = $purchase->getidpurchase();
                                }
                            }
                        }
                        if (!$idpurchase) {
                            $purchase_obj->setidtransaction($array['idtransaction']);
                            $idpurchase = $purchase_obj->getidpurchase();
                            $purchase_obj->updatehudline($idpurchase, $array['Page2ELine']);
                        }
                        /* Amount */
                        $AmountLine = 0;
                        if ($array['total_fee_deed']) {
                            $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $array['total_fee_deed']);
                        }
                        if ($array['total_fee_mortgage']) {
                            $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $array['total_fee_mortgage']);
                        }
                        /**/
                        /**/
                        if (!$array['IdContact2']) {
                            $array['IdContact2'] = CreaContactForCheck($array['FirstName2'], $array['LastName2'], $array['CompanyName2']);
                        }
                        $Line = array('TypeContact' => $array['TypeContact2'], 'Amount' => $AmountLine, 'IdContact' => $array['IdContact2']);
                        $Lines = array(json_encode($Line));
                        $account = json_encode($Lines);
                        /* Update Data Purchase */
                        $purchase_obj->updateaccount($idpurchase, $account);
                        $purchase_obj->updateexpensedate($idpurchase, date('Y-m-d'));
                        $purchase_obj->updateidlogin($idpurchase, $idlogin);
                        $purchase_obj->updatedescription($idpurchase, 'Recording Fees');
                        $purchase_obj->updateamount($idpurchase, $AmountLine);
                        $purchase_obj->updatebankaccount($idpurchase, $cdhudTransaction->getbankaccount());
                        /**/
                    } else {
                        if ($AllPurchasesTransaction) {
                            $idpurchase = '';
                            foreach ($AllPurchasesTransaction as $purchase) {
                                if ($purchase->gethudline() == $array['Page2ELine']) {
                                    $idpurchase = $purchase->getidpurchase();
                                }
                            }
                            if ($idpurchase) {
                                $purchase_obj->deletepurchase($idpurchase);
                            }
                        }
                    }
                    if ($array['BlockE'] == 'BlockE') {
                        if (!$array['idcontact']) {
                            $array['idcontact'] = CreaContactForCheck($array['firstname'], $array['lastname'], $array['company']);
                        }
                        /* Review Check For E */
                        $idpurchase = '';
                        if ($AllPurchasesTransaction) {
                            foreach ($AllPurchasesTransaction as $purchase) {
                                if ($purchase->gethudline() == 'BlockE') {
                                    $idpurchase = $purchase->getidpurchase();
                                }
                            }
                        }
                        if (!$idpurchase) {
                            $purchase_obj->setidtransaction($array['idtransaction']);
                            $idpurchase = $purchase_obj->getidpurchase();
                            $purchase_obj->updatehudline($idpurchase, 'BlockE');
                        }
                        $AmountLine = str_replace(array('$', ','), array('', ''), $array['amount']);
                        $Line = array('TypeContact' => 'Company', 'Amount' => $AmountLine, 'IdContact' => $array['idcontact']);
                        $Lines = array(json_encode($Line));
                        $account = json_encode($Lines);
                        /**/
                        $purchase_obj->updateaccount($idpurchase, $account);
                        $purchase_obj->updateexpensedate($idpurchase, date('Y-m-d'));
                        $purchase_obj->updateidlogin($idpurchase, $idlogin);
                        $purchase_obj->updatedescription($idpurchase, 'Check For All Block Recordings');
                        $purchase_obj->updateamount($idpurchase, $AmountLine);
                        $purchase_obj->updatebankaccount($idpurchase, $cdhudTransaction->getbankaccount());
                        /**/
                        $ArrayReturn['Function'] = 'ShowMessage';
                        $ArrayReturn['Data'] = 'Check For Block E Saved';
                        echo json_encode($ArrayReturn);
                        exit();
                    }
                    if ($array['line'] == 'ReComm') {
                        if ($cdhud_page245) {
                            $cdhud_page245 = $cdhud_page245[0];
                            $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                        } else {
                            $cdhud_page245_obj->setidcdhud($cdhudTransaction->getidcdhud());
                            $idcdhud_page245 = $cdhud_page245_obj->getidcdhud_page245();
                        }
                        /* Checks */
                        $idLineCommission1 = $array['LineCommission1'];
                        $idLineCommission2 = $array['LineCommission2'];
                        if ($AllPurchasesTransaction) {
                            foreach ($AllPurchasesTransaction as $purchase) {
                                if ($purchase->gethudline() == $idLineCommission1) {
                                    $idpurchase1 = $purchase->getidpurchase();
                                }
                                if ($purchase->gethudline() == $idLineCommission2) {
                                    $idpurchase2 = $purchase->getidpurchase();
                                }
                            }
                        }
                        /* comm1 */
                        if ($array['CheckCommission1']) {
                            if ($array['Splitcheck1'] != '0') {
                                /* Delete Original */
                                if ($idpurchase1) {
                                    $purchase_obj->deletepurchase($idpurchase1);
                                }
                                /**/
                                /* Create Splits */
                                for ($i = 1; $i <= $array['Splitcheck1']; $i++) {
                                    $idpurchaseSplit = '';
                                    if ($AllPurchasesTransaction) {
                                        foreach ($AllPurchasesTransaction as $purchase) {
                                            if ($purchase->gethudline() == $idLineCommission1 . '_' . $i) {
                                                $idpurchaseSplit = $purchase->getidpurchase();
                                            }
                                        }
                                    }
                                    /* Create check Split */
                                    if (!$idpurchaseSplit) {
                                        $purchase_obj->setidtransaction($array['idtransaction']);
                                        $idpurchaseSplit = $purchase_obj->getidpurchase();
                                        $purchase_obj->updatehudline($idpurchaseSplit, $idLineCommission1 . '_' . $i);
                                    }
                                    if (!$array['IdContact1_' . $i]) {
                                        $array['IdContact1_' . $i] = CreaContactForCheck($array['FirstName1_' . $i], $array['LastName1_' . $i], $array['CompanyName1_' . $i]);
                                    }
                                    $AmountLine = str_replace(array('$', ','), array('', ''), $array['AmountRecomm1_' . $i]);
                                    $Line = array('TypeContact' => $array['TypeContact1_' . $i], 'Amount' => $AmountLine, 'IdContact' => $array['IdContact1_' . $i]);
                                    $Lines = array(json_encode($Line));
                                    $account = json_encode($Lines);
                                    /* Update Data Purchase */
                                    $purchase_obj->updateaccount($idpurchaseSplit, $account);
                                    $purchase_obj->updateexpensedate($idpurchaseSplit, date('Y-m-d'));
                                    $purchase_obj->updateidlogin($idpurchaseSplit, $idlogin);
                                    $purchase_obj->updatedescription($idpurchaseSplit, 'Real Estate Commission (Split Part ' . $i . ')');
                                    $purchase_obj->updateamount($idpurchaseSplit, $AmountLine);
                                    $purchase_obj->updatebankaccount($idpurchaseSplit, $cdhudTransaction->getbankaccount());
                                    /**/
                                }
                                /**/
                            } else {
                                /* Delete Splits */
                                if ($AllPurchasesTransaction) {
                                    for ($i = 1; $i <= 10; $i++) {
                                        foreach ($AllPurchasesTransaction as $purchase) {
                                            if ($purchase->gethudline() == $idLineCommission1 . '_' . $i) {
                                                $purchase_obj->deletepurchase($purchase->getidpurchase());
                                            }
                                        }
                                    }
                                }
                                /**/
                                /* Create Original */
                                if (!$idpurchase1) {
                                    $purchase_obj->setidtransaction($array['idtransaction']);
                                    $idpurchase1 = $purchase_obj->getidpurchase();
                                    $purchase_obj->updatehudline($idpurchase1, $idLineCommission1);
                                }
                                if (!$array['IdContact3']) {
                                    $array['IdContact3'] = CreaContactForCheck($array['FirstName3'], $array['LastName3'], $array['CompanyName3']);
                                }
                                $AmountLine = str_replace(array('$', ','), array('', ''), $array['AmountCommission1']);
                                $Line = array('TypeContact' => $array['TypeContact3'], 'Amount' => $AmountLine, 'IdContact' => $array['IdContact3']);
                                $Lines = array(json_encode($Line));
                                $account = json_encode($Lines);
                                /* Update Data Purchase */
                                $purchase_obj->updateaccount($idpurchase1, $account);
                                $purchase_obj->updateexpensedate($idpurchase1, date('Y-m-d'));
                                $purchase_obj->updateidlogin($idpurchase1, $idlogin);
                                $purchase_obj->updatedescription($idpurchase1, 'Real Estate Commission');
                                $purchase_obj->updateamount($idpurchase1, $AmountLine);
                                $purchase_obj->updatebankaccount($idpurchase1, $cdhudTransaction->getbankaccount());
                                /**/
                            }
                        } else {
                            if ($idpurchase1) {
                                $purchase_obj->deletepurchase($idpurchase1);
                            }
                            /* Delete Splits */
                            if ($AllPurchasesTransaction) {
                                for ($i = 1; $i <= 10; $i++) {
                                    foreach ($AllPurchasesTransaction as $purchase) {
                                        if ($purchase->gethudline() == $idLineCommission1 . '_' . $i) {
                                            $purchase_obj->deletepurchase($purchase->getidpurchase());
                                        }
                                    }
                                }
                            }
                            /**/
                        }
                        /**/
                        /* comm2 */
                        if ($array['CheckCommission2']) {
                            if ($array['Splitcheck2'] != '0') {
                                /* Delete Original */
                                if ($idpurchase2) {
                                    $purchase_obj->deletepurchase($idpurchase2);
                                }
                                /**/
                                /* Create Splits */
                                for ($i = 1; $i <= $array['Splitcheck2']; $i++) {
                                    $idpurchaseSplit = '';
                                    if ($AllPurchasesTransaction) {
                                        foreach ($AllPurchasesTransaction as $purchase) {
                                            if ($purchase->gethudline() == $idLineCommission2 . '_' . $i) {
                                                $idpurchaseSplit = $purchase->getidpurchase();
                                            }
                                        }
                                    }
                                    /* Create check Split */
                                    if (!$idpurchaseSplit) {
                                        $purchase_obj->setidtransaction($array['idtransaction']);
                                        $idpurchaseSplit = $purchase_obj->getidpurchase();
                                        $purchase_obj->updatehudline($idpurchaseSplit, $idLineCommission2 . '_' . $i);
                                    }
                                    if (!$array['IdContact2_' . $i]) {
                                        $array['IdContact2_' . $i] = CreaContactForCheck($array['FirstName2_' . $i], $array['LastName2_' . $i], $array['CompanyName2_' . $i]);
                                    }
                                    $AmountLine = str_replace(array('$', ','), array('', ''), $array['AmountRecomm2_' . $i]);
                                    $Line = array('TypeContact' => $array['TypeContact2_' . $i], 'Amount' => $AmountLine, 'IdContact' => $array['IdContact2_' . $i]);
                                    $Lines = array(json_encode($Line));
                                    $account = json_encode($Lines);
                                    /* Update Data Purchase */
                                    $purchase_obj->updateaccount($idpurchaseSplit, $account);
                                    $purchase_obj->updateexpensedate($idpurchaseSplit, date('Y-m-d'));
                                    $purchase_obj->updateidlogin($idpurchaseSplit, $idlogin);
                                    $purchase_obj->updatedescription($idpurchaseSplit, 'Real Estate Commission (Split Part ' . $i . ')');
                                    $purchase_obj->updateamount($idpurchaseSplit, $AmountLine);
                                    $purchase_obj->updatebankaccount($idpurchaseSplit, $cdhudTransaction->getbankaccount());
                                    /**/
                                }
                                /**/
                            } else {
                                /* Delete Splits */
                                if ($AllPurchasesTransaction) {
                                    for ($i = 1; $i <= 10; $i++) {
                                        foreach ($AllPurchasesTransaction as $purchase) {
                                            if ($purchase->gethudline() == $idLineCommission2 . '_' . $i) {
                                                $purchase_obj->deletepurchase($purchase->getidpurchase());
                                            }
                                        }
                                    }
                                }
                                /**/
                                /* Create Original */
                                if (!$idpurchase2) {
                                    $purchase_obj->setidtransaction($array['idtransaction']);
                                    $idpurchase2 = $purchase_obj->getidpurchase();
                                    $purchase_obj->updatehudline($idpurchase2, $idLineCommission2);
                                }
                                if (!$array['IdContact4']) {
                                    $array['IdContact4'] = CreaContactForCheck($array['FirstName4'], $array['LastName4'], $array['CompanyName4']);
                                }
                                $AmountLine = str_replace(array('$', ','), array('', ''), $array['AmountCommission2']);
                                $Line = array('TypeContact' => $array['TypeContact4'], 'Amount' => $AmountLine, 'IdContact' => $array['IdContact4']);
                                $Lines = array(json_encode($Line));
                                $account = json_encode($Lines);
                                /* Update Data Purchase */
                                $purchase_obj->updateaccount($idpurchase2, $account);
                                $purchase_obj->updateexpensedate($idpurchase2, date('Y-m-d'));
                                $purchase_obj->updateidlogin($idpurchase2, $idlogin);
                                $purchase_obj->updatedescription($idpurchase2, 'Real Estate Commission');
                                $purchase_obj->updateamount($idpurchase2, $AmountLine);
                                $purchase_obj->updatebankaccount($idpurchase2, $cdhudTransaction->getbankaccount());
                                /**/
                            }
                        } else {
                            if ($idpurchase2) {
                                $purchase_obj->deletepurchase($idpurchase2);
                            }
                            /* Delete Splits */
                            if ($AllPurchasesTransaction) {
                                for ($i = 1; $i <= 10; $i++) {
                                    foreach ($AllPurchasesTransaction as $purchase) {
                                        if ($purchase->gethudline() == $idLineCommission2 . '_' . $i) {
                                            $purchase_obj->deletepurchase($purchase->getidpurchase());
                                        }
                                    }
                                }
                            }
                            /**/
                        }
                        /**/
                        $cdhud_page245_obj->updateREComm($idcdhud_page245, json_encode($array));
                        $ArrayReturn['Function'] = 'PushDataRecomm';
                        $ArrayReturn['Page'] = 'ReComm';
                        $ArrayReturn['Line'] = 'ReComm';
                        $ArrayReturn['Data'] = json_encode($array);
                        echo json_encode($ArrayReturn);
                        exit();
                    }
                    if ($array['line'] == 'TitleIns') {
                        if ($cdhud_page245) {
                            $cdhud_page245 = $cdhud_page245[0];
                            $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                        } else {
                            $cdhud_page245_obj->setidcdhud($cdhudTransaction->getidcdhud());
                            $idcdhud_page245 = $cdhud_page245_obj->getidcdhud_page245();
                        }
                        /* Checks */
                        if ($AllPurchasesTransaction) {
                            foreach ($AllPurchasesTransaction as $purchase) {
                                if ($purchase->gethudline() == 'TitleUnderW') {
                                    $idpurchase1 = $purchase->getidpurchase();
                                }
                                if ($purchase->gethudline() == 'TitleOffice') {
                                    $idpurchase2 = $purchase->getidpurchase();
                                }
                                if ($purchase->gethudline() == $array['selectins5']) {
                                    $idpurchase3 = $purchase->getidpurchase();
                                }
                            }
                        }
                        if ($array['checkforundercheck']) {
                            /* Create Original */
                            if (!$idpurchase1) {
                                $purchase_obj->setidtransaction($array['idtransaction']);
                                $idpurchase1 = $purchase_obj->getidpurchase();
                                $purchase_obj->updatehudline($idpurchase1, 'TitleUnderW');
                            }
                            $AmountLine = str_replace(array('$', ','), array('', ''), $array['value_underw']);
                            $Line = array('TypeContact' => 'Company', 'Amount' => $AmountLine, 'IdContact' => $array['totileins']);
                            $Lines = array(json_encode($Line));
                            $account = json_encode($Lines);
                            /* Update Data Purchase */
                            $purchase_obj->updateaccount($idpurchase1, $account);
                            $purchase_obj->updateexpensedate($idpurchase1, date('Y-m-d'));
                            $purchase_obj->updateidlogin($idpurchase1, $idlogin);
                            $purchase_obj->updatedescription($idpurchase1, 'Title Insurance Underwriter');
                            $purchase_obj->updateamount($idpurchase1, $AmountLine);
                            $purchase_obj->updatebankaccount($idpurchase1, $cdhudTransaction->getbankaccount());
                            /**/
                        } else {
                            if ($idpurchase1) {
                                $purchase_obj->deletepurchase($idpurchase1);
                            }
                        }
                        if ($array['checkforofficecheck']) {
                            /* Create Original */
                            if (!$idpurchase2) {
                                $purchase_obj->setidtransaction($array['idtransaction']);
                                $idpurchase2 = $purchase_obj->getidpurchase();
                                $purchase_obj->updatehudline($idpurchase2, 'TitleOffice');
                            }
                            if (!$array['idcontactoffice']) {
                                $office = $GetClass->GetClass('office');
                                $idofficeinfo = '1';
                                $office = $office->getofficeById($idofficeinfo);
                                $array['idcontactoffice'] = CreaContactForCheck('', '', $office->getname());
                            }
                            $AmountLine = str_replace(array('$', ','), array('', ''), $array['value_foroffice']);
                            $Line = array('TypeContact' => 'Company', 'Amount' => $AmountLine, 'IdContact' => $array['idcontactoffice']);
                            $Lines = array(json_encode($Line));
                            $account = json_encode($Lines);
                            /* Update Data Purchase */
                            $purchase_obj->updateaccount($idpurchase2, $account);
                            $purchase_obj->updateexpensedate($idpurchase2, date('Y-m-d'));
                            $purchase_obj->updateidlogin($idpurchase2, $idlogin);
                            $purchase_obj->updatedescription($idpurchase2, 'Title Insurance Office');
                            $purchase_obj->updateamount($idpurchase2, $AmountLine);
                            $purchase_obj->updatebankaccount($idpurchase2, $cdhudTransaction->getbankaccount());
                            /**/
                        } else {
                            if ($idpurchase2) {
                                $purchase_obj->deletepurchase($idpurchase2);
                            }
                        }
                        if ($array['check_SDBC']) {
                            /* Create Original */
                            if (!$idpurchase3) {
                                $purchase_obj->setidtransaction($array['idtransaction']);
                                $idpurchase3 = $purchase_obj->getidpurchase();
                                $purchase_obj->updatehudline($idpurchase3, 'TitleOffice');
                            }
                            if (!$array['IdContactCheckLender']) {
                                $array['IdContactCheckLender'] = CreaContactForCheck($array['FirstCheckLender'], $array['LastCheckLender'], $array['CompanyCheckLender']);
                            }
                            $AmountLine = str_replace(array('$', ','), array('', ''), $array['LenderPolicyFinalForcheck']);
                            $Line = array('TypeContact' => 'Company', 'Amount' => $AmountLine, 'IdContact' => $array['IdContactCheckLender']);
                            $Lines = array(json_encode($Line));
                            $account = json_encode($Lines);
                            /* Update Data Purchase */
                            $purchase_obj->updateaccount($idpurchase3, $account);
                            $purchase_obj->updateexpensedate($idpurchase3, date('Y-m-d'));
                            $purchase_obj->updateidlogin($idpurchase3, $idlogin);
                            $purchase_obj->updatedescription($idpurchase3, 'Seller Debit/Buyer Credit');
                            $purchase_obj->updateamount($idpurchase3, $AmountLine);
                            $purchase_obj->updatebankaccount($idpurchase3, $cdhudTransaction->getbankaccount());
                            /**/
                        } else {
                            if ($idpurchase3) {
                                $purchase_obj->deletepurchase($idpurchase3);
                            }
                        }
                        $cdhud_page245_obj->updateTitleIns($idcdhud_page245, json_encode($array));
                        $ArrayReturn['Function'] = 'PushDataTitleIns';
                        $ArrayReturn['Page'] = 'TitleIns';
                        $ArrayReturn['Line'] = 'TitleIns';
                        $ArrayReturn['Data'] = json_encode($array);
                        echo json_encode($ArrayReturn);
                        exit();
                        /**/
                    }
                    if ($array['check_d2']) {
                        $idhudline = $array['Hudlinep3_1'];
                        if ($array['radiod2'] == 'a') {
                            $idhudline = $array['Hudlinep3_1'] . '_' . $array['Hudlinep3_2'];
                        }
                        if ($array['radiod2'] == 'b') {
                            $idhudline = $array['Hudlinep3_1'];
                        }
                        if ($array['radiod2'] == 'c') {
                            $idhudline = $array['Hudlinep3_2'];
                        }
                        $idpurchase = '';
                        if ($AllPurchasesTransaction) {
                            foreach ($AllPurchasesTransaction as $purchase) {
                                if ($purchase->gethudline() == $idhudline) {
                                    $idpurchase = $purchase->getidpurchase();
                                }
                            }
                        }
                        if (!$idpurchase) {
                            $purchase_obj->setidtransaction($array['idtransaction']);
                            $idpurchase = $purchase_obj->getidpurchase();
                            $purchase_obj->updatehudline($idpurchase, $idhudline);
                        }
                        /* Amount */
                        $AmountLine = str_replace(array('$', ','), array('', ''), $array['amount_d2']);
                        if ($array['adj_check_d2']) {
                            $AmountLine = str_replace(array('$', ','), array('', ''), $array['adj_d2']);
                        }
                        /**/
                        /**/
                        if (!$array['IdContact5']) {
                            $array['IdContact5'] = CreaContactForCheck($array['FirstName5'], $array['LastName5'], $array['CompanyName5']);
                        }
                        $Line = array('TypeContact' => $array['TypeContact5'], 'Amount' => $AmountLine, 'IdContact' => $array['IdContact5']);
                        $Lines = array(json_encode($Line));
                        $account = json_encode($Lines);
                        /* Update Data Purchase */
                        $purchase_obj->updateaccount($idpurchase, $account);
                        $purchase_obj->updateexpensedate($idpurchase, date('Y-m-d'));
                        $purchase_obj->updateidlogin($idpurchase, $idlogin);
                        $purchase_obj->updatedescription($idpurchase, $array['desc_d2']);
                        $purchase_obj->updateamount($idpurchase, $AmountLine);
                        $purchase_obj->updateidcontact($idpurchase, $array['IdContact5']);
                        $purchase_obj->updatebankaccount($idpurchase, $cdhudTransaction->getbankaccount());
                        /**/
                    } else {
                        if ($AllPurchasesTransaction) {
                            $idhudline = $array['Hudlinep3_1'];
                            if ($array['radiod2'] == 'a') {
                                $idhudline = $array['Hudlinep3_1'] . '_' . $array['Hudlinep3_2'];
                            }
                            if ($array['radiod2'] == 'b') {
                                $idhudline = $array['Hudlinep3_1'];
                            }
                            if ($array['radiod2'] == 'c') {
                                $idhudline = $array['Hudlinep3_2'];
                            }
                            $idpurchase = '';
                            foreach ($AllPurchasesTransaction as $purchase) {
                                if ($purchase->gethudline() == $idhudline) {
                                    $idpurchase = $purchase->getidpurchase();
                                }
                            }
                            if ($idpurchase) {
                                $purchase_obj->deletepurchase($idpurchase);
                            }
                        }
                    }
                    /**/
                    $IdCDLine = '';
                    if ($array['Page2Line']) {
                        $IdCDLine = $array['Page2Line'];
                    }
                    if ($array['Page2ELine']) {
                        $IdCDLine = $array['Page2ELine'];
                    }
                    if ($IdCDLine) {
                        $cdhud_page2 = $cdhud_page2_obj->getAllcdhud_page2ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                        $update = 'update' . str_replace('-', '', $IdCDLine);
                        if ($cdhud_page2) {
                            $cdhud_page2 = $cdhud_page2[0];
                            $cdhud_page2_obj->$update($cdhud_page2->getidcdhud_page2(), json_encode($array));
                        } else {
                            $cdhud_page2_obj->setidcdhud($cdhudTransaction->getidcdhud());
                            $cdhud_page2_obj->$update($cdhud_page2_obj->getidcdhud_page2(), json_encode($array));
                        }
                    } else {
                        if ($array['Hudlinep3_1'] || $array['Hudlinep3_2']) {
                            $cdhud_page3 = $cdhud_page3_obj->getAllcdhud_page3ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                            $update = 'update' . str_replace('-', '', $array['Hudlinep3_1']);
                            if ($array['radiod2'] == 'c') {
                                $update = 'update' . str_replace('-', '', $array['Hudlinep3_2']);
                            }
                            if ($cdhud_page3) {
                                $cdhud_page3 = $cdhud_page3[0];
                                $cdhud_page3_obj->$update($cdhud_page3->getidcdhud_page3(), json_encode($array));
                            } else {
                                $cdhud_page3_obj->setidcdhud($cdhudTransaction->getidcdhud());
                                $cdhud_page3_obj->$update($cdhud_page3_obj->getidcdhud_page3(), json_encode($array));
                            }
                            if ($array['radiod2'] == 'a') {
                                $cdhud_page3 = $cdhud_page3_obj->getAllcdhud_page3ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                                $update = 'update' . str_replace('-', '', $array['Hudlinep3_2']);
                                if ($cdhud_page3) {
                                    $cdhud_page3 = $cdhud_page3[0];
                                    $cdhud_page3_obj->$update($cdhud_page3->getidcdhud_page3(), json_encode($array));
                                } else {
                                    $cdhud_page3_obj->setidcdhud($cdhudTransaction->getidcdhud());
                                    $cdhud_page3_obj->$update($cdhud_page3_obj->getidcdhud_page3(), json_encode($array));
                                }
                            }
                        }
                    }
                    /* Deposit */
                    /* Page 2 not Have Deposits */
                    /* page3 */
                    if ($array['radiod2'] == 'b' || $array['radiod2'] == 'c') {
                        $DepositsTransaction = $deposit_obj->getAlldepositForcolumnvalue('idtransaction', $array['idtransaction']);
                        $iddeposit = '';
                        if ($array['radiod2'] == 'b') {
                            $idhudline = $array['Hudlinep3_1'];
                        } else {
                            $idhudline = $array['Hudlinep3_2'];
                        }
                        if ($DepositsTransaction) {
                            foreach ($DepositsTransaction as $Deposit) {
                                $data = json_decode($Deposit->getdata(), true);
                                if ($data['hudline'] == $idhudline) {
                                    $iddeposit = $Deposit->getiddeposit();
                                }
                            }
                        }
                        if ($array['fordeduccheck'] == 'a') {
                            /* eliminar deduct or provided */
                            if ($cdhud_page245) {
                                if (is_array($cdhud_page245)) {
                                    $cdhud_page245 = $cdhud_page245[0];
                                }
                                $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                                $JsonDeducts = $cdhud_page245->getDeducts();
                                $JsonProceds = $cdhud_page245->getProceds();
                                $NewJsonDeduct = array();
                                $NewJsonProceds = array();
                                if ($JsonDeducts) {
                                    $JsonDeducts = json_decode($JsonDeducts, true);
                                    foreach ($JsonDeducts as $CodeLine => $datade) {
                                        if ($CodeLine != $idhudline) {
                                            $NewJsonDeduct[$CodeLine] = $datade;
                                        }
                                    }
                                }
                                if ($JsonProceds) {
                                    $JsonProceds = json_decode($JsonProceds, true);
                                    foreach ($JsonProceds as $CodeLine => $datapre) {
                                        if ($CodeLine != $idhudline) {
                                            $NewJsonProceds[$CodeLine] = $datapre;
                                        }
                                    }
                                }
                                $cdhud_page245_obj->updateDeducts($idcdhud_page245, json_encode($NewJsonDeduct));
                                $cdhud_page245_obj->updateProceds($idcdhud_page245, json_encode($NewJsonProceds));
                                $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                            }
                            /**/
                            if ($array['isdeductrecomm'] == 'Deposit') {
                                /* create deposit */
                                if (!$iddeposit) {
                                    $deposit_obj->setidtransaction($array['idtransaction']);
                                    $iddeposit = $deposit_obj->getiddeposit();
                                }
                                if ($array['adj_check_d2']) {
                                    $Amount = $array['adj_d2'];
                                } else {
                                    $Amount = $array['amount_d2'];
                                }
                                $Amount = str_replace('$', '', $Amount);
                                $Amount = str_replace(',', '', $Amount);
                                /**/
                                if (!$array['IdContact5']) {
                                    $array['IdContact5'] = CreaContactForCheck($array['FirstName5'], $array['LastName5'], $array['CompanyName5']);
                                    $contact = $contact_obj->getcontactById($array['IdContact5']);
                                }
                                /**/
                                $ArrayData = array();
                                $ArrayData['hudline'] = $idhudline;
                                $ArrayData['Amount'] = $Amount;
                                $ArrayData['Description'] = $array['desc_d2'];
                                $ArrayData['PaymentMethodRef'] = 'Cash';
                                $ArrayData['PaymentForLender'] = '1';
                                $ArrayData['idcontact'] = $array['IdContact5'];
                                /**/
                                /**/
                                $deposit_obj->updatetotal_amount($iddeposit, $Amount);
                                $deposit_obj->updatedata($iddeposit, json_encode($ArrayData));
                                $deposit_obj->updatedeposittoaccountref($iddeposit, $cdhudTransaction->getbankaccount()); //idbank
                                $deposit_obj->updatetxnDate($iddeposit, date('Y-m-d H:i:s')); //today
                                $deposit_obj->updateidlogin($iddeposit, $idlogin);
                                $deposit_obj->updateidtransaction($iddeposit, $array['idtransaction']);
                                $deposit_obj->updateidcontact($iddeposit, $array['IdContact5']);
                                $deposit_obj->updatecreated_at($iddeposit, date('Y-m-d H:i:s'));
                                /* delete deduct recomm */
                                if ($cdhud_page245) {
                                    if (is_array($cdhud_page245)) {
                                        $cdhud_page245 = $cdhud_page245[0];
                                    }
                                    $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                                    $JsonReccomDeduct = $cdhud_page245->getReccomDeduct();
                                    if ($JsonReccomDeduct) {
                                        $JsonReccomDeduct = json_decode($JsonReccomDeduct, true);
                                        $NewJsonReccomDeduct = array();
                                        foreach ($JsonReccomDeduct as $CodeLine => $dataded) {
                                            if ($CodeLine != $idhudline) {
                                                $NewJsonReccomDeduct[$CodeLine] = $dataded;
                                            }
                                        }
                                    }
                                    $cdhud_page245_obj->updateReccomDeduct($idcdhud_page245, json_encode($NewJsonReccomDeduct));
                                    $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                                }
                                /**/
                            } else {
                                if ($iddeposit) {
                                    $deposit_obj->deletedeposit($iddeposit);
                                }
                                /* create deduct Recomm */
                                if ($cdhud_page245) {
                                    if (is_array($cdhud_page245)) {
                                        $cdhud_page245 = $cdhud_page245[0];
                                    }
                                    $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                                }
                                if (!$idcdhud_page245) {
                                    $cdhud_page245_obj->setidcdhud($cdhudTransaction->getidcdhud());
                                    $idcdhud_page245 = $cdhud_page245_obj->getidcdhud_page245();
                                }
                                $JsonReccomDeduct = $cdhud_page245->getReccomDeduct();
                                if ($JsonReccomDeduct) {
                                    $JsonReccomDeduct = json_decode($JsonReccomDeduct, true);
                                    if ($array['adj_check_d2']) {
                                        $JsonReccomDeduct[$idhudline] = $array['isdiscountselleragent'] . '||' . $array['adj_d2'];
                                    } else {
                                        $JsonReccomDeduct[$idhudline] = $array['isdiscountselleragent'] . '||' . $array['amount_d2'];
                                    }
                                } else {
                                    $JsonReccomDeduct = array($idhudline => $array['isdiscountselleragent'] . '||' . $array['amount_d2']);
                                }
                                $cdhud_page245_obj->updateReccomDeduct($idcdhud_page245, json_encode($JsonReccomDeduct));
                                $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                                /**/
                            }
                        } else {
                            /* delete deposit */
                            if ($iddeposit) {
                                $deposit_obj->deletedeposit($iddeposit);
                            }
                            /**/
                            /* delete deduct recomm */
                            if ($cdhud_page245) {
                                if (is_array($cdhud_page245)) {
                                    $cdhud_page245 = $cdhud_page245[0];
                                }
                                $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                                $JsonReccomDeduct = $cdhud_page245->getReccomDeduct();
                                if ($JsonReccomDeduct) {
                                    $JsonReccomDeduct = json_decode($JsonReccomDeduct, true);
                                    $NewJsonReccomDeduct = array();
                                    foreach ($JsonReccomDeduct as $CodeLine => $dataded) {
                                        if ($CodeLine != $idhudline) {
                                            $NewJsonReccomDeduct[$CodeLine] = $dataded;
                                        }
                                    }
                                }
                                $cdhud_page245_obj->updateReccomDeduct($idcdhud_page245, json_encode($NewJsonReccomDeduct));
                                $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                            }
                            /**/
                            if ($array['fordeduccheck'] == 'b') {
                                /* delete provided */
                                if ($cdhud_page245) {
                                    if (is_array($cdhud_page245)) {
                                        $cdhud_page245 = $cdhud_page245[0];
                                    }
                                    $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                                    $JsonProceds = $cdhud_page245->getProceds();
                                    if ($JsonProceds) {
                                        $JsonProceds = json_decode($JsonProceds, true);
                                        $NewJsonProceds = array();
                                        foreach ($JsonProceds as $CodeLine => $datapre) {
                                            if ($CodeLine != $idhudline) {
                                                $NewJsonProceds[$CodeLine] = $datapre;
                                            }
                                        }
                                    }
                                }
                                if ($idcdhud_page245) {
                                    $cdhud_page245_obj->updateProceds($idcdhud_page245, json_encode($NewJsonProceds));
                                }
                                /**/
                                /* Deduct */
                                if (!$idcdhud_page245) {
                                    $cdhud_page245_obj->setidcdhud($cdhudTransaction->getidcdhud());
                                    $idcdhud_page245 = $cdhud_page245_obj->getidcdhud_page245();
                                }
                                $JsonDeducts = $cdhud_page245->getDeducts();
                                if ($JsonDeducts) {
                                    $JsonDeducts = json_decode($JsonDeducts, true);
                                    if ($array['adj_check_d2']) {
                                        $JsonDeducts[$idhudline] = $array['desc_d2'] . '||' . $array['adj_d2'];
                                    } else {
                                        $JsonDeducts[$idhudline] = $array['desc_d2'] . '||' . $array['amount_d2'];
                                    }
                                } else {
                                    $JsonDeducts = array($idhudline => $array['desc_d2'] . '||' . $array['amount_d2']);
                                }
                                $cdhud_page245_obj->updateDeducts($idcdhud_page245, json_encode($JsonDeducts));
                                /**/
                            } else {
                                if ($array['fordeduccheck'] == 'c') {
                                    /* delete deduct */
                                    if ($cdhud_page245) {
                                        if (is_array($cdhud_page245)) {
                                            $cdhud_page245 = $cdhud_page245[0];
                                        }
                                        $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                                        $JsonDeducts = $cdhud_page245->getDeducts();
                                        if ($JsonDeducts) {
                                            $JsonDeducts = json_decode($JsonDeducts, true);
                                            $NewJsonDeduct = array();
                                            foreach ($JsonDeducts as $CodeLine => $datade) {
                                                if ($CodeLine != $idhudline) {
                                                    $NewJsonDeduct[$CodeLine] = $datade;
                                                }
                                            }
                                        }
                                    }
                                    if ($idcdhud_page245) {
                                        $cdhud_page245_obj->updateDeducts($idcdhud_page245, json_encode($NewJsonDeduct));
                                    }
                                    /**/
                                    /* provide */
                                    if (!$idcdhud_page245) {
                                        $cdhud_page245_obj->setidcdhud($cdhudTransaction->getidcdhud());
                                        $idcdhud_page245 = $cdhud_page245_obj->getidcdhud_page245();
                                    }
                                    $JsonProceds = $cdhud_page245->getProceds();
                                    if ($JsonProceds) {
                                        $JsonProceds = json_decode($JsonProceds, true);
                                        $JsonProceds[] = $idhudline;
                                        if ($array['adj_check_d2']) {
                                            $JsonProceds[$idhudline] = $array['desc_d2'] . '||' . $array['adj_d2'];
                                        } else {
                                            $JsonProceds[$idhudline] = $array['desc_d2'] . '||' . $array['amount_d2'];
                                        }
                                    } else {
                                        $JsonProceds = array($idhudline => $array['desc_d2'] . '||' . $array['amount_d2']);
                                    }
                                    $cdhud_page245_obj->updateProceds($idcdhud_page245, json_encode($JsonProceds));
                                    /**/
                                } else {
                                    /* delete b,c */
                                    if ($cdhud_page245) {
                                        if (is_array($cdhud_page245)) {
                                            $cdhud_page245 = $cdhud_page245[0];
                                        }
                                        $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                                        $JsonDeducts = $cdhud_page245->getDeducts();
                                        $JsonProceds = $cdhud_page245->getProceds();
                                        if ($JsonDeducts) {
                                            $JsonDeducts = json_decode($JsonDeducts, true);
                                            $NewJsonDeduct = array();
                                            foreach ($JsonDeducts as $CodeLine => $datade) {
                                                if ($CodeLine != $idhudline) {
                                                    $NewJsonDeduct[$CodeLine] = $datade;
                                                }
                                            }
                                        }
                                        if ($JsonProceds) {
                                            $JsonProceds = json_decode($JsonProceds, true);
                                            $NewJsonProceds = array();
                                            foreach ($JsonProceds as $CodeLine => $datapre) {
                                                if ($CodeLine != $idhudline) {
                                                    $NewJsonProceds[$CodeLine] = $datapre;
                                                }
                                            }
                                        }
                                    }
                                    if ($idcdhud_page245) {
                                        $cdhud_page245_obj->updateDeducts($idcdhud_page245, json_encode($NewJsonDeduct));
                                        $cdhud_page245_obj->updateProceds($idcdhud_page245, json_encode($NewJsonProceds));
                                    }
                                    /**/
                                }
                            }
                            $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                        }
                    } else {
                        /* Delete deposit ,recomm, deduct and provided */
                        /* delete deposit */
                        $iddeposit = '';
                        if ($DepositsTransaction) {
                            foreach ($DepositsTransaction as $Deposit) {
                                $data = json_decode($Deposit->getdata(), tru);
                                if ($data['hudline'] == $array['Hudlinep3_1']) {
                                    $iddeposit = $Deposit->getiddeposit();
                                }
                            }
                        }
                        if ($iddeposit) {
                            $deposit_obj->deletedeposit($iddeposit);
                        }
                        $iddeposit = '';
                        if ($DepositsTransaction) {
                            foreach ($DepositsTransaction as $Deposit) {
                                $data = json_decode($Deposit->getdata(), tru);
                                if ($data['hudline'] == $array['Hudlinep3_2']) {
                                    $iddeposit = $Deposit->getiddeposit();
                                }
                            }
                        }
                        if ($iddeposit) {
                            $deposit_obj->deletedeposit($iddeposit);
                        }
                        /**/
                        /* delete deduct recomm */
                        if ($cdhud_page245) {
                            if (is_array($cdhud_page245)) {
                                $cdhud_page245 = $cdhud_page245[0];
                            }
                            $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                            $JsonReccomDeduct = $cdhud_page245->getReccomDeduct();
                            if ($JsonReccomDeduct) {
                                $JsonReccomDeduct = json_decode($JsonReccomDeduct, true);
                                $NewJsonReccomDeduct = array();
                                foreach ($JsonReccomDeduct as $CodeLine => $dataDed) {
                                    if ($CodeLine != $array['Hudlinep3_1'] && $CodeLine != $array['Hudlinep3_2']) {
                                        $NewJsonReccomDeduct[$CodeLine] = $dataDed;
                                    }
                                }
                            }
                            $cdhud_page245_obj->updateReccomDeduct($idcdhud_page245, json_encode($NewJsonReccomDeduct));
                            $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                        }
                        /**/
                        /* eliminar deduct or provided */
                        if ($cdhud_page245) {
                            if (is_array($cdhud_page245)) {
                                $cdhud_page245 = $cdhud_page245[0];
                            }
                            $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                            $JsonDeducts = $cdhud_page245->getDeducts();
                            $JsonProceds = $cdhud_page245->getProceds();
                            $NewJsonDeduct = array();
                            $NewJsonProceds = array();
                            if ($JsonDeducts) {
                                $JsonDeducts = json_decode($JsonDeducts, true);
                                foreach ($JsonDeducts as $CodeLine => $datade) {
                                    if ($CodeLine != $array['Hudlinep3_1'] && $CodeLine != $array['Hudlinep3_2']) {
                                        $NewJsonDeduct[$CodeLine] = $datade;
                                    }
                                }
                            }
                            if ($JsonProceds) {
                                $JsonProceds = json_decode($JsonProceds, true);
                                foreach ($JsonProceds as $CodeLine => $datapre) {
                                    if ($CodeLine != $array['Hudlinep3_1'] && $CodeLine != $array['Hudlinep3_2']) {
                                        $NewJsonProceds[$CodeLine] = $datapre;
                                    }
                                }
                            }
                            $cdhud_page245_obj->updateDeducts($idcdhud_page245, json_encode($NewJsonDeduct));
                            $cdhud_page245_obj->updateProceds($idcdhud_page245, json_encode($NewJsonProceds));
                            $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                        }
                        /**/
                        /**/
                    }
                    /**/
                    /**/
                    /* Deduct and Proceds */
                    /* delete deduct and proceds */
                    if ($cdhud_page245) {
                        if (is_array($cdhud_page245)) {
                            $cdhud_page245 = $cdhud_page245[0];
                        }
                        $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                        $JsonDeducts = $cdhud_page245->getDeducts();
                        $JsonProceds = $cdhud_page245->getProceds();
                        if ($JsonDeducts) {
                            $JsonDeducts = json_decode($JsonDeducts, true);
                            $NewJsonDeduct = array();
                            foreach ($JsonDeducts as $CodeLine => $datade) {
                                if ($CodeLine != $IdCDLine) {
                                    $NewJsonDeduct[$CodeLine] = $datade;
                                }
                            }
                        }
                        if ($JsonProceds) {
                            $JsonProceds = json_decode($JsonProceds, true);
                            $NewJsonProceds = array();
                            foreach ($JsonProceds as $CodeLine => $datapre) {
                                if ($CodeLine != $IdCDLine) {
                                    $NewJsonProceds[$CodeLine] = $datapre;
                                }
                            }
                        }
                    }
                    /**/
                    $amountPage2 = 0;
                    if ($array['AmountD21']) {
                        $amountPage2 = $amountPage2 + str_replace(array('$', ','), array('', ''), $array['AmountD21']);
                    }
                    if ($array['AmountD22']) {
                        $amountPage2 = $amountPage2 + str_replace(array('$', ','), array('', ''), $array['AmountD22']);
                    }
                    if ($array['AmountD23']) {
                        $amountPage2 = $amountPage2 + str_replace(array('$', ','), array('', ''), $array['AmountD23']);
                    }
                    if ($array['AmountD24']) {
                        $amountPage2 = $amountPage2 + str_replace(array('$', ','), array('', ''), $array['AmountD24']);
                    }
                    if ($array['AmountD25']) {
                        $amountPage2 = $amountPage2 + str_replace(array('$', ','), array('', ''), $array['AmountD25']);
                    }
                    if ($array['DeductProceds'] == 'Deduct') {
                        /* add deduct */
                        $NewJsonDeduct[$array['Page2Line']] = $array['Description'] . '||' . $amountPage2;
                        /**/
                    }
                    if ($array['DeductProceds'] == 'IncludedWire') {
                        /* add proceds */
                        $NewJsonProceds[$array['Page2Line']] = $array['Description'] . '||' . $amountPage2;
                        /**/
                    }
                    //print_r($NewJsonDeduct);
                    //if ($NewJsonDeduct) {
                    if (!$idcdhud_page245) {
                        $cdhud_page245_obj->setidcdhud($cdhudTransaction->getidcdhud());
                        $idcdhud_page245 = $cdhud_page245_obj->getidcdhud_page245();
                    }
                    $cdhud_page245_obj->updateDeducts($idcdhud_page245, json_encode($NewJsonDeduct));
                    //}
                    //if ($NewJsonProceds) {
                    if (!$idcdhud_page245) {
                        $cdhud_page245_obj->setidcdhud($cdhudTransaction->getidcdhud());
                        $idcdhud_page245 = $cdhud_page245_obj->getidcdhud_page245();
                    }
                    $cdhud_page245_obj->updateProceds($idcdhud_page245, json_encode($NewJsonProceds));
                    //}
                    /**/
                    /**/
                    $ArrayReturn['Function'] = 'PushData';
                    $ArrayReturn['Page'] = 'page2';
                    $ArrayReturn['Line'] = $IdCDLine;
                    $ArrayReturn['Data'] = json_encode($array);
                    echo json_encode($ArrayReturn);
                }
            }
            /**/
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
                $idb = $cdhudTransaction->getbankaccount();
                if ($idb) {
                    $bank = $bank_obj->getbankById($idb);
                    //print_r($bank);
                    if ($bank) {
                        UpdateBanksBalances($idb);
                    }
                }
            }
            /**/
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 02
function GetDataCDHUD($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //check
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $cdhud_page2_obj = $GetClass->GetClass('cdhud_page2');
        $cdhud_page3_obj = $GetClass->GetClass('cdhud_page3');
        $cdhud_page245_obj = $GetClass->GetClass('cdhud_page245');
        $deposit_obj = $GetClass->GetClass('deposit');
        $office_obj = $GetClass->GetClass('office');
        $contact_obj = $GetClass->GetClass('contact');
        $rolelist_obj = $GetClass->GetClass('rolelist');
        $transaction_obj = $GetClass->GetClass('transaction');
        $property_obj = $GetClass->GetClass('property');
        $requeriment_list_obj = $GetClass->GetClass('requeriment_list');
        $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
        $idofficeinfo = '1';
        $office = $office_obj->getofficeById($idofficeinfo);
        if ($array['idtransaction']) {//RealEstateBrokerSellerContact
            $ArrayReturn = array();
            if ($office) {
                $sett_name = $office->getmanagername();
                $sett_address = $office->getaddress() . ',' . $office->getcity() . ',' . $office->getstate() . ',' . $office->getzip();
                $nameoffice = $office->getname();
                $sett_phone = $office->getphone();
                if (strpos($office->getlicense(), '||') !== false) {
                    $office_license = explode('||', $office->getlicense());
                    $office_license_contact = $office_license[1];
                    $office_license = $office_license[0];
                } else {
                    $office_license = $office->getlicense();
                    $office_license_contact = '';
                }
                $ArrayReturn['SettlementAgentNameb'] = json_encode(array('SettlementAgentName' => $nameoffice));
                $ArrayReturn['SettlementAgentAddressb'] = json_encode(array('SettlementAgentAddress' => $sett_address));
                $ArrayReturn['SettlementAgentSTLicenseIDb'] = json_encode(array('SettlementAgentSTLicenseID' => $office_license));
                $ArrayReturn['SettlementAgentContactb'] = json_encode(array('SettlementAgentContact' => $sett_name));
                $ArrayReturn['SettlementAgentContactSTLicenseIDb'] = json_encode(array('SettlementAgentContactSTLicenseID' => $office_license_contact));
                //$ArrayReturn['SettlementAgentEmailb'] = json_encode(array('SettlementAgentEmail' => $Name));
                $ArrayReturn['SettlementAgentPhoneb'] = json_encode(array('SettlementAgentPhone' => $sett_phone));
            }
            /* Transaction */
            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
            //print_r($transaction);
            if ($transaction) {
                //$ArrayReturn['Forclosingdate'] = json_encode(array('Forclosingdate'=>date("m/d/Y", strtotime($transaction->getclosingdate()))));
                $ArrayReturn['Forclosingdate'] = date("m/d/Y", strtotime($transaction->getclosingdate()));
                $ArrayReturn['ForFileNumber'] = $transaction->gettransactionnumber();
                $property = $property_obj->getpropertyById($transaction->getidproperty());
                $propertyAddress = $property->get_StreetAddress();
                if ($property->get_StreetAddress2()) {
                    $propertyAddress .= ' ' . $property->get_StreetAddress2();
                }
                $propertyAddress .= ', ' . $property->get_City() . ', ' . $property->get_State() . ' - ' . $property->get_PostalCode();
                $ArrayReturn['ForPropertyAddress'] = '<b>' . $propertyAddress . '</b>';
                //$ArrayReturn['CurrentCounty'] = $property->get_County();
                if ($transaction->getidrequirementslist()) {
                    $requeriment_list = $requeriment_list_obj->getrequeriment_listById($transaction->getidrequirementslist());
                    $ArrayReturn['ForPurpose'] = '<b>' . $requeriment_list->getname() . '</b>';
                }
                $ArrayReturn['DateIssuedCD'] = json_encode(array('DateIssued' => date('m/d/Y')));
                $ArrayReturn['DisbursementDateCD'] = json_encode(array('DisbursementDate' => date("m/d/Y", strtotime($transaction->getclosingdate()))));
                $ArrayReturn['LoanIDCD'] = json_encode(array('LoanID' => $transaction->getidloan()));
            } else {
                die('Error : Not Found Transaction');
            }
            /**/
            /* parties */
            $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $array['idtransaction']);
            //print_r($transaction_contact);
            if ($transaction_contact) {
                $Buyercount = 1;
                $Sellercount = 1;
                foreach ($transaction_contact as $t_c) {
                    if ($t_c->getidcontact() && $t_c->getidrole()) {
                        $party = $contact_obj->getcontactById($t_c->getidcontact());
                        $rolelist = $rolelist_obj->getrolelistById($t_c->getidrole());
                        $ControlBuyer = 'buyer';
                        if (strtolower($requeriment_list->getname()) == 'refi') {
                            $ControlBuyer = 'borrower';
                        }
                        if (strtolower($rolelist->getname()) == $ControlBuyer) {
                            $Name = $party->getfirstname() . ' ' . $party->getsurname();
                            if (!trim($Name)) {
                                $Name = $party->getcompany();
                            }
                            $ArrayReturn['ForBuyer' . $Buyercount] = '<b>' . $Name . '</b>';
                            $Buyercount++;
                            if (!$ArrayReturn['ForBuyerAddress']) {
                                $Address = $party->getaddress1();
                                if ($party->getaddress2()) {
                                    $Address .= ', ' . $party->getaddress1();
                                }
                                $Address .= ' ' . $party->getcity() . ', ' . $party->getstate() . ' - ' . $party->getzip();
                                if (trim(str_replace(array(',', '-'), '', $Address))) {
                                    $ArrayReturn['ForBuyerAddress'] = '<b>' . $Address . '</b>';
                                }
                            }
                        }
                        if (strtolower($rolelist->getname()) == 'seller') {
                            $Name = $party->getfirstname() . ' ' . $party->getsurname();
                            if (!trim($Name)) {
                                $Name = $party->getcompany();
                            }
                            $ArrayReturn['ForSeller' . $Sellercount] = '<b>' . $Name . '</b>';
                            $Sellercount++;
                            if (!$ArrayReturn['ForSellerAddress']) {
                                $Address = $party->getaddress1();
                                if ($party->getaddress2()) {
                                    $Address .= ', ' . $party->getaddress1();
                                }
                                $Address .= ' ' . $party->getcity() . ', ' . $party->getstate() . ' - ' . $party->getzip();
                                if (trim(str_replace(array(',', '-'), '', $Address))) {
                                    $ArrayReturn['ForSellerAddress'] = '<b>' . $Address . '</b>';
                                }
                            }
                        }
                        if (strtolower($rolelist->getname()) == 'lender') {
                            $Name = $party->getfirstname() . ' ' . $party->getsurname();
                            $ArrayReturn['LenderContactb'] = json_encode(array('LenderContact' => $Name));
                            if (!trim($Name)) {
                                $Name = $party->getcompany();
                                $ArrayReturn['LenderNameb'] = json_encode(array('LenderName' => $party->getcompany()));
                                $ArrayReturn['LenderContactb'] = json_encode(array('LenderContact' => $party->getcompany()));
                            }
                            $ArrayReturn['ForLender'] = '<b>' . $Name . '</b>';
                            $lender_address = $party->getaddress1();
                            if ($party->getcity()) {
                                if (trim($lender_address) != '') {
                                    $lender_address = $lender_address . ',' . $party->getcity();
                                } else {
                                    $lender_address = $party->getcity();
                                }
                            }
                            if ($party->getstate()) {
                                if (trim($lender_address) != '') {
                                    $lender_address = $lender_address . ',' . $party->getstate();
                                } else {
                                    $lender_address = $party->getstate();
                                }
                            }
                            if ($party->getzip()) {
                                if (trim($lender_address) != '') {
                                    $lender_address = $lender_address . ',' . $party->getzip();
                                } else {
                                    $lender_address = $party->getzip();
                                }
                            }
                            $ArrayReturn['LenderAddressb'] = json_encode(array('LenderAddress' => $lender_address));
                            $ArrayReturn['LenderEmailb'] = json_encode(array('LenderEmail' => $party->getemail()));
                            $ArrayReturn['LenderPhoneb'] = json_encode(array('LenderPhone' => $party->getphone()));
                            $ArrayReturn['LenderSTLicenseIDb'] = json_encode(array('LenderSTLicenseID' => $party->getlicense()));
                        }
                        if (strtolower($rolelist->getname()) == 'mtgbroker') {
                            $Name = $party->getfirstname() . ' ' . $party->getsurname();
                            $ArrayReturn['MortgageBrokerContactb'] = json_encode(array('MortgageBrokerContact' => $Name));
                            if (!trim($Name)) {
                                $Name = $party->getcompany();
                                $ArrayReturn['MortgageBrokerNameb'] = json_encode(array('MortgageBrokerName' => $party->getcompany()));
                                $ArrayReturn['MortgageBrokerContactb'] = json_encode(array('MortgageBrokerContact' => $party->getcompany()));
                            }
                            $MortgageBroker_address = $party->getaddress1();
                            if ($party->getcity()) {
                                if (trim($MortgageBroker_address) != '') {
                                    $MortgageBroker_address = $MortgageBroker_address . ',' . $party->getcity();
                                } else {
                                    $MortgageBroker_address = $party->getcity();
                                }
                            }
                            if ($party->getstate()) {
                                if (trim($MortgageBroker_address) != '') {
                                    $MortgageBroker_address = $MortgageBroker_address . ',' . $party->getstate();
                                } else {
                                    $MortgageBroker_address = $party->getstate();
                                }
                            }
                            if ($party->getzip()) {
                                if (trim($MortgageBroker_address) != '') {
                                    $MortgageBroker_address = $MortgageBroker_address . ',' . $party->getzip();
                                } else {
                                    $MortgageBroker_address = $party->getzip();
                                }
                            }
                            $ArrayReturn['MortgageBrokerAddressb'] = json_encode(array('MortgageBrokerAddress' => $MortgageBroker_address));
                            $ArrayReturn['MortgageBrokerEmailb'] = json_encode(array('MortgageBrokerEmail' => $party->getemail()));
                            $ArrayReturn['MortgageBrokerPhoneb'] = json_encode(array('MortgageBrokerPhone' => $party->getphone()));
                            $ArrayReturn['MortgageBrokerSTLicenseIDb'] = json_encode(array('MortgageBrokerSTLicenseID' => $party->getlicense()));
                        }
                        if (strtolower($rolelist->getname()) == 'reagent' || strtolower($rolelist->getname()) == 'selling agent') {
                            if ($t_c->getside() == 'buyer' || strtolower($rolelist->getname()) == 'selling agent') {
                                $Name = $party->getfirstname() . ' ' . $party->getsurname();
                                $ArrayReturn['RealEstateBrokerBuyerContactb'] = json_encode(array('RealEstateBrokerBuyerContact' => $Name));
                                $ArrayReturn['RealEstateBrokerBuyerNameb'] = json_encode(array('RealEstateBrokerBuyerName' => $party->getcompany()));
                                if (!trim($Name)) {
                                    $ArrayReturn['RealEstateBrokerBuyerContactb'] = json_encode(array('RealEstateBrokerBuyerContact' => $party->getcompany()));
                                }
                                $RealEstateBrokerBuyer_address = $party->getaddress1();
                                if ($party->getcity()) {
                                    if (trim($RealEstateBrokerBuyer_address) != '') {
                                        $RealEstateBrokerBuyer_address = $RealEstateBrokerBuyer_address . ',' . $party->getcity();
                                    } else {
                                        $RealEstateBrokerBuyer_address = $party->getcity();
                                    }
                                }
                                if ($party->getstate()) {
                                    if (trim($RealEstateBrokerBuyer_address) != '') {
                                        $RealEstateBrokerBuyer_address = $RealEstateBrokerBuyer_address . ',' . $party->getstate();
                                    } else {
                                        $RealEstateBrokerBuyer_address = $party->getstate();
                                    }
                                }
                                if ($party->getzip()) {
                                    if (trim($RealEstateBrokerBuyer_address) != '') {
                                        $RealEstateBrokerBuyer_address = $RealEstateBrokerBuyer_address . ',' . $party->getzip();
                                    } else {
                                        $RealEstateBrokerBuyer_address = $party->getzip();
                                    }
                                }
                                $ArrayReturn['RealEstateBrokerBuyerAddressb'] = json_encode(array('RealEstateBrokerBuyerAddress' => $RealEstateBrokerBuyer_address));
                                $ArrayReturn['RealEstateBrokerBuyerEmailb'] = json_encode(array('RealEstateBrokerBuyerEmail' => $party->getemail()));
                                $ArrayReturn['RealEstateBrokerBuyerPhoneb'] = json_encode(array('RealEstateBrokerBuyerPhone' => $party->getphone()));
                                $ArrayReturn['RealEstateBrokerBuyerSTLicenseIDb'] = json_encode(array('RealEstateBrokerBuyerSTLicenseID' => $party->getlicense()));
                            }
                        }
                        if (strtolower($rolelist->getname()) == 'reagent' || strtolower($rolelist->getname()) == 'listing agent') {
                            if ($t_c->getside() == 'seller' || strtolower($rolelist->getname()) == 'listing agent') {
                                $Name = $party->getfirstname() . ' ' . $party->getsurname();
                                $ArrayReturn['RealEstateBrokerSellerContactb'] = json_encode(array('RealEstateBrokerSellerContact' => $Name));
                                $ArrayReturn['RealEstateBrokerSellerNameb'] = json_encode(array('RealEstateBrokerSellerName' => $party->getcompany()));
                                if (!trim($Name)) {
                                    $ArrayReturn['RealEstateBrokerSellerContactb'] = json_encode(array('RealEstateBrokerSellerContact' => $party->getcompany()));
                                }
                                $RealEstateBrokerSeller_address = $party->getaddress1();
                                if ($party->getcity()) {
                                    if (trim($RealEstateBrokerSeller_address) != '') {
                                        $RealEstateBrokerSeller_address = $RealEstateBrokerSeller_address . ',' . $party->getcity();
                                    } else {
                                        $RealEstateBrokerSeller_address = $party->getcity();
                                    }
                                }
                                if ($party->getstate()) {
                                    if (trim($RealEstateBrokerSeller_address) != '') {
                                        $RealEstateBrokerSeller_address = $RealEstateBrokerSeller_address . ',' . $party->getstate();
                                    } else {
                                        $RealEstateBrokerSeller_address = $party->getstate();
                                    }
                                }
                                if ($party->getzip()) {
                                    if (trim($RealEstateBrokerSeller_address) != '') {
                                        $RealEstateBrokerSeller_address = $RealEstateBrokerSeller_address . ',' . $party->getzip();
                                    } else {
                                        $RealEstateBrokerSeller_address = $party->getzip();
                                    }
                                }
                                $ArrayReturn['RealEstateBrokerSellerAddressb'] = json_encode(array('RealEstateBrokerSellerAddress' => $RealEstateBrokerSeller_address));
                                $ArrayReturn['RealEstateBrokerSellerEmailb'] = json_encode(array('RealEstateBrokerSellerEmail' => $party->getemail()));
                                $ArrayReturn['RealEstateBrokerSellerPhoneb'] = json_encode(array('RealEstateBrokerSellerPhone' => $party->getphone()));
                                $ArrayReturn['RealEstateBrokerSellerSTLicenseIDb'] = json_encode(array('RealEstateBrokerSellerSTLicenseID' => $party->getlicense()));
                            }
                        }
                    }
                }
            }
            /**/
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            //print_r($cdhudTransaction);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
                /* Use CD */
                $dataCD = $cdhudTransaction->getdata();
                if ($dataCD) {
                    $dataCD = json_decode($dataCD, true);
                } else {
                    $dataCD = array();
                }
                if (!isset($_SESSION)) {
                    session_start();
                }
                if ($dataCD['UserOpenId']) {
                    if (session_id() == $dataCD['UserOpenId']) {
                        $ArrayReturn['BlockedCD'] = '';
                    }
                } else {
                    
                }
                /**/
                /* Loan Terms */
                $loanterm = '';
                if ($cdhudTransaction->getLoanTerm()) {
                    $ArrayLoanTerm = json_decode($cdhudTransaction->getLoanTerm(), true);
                    $loanterm = $ArrayLoanTerm['year_terms'];
                    foreach ($ArrayLoanTerm as $keyT => $ValueT) {
                        $ArrayReturn[$keyT . 'b'] = json_encode(array($keyT => $ValueT));
                    }
                    $ArrayReturn['t19b'] = json_encode(array('t19' => $ArrayLoanTerm['interest_rate']));
                    $ArrayReturn['t21b'] = json_encode(array('t21' => $ArrayLoanTerm['CreditMonthlyinterest']));
                }
                /**/
                /**/
                if ($cdhudTransaction->getProjectedPayments()) {
                    $ArrayProjectedPayments = json_decode($cdhudTransaction->getProjectedPayments(), true);
                    foreach ($ArrayProjectedPayments as $keyP => $ValueP) {
                        $ArrayReturn[$keyP . 'b'] = json_encode(array($keyP => $ValueP));
                    }
                }
                /**/
                $ArrayReturn['LoanTermCD'] = json_encode(array('LoanTerm' => $loanterm));
                $ArrayReturn['ProductCD'] = json_encode(array('Product' => $cdhudTransaction->getProduct()));
                $ArrayReturn['LoanTypeCD'] = json_encode(array('LoanType' => $cdhudTransaction->getLoanType()));
                $ArrayReturn['MICCD'] = json_encode(array('MIC' => $cdhudTransaction->getMIC()));
                if ($cdhudTransaction->getDisbursementDate()) {
                    $ArrayReturn['DisbursementDateCD'] = json_encode(array('DisbursementDate' => date("m/d/Y", strtotime($cdhudTransaction->getDisbursementDate()))));
                }
                if ($cdhudTransaction->getDateIssued()) {
                    $ArrayReturn['DateIssuedCD'] = json_encode(array('DateIssued' => date("m/d/Y", strtotime($cdhudTransaction->getDateIssued()))));
                }
                $DataCD = $cdhudTransaction->getjsoninformation();
                if ($DataCD) {
                    $DataCD = json_decode($DataCD, true);
                    if ($DataCD['SettlementAgent']) {
                        $ArrayReturn['SettlementAgentCD'] = json_encode(array('SettlementAgent' => $DataCD['SettlementAgent']));
                    } else {
                        $ArrayReturn['SettlementAgentCD'] = json_encode(array('SettlementAgent' => $office->getname()));
                    }
                    if ($DataCD['LoanTypeOther']) {
                        $ArrayReturn['LoanTypeOtherCD'] = json_encode(array('LoanTypeOther' => $DataCD['LoanTypeOther']));
                    }
                } else {
                    $ArrayReturn['SettlementAgentCD'] = json_encode(array('SettlementAgent' => $office->getname()));
                }
                $cdhud_page2 = $cdhud_page2_obj->getAllcdhud_page2ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                $cdhud_page3 = $cdhud_page3_obj->getAllcdhud_page3ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                $DepositsTransaction = $deposit_obj->getAlldepositForColumnValue('idtransaction', $array['idtransaction']);
                if ($DepositsTransaction) {
                    foreach ($DepositsTransaction as $Deposit) {
                        if (is_object($Deposit)) {
                            $datadeposit = json_decode($Deposit->getdata(), true);
                            if ($datadeposit['hudlineDeposit']) {
                                if ($datadeposit['escrowthird']) {
                                    $ArrayReturn[$datadeposit['hudlineDeposit']] = json_encode(array($datadeposit['hudlineDeposit'] . '_1' => $datadeposit['heldamount']));
                                } else {
                                    $ArrayReturn[$datadeposit['hudlineDeposit']] = json_encode(array($datadeposit['hudlineDeposit'] . '_1' => $datadeposit['TotalDeposit']));
                                }
                            }
                        }
                    }
                }
                if ($cdhudTransaction) {
                    $SalesJson = json_decode($cdhudTransaction->getSalesPrice(), true);
                    $arrayE02 = array();
                    if (is_array($SalesJson)) {
                        $ArrayReturn['SalesPrice'] = json_encode(array('SalesPrice' => $SalesJson['SalesPriceDialog']));
                        $ArrayReturn['SalesPrice2'] = json_encode(array('SalesAmountPanel' => $SalesJson['SalesPriceDialog']));
                        $ArrayReturn['K-01'] = json_encode(array('K-01_1' => $SalesJson['SalesPriceDialog']));
                        $ArrayReturn['M-01'] = json_encode(array('M-01_1' => $SalesJson['SalesPriceDialog']));
                        $ArrayReturn['K-02'] = json_encode(array('K-02_1' => $SalesJson['PersonalPropertyDialog']));
                        $ArrayReturn['M-02'] = json_encode(array('M-02_1' => $SalesJson['PersonalPropertyDialog']));
                        if ($SalesJson['CheckStampDeed']) {
                            $arrayE02['Description'] = 'Docs Stamps';
                            $arrayE02['E-02_3'] = $SalesJson['StampDeedInput'];
                            $ArrayReturn['E-02'] = json_encode($arrayE02);
                        }
                    }
                    $LoanJson = json_decode($cdhudTransaction->getLoanAmount(), true);
                    if (is_array($LoanJson)) {
                        $ArrayReturn['LoanAmount'] = json_encode(array('LoanAmount' => $LoanJson['LoanAmountDialog']));
                        $ArrayReturn['LoanAmount2'] = json_encode(array('LoanAmountPanel' => $LoanJson['LoanAmountDialog']));
                        $ArrayReturn['L-02'] = json_encode(array('L-02_1' => $LoanJson['LoanAmountDialog']));
                        if ($LoanJson['CheckStampMortgage']) {
                            $arrayE02['Description'] = 'Docs Stamps';
                            $arrayE02['E-02_1'] = $LoanJson['StampMortgageInput'];
                            $ArrayReturn['E-02'] = json_encode($arrayE02);
                        }
                        if ($LoanJson['CheckIntangibleTax']) {
                            $ArrayReturn['E-03'] = json_encode(array('Description' => 'Intangible Tax', 'E-03_1' => $LoanJson['IntangibleTaxInput']));
                        }
                    }
                }
                if ($cdhud_page2) {
                    $cdhud_page2 = $cdhud_page2[0];
                    /**/
                    $ArrayReturn['LenderCreditb'] = json_encode(array('LenderCredit' => $cdhud_page2->getLenderCredit()));
                    /**/
                    $cdhud_page2 = (array) $cdhud_page2;
                    $ArrayLine = array();
                    foreach ($cdhud_page2 as $key => $value) {
                        if ($value) {
                            $Line = '';
                            $JsonPage2 = json_decode($value, true);
                            if ($JsonPage2['Page2Line']) {
                                $Line = $JsonPage2['Page2Line'];
                            }
                            if ($JsonPage2['Page2ELine']) {
                                $Line = $JsonPage2['Page2ELine'];
                            }
                            //$Line = $JsonPage2['Page2Line'];
                            if ($Line == 'A-01') {
                                $ArrayLine[$Line . '_a'] = $JsonPage2['AmountPoints'];
                            } else {
                                if ($Line == 'E-01') {
                                    $ArrayLine[$Line . '_a'] = $JsonPage2['total_fee_deed'];
                                    $ArrayLine[$Line . '_b'] = $JsonPage2['total_fee_mortgage'];
                                    $ArrayLine[$Line . '_1'] = '' . str_replace('USD ', '', money_format('%i', str_replace(array('$', ','), array('', ''), $JsonPage2['total_fee_deed']) + str_replace(array('$', ','), array('', ''), $JsonPage2['total_fee_mortgage'])));
                                    ;
                                } else {
                                    if ($Line == 'F-01' || $Line == 'F-02') {
                                        $ArrayLine[$Line . '_a'] = $JsonPage2['AmountProration'];
                                        $ArrayLine[$Line . '_b'] = $JsonPage2['MonthsProration'];
                                        $ArrayLine[$Line . '_c'] = $JsonPage2['ToLine'];
                                    } else {
                                        if ($Line == 'G-01' || $Line == 'G-02' || $Line == 'G-03' || $Line == 'G-04' || $Line == 'G-05' || $Line == 'G-06' || $Line == 'G-07') {
                                            $ArrayLine[$Line . '_b'] = $JsonPage2['AmountProration'];
                                            $ArrayLine[$Line . '_c'] = $JsonPage2['MonthsProration'];
                                            $ArrayLine[$Line . '_a'] = $JsonPage2['Description'];
                                        } else {
                                            $AddDescription = '';
                                            if ($JsonPage2['ProrationAmount'] == 'Perday') {
                                                $AddDescription = '(' . $JsonPage2['AmountProration'] . ' per day from ' . $JsonPage2['DateInit'] . ' to ' . $JsonPage2['DateEnd'] . ')';
                                            }
                                            if ($JsonPage2['ProrationAmount'] == 'PerMonth') {
                                                $AddDescription = '(' . $JsonPage2['AmountProration'] . ' per ' . $JsonPage2['MonthsProration'] . ' Months)';
                                            }
                                            $ArrayLine['Description'] = $JsonPage2['Description'] . ' ' . $AddDescription;
                                            $ArrayLine['ToLine'] = $JsonPage2['ToLine'];
                                        }
                                    }
                                }
                            }
                            for ($i = 1; $i <= 5; $i++) {
                                if ($JsonPage2['AmountD2' . $i]) {
                                    $ArrayLine[$Line . '_' . $i] = $JsonPage2['AmountD2' . $i];
                                }
                            }
                            if ($Line) {
                                $ArrayReturn[$Line] = json_encode($ArrayLine);
                            }
                            $ArrayLine = array();
                        }
                    }
                }
                /* page3 */
                if ($cdhud_page3) {
                    $cdhud_page3 = $cdhud_page3[0];
                    /* Calculating */
                    $ArrayDataCalc = $cdhud_page3->getDataCalculating();
                    if ($ArrayDataCalc) {
                        $ArrayDataCalc = json_decode($ArrayDataCalc, true);
                        foreach ($ArrayDataCalc as $keyDT => $valueDT) {
                            $ArrayReturn[$keyDT . 'b'] = json_encode(array($keyDT => $valueDT));
                        }
                    }
                    /**/
                    $cdhud_page3 = (array) $cdhud_page3;
                    $ArrayLine = array();
                    foreach ($cdhud_page3 as $key => $value) {
                        if ($value) {
                            $Line = '';
                            $JsonPage3 = json_decode($value, true);
                            if ($ArrayReturn[$JsonPage3['Hudlinep3_1']]) {
                                $Line = $JsonPage3['Hudlinep3_2'];
                            } else {
                                if ($JsonPage3['Hudlinep3_1']) {
                                    $Line = $JsonPage3['Hudlinep3_1'];
                                } else {
                                    if ($JsonPage3['Hudlinep3_2']) {
                                        $Line = $JsonPage3['Hudlinep3_2'];
                                    }
                                }
                            }
                            if ($Line) {
                                $toname = '';
                                if ($JsonPage3['TypeContact5'] == 'Person') {
                                    $toname .= trim($JsonPage3['FirstName5'] . ' ' . $JsonPage3['LastName5']);
                                } else {
                                    $toname .= trim($JsonPage3['CompanyName5']);
                                }
                                if ($toname) {
                                    $toname = ' to ' . $toname;
                                }
                                $ArrayLine[$Line . '_a'] = $JsonPage3['desc_d2'] . $toname;
                                if ($JsonPage3['adj_check_d2']) {
                                    $ArrayLine[$Line . '_1'] = $JsonPage3['adj_d2'];
                                } else {
                                    $ArrayLine[$Line . '_1'] = $JsonPage3['amount_d2'];
                                }
                            }
                            /**/
                            if (array_key_exists('total_Assumed', $JsonPage3)) {
                                $ArrayReturn['L-03'] = json_encode(array('L-03_1' => $JsonPage3['total_Assumed']));
                                $ArrayReturn['N-03'] = json_encode(array('N-03_1' => $JsonPage3['total_Assumed']));
                                $ArrayReturn['N-04'] = json_encode(array('N-04_1' => $JsonPage3['Amount_payyoff_a']));
                                $ArrayReturn['N-05'] = json_encode(array('N-05_1' => $JsonPage3['Amount_payyoff_b']));
                            }
                            /**/
                            if ($Line) {
                                $ArrayReturn[$Line] = json_encode($ArrayLine);
                            } else {
                                if ($ArrayReturn[$JsonPage3['Hudlinep32_1']]) {
                                    $Line = $JsonPage3['Hudlinep32_2'];
                                } else {
                                    if ($JsonPage3['Hudlinep32_1']) {
                                        $Line = $JsonPage3['Hudlinep32_1'];
                                    } else {
                                        $Line = $JsonPage3['Hudlinep32_2'];
                                    }
                                }
                                if ($Line) {
                                    if ($JsonPage3['Hudlinep32_1'] == 'K-11') {
                                        $ArrayLine[$Line . '_a'] = $JsonPage3['DescriptionTax'];
                                    } else {
                                        if ($JsonPage3['Hudlinep32_1'] == 'K-12' || $JsonPage3['Hudlinep32_1'] == 'K-13' || $JsonPage3['Hudlinep32_1'] == 'K-14' || $JsonPage3['Hudlinep32_1'] == 'K-15') {
                                            $ArrayLine[$Line . '_a'] = $JsonPage3['DescriptionTax'] . ' ' . $JsonPage3['BeginTax'] . ' to ' . $JsonPage3['ProrationTax'];
                                        }
                                    }
                                    $ArrayLine[$Line . '_b'] = $JsonPage3['BeginTax'];
                                    $ArrayLine[$Line . '_c'] = $JsonPage3['ProrationTax'];
                                    $ArrayLine[$Line . '_1'] = $JsonPage3['Taxline'];
                                    $ArrayReturn[$Line] = json_encode($ArrayLine);
                                }
                            }
                            $ArrayLine = array();
                        }
                    }
                }
                /**/
                /* page245 */
                if ($cdhud_page245) {
                    $cdhud_page245 = $cdhud_page245[0];
                    $REComm = $cdhud_page245->getREComm();
                    if ($REComm) {
                        $REComm = json_decode($REComm, true);
                        if ($REComm['LineCommission1'] && $REComm['TargetCommission1']) {
                            $ArrayLine['Description'] = 'Real Estate Commission';
                            if ($REComm['TypeContact3'] == 'Person') {
                                $ArrayLine['ToLine'] = $REComm['FirstName3'] . ' ' . $REComm['LastName3'];
                            } else {
                                $ArrayLine['ToLine'] = $REComm['CompanyName3'];
                            }
                            $ArrayLine[$REComm['LineCommission1'] . '_' . $REComm['TargetCommission1']] = $REComm['AmountCommission1Real'];
                            $ArrayReturn[$REComm['LineCommission1']] = json_encode($ArrayLine);
                            $ArrayLine = array();
                        }
                        if ($REComm['LineCommission2'] && $REComm['TargetCommission2']) {
                            $ArrayLine['Description'] = 'Real Estate Commission';
                            if ($REComm['TypeContact4'] == 'Person') {
                                $ArrayLine['ToLine'] = $REComm['FirstName4'] . ' ' . $REComm['LastName4'];
                            } else {
                                $ArrayLine['ToLine'] = $REComm['CompanyName4'];
                            }
                            $ArrayLine[$REComm['LineCommission2'] . '_' . $REComm['TargetCommission2']] = $REComm['AmountCommission2Real'];
                            $ArrayReturn[$REComm['LineCommission2']] = json_encode($ArrayLine);
                            $ArrayLine = array();
                        }
                        $ArrayLine = array();
                    }
                    $TitleIns = $cdhud_page245->getTitleIns();
                    if ($TitleIns) {
                        $TitleIns = json_decode($TitleIns, true);
                        if ($TitleIns['totileins']) {
                            $contact = $contact_obj->getcontactById($TitleIns['totileins']);
                            if (trim($contact->getfirstname() . ' ' . $contact->getsurname())) {
                                $underwriterlist = $contact->getfirstname() . ' ' . $contact->getsurname();
                            } else {
                                $underwriterlist = $contact->getcompany();
                            }
                        } else {
                            $underwriterlist = '';
                        }
                        if ($TitleIns['selectins2'] && $TitleIns['selectins2a']) {
                            $ArrayLine['Description'] = 'Title - Owner Title Insurance';
                            $ArrayLine['ToLine'] = $office->getname() . '/' . $underwriterlist;
                            $ArrayLine[$TitleIns['selectins2'] . '_' . $TitleIns['selectins2a']] = $TitleIns['value_owner'];
                            $ArrayReturn[$TitleIns['selectins2']] = json_encode($ArrayLine);
                            $ArrayLine = array();
                        }
                        if ($array['CheckSimulCredit']) {
                            $keyLender = 'LenderPolicyFinal';
                        } else {
                            $keyLender = 'value_lender';
                        }
                        if ($TitleIns['selectins3'] && $TitleIns['selectins3a']) {
                            $ArrayLine['Description'] = 'Title - Lender Title Insurance';
                            $ArrayLine['ToLine'] = $office->getname() . '/' . $underwriterlist;
                            $ArrayLine[$TitleIns['selectins3'] . '_' . $TitleIns['selectins3a']] = $TitleIns[$keyLender];
                            $ArrayReturn[$TitleIns['selectins3']] = json_encode($ArrayLine);
                            $ArrayLine = array();
                        }
                        if ($TitleIns['selectins4'] && $TitleIns['selectins4a']) {
                            $ArrayLine['Description'] = 'Title - Endorsment';
                            $ArrayLine['ToLine'] = $office->getname() . '/' . $underwriterlist;
                            $ArrayLine[$TitleIns['selectins4'] . '_' . $TitleIns['selectins4a']] = $TitleIns['value_endors'];
                            $ArrayReturn[$TitleIns['selectins4']] = json_encode($ArrayLine);
                            $ArrayLine = array();
                        }
                        if ($TitleIns['SplitOwner'] && $TitleIns['selectins2Split'] && $TitleIns['selectins2Splita']) {
                            $ArrayLine['Description'] = 'Title - Owner Title Insurance (Split)';
                            $ArrayLine['ToLine'] = $office->getname() . '/' . $underwriterlist;
                            $ArrayLine[$TitleIns['selectins2Split'] . '_' . $TitleIns['selectins2Splita']] = $TitleIns['value_ownerSplit'];
                            $ArrayReturn[$TitleIns['selectins2Split']] = json_encode($ArrayLine);
                            $ArrayLine = array();
                        }
                        if ($TitleIns['SplitLender'] && $TitleIns['selectins3Split'] && $TitleIns['selectins3Splita']) {
                            $ArrayLine['Description'] = 'Title - Lender Title Insurance (Split)';
                            $ArrayLine['ToLine'] = $office->getname() . '/' . $underwriterlist;
                            $ArrayLine[$TitleIns['selectins3Split'] . '_' . $TitleIns['selectins3Splita']] = $TitleIns['value_lenderSplit'];
                            $ArrayReturn[$TitleIns['selectins3Split']] = json_encode($ArrayLine);
                            $ArrayLine = array();
                        }
                        if ($TitleIns['CheckSimulCredit'] && $TitleIns['selectins5']) {
                            $ArrayLine['Description'] = 'SD/BC';
                            $ArrayLine['ToLine'] = '';
                            $ArrayLine[$TitleIns['selectins5'] . '_1'] = $TitleIns['LenderPolicyFinalForcheck'];
                            $ArrayReturn[$TitleIns['selectins5']] = json_encode($ArrayLine);
                            $ArrayLine = array();
                        }


                        $ArrayLine = array();
                    }
                    /* page4 */
                    $Arraycdpage4 = $cdhud_page245->getcdpage4();
                    if ($Arraycdpage4) {
                        $Arraycdpage4 = json_decode($Arraycdpage4, true);
                        foreach ($Arraycdpage4 as $keyp4 => $valuep4) {
                            $ArrayReturn[$keyp4 . 'b'] = json_encode(array($keyp4 => $valuep4));
                        }
                    }
                    /**/
                    /* page5 */
                    $Arraycdpage5 = $cdhud_page245->getcdpage5();
                    if ($Arraycdpage5) {
                        $Arraycdpage5 = json_decode($Arraycdpage5, true);
                        foreach ($Arraycdpage5 as $keyp5 => $valuep5) {
                            $ArrayReturn[$keyp5 . 'b'] = json_encode(array($keyp5 => $valuep5));
                        }
                    }
                    /**/
                }
                /**/
            }
            /* Check Predef */
            /* Page 2 */
            $name_template = 'HUDTEMPLATE_' . $transaction->getidrequirementslist();
            $hud2015_obj = $GetClass->GetClass('hud2015');
            $hud2015 = $hud2015_obj->getAllhud2015ForColumnValue('datalist', '"' . $name_template . '"');
            /**/
            $ArrayTransas = array();
            $CdReplaced = $hud2015_obj->getAllhud2015ForColumnValue('datalist', '"ArrayTransactionsInit"');
            if ($CdReplaced) {
                $CdReplaced = $CdReplaced[0];
                $ArrayTransas = $CdReplaced->getclosinginfo();
                if ($ArrayTransas) {
                    $ArrayTransas = json_decode($ArrayTransas, true);
                }
                $idArrayList = $CdReplaced->getidhud2015();
            } else {
                $hud2015_obj->setdatalist("ArrayTransactionsInit");
                $idArrayList = $hud2015_obj->getidhud2015();
            }
            /**/
            if ($hud2015) {
                $hud2015 = $hud2015[0];
                $ArrayTemplate = $hud2015->getclosinginfo();
                if ($ArrayTemplate) {
                    $ArrayTemplate = json_decode($ArrayTemplate, true);
                }
            }
            if ($ArrayTransas[$array['idtransaction']] != 'yes') {
                $ArrayTransas[$array['idtransaction']] = 'yes';
                $arrayLetter = array('A', 'B', 'C', 'E', 'F', 'G', 'H');
                foreach ($arrayLetter as $letter) {
                    for ($ln = 1; $ln <= 10; $ln++) {
                        $numline = $letter . '-' . $ln;
                        if ($ln < 10) {
                            $numline = $letter . '-0' . $ln;
                        }
                        if (!$ArrayReturn[$numline]) {
                            /* Insertar */
                            if ($ArrayTemplate[$numline]) {
                                $ArrayReturn[$numline] = InsertTemplate($numline, json_decode($ArrayTemplate[$numline], true), $array['idtransaction']);
                            }
                            /**/
                        }
                    }
                }
                $hud2015_obj->updateclosinginfo($idArrayList, json_encode($ArrayTransas));
            }
            /**/
            /**/
            /**/
            echo json_encode($ArrayReturn);
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

function InsertTemplate($Line, $ArrayTemplate, $idTransaction) {
    $GetClass = GetClassPsToDb();
    $cdhud_obj = $GetClass->GetClass('cdhud');
    $cdhud_page2_obj = $GetClass->GetClass('cdhud_page2');
    if ($idTransaction) {
        $ArrayTemplate['idtransaction'] = $idTransaction;
        $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $idTransaction);
        if ($cdhudTransaction) {
            $cdhudTransaction = $cdhudTransaction[0];
        } else {
            $cdhud_obj->setidtransaction($idTransaction);
            if ($ArrayTemplate['BankSelect']) {
                $cdhud_obj->updatebankaccount($cdhud_obj->getidcdhud(), $ArrayTemplate['BankSelect']);
            }
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $idTransaction);
            $cdhudTransaction = $cdhudTransaction[0];
        }
        $purchase_obj = $GetClass->GetClass('purchase');
        $AllPurchasesTransaction = $purchase_obj->getAllpurchaseForColumnvalue('idtransaction', $idTransaction);
        if ($ArrayTemplate['Page2Line']) {
            if ($ArrayTemplate['IsCheck']) {
                $idpurchase = '';
                if ($AllPurchasesTransaction) {
                    foreach ($AllPurchasesTransaction as $purchase) {
                        if ($purchase->gethudline() == $ArrayTemplate['Page2Line']) {
                            $idpurchase = $purchase->getidpurchase();
                        }
                    }
                }
                if (!$idpurchase) {
                    $purchase_obj->setidtransaction($ArrayTemplate['idtransaction']);
                    $idpurchase = $purchase_obj->getidpurchase();
                    $purchase_obj->updatehudline($idpurchase, $ArrayTemplate['Page2Line']);
                }
                /* Amount */
                $AmountLine = 0;
                if ($ArrayTemplate['AmountD21']) {
                    $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $ArrayTemplate['AmountD21']);
                }
                if ($ArrayTemplate['AmountD22']) {
                    $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $ArrayTemplate['AmountD22']);
                }
                if ($ArrayTemplate['AmountD23']) {
                    $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $ArrayTemplate['AmountD23']);
                }
                if ($ArrayTemplate['AmountD24']) {
                    $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $ArrayTemplate['AmountD24']);
                }
                if ($ArrayTemplate['AmountD25']) {
                    $AmountLine = $AmountLine + str_replace(array('$', ','), array('', ''), $ArrayTemplate['AmountD25']);
                }
                /**/
                if (!$ArrayTemplate['IdContact1']) {
                    $ArrayTemplate['IdContact1'] = CreaContactForCheck($ArrayTemplate['FirstName1'], $ArrayTemplate['LastName1'], $ArrayTemplate['CompanyName1']);
                }
                $Line_ = array('TypeContact' => $ArrayTemplate['TypeContact'], 'Amount' => $AmountLine, 'IdContact' => $ArrayTemplate['IdContact1']);
                $Lines_ = array(json_encode($Line_));
                $account = json_encode($Lines_);
                /* Update Data Purchase */
                $purchase_obj->updateaccount($idpurchase, $account);
                $purchase_obj->updateexpensedate($idpurchase, date('Y-m-d'));
                $purchase_obj->updateidlogin($idpurchase, $idlogin);
                $purchase_obj->updatedescription($idpurchase, $ArrayTemplate['Description']);
                $purchase_obj->updateamount($idpurchase, $AmountLine);
                $purchase_obj->updatebankaccount($idpurchase, $cdhudTransaction->getbankaccount());
                /**/
            } else {
                if ($AllPurchasesTransaction) {
                    $idpurchase = '';
                    foreach ($AllPurchasesTransaction as $purchase) {
                        if ($purchase->gethudline() == $ArrayTemplate['Page2Line']) {
                            $idpurchase = $purchase->getidpurchase();
                        }
                    }
                    if ($idpurchase) {
                        $purchase_obj->deletepurchase($idpurchase);
                    }
                }
            }
        }
        $cdhud_page2 = $cdhud_page2_obj->getAllcdhud_page2ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
        $update = 'update' . str_replace('-', '', $Line);
        if ($cdhud_page2) {
            $cdhud_page2 = $cdhud_page2[0];
            $cdhud_page2_obj->$update($cdhud_page2->getidcdhud_page2(), json_encode($ArrayTemplate));
        } else {
            $cdhud_page2_obj->setidcdhud($cdhudTransaction->getidcdhud());
            $cdhud_page2_obj->$update($cdhud_page2_obj->getidcdhud_page2(), json_encode($ArrayTemplate));
        }
        /**/
        $ArrayLine = array();
        $JsonPage2 = $ArrayTemplate;
        if ($Line == 'A-01') {
            $ArrayLine[$Line . '_a'] = $JsonPage2['AmountPoints'];
        } else {
            if ($Line == 'E-01') {
                $ArrayLine[$Line . '_a'] = $JsonPage2['total_fee_deed'];
                $ArrayLine[$Line . '_b'] = $JsonPage2['total_fee_mortgage'];
                $ArrayLine[$Line . '_1'] = '' . str_replace('USD ', '', money_format('%i', str_replace(array('$', ','), array('', ''), $JsonPage2['total_fee_deed']) + str_replace(array('$', ','), array('', ''), $JsonPage2['total_fee_mortgage'])));
                ;
            } else {
                if ($Line == 'F-01' || $Line == 'F-02') {
                    $ArrayLine[$Line . '_a'] = $JsonPage2['AmountProration'];
                    $ArrayLine[$Line . '_b'] = $JsonPage2['MonthsProration'];
                    $ArrayLine[$Line . '_c'] = $JsonPage2['ToLine'];
                } else {
                    if ($Line == 'G-01' || $Line == 'G-02' || $Line == 'G-03' || $Line == 'G-04' || $Line == 'G-05' || $Line == 'G-06' || $Line == 'G-07') {
                        $ArrayLine[$Line . '_b'] = $JsonPage2['AmountProration'];
                        $ArrayLine[$Line . '_c'] = $JsonPage2['MonthsProration'];
                        $ArrayLine[$Line . '_a'] = $JsonPage2['Description'];
                    } else {
                        $AddDescription = '';
                        if ($JsonPage2['ProrationAmount'] == 'Perday') {
                            $AddDescription = '(' . $JsonPage2['AmountProration'] . ' per day from ' . $JsonPage2['DateInit'] . ' to ' . $JsonPage2['DateEnd'] . ')';
                        }
                        if ($JsonPage2['ProrationAmount'] == 'PerMonth') {
                            $AddDescription = '(' . $JsonPage2['AmountProration'] . ' per ' . $JsonPage2['MonthsProration'] . ' Months)';
                        }
                        $ArrayLine['Description'] = $JsonPage2['Description'] . ' ' . $AddDescription;
                        $ArrayLine['ToLine'] = $JsonPage2['ToLine'];
                    }
                }
            }
        }
        for ($i = 1; $i <= 5; $i++) {
            if ($JsonPage2['AmountD2' . $i]) {
                $ArrayLine[$Line . '_' . $i] = $JsonPage2['AmountD2' . $i];
            }
        }
        return json_encode($ArrayLine);
        /**/
    }
}

// 03
function SaveBankTransaction($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $cdhud_page2_obj = $GetClass->GetClass('cdhud_page2');
        $cdhud_page3_obj = $GetClass->GetClass('cdhud_page3');
        $cdhud_page245_obj = $GetClass->GetClass('cdhud_page245');
        if ($array['idtransaction']) {
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
                $cdhud_obj->updatebankaccount($cdhudTransaction->getidcdhud(), $array['IdBank']);
            } else {
                $cdhud_obj->setidtransaction($array['idtransaction']);
                $cdhud_obj->updatebankaccount($cdhud_obj->getidcdhud(), $array['IdBank']);
                echo $cdhud_obj->getidcdhud();
            }
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 04
function GetIDLinesMarks($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $cdhud_page2_obj = $GetClass->GetClass('cdhud_page2');
        $cdhud_page3_obj = $GetClass->GetClass('cdhud_page3');
        $cdhud_page245_obj = $GetClass->GetClass('cdhud_page245');
        $purchase_obj = $GetClass->GetClass('purchase');
        $ArrayReturn = array();
        if ($array['idtransaction']) {
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            //print_r($cdhudTransaction);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
                $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                //print_r($cdhud_page245);
                if ($cdhud_page245) {
                    $cdhud_page245 = $cdhud_page245[0]; //print_r($cdhud_page245);
                    $JsonDeducts = $cdhud_page245->getDeducts();
                    $JsonProceds = $cdhud_page245->getProceds();
                    if ($JsonDeducts) {
                        $JsonDeducts = json_decode($JsonDeducts, true);
                        foreach ($JsonDeducts as $ValueDedcut => $datade) {
                            $ArrayReturn[$ValueDedcut] = 'ClassDeduct';
                        }
                    }
                    if ($JsonProceds) {
                        $JsonProceds = json_decode($JsonProceds, true);
                        foreach ($JsonProceds as $ValueProceds => $datapro) {
                            $ArrayReturn[$ValueProceds] = 'ClassDeduct';
                        }
                    }
                }
            }
            /* Checks */
            $AllPurchasesTransaction = $purchase_obj->getAllpurchaseForColumnvalue('idtransaction', $array['idtransaction']);
            if ($AllPurchasesTransaction) {
                foreach ($AllPurchasesTransaction as $purchase) {
                    $hudline = $purchase->gethudline();
                    /* if (strpos($hudline, '_') === false) {
                      $hudline = explode('_', $hudline);
                      $hudline = $hudline[0];
                      } */
                    if ($ArrayReturn[$hudline] == 'ClassDeduct' || $ArrayReturn[$hudline] == 'ClassProceds') {
                        $ArrayReturn[$hudline] = 'ClassDeductCheck';
                    } else {
                        if ($hudline == 'TitleOffice' || $hudline == 'TitleUnderW') {
                            if ($cdhud_page245) {
                                if (is_array($cdhud_page245)) {
                                    $cdhud_page245 = $cdhud_page245[0];
                                }
                                $JsonTitleIns = $cdhud_page245->getTitleIns();
                                if ($JsonTitleIns) {
                                    $JsonTitleIns = json_decode($JsonTitleIns, true);
                                    if ($JsonTitleIns['selectins2']) {
                                        $ArrayReturn[$JsonTitleIns['selectins2']] = 'ClassCheck';
                                    }
                                    if ($JsonTitleIns['selectins3']) {
                                        $ArrayReturn[$JsonTitleIns['selectins3']] = 'ClassCheck';
                                    }
                                    if ($JsonTitleIns['selectins4']) {
                                        $ArrayReturn[$JsonTitleIns['selectins4']] = 'ClassCheck';
                                    }
                                    if ($JsonTitleIns['selectins5']) {
                                        $ArrayReturn[$JsonTitleIns['selectins5']] = 'ClassCheck';
                                    }
                                    if ($JsonTitleIns['selectins2Split']) {
                                        $ArrayReturn[$JsonTitleIns['selectins2Split']] = 'ClassCheck';
                                    }
                                    if ($JsonTitleIns['selectins3Split']) {
                                        $ArrayReturn[$JsonTitleIns['selectins3Split']] = 'ClassCheck';
                                    }
                                }
                            }
                        } else {
                            $ArrayReturn[$hudline] = 'ClassCheck';
                        }
                    }
                }
            }
            /**/
            echo json_encode($ArrayReturn);
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 05
function t_selected($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if (is_array($array) && $array['idtransaction']) {
            $id_transaction = $array['idtransaction'];
            $array_resp = array();
            /* ojb's */
            $transaction_obj = $GetClass->GetClass('transaction');
            $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
            $login_users_obj = $GetClass->GetClass('login_users');
            $general_config_obj = $GetClass->GetClass('general_config');
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $cdhud_page3_obj = $GetClass->GetClass('cdhud_page3');
            $purchase_obj = $GetClass->GetClass('purchase');
            $deposit_obj = $GetClass->GetClass('deposit');
            $requeriment_list_obj = $GetClass->GetClass('requeriment_list');
            $rolelist_obj = $GetClass->GetClass('rolelist');
            $contact_obj = $GetClass->GetClass('contact');
            $alert_obj = $GetClass->GetClass('alert');
            $task_obj = $GetClass->GetClass('task');
            $property_obj = $GetClass->GetClass('property');
            /**/
            /* GetData */
            $transaction = $transaction_obj->gettransactionById($id_transaction);
            $cdhud = $cdhud_obj->getAllcdhudForColumnvalue('idtransaction', $id_transaction);
            $deposit = $deposit_obj->getAlldepositForColumnValue('idtransaction', $id_transaction);
            $requeriment_list = $requeriment_list_obj->getAllrequeriment_lists();
            $login_users = $login_users_obj->getAlllogin_users();
            $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $id_transaction);
            $alerts = $alert_obj->getAllalertForColumnValue('idtransaction', $id_transaction);
            /**/
            /* Send Data */
            $array_return = array();
            if ($transaction) {
                /**/
                $property = $property_obj->getpropertyById($transaction->getidproperty());
                if($property){
                    $array_return['CurrentCounty'] = $property->get_County();
                }
                /**/
                $UserCreateTransaction = $login_users_obj->getlogin_usersById($transaction->getidlogin());
                $array_return['TransactionName'] = $transaction->getname();
                $array_return['TransactionNumber'] = $transaction->gettransactionnumber();
                $array_return['inputedBy'] = $UserCreateTransaction->getnameu();
                $array_return['dateT'] = $transaction->getdate();
                $array_return['TransactionType'] = $transaction->getidrequirementslist();
                $array_return['AutorizeAdmin'] = $transaction->gethomeownerassoc();
                $array_return['ClosingDatePanel'] = date('m/d/Y', strtotime($transaction->getclosingdate()));
                $aditionaldata = $transaction->getwarning();
                if ($aditionaldata) {
                    $aditionaldata = json_decode($aditionaldata, true);
                    if ($aditionaldata) {
                        $array_return['FullExecutedDate'] = $aditionaldata['FullExecutedDate'];
                        $array_return['InspectionPeriod'] = $aditionaldata['InspectionPeriod'];
                        if ($aditionaldata['InspectionPeriod']) {
                            $array_return['InspectionPeriodDate'] = date("m/d/Y", strtotime($aditionaldata['FullExecutedDate'] . '+ ' . $aditionaldata['InspectionPeriod'] . ' days'));
                        }
                        $array_return['LoanApprovalPeriod'] = $aditionaldata['LoanApprovalPeriod'];
                        if ($aditionaldata['LoanApprovalPeriod']) {
                            $array_return['LoanApprovalPeriodDate'] = date("m/d/Y", strtotime($aditionaldata['FullExecutedDate'] . '+ ' . $aditionaldata['LoanApprovalPeriod'] . ' days'));
                        }
                        $array_return['AppraisalContingency'] = $aditionaldata['AppraisalContingency'];
                        if ($aditionaldata['AppraisalContingency']) {
                            $array_return['AppraisalContingencyDate'] = date("m/d/Y", strtotime($aditionaldata['FullExecutedDate'] . '+ ' . $aditionaldata['AppraisalContingency'] . ' days'));
                        }

                        $array_return['HOAApplicationPeriod'] = $aditionaldata['HOAApplicationPeriod'];
                        if ($aditionaldata['HOAApplicationPeriod']) {
                            $array_return['HOAApplicationPeriodDate'] = date("m/d/Y", strtotime($aditionaldata['FullExecutedDate'] . '+ ' . $aditionaldata['HOAApplicationPeriod'] . ' days'));
                        }
                        $array_return['HOAApprovalPeriod'] = $aditionaldata['HOAApprovalPeriod'];
                        if ($aditionaldata['HOAApprovalPeriod']) {
                            $array_return['HOAApprovalPeriodDate'] = date("m/d/Y", strtotime($aditionaldata['FullExecutedDate'] . '+ ' . $aditionaldata['HOAApprovalPeriod'] . ' days'));
                        }
                        $array_return['LoanApplicationPeriod'] = $aditionaldata['LoanApplicationPeriod'];
                        if ($aditionaldata['LoanApplicationPeriod']) {
                            $array_return['LoanApplicationPeriodDate'] = date("m/d/Y", strtotime($aditionaldata['FullExecutedDate'] . '+ ' . $aditionaldata['LoanApplicationPeriod'] . ' days'));
                        }
                        /* checkbox */
                        if ($aditionaldata['SellerContributionOption']) {
                            $array_return['SellerContributionOption'] = $aditionaldata['SellerContributionOption'];
                        }
                        if ($aditionaldata['AbstractPaidOption']) {
                            $array_return['AbstractPaidOption'] = $aditionaldata['AbstractPaidOption'];
                        }
                        if ($aditionaldata['OriginalCondoOption']) {
                            $array_return['OriginalCondoOption'] = $aditionaldata['OriginalCondoOption'];
                        }
                        if ($aditionaldata['CommitmentIssuedPanel']) {
                            $array_return['CommitmentIssuedPanel'] = $aditionaldata['CommitmentIssuedPanel'];
                        }
                        /**/
                        /* inputs */
                        if ($aditionaldata['AmountSellerContribution']) {
                            $array_return['AmountSellerContribution'] = $aditionaldata['AmountSellerContribution'];
                        }
                        if ($aditionaldata['AmountAbstractPaid']) {
                            $array_return['AmountAbstractPaid'] = $aditionaldata['AmountAbstractPaid'];
                        }
                        if ($aditionaldata['ByOriginalCondo']) {
                            $array_return['ByOriginalCondo'] = $aditionaldata['ByOriginalCondo'];
                        }
                        if ($aditionaldata['CloserTransaction']) {
                            $array_return['CloserTransaction'] = $aditionaldata['CloserTransaction'];
                        }
                        if ($aditionaldata['ReceivedTransaction']) {
                            $array_return['ReceivedTransaction'] = $aditionaldata['ReceivedTransaction'];
                        }
                        if ($aditionaldata['PolicyNamePanel']) {
                            $array_return['PolicyNamePanel'] = $aditionaldata['PolicyNamePanel'];
                        }
                        if ($aditionaldata['CreateAtPanel']) {
                            $array_return['CreateAtPanel'] = $aditionaldata['CreateAtPanel'];
                        }
                        if ($aditionaldata['CreatedByPanel']) {
                            $array_return['CreatedByPanel'] = $aditionaldata['CreatedByPanel'];
                        }
                        if ($aditionaldata['OwnerPolicyPanel']) {
                            $array_return['OwnerPolicyPanel'] = $aditionaldata['OwnerPolicyPanel'];
                        }
                        if ($aditionaldata['LoanPolicyPanel']) {
                            $array_return['LoanPolicyPanel'] = $aditionaldata['LoanPolicyPanel'];
                        }
                        /**/
                        /**/
                        if ($aditionaldata['check_WF_RECORDED_DOCS']) {
                            $array_return['check_WF_RECORDED_DOCS'] = $aditionaldata['check_WF_RECORDED_DOCS'];
                        }
                        if ($aditionaldata['check_HOLDING_TAX_ESCROW']) {
                            $array_return['check_HOLDING_TAX_ESCROW'] = $aditionaldata['check_HOLDING_TAX_ESCROW'];
                        }
                        if ($aditionaldata['check_WF_SAT_OF_MTG']) {
                            $array_return['check_WF_SAT_OF_MTG'] = $aditionaldata['check_WF_SAT_OF_MTG'];
                        }
                        if ($aditionaldata['check_HOLDING_GENERAL_ESCROW']) {
                            $array_return['check_HOLDING_GENERAL_ESCROW'] = $aditionaldata['check_HOLDING_GENERAL_ESCROW'];
                        }
                        if ($aditionaldata['check_WF_RELEASE_OF_LIEN']) {
                            $array_return['check_WF_RELEASE_OF_LIEN'] = $aditionaldata['check_WF_RELEASE_OF_LIEN'];
                        }
                        if ($aditionaldata['check_WATER_ESCROW']) {
                            $array_return['check_WATER_ESCROW'] = $aditionaldata['check_WATER_ESCROW'];
                        }
                        if ($aditionaldata['check_WF_FINAL_POLICY']) {
                            $array_return['check_WF_FINAL_POLICY'] = $aditionaldata['check_WF_FINAL_POLICY'];
                        }
                        if ($aditionaldata['LockPanelDate']) {
                            $array_return['LockPanelDate'] = $aditionaldata['LockPanelDate'];
                        }
                        if ($aditionaldata['LockExpirationPanelDate']) {
                            $array_return['LockExpirationPanelDate'] = $aditionaldata['LockExpirationPanelDate'];
                        }
                        /**/
                    }
                }
                /**/
                $aditionaldataCla = $transaction->getclasification();
                if ($aditionaldataCla) {
                    $aditionaldataCla = json_decode($aditionaldataCla, true);
                    if ($aditionaldataCla) {
                        if ($aditionaldataCla['ListingPrice']) {
                            $array_return['ListingPrice'] = $aditionaldataCla['ListingPrice'];
                        }
                        if ($aditionaldataCla['MLSNumber']) {
                            $array_return['MLSNumber'] = $aditionaldataCla['MLSNumber'];
                        }
                        foreach ($aditionaldataCla as $keyadd => $valadd) {
                            $array_return[$keyadd] = $valadd;
                        }
                    }
                }
                /**/
                /* underwriter */
                $contact_obj = $GetClass->GetClass('contact'); //echo var_dump(get_class_methods(transaction_contact));
                $rolelist_obj = $GetClass->GetClass('rolelist');
                $underwri = '';
                $AllRoles = $rolelist_obj->getAllrolelistForcolumnValue('name', "'underwriter'");
                $underwriterlist = '';
                $selected = '';
                foreach ($AllRoles as $Role) {
                    $Underwriters = $contact_obj->getAllcontactForColumnValue('idrolelist', $Role->getidrolelist());
                    foreach ($Underwriters as $Underwriter) {
                        foreach ($transaction_contact as $t_c) {
                            if ($t_c->getidrole() == $Role->getidrolelist()) {
                                if ($t_c->getidcontact() == $Underwriter->getidcontact()) {
                                    $selected = $t_c->getidcontact();
                                }
                            }
                        }
                    }
                }
                $array_return['UnderWriterTransaction'] = $selected;
                $array_return['ProcessorTransaction'] = $transaction->getidprocessor();
                /**/
                /* notification tasks */
                if ($transaction->getidrequirementslist()) {
                    $reqlsit = $requeriment_list_obj->getrequeriment_listById($transaction->getidrequirementslist());
                }
                $NotificaionesSystemm = array();
                if ($reqlsit) {
                    $tasks = $task_obj->getAlltaskForColumnValue('idtransaction', "'" . $id_transaction . "'");
                    $arrayTasks = array();
                    if ($tasks) {
                        foreach ($tasks as $taskt) {
                            $arrayTasks[$taskt->getsubject()] = $taskt;
                        }
                    }
                    if ($reqlsit->getnamer() != 'Model1') {
                        $reqlist = json_decode($reqlsit->getrequerimentsjson(), true);
                        $subjectreq = '';
                        $diasreq = '';
                        if ($reqlist) {
                            foreach ($reqlist as $k => $v) {
                                $temp = explode('_', $k);
                                if ($temp[1] == 'Name') {
                                    $subjectreq = $v;
                                    $diasreq = $reqlist[str_replace('Name', 'days', $k)];
                                    if ($reqlist[str_replace('Name', 'notify', $k)]) {
                                        if ($reqlist[str_replace('Name', 'executed', $k)] && $array_return['FullExecutedDate']) {
                                            $DateClosing = new DateTime($array_return['FullExecutedDate']);
                                        } else {
                                            $DateClosing = new DateTime($transaction->getclosingdate());
                                        }
                                        $DateClosing = new DateTime($transaction->getclosingdate());
                                        $DateNow = new DateTime(date('m/d/Y'));
                                        $diffCreationNowClosing = $DateNow->diff($DateClosing);
                                        if ($diffCreationNowClosing->invert != 1) {
                                            $daysforclosing = $diffCreationNowClosing->days;
                                            if ($daysforclosing <= $diasreq) {
                                                $completed = false;
                                                foreach ($tasks as $task) {
                                                    if ($task->getsubject() == $subjectreq) {
                                                        if ($task->getstatus() == 'Completed' || $task->getstatus() == 'Completed-Blue') {
                                                            $completed = true;
                                                        }
                                                    }
                                                }
                                                if (!$completed) {
                                                    $NotificaionesSystemm[$subjectreq] = 'You Need Complete for this Transaction the task : ' . $subjectreq . ', rest ' . $daysforclosing . ' days for Closng Date';
                                                }
                                            }
                                        }
                                    }
                                    /**/
                                    if (!$arrayTasks[$v]) {
                                        /* create task */
                                        $start_date = date('Y-m-d H:i:s');
                                        $task_obj->setsubject($v);
                                        $task_obj->updatelocation($task_obj->getidtask(), '');
                                        $task_obj->updatestart_date($task_obj->getidtask(), $start_date);
                                        //$task_obj->updateend_date($task_obj->getidtask(), $end_date);
                                        $task_obj->updatestatus($task_obj->getidtask(), 'Not Started');
                                        $task_obj->updateprogress_status($task_obj->getidtask(), 'Created');
                                        $task_obj->updateidtransaction($task_obj->getidtask(), $id_transaction);
                                        $task_obj->updateiduser($task_obj->getidtask(), $_SESSION['jigowatt']['user_id']);
                                        $task_obj->updatenote($task_obj->getidtask(), '');
                                        /**/
                                    }
                                    /**/
                                }
                            }
                        }
                    }
                    $datalifetime = '';
                    foreach ($tasks as $task) {
                        $formtask = '';
                        $isorder = false;
                        $formorder = '';
                        $orderDate = '';
                        if ($task->getoderdate()) {
                            $orderDate = date("m/d/Y", strtotime($task->getoderdate()));
                        }
                        $receivedDate = '';
                        if ($task->getreceiveddate()) {
                            $receivedDate = date("m/d/Y", strtotime($task->getreceiveddate()));
                        }
                        foreach ($reqlist as $k => $v) {
                            $temp = explode('_', $k);
                            if ($temp[1] == 'Name') {
                                if ($task->getsubject() == $v) {
                                    if ($reqlist[str_replace('Name', 'order', $k)] == '1') {
                                        $isorder = true;
                                        $formorder = '<div class="row">
                                                            <div class="col col-md-6"><label class="label">Order Date</label><label class="input"><input name="InputOrderDate" class="InputDateTask" value="' . $orderDate . '"></label></div>
                                                            <div class="col col-md-6"><label class="label">Receive Date</label><label class="input"><input name="InputReceiveDate" class="InputDateTask" value="' . $receivedDate . '"></label></div>
                                                          </div>';
                                    }
                                }
                            }
                        }
                        if ($task->getstatus() == 'Completed') {
                            $classline = 'success';
                            $formtask = '<label class="toggle state-success">
                                                    <input type="checkbox" name="checkboxSuccess" checked>
                                                    <i></i>Completed</label>
                                             <label class="toggle">
                                                    <input type="checkbox" name="checkboxNA">
                                                    <i></i>N/A</label>
                                             ' . $formorder . '
                                             <br><div style="text-align:right"><label class="btn btn-success btn-sm SaveForm" data-id="' . $task->getidtask() . '">Save</label><label class="btn btn-default btn-sm CancelSaveForm" data-id="' . $task->getidtask() . '">Cancel</label></div>';
                        } else {
                            if ($task->getstatus() == 'Completed-Blue') {
                                $classline = 'na';
                                $formtask = '<label class="toggle">
                                                    <input type="checkbox" name="checkboxSuccess" >
                                                    <i></i>Completed</label>
                                             <label class="toggle state-success">
                                                    <input type="checkbox" name="checkboxNA" checked>
                                                    <i></i>N/A</label>
                                             ' . $formorder . '
                                             <br><div style="text-align:right"><label class="btn btn-success btn-sm SaveForm" data-id="' . $task->getidtask() . '">Save</label><label class="btn btn-default btn-sm CancelSaveForm" data-id="' . $task->getidtask() . '">Cancel</label></div>';
                            } else {

                                if ($isorder) {
                                    $classline = 'warning';
                                } else {
                                    $classline = 'pending';
                                }
                                $formtask = '<label class="toggle">
                                                    <input type="checkbox" name="checkboxSuccess" >
                                                    <i></i>Completed</label>
                                             <label class="toggle ">
                                                    <input type="checkbox" name="checkboxNA" >
                                                    <i></i>N/A</label>
                                             ' . $formorder . '
                                             <br><div style="text-align:right"><label class="btn btn-success btn-sm SaveForm" data-id="' . $task->getidtask() . '">Save</label><label class="btn btn-default btn-sm CancelSaveForm" data-id="' . $task->getidtask() . '">Cancel</label></div>';
                            }
                        }
                        $datalifetime .= '<li class="" style="margin-top: 0.3% !important;"><a href="javascript:void(0);" data-id="' . $task->getidtask() . '" title="Update Task" data-toggle="popover" data-placement="top" data-content="' . str_replace('"', "'", "<form id='Updatetasklife' class='orb-form'>" . $formtask . "</form>") . '" data-container="body" class="popovered UpdateTaskLifeTime ' . $classline . '"><b>' . $task->getsubject() . '</b></a></li>';
                    }
                }
                $tasks = $task_obj->getAlltaskForColumnValue('idtransaction', "'" . $id_transaction . "'");
                /**/
                /* Deposits */
                if ($deposit) {
                    foreach ($deposit as $dep) {
                        $data = $dep->getdata();
                        if ($data) {
                            $data = json_decode($data, true);
                            if ($data['hudlineDeposit'] == 'L-01') {
                                $array_return['EscrowDeposit'] = str_replace('USD ', '$', money_format('%i', $dep->gettotal_amount()));
                            }
                        }
                    }
                }
                /**/
                /* policy */
                foreach ($alerts as $alert) {
                    if ($alert->gettype() == 'policy') {
                        $data = json_decode($alert->getdata(), true);
                        $array_return['PolicyNamePanel'] = $data['name'];
                        $array_return['CreateAtPanel'] = date('m/d/Y', strtotime($data['date']));
                        $array_return['CreatedByPanel'] = $data['nameUser'];
                        $array_return['OwnerPolicyPanel'] = $data['owner'];
                        $array_return['LoanPolicyPanel'] = $data['lender'];
                    }
                }
                /**/
                /* Data CD */
                if ($cdhud) {
                    $cdhud = $cdhud[0];
                    $cdhud_page3 = $cdhud_page3_obj->getAllcdhud_page3ForcolumnValue('idcdhud', $cdhud->getidcdhud());
                    if ($cdhud_page3) {
                        $cdhud_page3 = $cdhud_page3[0];
                        $HOA = $cdhud_page3->getK11();
                        if ($HOA) {
                            $HOA = json_decode($HOA, true);
                            $array_return['HoaPanel'] = $HOA['Taxline'];
                        }
                    }
                    $SalesPrice = $cdhud->getSalesPrice();
                    if ($SalesPrice) {
                        $SalesPrice = json_decode($SalesPrice, true);
                        $array_return['SalesAmountPanel'] = $SalesPrice['SalesPriceDialog'];
                    }
                    $LoanAmount = $cdhud->getLoanAmount();
                    if ($LoanAmount) {
                        $LoanAmount = json_decode($LoanAmount, true);
                        $array_return['LoanAmountPanel'] = $LoanAmount['LoanAmountDialog'];
                    }
                }
                /**/
                $array_resp['idtransaction'] = $array['idtransaction'];
                $array_resp['data'] = json_encode($array_return);
                if ($NotificaionesSystemm) {
                    $array_resp['notifications'] = json_encode($NotificaionesSystemm);
                }
                $array_resp['timelife'] = $datalifetime;
                echo json_encode($array_resp);
                //echo $array_resp;
            } else {
                $array_resp['idtransaction'] = $array['idtransaction'];
                $array_resp['data'] = 'Error : Not Found Transaction Data, please try again or contact with the admin';
                if ($NotificaionesSystemm) {
                    $array_resp['notifications'] = json_encode($NotificaionesSystemm);
                }
                $array_resp = json_encode($array_resp);
                echo $array_resp;
            }
            /**/
        }
    } else {
        echo 'Error : an Array has expected';
    }
}

function returnAcoordion($name, $content, $idnumber, $inputTextarea, $table, $campo) {
    $placeholder = ' placeholder=""';
    if (strpos($content, 'not have') !== false) {
        $placeholder = ' placeholder="' . $content . '"';
        $content = '';
    }
    if ($inputTextarea) {
        $inputTextarea = '<label class="input"><input style="color: black;font-size: 0.9rem;padding: 1%;height:25px;" data-tabla="' . $table . '" data-campo="' . $campo . '" data-id="' . $idnumber . '" name="PropertyId_' . $idnumber . '" class="SaveDataProperty form-control newinputname' . $idnumber . '" ' . $placeholder . ' value="' . $content . '"></label>';
        //$inputTextarea = '<input name="PropertyId_'.$idnumber.'" class="SaveDataProperty form-control newinputname'.$idnumber.'" '.$placeholder.' value="'.$content.'">';
    } else {
        $inputTextarea = '<label class="textarea"><textarea style="color: black;font-size: 0.9rem;padding: 1%;" data-tabla="' . $table . '" data-campo="' . $campo . '" data-id="' . $idnumber . '" class="SaveDataProperty form-control newinputname' . $idnumber . '" name="PropertyId_' . $idnumber . '" ' . $placeholder . '>' . $content . '</textarea></label>';
        //$inputTextarea = '<textarea class="SaveDataProperty form-control newinputname'.$idnumber.'" name="PropertyId_'.$idnumber.'" '.$placeholder.'>'.$content.'</textarea>';
    }
    //$button_save = '<label data-tabla="'.$table.'" data-campo="'.$campo.'" data-id="'.$idnumber.'" class="btn btn-primary save_pop3 SavePropertyinfo">SAVE</label>';
    return '<div class="col col-md-3"><div class="panel panel-primary">
                <div class="panel-heading color-theme">
                    <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion_property" href="#collapse_' . $idnumber . '"> ' . $name . ' </a> </h4>
                </div>
                <div id="collapse_' . $idnumber . '" class="panel-collapse collapse in">
                    <div class="panel-body color-theme-b" style="padding: 1%;"><div class="">' . $inputTextarea . '' . $button_save . '</div></div>
                </div>
            </div></div>';
}

// 06
function AlertsNumberTransaction($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        //if ($array['idtransaction']) {
        $alert_obj = $GetClass->GetClass('alert');
        $transaction_obj = $GetClass->GetClass('transaction');
        $task_obj = $GetClass->GetClass('task');
        $requeriment_list_obj = $GetClass->GetClass('requeriment_list');
        $ArrayReturn = array();
        $TransactionIds = explode(',', $array['ids']);
        foreach ($TransactionIds as $IdTransaction) {
            /* porcentage */
            /* $tasks = $task_obj->getAlltaskForColumnValue('idtransaction', $IdTransaction);
              $arrayTasks = array();
              foreach ($tasks as $task) {
              $arrayTasks[$task->getsubject()] = $task->getstatus();
              } */
            $transaction = $transaction_obj->gettransactionById($IdTransaction);
            $percentage = 0;
            /* if ($transaction->getidrequirementslist()) {
              $requeriment_list = $requeriment_list_obj->getrequeriment_listById($transaction->getidrequirementslist());
              if ($requeriment_list) {
              $requerimentsjson = json_decode($requeriment_list->getrequerimentsjson(), true);
              foreach ($requerimentsjson as $key => $value) {
              $name = explode('_', $key);
              if ($name[1] == 'Name') {
              $namePer = str_replace('Name', 'porcentaje', $key);
              if ($arrayTasks[$value] == 'Completed' || $arrayTasks[$value] == 'deleted' || $arrayTasks[$value] == 'NA') {
              $percentage = $percentage + $requerimentsjson[$namePer];
              }
              }
              }
              }
              } */
            /**/
            $alerts = $alert_obj->getAllalertForColumnValue('idtransaction', $IdTransaction);
            if ($alerts) {
                $countAlerts = 0;
                $countWarnings = 0;
                foreach ($alerts as $alert) {
                    if ($alert->gettype() == 'important') {
                        $countAlerts++;
                    }
                    if ($alert->gettype() == 'warning') {
                        $countWarnings++;
                    }
                }
                $ArrayReturn[$IdTransaction] = $countAlerts . '|' . $countWarnings . '|' . $percentage;
            } else {
                $ArrayReturn[$IdTransaction] = '0|0|' . $percentage;
            }
        }
        /**/
        echo json_encode($ArrayReturn);
        /* } else {
          echo "Error, First Select an Transaction";
          } */
    } else {
        echo "Error, An array expected";
    }
}

// 07
function GetPropertyInformation($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idtransaction']) {
            $transaction_obj = $GetClass->GetClass('transaction');
            $property_obj = $GetClass->GetClass('property');
            $_legal_description_obj = $GetClass->GetClass('_legal_description');
            $property_owner_obj = $GetClass->GetClass('property_owner');
            $_property_tax_obj = $GetClass->GetClass('_property_tax');
            $_dimensions_obj = $GetClass->GetClass('_dimensions');
            $ArrayReturn = array();
            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
            if ($transaction) {
                $idproperty = $transaction->getidproperty();
                $property = $property_obj->getpropertyById($idproperty);
                $_legal_description = $_legal_description_obj->getAll_legal_descriptionForColumnValue('idproperty', $idproperty);
                $property_owner = $property_owner_obj->getAllproperty_ownerForColumnValue('idproperty', $idproperty);
                $_property_tax = $_property_tax_obj->getAll_property_taxForColumnValue('idproperty', $idproperty);
                if ($property) {
                    $ArrayReturn['TaxIdPanel'] = $property->get_AssessorsParcelIdentifier() . '||' . $idproperty;
                    $ArrayReturn['AddressPanel'] = $property->get_StreetAddress() . '||' . $idproperty;
                    $ArrayReturn['Address2Panel'] = $property->get_StreetAddress2() . '||' . $idproperty;
                    $ArrayReturn['StatePanel'] = $property->get_State() . '||' . $idproperty;
                    $ArrayReturn['CountyPanel'] = $property->get_County() . '||' . $idproperty;
                    /* CountyLink */
                    if ($property->get_County()) {
                        $county_obj = $GetClass->GetClass('erec_county');
                        $countyList = $county_obj->getAllerec_countyForColumnValue('name', '"' . $property->get_County() . '"');
                        if ($countyList) {
                            $countyList = $countyList[0];
                            $county_data = json_decode($countyList->getdata(), true);
                            if ($county_data['url'] != '') {
                                if ($county_data[param] != '' && $county_data[param] != 'no') {
                                    $ArrayReturn['countyLink'] = $county_data['url'] . '?' . $county_data['param'] . '=' . str_replace(' ', '', $property->get_AssessorsParcelIdentifier());
                                } else {
                                    $ArrayReturn['countyLink'] = $county_data['url'];
                                }
                            }
                        }
                    }
                    /**/
                    $ArrayReturn['CityPanel'] = $property->get_City() . '||' . $idproperty;
                    $ArrayReturn['MunicipalityPanel'] = $property->get_Municipality() . '||' . $idproperty;
                    $ArrayReturn['ZipPanel'] = $property->get_PostalCode() . '||' . $idproperty;
                }
                if ($_legal_description) {
                    foreach ($_legal_description as $ld) {
                        $ArrayReturn['ShortLegalPanel'] = $ld->get_TextDescription() . '||' . $ld->getid_legal_description();
                        $ArrayReturn['FullLegalPanel'] = $ld->get_LegalAndVestingTextDescription() . '||' . $ld->getid_legal_description();
                    }
                } else {
                    $_legal_description_obj->setidproperty($idproperty);
                    $ArrayReturn['ShortLegalPanel'] = '||' . $_legal_description_obj->getid_legal_description();
                    $ArrayReturn['FullLegalPanel'] = '||' . $_legal_description_obj->getid_legal_description();
                }
                if ($_property_tax) {
                    foreach ($_property_tax as $pt) {
                        $ArrayReturn['TotalAssePanel'] = $pt->get_TotalAssessedValueAmount() . '||' . $pt->getid_property_tax();
                        $ArrayReturn['TaxYearPanel'] = $pt->get_TaxYear() . '||' . $pt->getid_property_tax();
                        $ArrayReturn['TotalTaxablePanel'] = $pt->get_TotalTaxableValueAmount() . '||' . $pt->getid_property_tax();
                        $ArrayReturn['RealStateTaxPanel'] = $pt->get_RealEstateTotalTaxAmount() . '||' . $pt->getid_property_tax();
                    }
                } else {
                    $_property_tax_obj->setidproperty($idproperty);
                    $ArrayReturn['TotalAssePanel'] = '||' . $_property_tax_obj->getid_property_tax();
                    $ArrayReturn['TaxYearPanel'] = '||' . $_property_tax_obj->getid_property_tax();
                    $ArrayReturn['TotalTaxablePanel'] = '||' . $_property_tax_obj->getid_property_tax();
                    $ArrayReturn['RealStateTaxPanel'] = '||' . $_property_tax_obj->getid_property_tax();
                }
                if ($property_owner) {
                    foreach ($property_owner as $po) {
                        $ArrayReturn['OwnerNamePanel'] = $po->get_OwnerName() . '||' . $po->getidproperty_owner();
                        $ArrayReturn['OwnerVestingPanel'] = $po->get_VestingName() . '||' . $po->getidproperty_owner();
                        $ArrayReturn['OwnerAddressPanel'] = $po->get_MailingAddress() . '||' . $po->getidproperty_owner();
                    }
                } else {
                    $property_owner_obj->setidproperty($idproperty);
                    $ArrayReturn['OwnerNamePanel'] = '||' . $property_owner_obj->getidproperty_owner();
                    $ArrayReturn['OwnerVestingPanel'] = '||' . $property_owner_obj->getidproperty_owner();
                    $ArrayReturn['OwnerAddressPanel'] = '||' . $property_owner_obj->getidproperty_owner();
                }
                echo json_encode($ArrayReturn);
            } else {
                echo 'Error : Transaction Not Found';
            }
            /**/
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 08
function SaveAutoSave($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        //print_r($array);
        $login_users_obj = $GetClass->GetClass('login_users');
        if ($array['idtransaction']) {
            /* CDhud */
            if ($array['Clase'] == 'cdhud') {
                $Class_obj = $GetClass->GetClass($array['Clase']);
                $ForId = $Class_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                if ($ForId) {
                    $ForIdSave = $ForId[0];
                    $DataJsonInf = $ForIdSave->getjsoninformation();
                    $ForId = $ForIdSave->getidcdhud();
                } else {
                    $Class_obj->setidtransaction($array['idtransaction']);
                    $ForId = $Class_obj->getidcdhud();
                }


                $array['Id'] = $ForId;
                if ($array['Campo'] == 'jsoninformation') {
                    if ($DataJsonInf) {
                        $DataJsonInf = json_decode($DataJsonInf, true);
                    } else {
                        $DataJsonInf = array();
                    }
                    $DataJsonInf[$array['Name']] = $array['value'];
                    $array['value'] = json_encode($DataJsonInf);
                }
                if ($array['Campo'] == 'LoanTerm') {
                    if ($ForIdSave) {
                        $ArrayData = $ForIdSave->getLoanTerm();
                    }
                    if ($ArrayData) {
                        $ArrayData = json_decode($ArrayData, true);
                    } else {
                        $ArrayData = array();
                    }
                    $ArrayData[$array['Name']] = $array['value'];
                    $array['value'] = json_encode($ArrayData);
                }
                if ($array['Campo'] == 'ProjectedPayments') {
                    if ($ForIdSave) {
                        $ArrayData = $ForIdSave->getProjectedPayments();
                    }
                    if ($ArrayData) {
                        $ArrayData = json_decode($ArrayData, true);
                    } else {
                        $ArrayData = array();
                    }
                    $ArrayData[$array['Name']] = $array['value'];
                    $array['value'] = json_encode($ArrayData);
                }
            }
            if ($array['Clase'] == 'cdhud_page2') {
                /**/
                $cdhud_obj = $GetClass->GetClass('cdhud');
                $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                if ($cdhud) {
                    $cdhud = $cdhud[0];
                    $idcdhud = $cdhud->getidcdhud();
                } else {
                    $cdhud_obj->setidtransaction($array['idtransaction']);
                    $idcdhud = $cdhud_obj->getidcdhud();
                }
                /**/
                $Class_obj = $GetClass->GetClass($array['Clase']);
                $ForId = $Class_obj->getAllcdhud_page2ForColumnValue('idcdhud', $idcdhud);
                if ($ForId) {
                    $ForId = $ForId[0];
                    /* $get = 'get'.$array['Campo'];
                      $arrayPage = $ForId->$get();
                      if($arrayPage){
                      $arrayPage = json_decode($arrayPage,true);
                      }else{
                      $arrayPage = array();
                      } */
                    $ForId = $ForId->getidcdhud_page2();
                    /* $arrayPage[$array['Name']] = $array['value'];
                      $array['value'] = json_encode($arrayPage); */
                } else {
                    $Class_obj->setidtransaction($array['idtransaction']);
                    $ForId = $Class_obj->getidcdhud_page2();
                    /* $arrayPage = array();
                      $arrayPage[$array['Name']] = $array['value'];
                      $array['value'] = json_encode($arrayPage); */
                }
                $array['Id'] = $ForId;
            }
            if ($array['Clase'] == 'cdhud_page3') {
                /**/
                $cdhud_obj = $GetClass->GetClass('cdhud');
                $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                if ($cdhud) {
                    $cdhud = $cdhud[0];
                    $idcdhud = $cdhud->getidcdhud();
                } else {
                    $cdhud_obj->setidtransaction($array['idtransaction']);
                    $idcdhud = $cdhud_obj->getidcdhud();
                }
                /**/
                $Class_obj = $GetClass->GetClass($array['Clase']);
                $ForId = $Class_obj->getAllcdhud_page3ForColumnValue('idcdhud', $idcdhud);
                if ($ForId) {
                    $ForIdSave = $ForId[0];
                    $ForId = $ForIdSave->getidcdhud_page3();
                } else {
                    $Class_obj->setidtransaction($array['idtransaction']);
                    $ForId = $Class_obj->getidcdhud_page3();
                }
                if ($array['Campo'] == 'DataCalculating') {
                    if ($ForIdSave) {
                        $ArrayData = $ForIdSave->getDataCalculating();
                    }
                    if ($ArrayData) {
                        $ArrayData = json_decode($ArrayData, true);
                    } else {
                        $ArrayData = array();
                    }
                    $ArrayData[$array['Name']] = $array['value'];
                    $array['value'] = json_encode($ArrayData);
                }
                $array['Id'] = $ForId;
            }
            if ($array['Clase'] == 'cdhud_page245') {
                /**/
                $cdhud_obj = $GetClass->GetClass('cdhud');
                $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                if ($cdhud) {
                    $cdhud = $cdhud[0];
                    $idcdhud = $cdhud->getidcdhud();
                } else {
                    $cdhud_obj->setidtransaction($array['idtransaction']);
                    $idcdhud = $cdhud_obj->getidcdhud();
                }
                /**/
                $Class_obj = $GetClass->GetClass($array['Clase']);
                $ForId = $Class_obj->getAllcdhud_page245ForcolumnValue('idcdhud', $idcdhud);
                if ($ForId) {
                    $ForId = $ForId[0];
                    $get = 'get' . $array['Campo'];
                    $arrayPage = $ForId->$get();
                    if ($arrayPage) {
                        $arrayPage = json_decode($arrayPage, true);
                    } else {
                        $arrayPage = array();
                    }
                    $ForId = $ForId->getidcdhud_page245();
                    $arrayPage[$array['Name']] = $array['value'];
                    $array['value'] = json_encode($arrayPage);
                } else {
                    $Class_obj->setidtransaction($array['idtransaction']);
                    $ForId = $Class_obj->getidcdhud_page245();
                    $arrayPage = array();
                    $arrayPage[$array['Name']] = $array['value'];
                    $array['value'] = json_encode($arrayPage);
                }
                $array['Id'] = $ForId;
            }

            /**/
            if ($array['Id']) {
                $Class_obj = $GetClass->GetClass($array['Clase']);
                $update = 'update' . $array['Campo'];
                $Class_obj->$update($array['Id'], $array['value']);
                echo 'Update Successfully ';
            } else {
                $Class_obj = $GetClass->GetClass($array['Clase']);
                if ($array['Campo'] == 'warning') {
                    $Transaction = $Class_obj->gettransactionById($array['idtransaction']);
                    //print_r($Transaction);
                    $ArrayData = $Transaction->getwarning();
                    if ($ArrayData) {
                        $ArrayData = json_decode($ArrayData, true);
                        if ($array['Name'] == 'PolicyNamePanel' || $array['Name'] == 'OwnerPolicyPanel' || $array['Name'] == 'LoanPolicyPanel') {
                            if (!$ArrayData['CreateAtPanel']) {
                                $ArrayData['CreateAtPanel'] = date('m/d/Y');
                                $login_users = $login_users_obj->getlogin_usersById($idlogin);
                                $ArrayData['CreatedByPanel'] = $login_users->getnameu();
                            }
                        }
                    } else {
                        $ArrayData = array();
                    }
                    $event_obj = $GetClass->GetClass('event');
                    $property_obj = $GetClass->GetClass('property');
                    $transaction_obj = $GetClass->GetClass('transaction');
                    if ($array['Name'] == 'FullExecutedDate') {
                        $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                        //print_r($EventList);
                        if ($EventList) {
                            foreach ($EventList as $event) {
                                if ($event->getsubject() == 'Full Executed Date') {
                                    $idEvent = $event->getidevent();
                                }
                            }
                        }
                        if ($idEvent) {
                            /* Update Event */
                            $event_obj->updatestart_date($idEvent, date('Y-m-d H:i:s', strtotime($array['value'])));
                            $event_obj->updateend_date($idEvent, date('Y-m-d H:i:s', strtotime($array['value'])));
                            $event_obj->updatesubject($idEvent, 'Full Executed Date');
                            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                            //print_r($transaction);
                            $property = $property_obj->getpropertyById($transaction->getidproperty());
                            $event_obj->updatelocation($idEvent, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                            /**/
                        } else {
                            /* Create Event */
                            $event_obj->setidtransaction($array['idtransaction']);
                            $idEvent1 = $event_obj->getidevent();
                            $event_obj->updatestart_date($idEvent1, date('Y-m-d H:i:s', strtotime($array['value'])));
                            $event_obj->updateend_date($idEvent1, date('Y-m-d H:i:s', strtotime($array['value'])));
                            $event_obj->updatesubject($idEvent1, 'Full Executed Date');
                            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                            $property = $property_obj->getpropertyById($transaction->getidproperty());
                            $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                            /**/
                        }
                        /**/
                        if ($ArrayData['InspectionPeriod']) {
                            $NewDate = strtotime('+' . $ArrayData['InspectionPeriod'] . ' day', strtotime($array['value']));
                            $NewDate = date('Y-m-d H:I:s', $NewDate);
                            $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                            if ($EventList) {
                                foreach ($EventList as $event) {
                                    if ($event->getsubject() == 'Inspection Period') {
                                        $idEvent1 = $event->getidevent();
                                    }
                                }
                            }
                            if (!$idEvent1) {
                                $event_obj->setidtransaction($array['idtransaction']);
                                $idEvent1 = $event_obj->getidevent();
                            }
                            $event_obj->updatestart_date($idEvent1, $NewDate);
                            $event_obj->updateend_date($idEvent1, $NewDate);
                            $event_obj->updatesubject($idEvent1, 'Inspection Period');
                            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                            $property = $property_obj->getpropertyById($transaction->getidproperty());
                            $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                            $idEvent1 = '';
                        }
                        if ($ArrayData['LoanApprovalPeriod']) {
                            $NewDate = strtotime('+' . $ArrayData['LoanApprovalPeriod'] . ' day', strtotime($array['value']));
                            $NewDate = date('Y-m-d H:I:s', $NewDate);
                            $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                            if ($EventList) {
                                foreach ($EventList as $event) {
                                    if ($event->getsubject() == 'Loan Approval Period') {
                                        $idEvent1 = $event->getidevent();
                                    }
                                }
                            }
                            if (!$idEvent1) {
                                $event_obj->setidtransaction($array['idtransaction']);
                                $idEvent1 = $event_obj->getidevent();
                            }
                            $event_obj->updatestart_date($idEvent1, $NewDate);
                            $event_obj->updateend_date($idEvent1, $NewDate);
                            $event_obj->updatesubject($idEvent1, 'Loan Approval Period');
                            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                            $property = $property_obj->getpropertyById($transaction->getidproperty());
                            $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        }
                        if ($ArrayData['AppraisalContingency']) {
                            $NewDate = strtotime('+' . $ArrayData['AppraisalContingency'] . ' day', strtotime($array['value']));
                            $NewDate = date('Y-m-d H:I:s', $NewDate);
                            $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                            if ($EventList) {
                                foreach ($EventList as $event) {
                                    if ($event->getsubject() == 'Appraisal Contingency') {
                                        $idEvent1 = $event->getidevent();
                                    }
                                }
                            }
                            if (!$idEvent1) {
                                $event_obj->setidtransaction($array['idtransaction']);
                                $idEvent1 = $event_obj->getidevent();
                            }
                            $event_obj->updatestart_date($idEvent1, $NewDate);
                            $event_obj->updateend_date($idEvent1, $NewDate);
                            $event_obj->updatesubject($idEvent1, 'Appraisal Contingency');
                            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                            $property = $property_obj->getpropertyById($transaction->getidproperty());
                            $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        }

                        if ($ArrayData['HOAApplicationPeriod']) {
                            $NewDate = strtotime('+' . $ArrayData['HOAApplicationPeriod'] . ' day', strtotime($array['value']));
                            $NewDate = date('Y-m-d H:I:s', $NewDate);
                            $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                            if ($EventList) {
                                foreach ($EventList as $event) {
                                    if ($event->getsubject() == 'HOA/CONDO APPLICATION PERIOD') {
                                        $idEvent1 = $event->getidevent();
                                    }
                                }
                            }
                            if (!$idEvent1) {
                                $event_obj->setidtransaction($array['idtransaction']);
                                $idEvent1 = $event_obj->getidevent();
                            }
                            $event_obj->updatestart_date($idEvent1, $NewDate);
                            $event_obj->updateend_date($idEvent1, $NewDate);
                            $event_obj->updatesubject($idEvent1, 'HOA/CONDO APPLICATION PERIOD');
                            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                            $property = $property_obj->getpropertyById($transaction->getidproperty());
                            $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        }
                        if ($ArrayData['HOAApprovalPeriod']) {
                            $NewDate = strtotime('+' . $ArrayData['HOAApprovalPeriod'] . ' day', strtotime($array['value']));
                            $NewDate = date('Y-m-d H:I:s', $NewDate);
                            $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                            if ($EventList) {
                                foreach ($EventList as $event) {
                                    if ($event->getsubject() == 'HOA/CONDO APPROVAL PERIOD') {
                                        $idEvent1 = $event->getidevent();
                                    }
                                }
                            }
                            if (!$idEvent1) {
                                $event_obj->setidtransaction($array['idtransaction']);
                                $idEvent1 = $event_obj->getidevent();
                            }
                            $event_obj->updatestart_date($idEvent1, $NewDate);
                            $event_obj->updateend_date($idEvent1, $NewDate);
                            $event_obj->updatesubject($idEvent1, 'HOA/CONDO APPROVAL PERIOD');
                            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                            $property = $property_obj->getpropertyById($transaction->getidproperty());
                            $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        }
                        if ($ArrayData['LoanApplicationPeriod']) {
                            $NewDate = strtotime('+' . $ArrayData['LoanApplicationPeriod'] . ' day', strtotime($array['value']));
                            $NewDate = date('Y-m-d H:I:s', $NewDate);
                            $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                            if ($EventList) {
                                foreach ($EventList as $event) {
                                    if ($event->getsubject() == 'LOAN APPLICATION PERIOD') {
                                        $idEvent1 = $event->getidevent();
                                    }
                                }
                            }
                            if (!$idEvent1) {
                                $event_obj->setidtransaction($array['idtransaction']);
                                $idEvent1 = $event_obj->getidevent();
                            }
                            $event_obj->updatestart_date($idEvent1, $NewDate);
                            $event_obj->updateend_date($idEvent1, $NewDate);
                            $event_obj->updatesubject($idEvent1, 'LOAN APPLICATION PERIOD');
                            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                            $property = $property_obj->getpropertyById($transaction->getidproperty());
                            $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        }
                        /**/
                        //print_r($idEvent);
                        //Generateics();
                    }
                    if ($array['Name'] == 'InspectionPeriod' && $ArrayData['FullExecutedDate']) {
                        $NewDate = strtotime('+' . $array['value'] . ' day', strtotime($ArrayData['FullExecutedDate']));
                        $NewDate = date('Y-m-d H:I:s', $NewDate);
                        $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                        if ($EventList) {
                            foreach ($EventList as $event) {
                                if ($event->getsubject() == 'Inspection Period') {
                                    $idEvent1 = $event->getidevent();
                                }
                            }
                        }
                        if (!$idEvent1) {
                            $event_obj->setidtransaction($array['idtransaction']);
                            $idEvent1 = $event_obj->getidevent();
                        }
                        $event_obj->updatestart_date($idEvent1, $NewDate);
                        $event_obj->updateend_date($idEvent1, $NewDate);
                        $event_obj->updatesubject($idEvent1, 'Inspection Period');
                        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                        $property = $property_obj->getpropertyById($transaction->getidproperty());
                        $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        //Generateics();
                    }
                    if ($array['Name'] == 'LoanApprovalPeriod' && $ArrayData['FullExecutedDate']) {
                        $NewDate = strtotime('+' . $array['value'] . ' day', strtotime($ArrayData['FullExecutedDate']));
                        $NewDate = date('Y-m-d H:I:s', $NewDate);
                        $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                        if ($EventList) {
                            foreach ($EventList as $event) {
                                if ($event->getsubject() == 'Loan Approval Period') {
                                    $idEvent1 = $event->getidevent();
                                }
                            }
                        }
                        if (!$idEvent1) {
                            $event_obj->setidtransaction($array['idtransaction']);
                            $idEvent1 = $event_obj->getidevent();
                        }
                        $event_obj->updatestart_date($idEvent1, $NewDate);
                        $event_obj->updateend_date($idEvent1, $NewDate);
                        $event_obj->updatesubject($idEvent1, 'Loan Approval Period');
                        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                        $property = $property_obj->getpropertyById($transaction->getidproperty());
                        $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        //Generateics();
                    }
                    if ($array['Name'] == 'AppraisalContingency' && $ArrayData['FullExecutedDate']) {
                        $NewDate = strtotime('+' . $array['value'] . ' day', strtotime($ArrayData['FullExecutedDate']));
                        $NewDate = date('Y-m-d H:I:s', $NewDate);
                        $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                        if ($EventList) {
                            foreach ($EventList as $event) {
                                if ($event->getsubject() == 'Appraisal Contingency') {
                                    $idEvent1 = $event->getidevent();
                                }
                            }
                        }
                        if (!$idEvent1) {
                            $event_obj->setidtransaction($array['idtransaction']);
                            $idEvent1 = $event_obj->getidevent();
                        }
                        $event_obj->updatestart_date($idEvent1, $NewDate);
                        $event_obj->updateend_date($idEvent1, $NewDate);
                        $event_obj->updatesubject($idEvent1, 'Appraisal Contingency');
                        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                        $property = $property_obj->getpropertyById($transaction->getidproperty());
                        $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        //Generateics();
                    }

                    if ($array['Name'] == 'HOAApplicationPeriod' && $ArrayData['FullExecutedDate']) {
                        $NewDate = strtotime('+' . $array['value'] . ' day', strtotime($ArrayData['FullExecutedDate']));
                        $NewDate = date('Y-m-d H:I:s', $NewDate);
                        $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                        if ($EventList) {
                            foreach ($EventList as $event) {
                                if ($event->getsubject() == 'HOA/CONDO APPLICATION PERIOD') {
                                    $idEvent1 = $event->getidevent();
                                }
                            }
                        }
                        if (!$idEvent1) {
                            $event_obj->setidtransaction($array['idtransaction']);
                            $idEvent1 = $event_obj->getidevent();
                        }
                        $event_obj->updatestart_date($idEvent1, $NewDate);
                        $event_obj->updateend_date($idEvent1, $NewDate);
                        $event_obj->updatesubject($idEvent1, 'HOA/CONDO APPLICATION PERIOD');
                        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                        $property = $property_obj->getpropertyById($transaction->getidproperty());
                        $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        //Generateics();
                    }
                    if ($array['Name'] == 'HOAApprovalPeriod' && $ArrayData['FullExecutedDate']) {
                        $NewDate = strtotime('+' . $array['value'] . ' day', strtotime($ArrayData['FullExecutedDate']));
                        $NewDate = date('Y-m-d H:I:s', $NewDate);
                        $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                        if ($EventList) {
                            foreach ($EventList as $event) {
                                if ($event->getsubject() == 'HOA/CONDO APPROVAL PERIOD') {
                                    $idEvent1 = $event->getidevent();
                                }
                            }
                        }
                        if (!$idEvent1) {
                            $event_obj->setidtransaction($array['idtransaction']);
                            $idEvent1 = $event_obj->getidevent();
                        }
                        $event_obj->updatestart_date($idEvent1, $NewDate);
                        $event_obj->updateend_date($idEvent1, $NewDate);
                        $event_obj->updatesubject($idEvent1, 'HOA/CONDO APPROVAL PERIOD');
                        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                        $property = $property_obj->getpropertyById($transaction->getidproperty());
                        $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        //Generateics();
                    }
                    if ($array['Name'] == 'LoanApplicationPeriod' && $ArrayData['FullExecutedDate']) {
                        $NewDate = strtotime('+' . $array['value'] . ' day', strtotime($ArrayData['FullExecutedDate']));
                        $NewDate = date('Y-m-d H:I:s', $NewDate);
                        $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                        if ($EventList) {
                            foreach ($EventList as $event) {
                                if ($event->getsubject() == 'LOAN APPLICATION PERIOD') {
                                    $idEvent1 = $event->getidevent();
                                }
                            }
                        }
                        if (!$idEvent1) {
                            $event_obj->setidtransaction($array['idtransaction']);
                            $idEvent1 = $event_obj->getidevent();
                        }
                        $event_obj->updatestart_date($idEvent1, $NewDate);
                        $event_obj->updateend_date($idEvent1, $NewDate);
                        $event_obj->updatesubject($idEvent1, 'LOAN APPLICATION PERIOD');
                        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                        $property = $property_obj->getpropertyById($transaction->getidproperty());
                        $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                        Generateics();
                    }


                    $ArrayData[$array['Name']] = $array['value'];
                    ///print_r($ArrayData);
                    $Class_obj->updatewarning($array['idtransaction'], json_encode($ArrayData));
                    echo 'Update Successfully';
                } else {
                    if ($array['Name'] == 'TransactionName' || $array['Name'] == 'TransactionNumber') {
                        if ($array['Name'] == 'TransactionName') {
                            $Transactions = $Class_obj->getAlltransactionForColumnValue('name', '"' . trim($array['value']) . '"');
                            if ($Transactions) {
                                die('Error : Transaction Name Already Exist, Please try with other Transaction Name');
                            }
                        }
                        if ($array['Name'] == 'TransactionNumber') {
                            $Transactions = $Class_obj->getAlltransactionForColumnValue('transactionnumber', '"' . trim($array['value']) . '"');
                            if ($Transactions) {
                                die('Error : External Number Already Exist, Please try with other External Number');
                            }
                        }
                        $update = 'update' . $array['Campo'];
                        $Class_obj->$update($array['idtransaction'], $array['value']);
                        /* Update QB */
                        /* New QB */
                        include_once 'Server/QBHelper.php';

                        $QBHelper_obj = new QBHelper();
                        if ($QBHelper_obj->quickbooks_is_connected) {
                            /**/
                            //
                            $TransactionU = $Class_obj->gettransactionById($array['idtransaction']);
                            $TName = $TransactionU->getname();
                            $TExternal = $TransactionU->gettransactionnumber();
                            //
                            $objqbr = $GetClass->GetClass('qb_request');
                            $idlogin = $_SESSION['jigowatt']['user_id'];
                            $objqbr->setoperation('update account, Script: ' . basename(__FILE__) . ', Func.:' . __FUNCTION__);
                            $objqbr->updaterequest($objqbr->getidqb_request(), $theData);
                            $objqbr->updatestatus($objqbr->getidqb_request(), 'Update');
                            $objqbr->updateidlogin($objqbr->getidqb_request(), $idlogin);
                            $objqbr->updateidoperation($objqbr->getidqb_request(), '08');
                            $transactionDesc = $TName . '_' . $TExternal;
                            $transactionDesc = (strlen($transactionDesc) <= 100) ? $transactionDesc : substr($transactionDesc, 0, 100);
                            $transactionDesc = str_replace(array('"', ':'), '', $transactionDesc);
                            $transactionName = $QBHelper_obj->db . '_' . str_replace(array('"', ':'), '', $TExternal);
                            $params = array(
                                'Name' => $transactionName,
                                'Description' => $transactionDesc,
                            );
                            //

                            $idQ = $TransactionU->getidqaccount();
                            if (!$idQ) {
                                $response = $QBHelper_obj->AccountQB('Create', $params);
                                if ($Result = json_decode($response, true)) {
                                    if ($Result['Code'] == 200) {
                                        $objqbr->updateresponse($objqbr->getidqb_request(), $Result['Msj']);
                                        $objqbr->updatestatus($objqbr->getidqb_request(), 'checked');
                                        $Class_obj->updateidqaccount($array['idtransaction'], $Result['Msj']);
                                    } else {
                                        $str_info = 'Error to create new Account Transaction <br>';
                                        $obj_lu_m = $GetClass->GetClass('login_users');
                                        $obj_lu_m = $obj_lu_m->getlogin_usersById($_SESSION['jigowatt']['user_id']);
                                        $lu_email = '';
                                        if (is_object($obj_lu_m)) {
                                            $lu_email = $obj_lu_m->getemail();
                                        }
                                        $_email = explode('@', $lu_email);
                                        $rep_email = (is_array($_email) && count($_email) > 1) ? $_email[1] : '';
                                        $str_info .= 'Domain: ' . $rep_email . ' <br>';
                                        $str_info .= 'Email user logged: ' . $lu_email . ' <br>';
                                        $str_info .= 'Transaction: ' . $TName . ' <br>';
                                        $str_info .= 'Transaction ID: ' . $array['idtransaction'] . ' <br>';
                                        sendGeneralEmail('Error QB request ID: ' . $objqbr->getidqb_request(), '', 'notification', "$str_info $response");

                                        $response = ($response) ? $response : 'empty';
                                        $objqbr->updateresponse($objqbr->getidqb_request(), "Response: $response");
                                        $objqbr->updatestatus($objqbr->getidqb_request(), 'failed');
                                    }
                                } else {
                                    $str_info = 'Error to create new Account Transaction <br>';
                                    $obj_lu_m = $GetClass->GetClass('login_users');
                                    $obj_lu_m = $obj_lu_m->getlogin_usersById($_SESSION['jigowatt']['user_id']);
                                    $lu_email = '';
                                    if (is_object($obj_lu_m)) {
                                        $lu_email = $obj_lu_m->getemail();
                                    }
                                    $_email = explode('@', $lu_email);
                                    $rep_email = (is_array($_email) && count($_email) > 1) ? $_email[1] : '';
                                    $str_info .= 'Domain: ' . $rep_email . ' <br>';
                                    $str_info .= 'Email user logged: ' . $lu_email . ' <br>';
                                    $str_info .= 'Transaction: ' . $TName . ' <br>';
                                    $str_info .= 'Transaction ID: ' . $array['idtransaction'] . ' <br>';
                                    sendGeneralEmail('Error QB request ID: ' . $objqbr->getidqb_request(), '', 'notification', "$str_info $response");

                                    $response = ($response) ? $response : 'empty';
                                    $objqbr->updateresponse($objqbr->getidqb_request(), "Response: $response");
                                    $objqbr->updatestatus($objqbr->getidqb_request(), 'failed');
                                }
                            } else {
                                $params = array(
                                    'Name' => $transactionName,
                                    'Description' => $transactionDesc,
                                    'IdQ' => $idQ,
                                );
                                $response = $QBHelper_obj->AccountQB('Update', $params);
                                if ($Result = json_decode($response, true)) {
                                    if ($Result['Code'] == 200) {
                                        $objqbr->updateresponse($objqbr->getidqb_request(), $Result['Msj']);
                                        $objqbr->updatestatus($objqbr->getidqb_request(), 'checked');
                                    } else {
                                        $str_info = 'Error to create new Account Transaction <br>';
                                        $obj_lu_m = $GetClass->GetClass('login_users');
                                        $obj_lu_m = $obj_lu_m->getlogin_usersById($_SESSION['jigowatt']['user_id']);
                                        $lu_email = '';
                                        if (is_object($obj_lu_m)) {
                                            $lu_email = $obj_lu_m->getemail();
                                        }
                                        $_email = explode('@', $lu_email);
                                        $rep_email = (is_array($_email) && count($_email) > 1) ? $_email[1] : '';
                                        $str_info .= 'Domain: ' . $rep_email . ' <br>';
                                        $str_info .= 'Email user logged: ' . $lu_email . ' <br>';
                                        $str_info .= 'Transaction: ' . $TName . ' <br>';
                                        $str_info .= 'Transaction ID: ' . $array['idtransaction'] . ' <br>';
                                        sendGeneralEmail('Error QB request ID: ' . $objqbr->getidqb_request(), '', 'notification', "$str_info $response");

                                        $response = ($response) ? $response : 'empty';
                                        $objqbr->updateresponse($objqbr->getidqb_request(), "Response: $response");
                                        $objqbr->updatestatus($objqbr->getidqb_request(), 'failed');
                                    }
                                } else {
                                    $str_info = 'Error to create new Account Transaction <br>';
                                    $obj_lu_m = $GetClass->GetClass('login_users');
                                    $obj_lu_m = $obj_lu_m->getlogin_usersById($_SESSION['jigowatt']['user_id']);
                                    $lu_email = '';
                                    if (is_object($obj_lu_m)) {
                                        $lu_email = $obj_lu_m->getemail();
                                    }
                                    $_email = explode('@', $lu_email);
                                    $rep_email = (is_array($_email) && count($_email) > 1) ? $_email[1] : '';
                                    $str_info .= 'Domain: ' . $rep_email . ' <br>';
                                    $str_info .= 'Email user logged: ' . $lu_email . ' <br>';
                                    $str_info .= 'Transaction: ' . $TName . ' <br>';
                                    $str_info .= 'Transaction ID: ' . $array['idtransaction'] . ' <br>';
                                    sendGeneralEmail('Error QB request ID: ' . $objqbr->getidqb_request(), '', 'notification', "$str_info $response");

                                    $response = ($response) ? $response : 'empty';
                                    $objqbr->updateresponse($objqbr->getidqb_request(), "Response: $response");
                                    $objqbr->updatestatus($objqbr->getidqb_request(), 'failed');
                                }
                            }
                            /**/
                        }
                        /**/
                        /**/
                        echo 'Update Successfully';
                    } else {
                        if ($array['Name'] == 'ClosingDatePanel' || $array['Name'] == 'ClosingDate' || $array['Name'] == 'FullExecutedDate') {
                            $update = 'update' . $array['Campo'];
                            $Class_obj->$update($array['idtransaction'], date('Y-m-d', strtotime($array['value'])));
                            $event_obj = $GetClass->GetClass('event');
                            $property_obj = $GetClass->GetClass('property');
                            $transaction_obj = $GetClass->GetClass('transaction');
                            if ($array['Name'] == 'ClosingDatePanel' || $array['Name'] == 'ClosingDate') {
                                $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                                if ($EventList) {
                                    foreach ($EventList as $event) {
                                        if ($event->getsubject() == 'Closing Date') {
                                            $idEvent = $event->getidevent();
                                        }
                                    }
                                }
                                if ($idEvent) {
                                    /* Create Event */
                                    $event_obj->updatestart_date($idEvent, date('Y-m-d H:i:s', strtotime($array['value'])));
                                    $event_obj->updateend_date($idEvent, date('Y-m-d H:i:s', strtotime($array['value'])));
                                    $event_obj->updatesubject($idEvent, 'Closing Date');
                                    $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                                    $property = $property_obj->getpropertyById($transaction->getidproperty());
                                    $event_obj->updatelocation($idEvent, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                                    /**/
                                } else {
                                    /* Create Event */
                                    $event_obj->setidtransaction($array['idtransaction']);
                                    $idEvent1 = $event_obj->getidevent();
                                    $event_obj->updatestart_date($idEvent1, date('Y-m-d H:i:s', strtotime($array['value'])));
                                    $event_obj->updateend_date($idEvent1, date('Y-m-d H:i:s', strtotime($array['value'])));
                                    $event_obj->updatesubject($idEvent1, 'Closing Date');
                                    $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                                    $property = $property_obj->getpropertyById($transaction->getidproperty());
                                    $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                                    /**/
                                }
                                Generateics();
                            }
                            if ($array['Name'] == 'FullExecutedDate') {
                                $EventList = $event_obj->getAlleventForColumnValue('idtransaction', $array['idtransaction']);
                                if ($EventList) {
                                    foreach ($EventList as $event) {
                                        if ($event->getsubject() == 'Full Executed Date') {
                                            $idEvent = $event->getidevent();
                                        }
                                    }
                                }
                                if ($idEvent) {
                                    /* Create Event */
                                    $event_obj->updatestart_date($idEvent, date('Y-m-d H:i:s', strtotime($array['value'])));
                                    $event_obj->updateend_date($idEvent, date('Y-m-d H:i:s', strtotime($array['value'])));
                                    $event_obj->updatesubject($idEvent, 'Full Executed Date');
                                    $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                                    $property = $property_obj->getpropertyById($transaction->getidproperty());
                                    $event_obj->updatelocation($idEvent, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                                    /**/
                                } else {
                                    /* Create Event */
                                    $event_obj->setidtransaction($array['idtransaction']);
                                    $idEvent1 = $event_obj->getidevent();
                                    $event_obj->updatestart_date($idEvent1, date('Y-m-d H:i:s', strtotime($array['value'])));
                                    $event_obj->updateend_date($idEvent1, date('Y-m-d H:i:s', strtotime($array['value'])));
                                    $event_obj->updatesubject($idEvent1, 'Full Executed Date');
                                    $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                                    $property = $property_obj->getpropertyById($transaction->getidproperty());
                                    $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                                    /**/
                                }
                                Generateics();
                            }
                            echo 'Update Successfully';
                        } else {
                            if ($array['Name'] == 'ShortLegalPanel') {
                                $transaction_obj = $GetClass->GetClass('transaction');
                                $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                                if ($transaction) {
                                    $idproperty = $transaction->getidproperty();
                                    $newtarget = $Class_obj->getAll_legal_descriptionForColumnValue('idproperty', '"' . $idproperty . '"');
                                    $update = 'update' . $array['Campo'];
                                    if ($newtarget) {
                                        $newtarget = $newtarget[0];
                                        $Class_obj->$update($newtarget->getid_legal_description(), $array['value']);
                                    } else {
                                        $Class_obj->setidproperty($idproperty);
                                        $Class_obj->$update($Class_obj->getid_legal_description(), $array['value']);
                                    }
                                } else {
                                    echo 'Error : Transaction Not found';
                                }
                                echo 'Update Successfully';
                            } else {
                                if ($array['Campo'] == 'Clasification') {
                                    /**/
                                    $Transaction = $Class_obj->gettransactionById($array['idtransaction']);
                                    $aditionaldataCla = $Transaction->getclasification();
                                    if ($aditionaldataCla) {
                                        $aditionaldataCla = json_decode($aditionaldataCla, true);
                                        if (!$aditionaldataCla) {
                                            $aditionaldataCla = array();
                                        }
                                        $aditionaldataCla[$array['Name']] = $array['value'];
                                    }
                                    $Class_obj->updateclasification($array['idtransaction'], json_encode($aditionaldataCla));
                                    echo 'Update Successfully';
                                    /**/
                                } else {
                                    $update = 'update' . $array['Campo'];
                                    $Class_obj->$update($array['idtransaction'], $array['value']);
                                    echo 'Update Successfully';
                                }
                            }
                        }
                    }
                }
            }
            /* $transaction_obj = GetClass('transaction',$dbname);
              $property_obj = GetClass('property',$dbname); */

            /**/
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

function cleanname($name) {
    $retunrname = explode('.', $name);
    $typecount = strlen($retunrname[count($retunrname) - 1]) + 1;
    $justname = substr($name, 0, -$typecount);
    $newName = preg_replace('/[^a-zA-z0-9\/-]+/', '', $justname);
    $newName = str_replace(array('-', '_', ' ', '.', ',', '/'), '', $newName);
    return array('Name' => $newName, 'Type' => $retunrname[count($retunrname) - 1]);
}

// 09
function UploadDocument($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $m = GetClass('dbname');
        $dbname = $m->getdbname();
        if ($array['idtransaction']) {
            $file = $GetClass->GetClass('file');
            $path = $array['url'];
            $pathtemp = 'Server/' . $path; //echo $pathtemp;
            $fp = fopen($pathtemp, "r") or die("can't open File");
            $contenido = fread($fp, filesize($pathtemp));
            fclose($fp);
            $namefile = str_replace('temp/' . $dbname, '', $path);
            $namefile = cleanname($namefile);
            $file->setname($namefile['Name']);
            $file->updatetype($file->getidfile(), $namefile['Type']);
            $file->updatecontent($file->getidfile(), $contenido);
            $file->updateidsection($file->getidfile(), $array['section_file']);
            $file->updateidtransaction($file->getidfile(), $array['idtransaction']);
            echo $file->getidfile();
            unlink('Server/' . $path);
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 10
function ChangeFileName($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idupdatename']) {
            $file_obj = $GetClass->GetClass('file');
            $file_obj->updatename($array['idupdatename'], $array['NewName']);
            echo 'ok';
        } else {
            echo "Error, First Select a File";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 11 
function PreviewDoc($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idfile']) {
            $file_obj = $GetClass->GetClass('file');
            $contact_obj = $GetClass->GetClass('contact');
            $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
            $file = $file_obj->getfileById($array['idfile']);
            //print_r($file);
            if ($file) {
                if (strpos('.', $file->gettype()) === false) {
                    $path = 'temp/' . $file->getname() . '.' . $file->gettype();
                } else {
                    $path = 'temp/' . $file->getname() . $file->gettype();
                }
                $path = str_replace('..pdf', '.pdf', $path);
                $fp = fopen($path, 'w') or die('no');
                fwrite($fp, $file->getcontent());
                fclose($fp);
                $ArrayReturn = array();
                $npages = 0;
                $resp = shell_exec('pdftk ' . $path . ' dump_data | grep NumberOfPages');
                /* $resp = shell_exec('pdftk --v 2>&1');
                  print_r($resp); */
                //print_r($path);
                $resp = str_replace('NumberOfPages: ', '', $resp);
                if ($resp != '') {
                    $npages = intval($resp);
                }
                /* partieList */
                $PartieList = array();
                if ($file->getidtransaction()) {
                    $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $file->getidtransaction());
                    if ($transaction_contact) {
                        foreach ($transaction_contact as $t_c) {
                            if ($t_c->getidcontact()) {
                                $contact = $contact_obj->getcontactById($t_c->getidcontact());
                                if ($contact) {
                                    if ($contact->getemail()) {
                                        $PartieList[$contact->getname()] = $contact->getemail();
                                    }
                                }
                            }
                        }
                    }
                }
                /**/
                if ($PartieList) {
                    $ArrayReturn['parties'] = json_encode($PartieList);
                }
                $ArrayReturn['id'] = $array['idsec'];
                $ArrayReturn['num_p'] = $npages;
                $ArrayReturn['idfile'] = $array['idfile'];
                $ArrayReturn['path'] = $path;
                echo json_encode($ArrayReturn);
            } else {
                echo "Error, File not found";
            }
        } else {
            echo "Error, First Select a File";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 12
function SendMailPanel($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idfile']) {
            $file_obj = $GetClass->GetClass($array['class']);
            $get = 'get' . $array['class'] . 'ById';
            $file = $file_obj->$get($array['idfile']);
            if ($file) {
                /* File */
                if (strpos($file->gettype(), '.') === false) {
                    $NameFile = str_replace(',', '', $file->getname() . '.' . $file->gettype());
                    $NameFileFinal = str_replace(',', '', $file->getname() . 'Send.' . $file->gettype());
                } else {
                    $NameFile = $file->getname() . $file->gettype();
                    $NameFileFinal = $file->getname() . 'Send.' . $file->gettype();
                }
                $fh = fopen('temp/' . $NameFile, 'w') or die('no scribe');
                fwrite($fh, $file->getcontent());
                fclose($fh);
                /**/
                $pages = trim($array['EmailPages']);
                $pages = str_replace(' ', '', $pages);
                $pages = ',' . $pages;
                $pages = str_replace(',', ' A', $pages);
                exec("/usr/bin/pdftk A=temp/$NameFile cat $pages output temp/$NameFileFinal");
                if (!file_exists('temp/' . $NameFileFinal)) {
                    die('Error : File Result Not Found');
                }
                if ($array['EmailEncrypt']) {
                    $filename = 'temp/DocumentSended' . '_' . date("Ymd") . date("His") . 'Attch.zip';
                    exec('zip -P ' . $array['EmailPassword'] . ' -j ' . trim($filename) . ' /var/www/html/mrt/temp/' . trim($NameFileFinal));
                    //echo senderGeneralEmail($array['EmailSubject'], $array['EmailFrom'], $array['EmailTo'], $array['body'], $filename, $array['EmailCC'], $array['EmailBCC']);
                    echo sendGeneralEmail($array['EmailSubject'], $array['EmailFrom'], $array['EmailTo'], $array['body'], $filename, $array['EmailCC'], $array['EmailBCC']);
                } else {
                    //echo senderGeneralEmail($array['EmailSubject'], $array['EmailFrom'], $array['EmailTo'], $array['body'], 'temp/' . $NameFileFinal, $array['EmailCC'], $array['EmailBCC']);
                    echo sendGeneralEmail($array['EmailSubject'], $array['EmailFrom'], $array['EmailTo'], $array['body'], 'temp/' . $NameFileFinal, $array['EmailCC'], $array['EmailBCC']); //.-''.$NameFileFinal;
                }
            } else {
                echo "Error, File not found";
            }
        } else {
            echo "Error, First Select a File";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 13
function PreviewDivide($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idfile']) {
            $file_obj = $GetClass->GetClass($array['classe']);
            $file = $file_obj->getfileById($array['idfile']);
            if ($file) {
                if (strpos('.', $file->gettype()) === false) {
                    $path = 'temp/' . $file->getname() . '.' . $file->gettype();
                } else {
                    $path = 'temp/' . $file->getname() . $file->gettype();
                }
                $path = str_replace('..pdf', '.pdf', $path);
                $fp = fopen($path, 'w') or die('no');
                fwrite($fp, $file->getcontent());
                fclose($fp);
                $ArrayReturn = array();
                $npages = 0;
                $pages = $array[pages];
                $pages = ',' . $pages;
                $pages = str_replace(',', ' A', $pages);
                $name = str_replace('temp/', '', $path);
                if ($array['rotate']) {
                    $pages = $pages . $array['rotate'];
                }
                if (strpos('.', $file->gettype()) === false) {
                    $filename = $file->getname() . 'Rotate.' . $file->gettype();
                } else {
                    $filename = $file->getname() . 'Rotate' . $file->gettype();
                }
                unlink('temp/' . $filename);
                //print_r("/usr/bin/pdftk A=$path cat $pages output temp/$filename");
                exec("/usr/bin/pdftk A=$path cat $pages output temp/$filename");
                echo 'temp/' . $filename;
            } else {
                echo "Error, File not found";
            }
        } else {
            echo "Error, First Select a File";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 14
function SendMailDivide($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idfile']) {
            $file_obj = $GetClass->GetClass($array['class']);
            $file = $file_obj->getfileById($array['idfile']);
            if ($file) {
                if (strpos('.', $file->gettype()) === false) {
                    $path = str_replace(',', '', 'temp/' . $file->getname() . '.' . $file->gettype());
                } else {
                    $path = str_replace(',', '', 'temp/' . $file->getname() . $file->gettype());
                }
                $path = str_replace('..pdf', '.pdf', $path);
                $fp = fopen($path, 'w') or die('no');
                fwrite($fp, $file->getcontent());
                fclose($fp);
                $ArrayReturn = array();
                if (is_array($array['check_party_email'])) {
                    foreach ($array['check_party_email'] as $key => $value) {
                        if ($value == '1') {
                            $to = $array['TargetToParty'][$key];
                        } else {
                            $to = $array['DivideTo'][$key];
                        }
                        $body = $array['Bodydivide'][$key];
                        $subject = $array['SectionSubject'][$key];

                        $npages = 0;
                        $pages = $array['PagesDivide'][$key];
                        $pages = ',' . $pages;
                        $pages = str_replace(',', ' A', $pages);
                        $name = str_replace('temp/', '', $path);
                        if ($array['rotate_' . ($key + 1)]) {
                            $pages = $pages . $array['rotate_' . ($key + 1)];
                        }
                        if (strpos('.', $file->gettype()) === false) {
                            $filename = str_replace(',', '', $file->getname() . $key . 'Rotate.' . $file->gettype());
                        } else {
                            $filename = str_replace(',', '', $file->getname() . $key . 'Rotate' . $file->gettype());
                        }
                        unlink('temp/' . $filename);

                        exec("/usr/bin/pdftk A=$path cat $pages output temp/$filename");

                        //echo senderGeneralEmail($subject, '', $to, $body, 'temp/' . $filename) . ' ';
                        echo sendGeneralEmail($subject, '', $to, $body, 'temp/' . $filename);
                        /* Save */
                        if ($array['to_section']) {
                            $path_pdf = 'temp/' . $filename;
                            $fp = fopen($path_pdf, "r") or die("can't open File 2");
                            $contenido = fread($fp, filesize($path_pdf));
                            fclose($fp);
                            $file_obj->setcontent($contenido);
                            $file_obj->updateidtransaction($file_obj->getidfile(), $array['idtransaction']);
                            $file_obj->updateidsection($file_obj->getidfile(), $array['selected_section']);
                            $file_obj->updatename($file_obj->getidfile(), $file->getname() . $key . 'Rotate');
                            $file_obj->updatetype($file_obj->getidfile(), $file->gettype());
                        }
                        /**/
                    }
                } else {
                    if ($array['check_party_email'] == '1') {
                        $to = $array['TargetToParty'];
                    } else {
                        $to = $array['DivideTo'];
                    }
                    $body = $array['Bodydivide'];
                    $subject = $array['SectionSubject'];

                    $npages = 0;
                    $pages = $array['PagesDivide'];
                    $pages = ',' . $pages;
                    $pages = str_replace(',', ' A', $pages);
                    $name = str_replace('temp/', '', $path);
                    if ($array['rotate_1']) {
                        $pages = $pages . $array['rotate_1'];
                    }
                    if (strpos('.', $file->gettype()) === false) {
                        $filename = str_replace(',', '', $file->getname() . 'Rotate.' . $file->gettype());
                    } else {
                        $filename = str_replace(',', '', $file->getname() . 'Rotate' . $file->gettype());
                    }
                    unlink('temp/' . $filename);

                    exec("/usr/bin/pdftk A=$path cat $pages output temp/$filename");

                    //echo senderGeneralEmail($subject, '', $to, $body, 'temp/' . $filename) . ' ';
                    echo sendGeneralEmail($subject, '', $to, $body, 'temp/' . $filename);
                    /* Save */
                    if ($array['to_section']) {
                        $path_pdf = 'temp/' . $filename;
                        $fp = fopen($path_pdf, "r") or die("can't open File 2");
                        $contenido = fread($fp, filesize($path_pdf));
                        fclose($fp);
                        $file_obj->setcontent($contenido);
                        $file_obj->updateidtransaction($file_obj->getidfile(), $array['idtransaction']);
                        $file_obj->updateidsection($file_obj->getidfile(), $array['selected_section']);
                        $file_obj->updatename($file_obj->getidfile(), $file->getname() . 'Rotate');
                        $file_obj->updatetype($file_obj->getidfile(), $file->gettype());
                    }
                }
            } else {
                echo "Error, File not found";
            }
        } else {
            echo "Error, First Select a File";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 15
function deleteFile($theData) {
    /* error_reporting(E_ALL);
      ini_set('display_errors', 1); */
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);

    if (is_array($array)) {//var_dump($array);
        $file_obj = $GetClass->GetClass('file');
        if (is_object($file_obj)) {
            $file = $file_obj->getfileById($array['idfile']);
            if (is_object($file)) {
                /**/
                $livesigning_obj = $GetClass->GetClass('livesigning');
                $livesigning = $livesigning_obj->getAlllivesigningForColumnValue('idfile', $array['idfile']);
                if ($livesigning) {
                    foreach ($livesigning as $l_obj) {
                        $livesigning_obj->deletelivesigning($l_obj->getidlivesigning());
                    }
                }
                /**/

                $file_obj->deletefile($array['idfile']);
                $idfile = $array['idfile'];
                echo "The File #ID: $idfile has been successfully removed";
            } else {
                $idfile = $array['idfile'];
                echo "Error, The File #ID: $idfile no exists";
            }
        }
    } else {
        echo "Error, An array expected";
    }
}

// 16
function changesection($theData) {
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    if (is_array($array)) {
        if ($array['idsectionchange'] != 'no') {
            $file_obj = $GetClass->GetClass('file');
            $file = $file_obj->getfileById($array['idfile']);
            if ($file) {
                $file_obj->updateidsection($array['idfile'], $array['idsectionchange']);
                echo 'Update section is OK';
            } else {
                echo 'Error : file not found';
            }
        } else {
            echo 'Error : first select a section';
        }
    }
}

// 17
function TaskAjax($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //print_r($array); idupdate getrequerimentsjson
    if (!isset($_SESSION)) {
        session_start();
    }
    if (is_array($array)) {
        $task_obj = $GetClass->GetClass('task');
        $login_user_obj = $GetClass->GetClass('login_users');
        $transaction_obj = $GetClass->GetClass('transaction');
        $requeriment_list_obj = $GetClass->GetClass('requeriment_list');
        $general_config_obj = $GetClass->GetClass('general_config');
        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
        if ($transaction) {
            $Alltask = $task_obj->getAlltaskForColumnValue('idtransaction', $array['idtransaction']);
            /**/
            $general_config = $general_config_obj->getgeneral_configById(1);
            $officeinfo = json_decode($general_config->getechosign(), true);
            if ($officeinfo['TaskType'] == 'NoModel' || true) {
                if (!$Alltask) {
                    $requeriment_list = $requeriment_list_obj->getrequeriment_listById($transaction->getidrequirementslist());
                    if ($requeriment_list) {
                        $reqlist = json_decode($requeriment_list->getrequerimentsjson(), true);
                        if ($reqlist) {
                            $array_key = array();
                            foreach ($reqlist as $k => $v) {
                                $temp = explode('_', $k);
                                if ($temp[1] == 'Name' && !in_array($k, $array_key)) {
                                    array_push($array_key, $k);
                                    $start_date = date('Y-m-d H:i:s');
                                    $fecha = new DateTime(now);
                                    $anio = 0;
                                    $mes = 0;
                                    $dia = $reqlist[str_replace('Name', 'days', $k)];
                                    $hora = $reqlist[str_replace('Name', 'hour', $k)];
                                    $minuto = $reqlist[str_replace('Name', 'minute', $k)];
                                    $order = $reqlist[str_replace('Name', 'order', $k)];
                                    $percent = $reqlist[str_replace('Name', 'porcentaje', $k)];
                                    $segundo = 0;
                                    $arraylist = array();
                                    if($order){
                                        $arraylist['o'] = 'yes';
                                    }
                                    $arraylist['p'] = $percent;
                                    $task = $task_obj;
                                    $task->setsubject($reqlist[$k]);
                                    $task->updatelocation($task->getidtask(), '');
                                    $task->updatestart_date($task->getidtask(), $start_date);
                                    //$task->updateend_date($task->getidtask(), $end_date);
                                    $task->updatestatus($task->getidtask(), 'Not Started');
                                    $task->updateprogress_status($task->getidtask(), 'Created');
                                    $task->updateidtransaction($task->getidtask(), $array['idtransaction']);
                                    $task->updateiduser($task->getidtask(), $_SESSION['jigowatt']['user_id']);
                                    $task->updatenote($task->getidtask(), '');
                                    $task->updatetask_list($task->getidtask(), json_encode($arraylist));
                                }
                            }
                            $Alltask = $task_obj->getAlltaskForColumnValue('idtransaction', $array['idtransaction']);
                        }
                    }
                }
                $return = '';
                //$return .= '<ol class="dd-list">';
                $Flag = true;
                foreach ($Alltask as $task) {
                    $Color = 'BackGroundFree';
                    $checkedCm = '';
                    $checkedNa = '';
                    $ClassCm = '';
                    $ClassNa = '';
                    //status : Completed In Progress Not Started deleted Completed-Blue
                    //comp=green, na=blue, order=violet, 
                    switch ($task->getstatus()) {
                        case 'Completed':
                            $Color = 'BackGroundGreen';
                            $checkedCm = ' checked="checked"';
                            $ClassCm = ' state-success';
                            break;
                        case 'In Progress':
                            $Color = 'BackGroundFree';
                            break;
                        case 'Not Started':
                            $Color = 'BackGroundRed';
                            $Color = 'BackGroundFree';//change for other cases
                            break;
                        case 'Completed-Blue':
                            $Color = 'BackGroundBlue';
                            $checkedNa = ' checked="checked"';
                            $ClassNa = ' state-success';
                            break;
                        default:$Color = 'BackGroundFree';
                            break;
                    }
                    if ($task->getstatus() != 'deleted') {
                        if ($Flag) {
                            $return .= '<div class="row" id="">';
                            $Flag = false;
                        } else {
                            $Flag = true;
                        }
                        $datatask = json_decode($task->gettask_list(), true);
                        if (!$datatask) {
                            $datatask = array();
                        }
                        $orders = '';
                        if ($datatask['o'] == 'yes') {
                            if ($Color != 'BackGroundGreen' && $Color != 'BackGroundBlue') {
                                $Color = 'BackGroundOrange';
                            }
                            $DateOrd = '';
                            $DateRec = '';
                            if($task->getoderdate()){
                                $DateOrd = date('m/d/Y', strtotime($task->getoderdate()));
                            }
                            if($task->getreceiveddate()){
                                $DateRec = date('m/d/Y', strtotime($task->getreceiveddate()));
                            }
                            $orders = '<div class="row"><div class="col col-md-6"><input data-id="'.$task->getidtask().'" placeholder="Order Date" name="Ordered_'.$task->getidtask().'" value="'.$DateOrd.'" class="FechT form-control"></div>';
                            $orders .= '<div class="col col-md-6"><input data-id="'.$task->getidtask().'" placeholder="Received Date" name="Received_'.$task->getidtask().'" value="'.$DateRec.'" class="FechT form-control"></div></div>';
                        }
                        $return .= '
                                        <div class="col-md-6 col-sm-12 bootstrap-grid" style="padding:0.5%;">
                                            <div class="powerwidget powerwidget-as-portlet powerwidget-as-portlet-cold-grey BorderTop" id="" data-widget-editbutton="false">
                                                <header class="' . $Color . ' BorderTop"><h2 style="text-transform:uppercase"><strong>' . $task->getsubject() . '</strong></h2></header>
                                                <div class="inner-spacer nopadding ' . $Color . '">
                                                    <ul class="portlet-bottom-block">
                                                        <li class="col-md-4 ' . $Color . ' orb-form">
                                                            <label class="toggle ' . $ClassCm . '">
                                                                <input type="checkbox" class="ClickCmp" data-id="'.$task->getidtask().'" name="CheckComp" ' . $checkedCm . '>
                                                                <i></i>Completed
                                                            </label>
                                                            <label class="toggle ' . $ClassNa . '">
                                                                <input type="checkbox" class="ClickNa" data-id="'.$task->getidtask().'" name="CheckNA" ' . $checkedNa . '>
                                                                <i></i>N/A
                                                            </label>'.$orders.'
                                                        </li>
                                                        <li class="col-md-8 ' . $Color . ' orb-form">
                                                            <div class="row">
                                                                <div class="col col-md-12">
                                                                    <div class="input-group">
                                                                        <input id="appendedtext" name="appendedtext_'.$task->getidtask().'" class="form-control" placeholder="Write a Note" type="text">
                                                                        <span class="input-group-addon"><l class="btn btn-success SaveNoteTask btn-xs" data-id="'.$task->getidtask().'" style="width:100%">SEND</l></span> 
                                                                     </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col col-md-12">
                                                                    <div style="color:black!important;text-align: left;height:50px;overflow: auto;padding: 1%;background: whitesmoke;border: 1px solid grey;">'.$task->getnote().'</div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                        if ($Flag) {
                            $return .= '</div>';
                        }
                    }
                }
                if (!$Flag) {
                    $return .= '</div>';
                }
                //$return .= '</ol>';
                //$return .= '';
                echo $return;
            } else {
                $requeriment_list = $requeriment_list_obj->getAllrequeriment_listForColumnValue('namer', '"Model1"');
                if ($requeriment_list) {
                    $requeriment_list = $requeriment_list[0];
                    $json = json_decode($requeriment_list->getrequerimentsjson(), true);
                    $order = json_decode($json['Order'], true);
                    //print_r($order);
                    $content = json_decode($json['Content'], true);
                    $content_return = insertLists($order, $content, $array['idtransaction']);
                    echo $content_return;
                } else {
                    echo 'Error : Client not have Model Process';
                }
            }
            /**/
        } else {
            echo 'Error : Transaction not Found';
        }
        exit();
    }
}

function insertLists($array, $data, $idtransaction, $TasksAvailables = array()) {
    $GetClass = GetClassPsToDb();

    $task_obj = $GetClass->GetClass('task');
    $tasks = $task_obj->getAlltaskForColumnValue('idtransaction', $idtransaction);
    $arrayTasks = array();
    foreach ($tasks as $task) {
        $arrayTasks[$task->getz_id()] = $task->getstatus();
    }
    $return = '';
    $return .= '<ol class="dd-list">';
    foreach ($array as $key => $value) {
        $contenReq = json_decode($data['Data' . $value['id']], true);
        $json_next = json_decode($contenReq['ChildItem'], true);
        foreach ($json_next as $jsonkey => $jsonvalue) {
            if ($jsonkey == $arrayTasks[$value['id']]) {
                $jsonids = explode(',', $jsonvalue);
                foreach ($jsonids as $ids) {
                    $TasksAvailables[] = $ids;
                }
            }
        }
        if (in_array($value['id'], $TasksAvailables)) {
            $help = '';
            if (trim($contenReq['HelpItem'])) {
                $help = '<i class="fa fa-question-circle GetInfoTask" style="margin-left: 5px;font-size: 1.5rem;" data-easein="bounceInDown" data-content="' . $contenReq['HelpItem'] . '"></i>';
            }
            $return .= '<li class="dd-item" data-id="' . $value['id'] . '">
                                            <div class="dd-handle task-handle"></div>
                                            <div class="task-content header">
                                                <a href="javascript:void(0);" data-id="' . $value['id'] . '" class="ExpandInfo">' . $contenReq['NameItem'] . ' ' . $help . '</a>
                                                <textarea style="display:none;" name="Data' . $value['id'] . '">' . $data['Data' . $value['id']] . '</textarea>
                                            </div>';
            if ($value['children']) {
                $return .= insertLists($value['children'], $data, $idtransaction, $TasksAvailables);
            }
            $return .= '</li>';
        } else {
            /* $return .= '<li class="dd-item" noav data-id="' . $value['id'] . '">
              <div class="dd-handle task-handle"></div>
              <div class="task-content header">
              <a href="javascript:void(0);" data-id="' . $value['id'] . '" class="ExpandInfo">' . $contenReq['NameItem'] . ' </a>
              <textarea style="display:none;" name="Data' . $value['id'] . '">' . $data['Data' . $value['id']] . '</textarea>
              </div>'; */
            /* if ($value['children']) {
              $return .= insertLists($value['children'], $data,$idtransaction);
              } */
            //$return .= '</li>';
        }
    }
    $return .= '</ol>';
    return $return;
}

// 18
function UpdateTaskAjax($theData) {
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $array = json_decode($theData, true);
    if (is_array($array)) {
        $task_obj = $GetClass->GetClass('task');
        if (!$array['updateTask']) {
            if ($array['z_id'] && $array['idtransaction']) {
                $task_obj->setz_id($array['z_id']);
                $array['updateTask'] = $task_obj->getidtask();
                $task_obj->updateidtransaction($array['updateTask'], $array['idtransaction']);
                $task_obj->updatelocation($array['updateTask'], $array['location']);
            }
        }
        if ($array['z_id']) {
            $array['update'] = $array['updateTask'];
            $task = $task_obj->gettaskById($array['update']);
            if ($task) {
                //$task_obj->updatelocation($array['update'], $array['location']);
                if($array['status']){
                    $task_obj->updatestatus($array['update'], $array['status']);
                }
                if ($array['status'] == 'Completed') {
                    if (!$array['startdate']) {
                        $array['startdate'] = date('m/d/Y');
                    }
                    if (!$array['enddate']) {
                        $array['enddate'] = date('m/d/Y');
                    }
                    if (strpos(strtolower($array['location']), 'order') !== false) {
                        if (!$array['orderdate']) {
                            $array['orderdate'] = date('m/d/Y');
                        }
                        if (!$array['receiveddate']) {
                            $array['receiveddate'] = date('m/d/Y');
                        }
                    }
                }
                /* if ($array['status'] == 'Not Started') {
                  $task_obj->updatestatus($array['update'], $array['status']);
                  $task_obj->updateprogress_status($array['update'], '0');
                  } else {
                  if ($array['status'] == 'Completed') {
                  $task_obj->updatestatus($array['update'], $array['status']);
                  $task_obj->updateprogress_status($array['update'], '100');
                  } else {
                  if ($array['status'] == 'NA') {
                  $task_obj->updatestatus($array['update'], $array['status']);
                  $task_obj->updateprogress_status($array['update'], '0');
                  } else {
                  $task_obj->updatestatus($array['update'], $array['status']);
                  if ($array['progress'] == '0') {
                  $task_obj->updateprogress_status($array['update'], '10');
                  } else {
                  $task_obj->updateprogress_status($array['update'], $array['progress']);
                  }
                  }
                  }
                  } */
                if ($array['startdate']) {
                    $start_date = date('Y-m-d', strtotime($array['startdate']));
                }
                if ($array['enddate']) {
                    $end_date = date('Y-m-d', strtotime($array['enddate']));
                }
                if ($array['orderdate']) {
                    $order_date = date('Y-m-d', strtotime($array['orderdate']));
                }
                if ($array['receiveddate']) {
                    $received_date = date('Y-m-d', strtotime($array['receiveddate']));
                }
                if (trim($array['namelogin']) != '' && trim($array['message']) != '') {
                    $dato = "<p><strong>By " . $array['namelogin'] . " : </strong></p>" . $array['message'] . "" . $task->getnote();
                } else {
                    $dato = $task->getnote();
                }
                if ($start_date) {
                    $task_obj->updatestart_date($array['update'], $start_date);
                }
                if ($end_date) {
                    $task_obj->updateend_date($array['update'], $end_date);
                }
                if ($order_date) {
                    $task_obj->updateoderdate($array['update'], $order_date);
                    if ($array['status'] == 'Not Started') {
                        $array['status'] = 'In Progress';
                        $array['progress'] = '10%';
                    }
                }
                if ($received_date) {
                    $task_obj->updatereceiveddate($array['update'], $received_date);
                    $task_obj->updatestatus($array['update'], 'Completed');
                    $task_obj->updateprogress_status($array['update'], '100');
                    $array['status'] = '';
                }
                $task_obj->updatenote($array['update'], $dato);
                echo $array['z_id'];
                exit();
                /**/
                if ($array['status'] == 'Completed') {
                    $tranSelected = $GetClass->GetClass('transaction');
                    $tranSelected = $tranSelected->gettransactionById($task->getidtransaction());
                    $idReqList = $tranSelected->getidrequirementslist();

                    $requer = $GetClass->GetClass('requeriment_list');
                    $rlist = $requer->getrequeriment_listById($idReqList);
                    $req = $rlist->getrequerimentsjson();
                    $json = json_decode($req, true);
                    $partie = '';
                    $textMessage = '';
                    foreach ($json as $key => $item) {
                        if ($item == $array['subject']) {
                            $indexReq = str_replace('net_Name_', '', $key);
                            if ($json['net_message_' . $indexReq] == '1') {
                                $textMessage = $json['net_smsmessage_' . $indexReq];
                                $partie = $json['net_smspartie_' . $indexReq];
                                break;
                            }
                        }
                    }

                    $idtransaction = $task->getidtransaction();
                    $traContact = $GetClass->GetClass('transaction_contact');
                    $listTraContact = $traContact->getAlltransaction_contactForColumnValue('idtransaction', $idtransaction);
                    $listParties = array();
                    $listParties = $listTraContact;

                    if ($partie != 'all') {
                        $listParties = array();
                        foreach ($listTraContact as $key => $tcont) {
                            if ($tcont->getside() == $partie) {
                                $listParties[] = $tcont;
                            }
                        }
                    }
                    /* error_reporting(E_ALL);
                      ini_set('display_errors', 1); */
                    if ($textMessage) {
                        foreach ($listParties as $key => $p) {
                            //send sms
                            $contact = $GetClass->GetClass('contact');
                            $contact = $p->getidcontact() ? $contact->getcontactById($p->getidcontact()) : false;
                            if (is_object($contact)) {
                                if ($contact->getmobile()) {
                                    $myt = $GetClass->GetClass('mytwilio');
                                    $myt = $myt->getmytwilioById('1');
                                    if (is_object($myt)) {

                                        $AccountSid = $myt->getaccountSid();
                                        $AuthToken = $myt->getauthToken();
                                        if (isset($array[from]) && $array[from] != '') {
                                            $from = $array[from];
                                        } else {
                                            $from = $myt->gettwilionumber();
                                        }

                                        $mobile = str_replace(array('(', ')', '-', ' '), '', $contact->getmobile());
                                        $mobile = strlen($mobile) == 10 ? '1' . $mobile : $mobile;

                                        $to = $mobile;
                                        $handle = curl_init();
                                        $url = "https://lookups.twilio.com/v1/PhoneNumbers/$mobile";
                                        curl_setopt($handle, CURLOPT_URL, $url);
                                        curl_setopt($handle, CURLOPT_POST, 0);
                                        curl_setopt($handle, CURLOPT_USERPWD, "$AccountSid:$AuthToken");
                                        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
                                        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
                                        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, FALSE);
                                        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 300);

                                        $result = curl_exec($handle);
                                        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                                        if ($httpCode == 200) {
                                            $client = new Services_Twilio($AccountSid, $AuthToken);
                                            $options = array(
                                                "StatusCallbackMethod" => "GET",
                                                "StatusCallback" => 'http://ts.titlehost.com/mrt/twilio-php-master/clients/twiml.php'
                                            );
                                            $message = $client->account->sms_messages->create(
                                                    $from, $to, $textMessage, $options);
                                            if (is_array($array) && $message->sid != '') {
                                                $msj = $GetClass->GetClass('phone_message');
                                                $msj->setmessage($textMessage);
                                                $msj->updateiduser($msj->getidphone_message(), $_SESSION['jigowatt']['user_id']);
                                                $msj->updateinbound_outbound($msj->getidphone_message(), 'outbound');
                                                $msj->updatedestination_phone($msj->getidphone_message(), $to);
                                                $msj->updatesource_phone($msj->getidphone_message(), $from);
                                                $msj->updatesid($msj->getidphone_message(), $message->sid);
                                                $msj->updateidtransaction($msj->getidphone_message(), $array['idtransaction']);
                                                $msj->updateidcontact($msj->getidphone_message(), $p->getidcontact());
                                                $msj->updatestatus($msj->getidphone_message(), 'sending');
                                                $msj->updatemyread($msj->getidphone_message(), 'unread');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                /**/
                //$task_obj->updatenote($task_obj->getidtask(), '');
            } else {
                echo 'Error : task not found';
            }
        } else {
            echo 'Error : first select a task';
        }
    }
}

// 19
function deletetask_panel($theData) {
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    if (is_array($array)) {
        $task = $GetClass->GetClass('task');
        $task->updatestatus($array['idtask'], 'deleted');
        echo $array['idtask'];
    }
}

// 20
function ReturnParties($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    if (is_array($array) && $array['idtransaction']) {
        $transaction_obj = $GetClass->GetClass('transaction');
        $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
        $contact_obj = $GetClass->GetClass('contact');
        $rolelist_obj = $GetClass->GetClass('rolelist');
        $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $array['idtransaction']);
        $ResponseContent = '';
        $ArrayReturn = array();
        $Buyercount = 1;
        $Sellercount = 1;
        if ($transaction_contact) {
            foreach ($transaction_contact as $t_c) {
                if ($t_c && $t_c->getidcontact() && $t_c->getidrole()) {
                    $party = $contact_obj->getcontactById($t_c->getidcontact());
                    $rolelist = $rolelist_obj->getrolelistById($t_c->getidrole());
                    /**/
                    if (strtolower($rolelist->getname()) == 'buyer') {
                        $Name = $party->getfirstname() . ' ' . $party->getsurname();
                        if (!trim($Name)) {
                            $Name = $party->getcompany();
                        }
                        $ArrayReturn['ForBuyer' . $Buyercount] = '<b>' . $Name . '</b>';
                        $Buyercount++;
                        if (!$ArrayReturn['ForBuyerAddress']) {
                            $Address = $party->getaddress1();
                            if ($party->getaddress2()) {
                                $Address .= ', ' . $party->getaddress1();
                            }
                            $Address .= ' ' . $party->getcity() . ', ' . $party->getstate() . ' - ' . $party->getzip();
                            if (trim(str_replace(array(',', '-'), '', $Address))) {
                                $ArrayReturn['ForBuyerAddress'] = '<b>' . $Address . '</b>';
                            }
                        }
                    }
                    if (strtolower($rolelist->getname()) == 'seller') {
                        $Name = $party->getfirstname() . ' ' . $party->getsurname();
                        if (!trim($Name)) {
                            $Name = $party->getcompany();
                        }
                        $ArrayReturn['ForSeller' . $Sellercount] = '<b>' . $Name . '</b>';
                        $Sellercount++;
                        if (!$ArrayReturn['ForSellerAddress']) {
                            $Address = $party->getaddress1();
                            if ($party->getaddress2()) {
                                $Address .= ', ' . $party->getaddress1();
                            }
                            $Address .= ' ' . $party->getcity() . ', ' . $party->getstate() . ' - ' . $party->getzip();
                            if (trim(str_replace(array(',', '-'), '', $Address))) {
                                $ArrayReturn['ForSellerAddress'] = '<b>' . $Address . '</b>';
                            }
                        }
                    }
                    if (strtolower($rolelist->getname()) == 'lender') {
                        $Name = $party->getfirstname() . ' ' . $party->getsurname();
                        $ArrayReturn['LenderContactb'] = json_encode(array('LenderContact' => $Name));
                        if (!trim($Name)) {
                            $Name = $party->getcompany();
                            $ArrayReturn['LenderNameb'] = json_encode(array('LenderName' => $party->getcompany()));
                            $ArrayReturn['LenderContactb'] = json_encode(array('LenderContact' => $party->getcompany()));
                        }
                        $ArrayReturn['ForLender'] = '<b>' . $Name . '</b>';
                        $lender_address = $party->getaddress1();
                        if ($party->getcity()) {
                            if (trim($lender_address) != '') {
                                $lender_address = $lender_address . ',' . $party->getcity();
                            } else {
                                $lender_address = $party->getcity();
                            }
                        }
                        if ($party->getstate()) {
                            if (trim($lender_address) != '') {
                                $lender_address = $lender_address . ',' . $party->getstate();
                            } else {
                                $lender_address = $party->getstate();
                            }
                        }
                        if ($party->getzip()) {
                            if (trim($lender_address) != '') {
                                $lender_address = $lender_address . ',' . $party->getzip();
                            } else {
                                $lender_address = $party->getzip();
                            }
                        }
                        $ArrayReturn['LenderAddressb'] = json_encode(array('LenderAddress' => $lender_address));
                        $ArrayReturn['LenderEmailb'] = json_encode(array('LenderEmail' => $party->getemail()));
                        $ArrayReturn['LenderPhoneb'] = json_encode(array('LenderPhone' => $party->getphone()));
                        $ArrayReturn['LenderSTLicenseIDb'] = json_encode(array('LenderSTLicenseID' => $party->getlicense()));
                    }
                    if (strtolower($rolelist->getname()) == 'mtgbroker') {
                        $Name = $party->getfirstname() . ' ' . $party->getsurname();
                        $ArrayReturn['MortgageBrokerContactb'] = json_encode(array('MortgageBrokerContact' => $Name));
                        if (!trim($Name)) {
                            $Name = $party->getcompany();
                            $ArrayReturn['MortgageBrokerNameb'] = json_encode(array('MortgageBrokerName' => $party->getcompany()));
                            $ArrayReturn['MortgageBrokerContactb'] = json_encode(array('MortgageBrokerContact' => $party->getcompany()));
                        }
                        $MortgageBroker_address = $party->getaddress1();
                        if ($party->getcity()) {
                            if (trim($MortgageBroker_address) != '') {
                                $MortgageBroker_address = $MortgageBroker_address . ',' . $party->getcity();
                            } else {
                                $MortgageBroker_address = $party->getcity();
                            }
                        }
                        if ($party->getstate()) {
                            if (trim($MortgageBroker_address) != '') {
                                $MortgageBroker_address = $MortgageBroker_address . ',' . $party->getstate();
                            } else {
                                $MortgageBroker_address = $party->getstate();
                            }
                        }
                        if ($party->getzip()) {
                            if (trim($MortgageBroker_address) != '') {
                                $MortgageBroker_address = $MortgageBroker_address . ',' . $party->getzip();
                            } else {
                                $MortgageBroker_address = $party->getzip();
                            }
                        }
                        $ArrayReturn['MortgageBrokerAddressb'] = json_encode(array('MortgageBrokerAddress' => $MortgageBroker_address));
                        $ArrayReturn['MortgageBrokerEmailb'] = json_encode(array('MortgageBrokerEmail' => $party->getemail()));
                        $ArrayReturn['MortgageBrokerPhoneb'] = json_encode(array('MortgageBrokerPhone' => $party->getphone()));
                        $ArrayReturn['MortgageBrokerSTLicenseIDb'] = json_encode(array('MortgageBrokerSTLicenseID' => $party->getlicense()));
                    }
                    if (strtolower($rolelist->getname()) == 'reagent' || strtolower($rolelist->getname()) == 'selling agent') {
                        if ($t_c->getside() == 'buyer' || strtolower($rolelist->getname()) == 'selling agent') {
                            $Name = $party->getfirstname() . ' ' . $party->getsurname();
                            $ArrayReturn['RealEstateBrokerBuyerContactb'] = json_encode(array('RealEstateBrokerBuyerContact' => $Name));
                            $ArrayReturn['RealEstateBrokerBuyerNameb'] = json_encode(array('RealEstateBrokerBuyerName' => $party->getcompany()));
                            if (!trim($Name)) {
                                $ArrayReturn['RealEstateBrokerBuyerContactb'] = json_encode(array('RealEstateBrokerBuyerContact' => $party->getcompany()));
                            }
                            $RealEstateBrokerBuyer_address = $party->getaddress1();
                            if ($party->getcity()) {
                                if (trim($RealEstateBrokerBuyer_address) != '') {
                                    $RealEstateBrokerBuyer_address = $RealEstateBrokerBuyer_address . ',' . $party->getcity();
                                } else {
                                    $RealEstateBrokerBuyer_address = $party->getcity();
                                }
                            }
                            if ($party->getstate()) {
                                if (trim($RealEstateBrokerBuyer_address) != '') {
                                    $RealEstateBrokerBuyer_address = $RealEstateBrokerBuyer_address . ',' . $party->getstate();
                                } else {
                                    $RealEstateBrokerBuyer_address = $party->getstate();
                                }
                            }
                            if ($party->getzip()) {
                                if (trim($RealEstateBrokerBuyer_address) != '') {
                                    $RealEstateBrokerBuyer_address = $RealEstateBrokerBuyer_address . ',' . $party->getzip();
                                } else {
                                    $RealEstateBrokerBuyer_address = $party->getzip();
                                }
                            }
                            $ArrayReturn['RealEstateBrokerBuyerAddressb'] = json_encode(array('RealEstateBrokerBuyerAddress' => $RealEstateBrokerBuyer_address));
                            $ArrayReturn['RealEstateBrokerBuyerEmailb'] = json_encode(array('RealEstateBrokerBuyerEmail' => $party->getemail()));
                            $ArrayReturn['RealEstateBrokerBuyerPhoneb'] = json_encode(array('RealEstateBrokerBuyerPhone' => $party->getphone()));
                            $ArrayReturn['RealEstateBrokerBuyerSTLicenseIDb'] = json_encode(array('RealEstateBrokerBuyerSTLicenseID' => $party->getlicense()));
                        }
                    }
                    if (strtolower($rolelist->getname()) == 'reagent' || strtolower($rolelist->getname()) == 'listing agent') {
                        if ($t_c->getside() == 'seller' || strtolower($rolelist->getname()) == 'listing agent') {
                            $Name = $party->getfirstname() . ' ' . $party->getsurname();
                            $ArrayReturn['RealEstateBrokerSellerContactb'] = json_encode(array('RealEstateBrokerSellerContact' => $Name));
                            $ArrayReturn['RealEstateBrokerSellerNameb'] = json_encode(array('RealEstateBrokerSellerName' => $party->getcompany()));
                            if (!trim($Name)) {
                                $ArrayReturn['RealEstateBrokerSellerContactb'] = json_encode(array('RealEstateBrokerSellerContact' => $party->getcompany()));
                            }
                            $RealEstateBrokerSeller_address = $party->getaddress1();
                            if ($party->getcity()) {
                                if (trim($RealEstateBrokerSeller_address) != '') {
                                    $RealEstateBrokerSeller_address = $RealEstateBrokerSeller_address . ',' . $party->getcity();
                                } else {
                                    $RealEstateBrokerSeller_address = $party->getcity();
                                }
                            }
                            if ($party->getstate()) {
                                if (trim($RealEstateBrokerSeller_address) != '') {
                                    $RealEstateBrokerSeller_address = $RealEstateBrokerSeller_address . ',' . $party->getstate();
                                } else {
                                    $RealEstateBrokerSeller_address = $party->getstate();
                                }
                            }
                            if ($party->getzip()) {
                                if (trim($RealEstateBrokerSeller_address) != '') {
                                    $RealEstateBrokerSeller_address = $RealEstateBrokerSeller_address . ',' . $party->getzip();
                                } else {
                                    $RealEstateBrokerSeller_address = $party->getzip();
                                }
                            }
                            $ArrayReturn['RealEstateBrokerSellerAddressb'] = json_encode(array('RealEstateBrokerSellerAddress' => $RealEstateBrokerSeller_address));
                            $ArrayReturn['RealEstateBrokerSellerEmailb'] = json_encode(array('RealEstateBrokerSellerEmail' => $party->getemail()));
                            $ArrayReturn['RealEstateBrokerSellerPhoneb'] = json_encode(array('RealEstateBrokerSellerPhone' => $party->getphone()));
                            $ArrayReturn['RealEstateBrokerSellerSTLicenseIDb'] = json_encode(array('RealEstateBrokerSellerSTLicenseID' => $party->getlicense()));
                        }
                    }
                    /**/
                    $lev = ($_SESSION['jigowatt']['user_level']);
                    if (count($lev) == 3 || count($lev) == 2) {
                        $div_close = '<a href="javascript:void(0);" title="Delete" class="DeleteParty" data-id="' . $t_c->getidtransaction_contact() . '" style="border-radius:3px;background:white;color:red;"><i class="fa fa-trash-o"></i></a>';
                    } else {
                        $div_close = '';
                    }
                    if ($party->getmiddlename()) {
                        $ShowName = $party->getfirstname() . ' ' . $party->getmiddlename() . ' ' . $party->getsurname();
                    } else {
                        $ShowName = $party->getfirstname() . ' ' . $party->getsurname();
                    }
                    $mobile = str_replace(array('(', ')', ' ', '-'), array('', '', '', ''), $party->getmobile());
                    if ($mobile != '') {
                        $mobile = '1' . $mobile;
                    }
                    $phone = str_replace(array('(', ')', ' ', '-'), array('', '', '', ''), $party->getphone());
                    if ($phone != '') {
                        $phone = '1' . $phone;
                    }
                    if (strtolower($rolelist->getdescription()) == 'buyer' || strtolower($rolelist->getdescription()) == 'seller' || strtolower($rolelist->getdescription()) == 'listing agent' || (strtolower($rolelist->getdescription()) == 'reagent' && $t_c->getside() == 'seller')) {
                        $rol = $rolelist->getdescription();
                        if ($rol == 'Buyer' || $rol == 'buyer') {
                            $rolQ = 'Buyer';
                        }
                        if ($rol == 'Seller' || $rol == 'seller') {
                            $rolQ = 'Seller';
                        }
                        if (($rol == 'Listing Agent') || ($t_c->getside() == 'seller' && $rol == 'reagent')) {
                            $rolQ = 'Seller Agent';
                        }
                        if (!$party->getiduser()) {
                            /* AgregarUserContact */
                            $login_users_obj = $GetClass->GetClass('login_users');
                            $username = trim($party->getfirstname()) . trim($party->getsurname());
                            if ($username == '') {
                                $username = trim($party->getcompany());
                            }
                            $last = ucfirst(strtolower(trim($party->getsurname())));
                            if ($last == '') {
                                $last = ucfirst(strtolower(trim($party->getcompany())));
                            }
                            $password = trim($last) . rand(10, 99);
                            $password_md5 = md5($password);
                            $username = str_replace(' ', '', $username);
                            $password = str_replace(' ', '', $password);
                            $username = strtolower($username);
                            $loginuser = $login_users_obj->getAlllogin_usersForColumnValue('username', '"' . $username . '"');
                            $createuser = true;
                            if (is_array($loginuser) && count($loginuser)) {
                                if (is_object($loginuser[0])) {
                                    $contact_obj->updateiduser($t_c->getidcontact(), $loginuser[0]->getuser_id());
                                    $createuser = false;
                                }
                            }
                            if ($createuser) {
                                $namename = trim($party->getfirstname()) . ' ' . $party->getsurname();
                                if (!trim($namename)) {
                                    $namename = trim($party->getcompany());
                                }
                                $login_users_obj->setusername($username);
                                $login_users_obj->updatefirstname($login_users_obj->getuser_id(), trim($party->getfirstname()));
                                $login_users_obj->updatelastname($login_users_obj->getuser_id(), $party->getsurname());
                                $login_users_obj->updatenameu($login_users_obj->getuser_id(), $namename);
                                $login_users_obj->updateemail2($login_users_obj->getuser_id(), $party->getemail());
                                $login_users_obj->updatephone($login_users_obj->getuser_id(), $party->getphone());
                                $login_users_obj->updatemobile($login_users_obj->getuser_id(), $party->getmobile());
                                $login_users_obj->updatepassreset($login_users_obj->getuser_id(), 'ResetByAdmin');

                                $login_users_obj->updatepassword($login_users_obj->getuser_id(), $password_md5);
                                $login_users_obj->updateuser_level($login_users_obj->getuser_id(), 'a:1:{i:0;s:2:"10";}');
                                $contact_obj->updateiduser($t_c->getidcontact(), $login_users_obj->getuser_id());
                                $login_users_obj->updatezid($login_users_obj->getuser_id(), 'welcome');
                                if (isset($array['idtransaction'])) {
                                    $login_users_obj->updatezcalid($login_users_obj->getuser_id(), $array['idtransaction']);
                                }
                                $buttonQ = '<a href="javascript:void(0);" class="SendQuestionary" data-iduser="' . $login_users_obj->getuser_id() . '" data-type="' . $rolQ . '" title="Send Questionary" style="border-radius:3px;background:white;color:#357ebd;"><i class="fa fa-question"></i></a>';
                            } else {
                                $buttonQ = '<a href="javascript:void(0);" class="SendQuestionary" data-iduser="' . $loginuser[0]->getuser_id() . '" data-type="' . $rolQ . '" title="Send Questionary" style="border-radius:3px;background:white;color:#357ebd;"><i class="fa fa-question"></i></a>';
                            }
                            /**/
                        } else {
                            $buttonQ = '<a href="javascript:void(0);" class="SendQuestionary" data-iduser="' . $party->getiduser() . '" data-type="' . $rolQ . '" title="Send Questionary" style="border-radius:3px;background:white;color:#357ebd;"><i class="fa fa-question"></i></a>';
                        }
                    } else {
                        $buttonQ = '';
                    }
                    $party = $contact_obj->getcontactById($t_c->getidcontact());
                    $SideP = '';
                    if ($t_c->getside()) {
                        $SideP = ' / ' . ucwords(strtolower($t_c->getside()));
                    }
                    $ResponseContent .= '<li>
                                            <div class="items-inner clearfix color-theme-c">
                                                <a class="items-image updateContact" data-id="' . $t_c->getidcontact() . '" style="max-width:45px !important;" title="Click to Update Contact" href="javascript:void(0);"><img class="img-circle" src="images/user-male.png"></a>
                                                <h3 class="items-title color-theme">
                                                    <input class="col-md-12 InputUpdateParty Party1" name="name_' . $t_c->getidcontact() . '" id="name_' . $t_c->getidcontact() . '" data-id="' . $t_c->getidcontact() . '" data-cmp="name" value="' . $ShowName . '"><br>
                                                    <input class="col-md-12 InputUpdateParty Party1" name="company_' . $t_c->getidcontact() . '" id="company_' . $t_c->getidcontact() . '" data-id="' . $t_c->getidcontact() . '" data-cmp="company" value="' . $party->getcompany() . '"></h3>
                                                <span class="label label-default">' . ucwords(strtolower($rolelist->getdescription())) . $SideP . '</span>
                                                <div class="items-details" style="margin-top: 3%;">
                                                    <div class="row"><label class="label col-md-4" style="color:black;font-size: 1em;margin-top: 2%;text-align: left;">E-Mail</label>
                                                    <label class="input col-md-8"><input name="email_' . $t_c->getidcontact() . '" id="email_' . $t_c->getidcontact() . '" value="' . $party->getemail() . '" data-id="' . $t_c->getidcontact() . '" data-cmp="email" class="InputUpdateParty"></label><br>
                                                    </div><div class="row"><label class="select col-md-4"><select name="SelectPhCell' . $t_c->getidcontact() . '" class="ChangeCellPhone" style="padding-top: 0px;height:2em"><option value="1">Phone</option><option value="2">Cell &nbsp;</option></select></label>
                                                    <label class="input col-md-8"><input class="InputUpdateParty" name="phone_' . $t_c->getidcontact() . '" id="phone_' . $t_c->getidcontact() . '" data-id="' . $t_c->getidcontact() . '" data-cmp="phone" value="' . $phone . '"></label>
                                                    <label class="input col-md-8" style="display:none;"><input class="InputUpdateParty" name="cell_' . $t_c->getidcontact() . '" id="cell_' . $t_c->getidcontact() . '" data-id="' . $t_c->getidcontact() . '" data-cmp="mobile" value="' . $mobile . '"></label></div>
                                                </div>
                                                <div class="control-buttons color-theme-b" style="width:80%;margin-left:-70%;background:#357ebd;">
                                                    <!--<a href="javascript:void(0);" onclick="call(' . $t_c->getidcontact() . ')" data-id="' . $t_c->getidcontact() . '" class="callinit" title="Call Contact" style="border-radius:3px;background:white;color:green;"><i class="fa fa-phone-square"></i></a>
                                                    <a href="javascript:void(0);" onclick="hangup();" data-id="' . $t_c->getidcontact() . '" class="callend" title="Hangup Call" style="border-radius:3px;background:white;color:red;"><i class="fa fa-phone"></i></a>-->
                                                    <a href="javascript:void(0);" class="send_sms" data-id="' . $t_c->getidcontact() . '" title="Text Message" style="border-radius:3px;background:white;color:#357ebd;"><i class="fa fa-comment"></i></a>
                                                    <a href="javascript:void(0);" class="send_mail" data-id="' . $t_c->getidcontact() . '" title="Email Contact" style="border-radius:3px;background:white;color:#357ebd;"><i class="fa fa-envelope"></i></a>
                                                    ' . $buttonQ . '
                                                    <!--<a href="javascript:void(0);" class="join_room" data-user="' . $party->getiduser() . '" data-id="' . $t_c->getidcontact() . '" title="Video Call" style="border-radius:3px;background:white;color:#357ebd;"><i class="fa fa-video-camera"></i></a>-->
                                                    ' . $div_close . '
                                                </div>
                                            </div>
                                         </li>';
                }
            }
        }
        $ArrayReturn['Content'] = $ResponseContent;
        echo json_encode($ArrayReturn);
        exit();

        $tran9 = $GetClass->GetClass('transaction');
        $id_transaction = $array['idtransaction'];
        if (is_object($tran9)) {
            $transaction_contact = $GetClass->GetClass('transaction_contact');
            $contact = $GetClass->GetClass('contact');
            $role = $GetClass->GetClass('rolelist');
            $t_c_list = $transaction_contact->getAlltransaction_contactForColumnValue('idtransaction', $array['idtransaction']);
            //print_r($t_c_list);
            $response = '';
            $sep = 2;
            if (is_array($t_c_list) && count($t_c_list)) {
                foreach ($t_c_list as $k) {
                    if ($k && $k->getidcontact()) {
                        $cont = $contact->getcontactById($k->getidcontact());
                        if (is_object($cont)) {
                            $nombre = ''; //side
                            $company = '';
                            $nombre = $cont->getfirstname() . ' ' . $cont->getmiddlename() . ' ' . $cont->getsurname();
                            $company = $cont->getcompany();
                            if (!trim($nombre)) {
                                $nombre = $cont->getcompany();
                            }
                            $rolelist = '';
                            if ($k->getidrole()) {
                                $rolelist = $role->getrolelistById($k->getidrole());
                            }
                            if ($rolelist) {
                                $rol = $rolelist->getdescription();
                                if ($rol == 'Buyer' || $rol == 'buyer') {
                                    $rolQ = 'Buyer';
                                }
                                if ($rol == 'Seller' || $rol == 'seller') {
                                    $rolQ = 'Seller';
                                }
                                if (($rol == 'Listing Agent') || ($k->getside() == 'seller' && $rol == 'reagent')) {
                                    $rolQ = 'Seller Agent';
                                }
                            } else {
                                $rolQ = '';
                            }
                            $mobile = str_replace('(', '', $cont->getmobile());
                            $mobile = str_replace(')', '', $mobile);
                            $mobile = str_replace(' ', '', $mobile);
                            $mobile = str_replace('-', '', $mobile);
                            if ($mobile != '') {
                                $mobile = '1' . $mobile;
                            }
                            $ph = str_replace('(', '', $cont->getphone());
                            $ph = str_replace(')', '', $ph);
                            $ph = str_replace(' ', '', $ph);
                            $ph = str_replace('-', '', $ph);
                            if ($ph != '') {
                                $ph = '1' . $ph;
                            }
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $idlogin = $_SESSION['jigowatt']['user_id'];
                            $lev = ($_SESSION['jigowatt']['user_level']);
                            if (count($lev) == 3) {
                                $div_close = '<div class="delete_partieicv" data-id="' . $k->getidcontact() . '" data-idrole="' . $k->getidrole() . '"></div>';
                            } elseif (count($lev) == 2) {
                                $div_close = '<div class="delete_partieicv" data-id="' . $k->getidcontact() . '" data-idrole="' . $k->getidrole() . '"></div>';
                            } else {
                                $div_close = '';
                            }
                            $buttonQ = '';
                            if ($rol == 'buyer' || $rol == 'Buyer' || $rol == 'seller' || $rol == 'Seller' || $rol == 'Listing Agent' || ($k->getside() == 'seller' && $rol == 'reagent')) {
                                if (!$cont->getiduser()) {
                                    /* AgregarUserContact */
                                    $login_users_obj = $GetClass->GetClass('login_users');
                                    $username = trim($cont->getfirstname()) . trim($cont->getsurname());
                                    if ($username == '') {
                                        $username = trim($cont->getcompany());
                                    }
                                    $last = ucfirst(strtolower(trim($cont->getsurname())));
                                    if ($last == '') {
                                        $last = ucfirst(strtolower(trim($cont->getcompany())));
                                    }
                                    $password = trim($last) . rand(10, 99);
                                    $password_md5 = md5($password);
                                    $username = str_replace(' ', '', $username);
                                    $password = str_replace(' ', '', $password);
                                    $username = strtolower($username);
                                    $loginuser = $login_users_obj->getAlllogin_usersForColumnValue('username', '"' . $username . '"'); //echo var_dump($loginuser);
                                    if (is_array($loginuser) && count($loginuser)) {
                                        if (is_object($loginuser[0])) {
                                            $username = $username . rand(10, 99);
                                        }
                                    }
                                    $namename = trim($contact->getfirstname()) . ' ' . $contact->getsurname();
                                    if (!trim($namename)) {
                                        $namename = trim($contact->getcompany());
                                    }
                                    $login_users_obj->setusername($username);
                                    $login_users_obj->updatefirstname($login_users_obj->getuser_id(), trim($cont->getfirstname()));
                                    $login_users_obj->updatelastname($login_users_obj->getuser_id(), $cont->getsurname());
                                    $login_users_obj->updatenameu($login_users_obj->getuser_id(), $namename);
                                    $login_users_obj->updateemail2($login_users_obj->getuser_id(), $cont->getemail());
                                    $login_users_obj->updatephone($login_users_obj->getuser_id(), $cont->getphone());
                                    $login_users_obj->updatemobile($login_users_obj->getuser_id(), $cont->getmobile());
                                    $login_users_obj->updatepassreset($login_users_obj->getuser_id(), 'ResetByAdmin');

                                    $login_users_obj->updatepassword($login_users_obj->getuser_id(), $password_md5);
                                    $login_users_obj->updateuser_level($login_users_obj->getuser_id(), 'a:1:{i:0;s:2:"10";}');
                                    $contact->updateiduser($k->getidcontact(), $login_users_obj->getuser_id());
                                    $login_users_obj->updatezid($login_users_obj->getuser_id(), 'welcome');
                                    if (isset($array['idtransaction'])) {
                                        $login_users_obj->updatezcalid($login_users_obj->getuser_id(), $array['idtransaction']);
                                    }
                                    $buttonQ = '<a class="btn btn-primary tooltiped SendQuestionary" data-iduser="' . $login_users_obj->getuser_id() . '" data-type="' . $rolQ . '" data-toggle="tooltip" data-placement="top" title="Send Questionary"><i class="fa fa-question"></i></a>';
                                    /**/
                                } else {
                                    $buttonQ = '<a class="btn btn-primary tooltiped SendQuestionary" data-iduser="' . $cont->getiduser() . '" data-type="' . $rolQ . '" data-toggle="tooltip" data-placement="top" title="Send Questionary"><i class="fa fa-question"></i></a>';
                                }
                                /* If Dont IdQ */
                                $c_obj = $GetClass->GetClass('customer');
                                $conta = $c_obj->getAllcustomerforColumnValue('idcontact', $cont->getidcontact());
                                //print_r($conta);
                                if (!$conta) {
                                    $new_array = array();
                                    $new_array['firstname'] = $cont->getfirstname();
                                    $new_array['lastname'] = $cont->getsurname();
                                    $new_array['company'] = $cont->getcompany();
                                    $new_array['email'] = $cont->getemail();
                                    $new_array['phone'] = $cont->getphone();
                                    $new_array['mobile'] = $cont->getmobile();
                                    $new_array['idrole'] = $k->getidrole();
                                    $new_array['update'] = 'insert';
                                    $new_array['idcontact'] = $cont->getidcontact(); //var_dump($contact_obj->getidcontact());
                                    $customer_response = createCustomerVendorByContactQB($new_array);
                                } else {
                                    $conta = $conta[(count($conta) - 1)]; //print_r($conta);
                                    if (!$conta->getidq()) {
                                        $new_array = array();
                                        $new_array['firstname'] = $cont->getfirstname();
                                        $new_array['lastname'] = $cont->getsurname();
                                        $new_array['company'] = $cont->getcompany();
                                        $new_array['email'] = $cont->getemail();
                                        $new_array['phone'] = $cont->getphone();
                                        $new_array['mobile'] = $cont->getmobile();
                                        $new_array['idrole'] = $k->getidrole();
                                        $new_array['update'] = $conta->getidcustomer();
                                        $new_array['idcontact'] = $cont->getidcontact(); //print_r($new_array);//var_dump($contact_obj->getidcontact());
                                        $customer_response = createCustomerVendorByContactQB($new_array); //print_r($customer_response);
                                    }
                                }
                                /**/
                            }
                            $json = '{"profile_name":"' . $nombre . '","profile_company":"' . $company . '","profile_role":"' . $rol . '","profile_email":"' . $cont->getemail() . '","profile_phone":"' . $ph . '","profile_mobile":"' . $mobile . '"}';
                            $response .= '<li ' . $customer_response . ' data-id="' . $k->getidcontact() . '" class="forclickli col col-md-6">
                                            <textarea style="display:none;" name="data_p_' . $k->getidcontact() . '" >' . $json . '</textarea>
                                            <div class="color-theme-b items-inner clearfix innerall inner_' . $k->getidcontact() . '">' . $div_close . '
                                                <div class="col col-md-2" style="padding:0px;margin-bottom: 8px;"><img class="img-circle" src="images/user-male.png" width="100%"></div>
                                                <div class="col col-md-10" style="padding-left: 1%;padding-right: 1%;"><h3 class="items-title items-title col col-md-8 color-theme" style="font-size: 1rem;padding: 2%;">' . $nombre . '</h3></div>
                                                <div class="items-details col-md-12" style="width:100%;margin-top: 1%;padding: 2%;min-height:160px;">
                                                    <h5 style="font-size:1.3em;padding:1%;margin-top: 0px;text-align: center;margin-bottom: 2%;">' . $rol . '</h5>
                                                    <label style="width: 100%;"><strong>Company : </strong>' . $company . '</label>
                                                    <label style="width: 100%;"><strong>E-Mail : </strong>' . $cont->getemail() . '</label>
                                                    <label style="width: 100%;"><strong>Phone : </strong>' . $ph . '</label>
                                                    <label style="width: 100%;"><strong>Mobile : </strong>' . $mobile . '</label>
                                                    <div class="small-icons-buttons" style="text-align:center;bottom: 5px;position: absolute;width: 100%;">
                                                    <input type="hidden" name="number_' . $k->getidcontact() . '" id="number_' . $k->getidcontact() . '" value="' . $ph . '">
                                                        <div class="btn-group newbts">
                                                            <a onclick="call(' . $k->getidcontact() . ')" data-id="' . $k->getidcontact() . '" class="btn btn-default tooltiped callinit" data-toggle="tooltip" data-placement="top" title="Call Contact"><i class="fa fa-phone-square"></i></a>
                                                            <a onclick="hangup();" class="btn btn-danger tooltiped callend" data-toggle="tooltip" data-placement="top" title="Hangup Call"><i class="fa fa-phone"></i></a>
                                                            <a class="btn btn-primary tooltiped send_sms" data-number="' . $ph . '" data-toggle="tooltip" data-placement="top" title="Text Message"><i class="fa fa-comment"></i></a>
                                                            <a class="btn btn-primary tooltiped send_mail" data-id="' . $k->getidcontact() . '" data-to="' . $nombre . '" data-email="' . $cont->getemail() . '" data-toggle="tooltip" data-placement="top" title="Email Contact"><i class="fa fa-envelope"></i></a>' . $buttonQ . '
                                                            <a class="btn btn-default tooltiped join_room" data-user="' . $cont->getiduser() . '" data-toggle="tooltip" data-placement="top" title="Video Call"><i class="fa fa-video-camera"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>';
                            if ($sep == 3) {
                                $response .= '<br style="clear:both">';
                                $sep = 2;
                            } else {
                                $sep++;
                            }
                        }
                    }
                }
            }
        }
        $array_resp['idtransaction'] = $array[idtransaction];
        $array_resp['data'] = $response;
        $array_resp = json_encode($array_resp);
        echo $response;
    } else {
        echo 'Error : Not Have Array or Id Transaction';
    }
}

// 21
function SaveAutoSaveParty($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idtransaction']) {
            if ($array['Id']) {
                $Class_obj = $GetClass->GetClass($array['Clase']);
                if ($array['Campo'] == 'name') {
                    $name = explode(' ', $array['value']);
                    $newname = '';
                    foreach ($name as $value) {
                        if (trim($value) != ' ') {
                            $newname .= trim($value) . ' ';
                        }
                    }
                    $newname = trim($newname);
                    $name = explode(' ', $newname); //print_r($name);
                    if (count($name) == '4') {
                        $Class_obj->updatefirstname($array['Id'], trim($name[0]));
                        $Class_obj->updatemiddlename($array['Id'], trim($name[1]));
                        $Class_obj->updatesurname($array['Id'], trim($name[2]) . ' ' . $name[3]);
                    }
                    if (count($name) == '3') {
                        $Class_obj->updatefirstname($array['Id'], trim($name[0]));
                        $Class_obj->updatemiddlename($array['Id'], trim($name[1]));
                        $Class_obj->updatesurname($array['Id'], trim($name[2]));
                    }
                    if (count($name) == '2') {
                        $Class_obj->updatefirstname($array['Id'], trim($name[0]));
                        $Class_obj->updatesurname($array['Id'], trim($name[1]));
                    }
                    if (count($name) == '1') {
                        $Class_obj->updatesurname($array['Id'], trim($name[0]));
                    }
                } else {//print_r($array);
                    $update = 'update' . $array['Campo'];
                    $Class_obj->$update($array['Id'], $array['value']);
                }
                echo 'Update Successfully';
            } else {
                
            }
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 22
function SaveSalesPriceDialog($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //print_r($array);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idtransaction']) {
            $cdhud_obj = $GetClass->GetClass('cdhud');
            if ($array['Function'] == 'Get') {
                $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                if ($cdhud) {
                    $cdhud = $cdhud[0];
                    if ($cdhud->getSalesPrice()) {
                        if (json_decode($cdhud->getSalesPrice(), true)) {
                            $temp = json_decode($cdhud->getSalesPrice(), true);
                            echo $cdhud->getSalesPrice();
                        } else {
                            echo 'Empty';
                        }
                    } else {
                        echo 'Empty';
                    }
                } else {
                    echo 'Empty';
                }
            } else {
                $arrayReturn = array();
                $cdhud_page3_obj = $GetClass->GetClass('cdhud_page3');
                $cdhud_page2_obj = $GetClass->GetClass('cdhud_page2');
                $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                if ($cdhud) {
                    $cdhud = $cdhud[0];
                    $idcdhud = $cdhud->getidcdhud();
                } else {
                    $cdhud_obj->setidtransaction($array['idtransaction']);
                    $idcdhud = $cdhud_obj->getidcdhud();
                }
                $cdhud_obj->updateSalesPrice($idcdhud, json_encode($array));
                /**/
                $loanapp_obj = $GetClass->GetClass('loanapp');
                $loanapp = $loanapp_obj->getAllloanappForColumnValue('idtransaction', "'" . $array['idtransaction'] . "'");
                if ($loanapp) {
                    $loanapp = $loanapp[0];
                    $idloanapp = $loanapp->getidloanapp();
                    $dataoriginal = json_decode($loanapp->getdata(), true);
                } else {
                    $loanapp_obj->setidtransaction($array['idtransaction']);
                    $idloanapp = $loanapp_obj->getidloanapp();
                }
                $dataoriginal['PropertyValue'] = $array['SalesPriceDialog'];
                $loanapp_obj->updatedata($idloanapp, json_encode($dataoriginal));
                /**/
                echo 'Saved';
            }
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 23
function SaveLoanAmountDialog($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idtransaction']) {
            $cdhud_obj = $GetClass->GetClass('cdhud');
            if ($array['Function'] == 'Get') {
                $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                if ($cdhud) {
                    $cdhud = $cdhud[0];
                    if ($cdhud->getLoanAmount()) {
                        if (json_decode($cdhud->getLoanAmount(), true)) {
                            $temp = json_decode($cdhud->getLoanAmount(), true);
                            echo $cdhud->getLoanAmount();
                        } else {
                            echo 'Empty';
                        }
                    } else {
                        echo 'Empty';
                    }
                } else {
                    echo 'Empty';
                }
            } else {
                $arrayReturn = array();
                $cdhud_page3_obj = $GetClass->GetClass('cdhud_page3');
                $cdhud_page2_obj = $GetClass->GetClass('cdhud_page2');
                $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                if ($cdhud) {
                    $cdhud = $cdhud[0];
                    $idcdhud = $cdhud->getidcdhud();
                } else {
                    $cdhud_obj->setidtransaction($array['idtransaction']);
                    $idcdhud = $cdhud_obj->getidcdhud();
                }
                $cdhud_obj->updateLoanAmount($idcdhud, json_encode($array));
                /**/
                $loanapp_obj = $GetClass->GetClass('loanapp');
                $loanapp = $loanapp_obj->getAllloanappForColumnValue('idtransaction', "'" . $array['idtransaction'] . "'");
                if ($loanapp) {
                    $loanapp = $loanapp[0];
                    $idloanapp = $loanapp->getidloanapp();
                    $dataoriginal = json_decode($loanapp->getdata(), true);
                } else {
                    $loanapp_obj->setidtransaction($array['idtransaction']);
                    $idloanapp = $loanapp_obj->getidloanapp();
                }
                $dataoriginal['MortgageAmount'] = $array['LoanAmountDialog'];
                $loanapp_obj->updatedata($idloanapp, json_encode($dataoriginal));
                /**/
                echo 'Saved';
            }
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 24
function PrepaidInterest($theData) {
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    if (is_array($array)) {//print_r($array);
        $fecha = strtotime($array['fecha2']) - strtotime($array['fecha1']);
        $diferencia = intval($fecha / 60 / 60 / 24);
        $amount = $array['amount'];
        $amount = str_replace('$', '', $amount);
        $amount = str_replace(',', '', $amount);
        $total = $amount * $diferencia;
        if (strpos($total, '.') !== false) {
            $total = $total . '000';
        } else {
            $total = $total . '.000';
        }
        $total = number_format($total, 2);
        $array_return = array();
        if (is_numeric($array['rate'])) {
            //print_r($array);
            $array_return[$array['target']] = prorateo_parcial_04($array['fecha1'], $array['fecha2'], $array['amount'], $array['rate'], 'no');
        } else {
            $array_return[$array['target']] = $total;
        }
        //$array_return[$array['target']] = $total;
        echo json_encode($array_return);
    } else {
        echo 'Error : An Array Has Expected';
    }
}

function diff_fechas($fecha1, $fecha2, $indice) {
    $fecha = strtotime($fecha1) - strtotime($fecha2);
    $diferencia = intval($fecha / 60 / 60 / 24); //print_r($diferencia);
    return $diferencia;
}

function prorateo_parcial_04($fecha1, $fecha2, $amount, $time, $indice) {
    /* error_reporting(E_ERROR);
      ini_set('display_errors', 1); */
    $amount = str_replace('$', '', $amount);
    $amount = str_replace(',', '', $amount);
    $fecha1_desglosado = explode('/', $fecha1);
    $fecha2_desglosado = explode('/', $fecha2);
    $year1 = $fecha1_desglosado[2];
    $month1 = $fecha1_desglosado[0];
    $day1 = $fecha1_desglosado[1];
    $year2 = $fecha2_desglosado[2];
    $month2 = $fecha2_desglosado[0];
    $day2 = $fecha2_desglosado[1];
    $return = '0.00';
    if ($time == '1') {
        $return = 0;
        for ($year = $year1; $year <= $year2; $year++) {
            if ($year == $year1 && $year == $year2) {
                $days = 0;
                for ($i = 1; $i <= 12; $i++) {
                    $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year);
                }
                if ($indice == 'si') {
                    $return = ($amount / $days) * (diff_fechas($fecha2, $fecha1) + 1);
                } else {
                    //print_r($days);
                    $return = ($amount / $days) * (diff_fechas($fecha2, $fecha1));
                }
            } else {
                if ($year == $year1) {
                    $days = 0;
                    $lastday = '';
                    for ($i = 1; $i <= 12; $i++) {
                        $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year);
                        $lastday = cal_days_in_month(CAL_GREGORIAN, $i, $year);
                    }
                    $fecha2_a = '12/' . $lastday . '/' . $year;
                    $return = $return + ($amount / $days) * (diff_fechas($fecha2_a, $fecha1) + 1);
                } else {
                    if ($year == $year2) {
                        $days = 0;
                        for ($i = 1; $i <= 12; $i++) {
                            $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year);
                        }
                        $fecha1_a = '01/01/' . $year;
                        if ($indice == 'si') {
                            $return = $return + ($amount / $days) * (diff_fechas($fecha2, $fecha1_a) + 1);
                        } else {
                            $return = $return + ($amount / $days) * (diff_fechas($fecha2, $fecha1_a));
                        }
                    } else {
                        $return = $return + $amount;
                    }
                }
            }
        }
    }
    if ($time == '2') {
        if ($month1 != '10') {
            $month_a = str_replace('0', '', $month1);
        } else {
            $month_a = $month1;
        }
        if ($month2 != '10') {
            $month_b = str_replace('0', '', $month2);
        } else {
            $month_b = $month2;
        }
        $return = 0;
        $verifica_init = 'no';
        if (($month_a > 6 && $month_b > 6 && $year1 == $year2) || ($month_a <= 6 && $month_b <= 6 && $year1 == $year2)) {
            $days = 0;
            if ($month_a > 6) {
                for ($i = 7; $i <= 12; $i++) {
                    $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year1);
                }
            } else {
                for ($i = 1; $i <= 6; $i++) {
                    $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year1);
                }
            }
            if ($indice == 'si') {
                $return = $return + ($amount / $days) * (diff_fechas($fecha2, $fecha1) + 1);
            } else {
                $return = $return + ($amount / $days) * (diff_fechas($fecha2, $fecha1));
            }
        } else {
            for ($year = $year1; $year <= $year2; $year++) {
                if ($year == $year1) {
                    $days = 0;
                    $lastday = '';
                    $lastmonth = '';
                    if ($month_a > 6) {
                        for ($i = 7; $i <= 12; $i++) {
                            $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year);
                            $lastmonth = $i;
                            $lastday = cal_days_in_month(CAL_GREGORIAN, $i, $year);
                        }
                    } else {
                        for ($i = 1; $i <= 6; $i++) {
                            $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year);
                            $lastmonth = $i;
                            $lastday = cal_days_in_month(CAL_GREGORIAN, $i, $year);
                        }
                    }
                    $fecha2_a = $lastmonth . '/' . $lastday . '/' . $year;
                    $return = $return + ($amount / $days) * (diff_fechas($fecha2_a, $fecha1) + 1);
                    if ($year == $year2) {
                        $days = 0;
                        $lastday = '';
                        $lastmonth = '';
                        if ($month_b > 6) {
                            for ($i = 7; $i <= 12; $i++) {
                                $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year);
                            }
                            $lastmonth = 7;
                        } else {
                            for ($i = 1; $i <= 6; $i++) {
                                $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year);
                            }
                            $lastmonth = 1;
                        }
                        $fecha1_a = $lastmonth . '/01/' . $year;
                        if ($indice == 'si') {
                            $return = $return + ($amount / $days) * (diff_fechas($fecha2, $fecha1_a) + 1);
                        } else {
                            $return = $return + ($amount / $days) * (diff_fechas($fecha2, $fecha1_a));
                        }
                    } else {
                        $return = $return + ($amount);
                    }
                } else {
                    if ($year == $year2) {
                        $days = 0;
                        $lastday = '';
                        $lastmonth = '';
                        if ($month_b > 6) {
                            for ($i = 7; $i <= 12; $i++) {
                                $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year);
                            }
                            $lastmonth = 7;
                            $return = $return + ($amount);
                        } else {
                            for ($i = 1; $i <= 6; $i++) {
                                $days = $days + cal_days_in_month(CAL_GREGORIAN, $i, $year);
                            }
                            $lastmonth = 1;
                        }
                        $fecha1_a = $lastmonth . '/01/' . $year;
                        if ($indice == 'si') {
                            $return = $return + ($amount / $days) * (diff_fechas($fecha2, $fecha1_a) + 1);
                        } else {
                            $return = $return + ($amount / $days) * (diff_fechas($fecha2, $fecha1_a));
                        }
                    } else {
                        $return = $return + ($amount * 2);
                    }
                }
            }
        }
    }
    if ($time == '3') {
        $array_quaters = array(array('1', '2', '3'), array('4', '5', '6'), array('7', '8', '9'), array('10', '11', '12'));
        if ($month1 != '10') {
            $month_a = str_replace('0', '', $month1);
        } else {
            $month_a = $month1;
        }
        if ($month2 != '10') {
            $month_b = str_replace('0', '', $month2);
        } else {
            $month_b = $month2;
        }
        $return = 0;
        $verifica = 'no';
        $verifica2 = 'no';
        $verifica3 = 'no';
        for ($year = $year1; $year <= $year2; $year++) {
            for ($i = 1; $i <= 4; $i++) {
                $quater = $array_quaters[$i - 1]; //var_dump($quater);
                if (in_array($month_a, $quater) && in_array($month_b, $quater) && $year1 == $year2) {
                    $days = 0;
                    foreach ($quater as $mnth) {
                        $days = $days + cal_days_in_month(CAL_GREGORIAN, $mnth, $year);
                    }
                    if ($indice == 'si') {
                        $return = ($amount / $days) * (diff_fechas($fecha2, $fecha1) + 1);
                    } else {
                        $return = ($amount / $days) * (diff_fechas($fecha2, $fecha1));
                    }
                    $verifica = 'yes';
                } else {
                    if ($verifica == 'no') {
                        if (in_array($month_a, $quater) && $year == $year1) {
                            $days = 0;
                            foreach ($quater as $mnth) {
                                $days = $days + cal_days_in_month(CAL_GREGORIAN, $mnth, $year);
                            }
                            $fecha2_a = $quater[2] . '/' . cal_days_in_month(CAL_GREGORIAN, $quater[2], $year) . '/' . $year;
                            $return = $return + ($amount / $days) * (diff_fechas($fecha2_a, $fecha1) + 1); //echo $return.'*****';
                            $verifica3 = 'yes';
                        } else {
                            if ($verifica3 == 'yes') {
                                if (in_array($month_b, $quater) && $year == $year2) {
                                    $days = 0;
                                    foreach ($quater as $mnth) {
                                        $days = $days + cal_days_in_month(CAL_GREGORIAN, $mnth, $year);
                                    }
                                    $fecha1_a = $quater[0] . '/01/' . $year;
                                    if ($indice == 'si') {
                                        $return = $return + ($amount / $days) * (diff_fechas($fecha2, $fecha1_a) + 1);
                                    } else {
                                        $return = $return + ($amount / $days) * (diff_fechas($fecha2, $fecha1_a));
                                    }
                                    $verifica2 = 'yes';
                                    $verifica3 = 'no';
                                } else {
                                    if ($verifica2 == 'no') {
                                        $return = $return + $amount;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    if ($time == '4') {
        if ($month1 != $month2 || $year1 != $year2) {
            $month_a = $month1;
            $month_b = $month2;
            for ($year = $year1; $year <= $year2; $year++) {
                if ($year == $year1) {
                    $month_a = $month1;
                    $day1_a = $day1;
                } else {
                    $month_a = '01';
                    $day1_a = '01';
                }
                if ($year == $year2) {
                    $month_b = $month2;
                    $day2_a = $day2;
                } else {
                    $month_b = '12';
                    $day2_a = '01';
                }
                for ($i = $month_a; $i <= $month_b; $i++) {
                    if ($i == $month_a && $year == $year1) {
                        $fecha1_now = $i . '/' . $day1_a . '/' . $year; //echo $fecha1_now.'*******';
                        $fecha2_now = $i . '/' . cal_days_in_month(CAL_GREGORIAN, $i, $year) . '/' . $year; //echo $fecha2_now.'*******';
                        $return = $return + ($amount / cal_days_in_month(CAL_GREGORIAN, $i, $year)) * (diff_fechas($fecha2_now, $fecha1_now) + 1);
                    } else {
                        if ($i == $month_b && $year == $year2) {
                            $fecha1_now = $i . '/01/' . $year;
                            $fecha2_now = $i . '/' . $day2_a . '/' . $year;
                            if ($indice == 'si') {
                                $return = $return + ($amount / cal_days_in_month(CAL_GREGORIAN, $i, $year)) * (diff_fechas($fecha2_now, $fecha1_now) + 1);
                            } else {
                                $return = $return + ($amount / cal_days_in_month(CAL_GREGORIAN, $i, $year)) * (diff_fechas($fecha2_now, $fecha1_now));
                            }
                        } else {
                            $return = $return + ($amount);
                        }
                    }
                }
            }
        } else {
            if ($indice == 'si') {
                $return = ($amount / cal_days_in_month(CAL_GREGORIAN, $month1, $year1)) * (diff_fechas($fecha2, $fecha1) + 1);
            } else {
                $return = ($amount / cal_days_in_month(CAL_GREGORIAN, $month1, $year1)) * (diff_fechas($fecha2, $fecha1));
            }
        }
    }
    //$return = str_replace('USD ', '', money_format('%i.3n', $return));
    if (strpos($return, '.') === false) {
        $return = $return . '.0000';
    } else {
        $return = $return . '0000';
    }
    $return = number_format($return, 2);
    return '$' . $return;
}

// 25
function SaveTaxes($theData) {
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        if ($array['idtransaction']) {
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $cdhud_page3_obj = $GetClass->GetClass('cdhud_page3');
            $purchase_obj = $GetClass->GetClass('purchase');
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
            } else {
                $cdhud_obj->setidtransaction($array['idtransaction']);
                if ($array['BankSelect']) {
                    $cdhud_obj->updatebankaccount($cdhud_obj->getidcdhud(), $array['BankSelect']);
                }
                $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                $cdhudTransaction = $cdhudTransaction[0];
            }
            $AllPurchasesTransaction = $purchase_obj->getAllpurchaseForColumnvalue('idtransaction', $array['idtransaction']);
            if ($array['check_d2_2']) {
                $idhudline = $array['Hudlinep32_1'];
                if ($array['radiod2_2'] == 'a') {
                    $idhudline = $array['Hudlinep32_1'] . '_' . $array['Hudlinep32_2'];
                }
                if ($array['radiod2_2'] == 'b') {
                    $idhudline = $array['Hudlinep32_1'];
                }
                if ($array['radiod2_2'] == 'c') {
                    $idhudline = $array['Hudlinep32_2'];
                }
                $idpurchase = '';
                if ($AllPurchasesTransaction) {
                    foreach ($AllPurchasesTransaction as $purchase) {
                        if ($purchase->gethudline() == $idhudline) {
                            $idpurchase = $purchase->getidpurchase();
                        }
                    }
                }
                if (!$idpurchase) {
                    $purchase_obj->setidtransaction($array['idtransaction']);
                    $idpurchase = $purchase_obj->getidpurchase();
                    $purchase_obj->updatehudline($idpurchase, $idhudline);
                }
                /* Amount */
                $AmountLine = str_replace(array('$', ','), array('', ''), $array['Taxline']);
                /**/
                /**/
                if (!$array['IdContact6']) {
                    $array['IdContact6'] = CreaContactForCheck($array['FirstName6'], $array['LastName6'], $array['CompanyName6']);
                }
                $Line = array('TypeContact' => $array['TypeContact6'], 'Amount' => $AmountLine, 'IdContact' => $array['IdContact6']);
                $Lines = array(json_encode($Line));
                $account = json_encode($Lines);
                /* Update Data Purchase */
                $purchase_obj->updateaccount($idpurchase, $account);
                $purchase_obj->updateexpensedate($idpurchase, date('Y-m-d'));
                $purchase_obj->updateidlogin($idpurchase, $idlogin);
                $purchase_obj->updatedescription($idpurchase, $array['DescriptionTax']);
                $purchase_obj->updateamount($idpurchase, $AmountLine);
                $purchase_obj->updateidcontact($idpurchase, $array['IdContact6']);
                $purchase_obj->updatebankaccount($idpurchase, $cdhudTransaction->getbankaccount());
                /**/
            } else {
                if ($AllPurchasesTransaction) {
                    $idhudline = $array['Hudlinep32_1'];
                    if ($array['radiod2_2'] == 'a') {
                        $idhudline = $array['Hudlinep32_1'] . '_' . $array['Hudlinep32_2'];
                    }
                    if ($array['radiod2_2'] == 'b') {
                        $idhudline = $array['Hudlinep32_1'];
                    }
                    if ($array['radiod2_2'] == 'c') {
                        $idhudline = $array['Hudlinep32_2'];
                    }
                    $idpurchase = '';
                    foreach ($AllPurchasesTransaction as $purchase) {
                        if ($purchase->gethudline() == $idhudline) {
                            $idpurchase = $purchase->getidpurchase();
                        }
                    }
                    if ($idpurchase) {
                        $purchase_obj->deletepurchase($idpurchase);
                    }
                }
            }
            $cdhud_page3 = $cdhud_page3_obj->getAllcdhud_page3ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
            $update = 'update' . str_replace('-', '', $array['Hudlinep32_1']);
            if ($array['radiod2_2'] == 'c') {
                $update = 'update' . str_replace('-', '', $array['Hudlinep32_2']);
            }
            if ($cdhud_page3) {
                $cdhud_page3 = $cdhud_page3[0];
                $cdhud_page3_obj->$update($cdhud_page3->getidcdhud_page3(), json_encode($array));
            } else {
                $cdhud_page3_obj->setidcdhud($cdhudTransaction->getidcdhud());
                $cdhud_page3_obj->$update($cdhud_page3_obj->getidcdhud_page3(), json_encode($array));
            }
            if ($array['radiod2_2'] == 'a') {
                $cdhud_page3 = $cdhud_page3_obj->getAllcdhud_page3ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
                $update = 'update' . str_replace('-', '', $array['Hudlinep32_2']);
                if ($cdhud_page3) {
                    $cdhud_page3 = $cdhud_page3[0];
                    $cdhud_page3_obj->$update($cdhud_page3->getidcdhud_page3(), json_encode($array));
                } else {
                    $cdhud_page3_obj->setidcdhud($cdhudTransaction->getidcdhud());
                    $cdhud_page3_obj->$update($cdhud_page3_obj->getidcdhud_page3(), json_encode($array));
                }
            }
            /**/
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $bank_obj = $GetClass->GetClass('bank');
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
                $idb = $cdhudTransaction->getbankaccount();
                $bank = $bank_obj->getbankById($idb);
                if ($bank) {
                    UpdateBanksBalances($idb);
                }
            }
            /**/
            echo json_encode($array);
        } else {
            echo 'Error : An Array Has Expected';
        }
    } else {
        echo 'Error : An Array Has Expected';
    }
}

// 26
function SavePayyoffs($theData) {
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        if ($array['idtransaction']) {
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $cdhud_page3_obj = $GetClass->GetClass('cdhud_page3');
            $cdhud_page245_obj = $GetClass->GetClass('cdhud_page245');
            $purchase_obj = $GetClass->GetClass('purchase');
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
            } else {
                $cdhud_obj->setidtransaction($array['idtransaction']);
                if ($array['BankSelect']) {
                    $cdhud_obj->updatebankaccount($cdhud_obj->getidcdhud(), $array['BankSelect']);
                }
                $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                $cdhudTransaction = $cdhudTransaction[0];
            }
            $AllPurchasesTransaction = $purchase_obj->getAllpurchaseForColumnvalue('idtransaction', $array['idtransaction']);
            if ($array['check_payyoff_a']) {
                $idhudline = 'N-04';
                $idpurchase = '';
                if ($AllPurchasesTransaction) {
                    foreach ($AllPurchasesTransaction as $purchase) {
                        if ($purchase->gethudline() == $idhudline) {
                            $idpurchase = $purchase->getidpurchase();
                        }
                    }
                }
                if (!$idpurchase) {
                    $purchase_obj->setidtransaction($array['idtransaction']);
                    $idpurchase = $purchase_obj->getidpurchase();
                    $purchase_obj->updatehudline($idpurchase, $idhudline);
                }
                /* Amount */
                $AmountLine = str_replace(array('$', ','), array('', ''), $array['Amount_payyoff_a']);
                /**/
                /**/
                if (!$array['IdContact7']) {
                    $array['IdContact7'] = CreaContactForCheck($array['FirstName7'], $array['LastName7'], $array['CompanyName7']);
                }
                $Line = array('TypeContact' => $array['TypeContact7'], 'Amount' => $AmountLine, 'IdContact' => $array['IdContact7']);
                $Lines = array(json_encode($Line));
                $account = json_encode($Lines);
                /* Update Data Purchase */
                $purchase_obj->updateaccount($idpurchase, $account);
                $purchase_obj->updateexpensedate($idpurchase, date('Y-m-d'));
                $purchase_obj->updateidlogin($idpurchase, $idlogin);
                $purchase_obj->updatedescription($idpurchase, 'Payoff of First Mortgage Loan');
                $purchase_obj->updateamount($idpurchase, $AmountLine);
                $purchase_obj->updateidcontact($idpurchase, $array['IdContact7']);
                $purchase_obj->updatebankaccount($idpurchase, $cdhudTransaction->getbankaccount());
                /**/
            } else {
                if ($AllPurchasesTransaction) {
                    $idhudline = 'N-04';
                    $idpurchase = '';
                    foreach ($AllPurchasesTransaction as $purchase) {
                        if ($purchase->gethudline() == $idhudline) {
                            $idpurchase = $purchase->getidpurchase();
                        }
                    }
                    if ($idpurchase) {
                        $purchase_obj->deletepurchase($idpurchase);
                    }
                }
            }
            if ($array['check_payyoff_b']) {
                $idhudline = 'N-05';
                $idpurchase = '';
                if ($AllPurchasesTransaction) {
                    foreach ($AllPurchasesTransaction as $purchase) {
                        if ($purchase->gethudline() == $idhudline) {
                            $idpurchase = $purchase->getidpurchase();
                        }
                    }
                }
                if (!$idpurchase) {
                    $purchase_obj->setidtransaction($array['idtransaction']);
                    $idpurchase = $purchase_obj->getidpurchase();
                    $purchase_obj->updatehudline($idpurchase, $idhudline);
                }
                /* Amount */
                $AmountLine = str_replace(array('$', ','), array('', ''), $array['Amount_payyoff_b']);
                /**/
                /**/
                if (!$array['IdContact8']) {
                    $array['IdContact8'] = CreaContactForCheck($array['FirstName8'], $array['LastName8'], $array['CompanyName8']);
                }
                $Line = array('TypeContact' => $array['TypeContact8'], 'Amount' => $AmountLine, 'IdContact' => $array['IdContact8']);
                $Lines = array(json_encode($Line));
                $account = json_encode($Lines);
                /* Update Data Purchase */
                $purchase_obj->updateaccount($idpurchase, $account);
                $purchase_obj->updateexpensedate($idpurchase, date('Y-m-d'));
                $purchase_obj->updateidlogin($idpurchase, $idlogin);
                $purchase_obj->updatedescription($idpurchase, 'Payoff of Second Mortgage Loan');
                $purchase_obj->updateamount($idpurchase, $AmountLine);
                $purchase_obj->updateidcontact($idpurchase, $array['IdContact8']);
                $purchase_obj->updatebankaccount($idpurchase, $cdhudTransaction->getbankaccount());
                /**/
            } else {
                if ($AllPurchasesTransaction) {
                    $idhudline = 'N-05';
                    $idpurchase = '';
                    foreach ($AllPurchasesTransaction as $purchase) {
                        if ($purchase->gethudline() == $idhudline) {
                            $idpurchase = $purchase->getidpurchase();
                        }
                    }
                    if ($idpurchase) {
                        $purchase_obj->deletepurchase($idpurchase);
                    }
                }
            }
            /* Deducts */
            $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
            if ($cdhud_page245) {
                if (is_array($cdhud_page245)) {
                    $cdhud_page245 = $cdhud_page245[0];
                }
                $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
            }
            if (!$idcdhud_page245) {
                $cdhud_page245_obj->setidcdhud($cdhudTransaction->getidcdhud());
                $idcdhud_page245 = $cdhud_page245_obj->getidcdhud_page245();
            }
            $JsonDeducts = $cdhud_page245->getDeducts();
            if ($JsonDeducts) {
                $JsonDeducts = json_decode($JsonDeducts, true);
            } else {
                $JsonDeducts = array();
            }
            if ($array['deduct_payyoff_a']) {
                $JsonDeducts['N-04'] = 'Payoff 1||' . $array['Amount_payyoff_a'];
            } else {
                $NewJsonDeduct = array();
                foreach ($JsonDeducts as $CodeLine => $datade) {
                    if ($CodeLine != 'N-04') {
                        $NewJsonDeduct[$CodeLine] = $datade;
                    }
                }
                $JsonDeducts = $NewJsonDeduct;
            }
            if ($array['deduct_payyoff_b']) {
                $JsonDeducts['N-05'] = 'Payoff 2||' . $array['Amount_payyoff_b'];
            } else {
                $NewJsonDeduct = array();
                foreach ($JsonDeducts as $CodeLine => $datade) {
                    if ($CodeLine != 'N-05') {
                        $NewJsonDeduct[$CodeLine] = $datade;
                    }
                }
                $JsonDeducts = $NewJsonDeduct;
            }


            $cdhud_page245_obj->updateDeducts($idcdhud_page245, json_encode($JsonDeducts));
            /**/
            $cdhud_page3 = $cdhud_page3_obj->getAllcdhud_page3ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
            $update = 'updatePayoffLoan';
            if ($cdhud_page3) {
                $cdhud_page3 = $cdhud_page3[0];
                $cdhud_page3_obj->$update($cdhud_page3->getidcdhud_page3(), json_encode($array));
            } else {
                $cdhud_page3_obj->setidcdhud($cdhudTransaction->getidcdhud());
                $cdhud_page3_obj->$update($cdhud_page3_obj->getidcdhud_page3(), json_encode($array));
            }
            /**/
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $bank_obj = $GetClass->GetClass('bank');
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
                $idb = $cdhudTransaction->getbankaccount();
                $bank = $bank_obj->getbankById($idb);
                if ($bank) {
                    UpdateBanksBalances($idb);
                }
            }
            /**/
            echo json_encode($array);
        } else {
            echo 'Error : first Select Transaction';
        }
    } else {
        echo 'Error : An Array Has Expected';
    }
}

// 27
function GetSaveLineDeposits($theData) {
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        if ($array['idtransaction']) {
            $deposit_obj = $GetClass->GetClass('deposit');
            $transaction_obj = $GetClass->GetClass('transaction');
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $cdhud_page245_obj = $GetClass->GetClass('cdhud_page245');
            $DepositsTransaction = $deposit_obj->getAlldepositForColumnValue('idtransaction', $array['idtransaction']);
            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction['0'];
            } else {
                $cdhud_obj->setidtransaction($array['idtransaction']);
                if ($array['BankSelect']) {
                    $cdhud_obj->updatebankaccount($cdhud_obj->getidcdhud(), $array['BankSelect']);
                }
                $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                $cdhudTransaction = $cdhudTransaction[0];
            }
            $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
            $iddeposit = '';
            if ($array['Line']) {
                $idhudline = $array['Line'];
            }
            if ($array['hudlineDeposit']) {
                $idhudline = $array['hudlineDeposit'];
            }
            if ($DepositsTransaction) {
                foreach ($DepositsTransaction as $Deposit) {
                    if (is_object($Deposit)) {
                        $data = json_decode($Deposit->getdata(), true);
                        if ($data['hudlineDeposit'] == $idhudline) {
                            $iddeposit = $Deposit->getiddeposit();
                        }
                    }
                }
            }
            if ($array['Function'] == 'GetDataLine') {
                if ($iddeposit) {
                    $deposit = $deposit_obj->getdepositById($iddeposit);
                }
                if ($deposit) {
                    $array['Content'] = $deposit->getdata();
                } else {
                    $array['Content'] = 'Empty';
                }
            } else {
                if (!$iddeposit) {
                    $deposit_obj->setidtransaction($array['idtransaction']);
                    $iddeposit = $deposit_obj->getiddeposit();
                }
                $amount = $array['TotalDeposit'];
                if ($array['escrowthird']) {
                    $amount = $array['heldamount'];
                }
                if (!$array['IdContact9']) {
                    $array['IdContact9'] = CreaContactForCheck($array['FirstName8'], $array['LastName8'], $array['CompanyName8']);
                }
                if ($array['TotalDeposit']) {
                    $deposit_obj->updatetotal_amount($iddeposit, str_replace(array('$', ','), array('', ''), $amount));
                    $deposit_obj->updatedata($iddeposit, json_encode($array));
                    $deposit_obj->updatedeposittoaccountref($iddeposit, $cdhudTransaction->getbankaccount()); //idbank
                    $deposit_obj->updatetxnDate($iddeposit, date('Y-m-d H:i:s')); //today
                    $deposit_obj->updateidlogin($iddeposit, $idlogin);
                    $deposit_obj->updateidtransaction($iddeposit, $array['idtransaction']);
                    $deposit_obj->updateidcontact($iddeposit, $array['IdContact9']);
                    $deposit_obj->updatecreated_at($iddeposit, date('Y-m-d H:i:s'));
                    /**/
                    if ($array['hudlineDeposit'] == 'L-01') {
                        $escrow_obj = $GetClass->GetClass('escrow');
                        $escrow = $escrow_obj->getAllescrowForColumnValue('iddeposit', '"' . $iddeposit . '"');
                        if ($escrow) {
                            $escrow = $escrow[0];
                            ;
                            $idscrow = $escrow->getidescrow();
                        } else {
                            $escrow_obj->setiddeposit($iddeposit);
                            $idscrow = $escrow_obj->getidescrow();
                        }
                        $escrow_obj->updatetotal($idscrow, $amount);
                        $escrow_obj->updatebalance($idscrow, $amount);
                    }
                    /**/
                } else {
                    if ($iddeposit) {
                        $deposit_obj->deletedeposit($iddeposit);
                    }
                }
                if ($array['escrowthird']) {
                    if ($array['isdiscountselleragent'] == 'None') {
                        /* delete */
                        if ($cdhud_page245) {
                            if (is_array($cdhud_page245)) {
                                $cdhud_page245 = $cdhud_page245[0];
                            }
                            $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                            $JsonReccomDeduct = $cdhud_page245->getReccomDeduct();
                            if ($JsonReccomDeduct) {
                                $JsonReccomDeduct = json_decode($JsonReccomDeduct, true);
                                $NewJsonReccomDeduct = array();
                                foreach ($JsonReccomDeduct as $CodeLine => $dataLine) {
                                    if ($CodeLine != $idhudline) {
                                        $NewJsonReccomDeduct[$CodeLine] = $dataLine;
                                    }
                                }
                            }
                            $cdhud_page245_obj->updateReccomDeduct($idcdhud_page245, json_encode($NewJsonReccomDeduct));
                        }
                        /**/
                    } else {
                        /* add */
                        if ($cdhud_page245) {
                            if (is_array($cdhud_page245)) {
                                $cdhud_page245 = $cdhud_page245[0];
                            }
                            $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                        } else {
                            $cdhud_page245_obj->setidcdhud($cdhudTransaction->getidcdhud());
                            $idcdhud_page245 = $cdhud_page245_obj->getidcdhud_page245();
                        }
                        $JsonReccomDeduct = $cdhud_page245->getReccomDeduct();
                        if ($JsonReccomDeduct) {
                            $JsonReccomDeduct = json_decode($JsonReccomDeduct, true);
                            $JsonReccomDeduct[$idhudline] = $array['isdiscountselleragent'] . '||' . $array['heldamount'];
                        } else {
                            $JsonReccomDeduct = array();
                            $JsonReccomDeduct[$idhudline] = $array['isdiscountselleragent'] . '||' . $array['heldamount'];
                        }
                        $cdhud_page245_obj->updateReccomDeduct($idcdhud_page245, json_encode($JsonReccomDeduct));
                        /**/
                    }
                } else {
                    /* delete */
                    if ($cdhud_page245) {
                        if (is_array($cdhud_page245)) {
                            $cdhud_page245 = $cdhud_page245[0];
                        }
                        $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                        $JsonReccomDeduct = $cdhud_page245->getReccomDeduct();
                        if ($JsonReccomDeduct) {
                            $JsonReccomDeduct = json_decode($JsonReccomDeduct, true);
                            $NewJsonReccomDeduct = array();
                            foreach ($JsonReccomDeduct as $CodeLine => $dataLine) {
                                if ($CodeLine != $idhudline) {
                                    $NewJsonReccomDeduct[$CodeLine] = $dataLine;
                                }
                            }
                        }
                        $cdhud_page245_obj->updateReccomDeduct($idcdhud_page245, json_encode($NewJsonReccomDeduct));
                    }
                    /**/
                }
            }
            /**/
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $bank_obj = $GetClass->GetClass('bank');
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
                $idb = $cdhudTransaction->getbankaccount();
                if ($idb) {
                    $bank = $bank_obj->getbankById($idb);
                    if ($bank) {
                        UpdateBanksBalances($idb);
                    }
                }
            }
            /**/
            echo json_encode($array);
        } else {
            echo 'Error : first Select Transaction';
        }
    } else {
        echo 'Error : An Array Has Expected';
    }
}
function money_format($i = '', $number){
    $fmt = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );
    return $fmt->formatCurrency($number, "USD");
}
// 28
function DisbursmentData($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        if ($array['idtransaction']) {
            $deposit_obj = $GetClass->GetClass('deposit');
            $transaction_obj = $GetClass->GetClass('transaction');
            $contact_obj = $GetClass->GetClass('contact');
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $cdhud_page245_obj = $GetClass->GetClass('cdhud_page245');
            $purchase_obj = $GetClass->GetClass('purchase');
            $rolelist_obj = $GetClass->GetClass('rolelist');
            $login_users_obj = $GetClass->GetClass('login_users');
            $login_users = $login_users_obj->getlogin_usersById($idlogin);
            $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
            $requeriment_list_obj = $GetClass->GetClass('requeriment_list');
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            $DepositsTransaction = $deposit_obj->getAlldepositForcolumnvalue('idtransaction', $array['idtransaction']);
            $PurchasesTransaction = $purchase_obj->getAllpurchaseForColumnvalue('idtransaction', $array['idtransaction']);
            $ArrayReturn = array();
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
            }
            $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhudTransaction->getidcdhud());
            /* IdBuyer,IdSeller,IdLender */
            $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $array['idtransaction']);
            $IdBuyer = '';
            $IdSeller = '';
            $IdLender = '';
            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
            if ($transaction->getidrequirementslist()) {
                $requeriment_list = $requeriment_list_obj->getrequeriment_listById($transaction->getidrequirementslist());
            }
            $ControlBuyer = 'buyer';
            if (strtolower($requeriment_list->getname()) == 'refi') {
                $ControlBuyer = 'borrower';
            }
            if ($transaction_contact) {
                foreach ($transaction_contact as $t_c) {
                    if ($t_c && $t_c->getidcontact() && $t_c->getidrole()) {
                        $rolelist = $rolelist_obj->getrolelistById($t_c->getidrole());
                        if (strtolower($rolelist->getdescription()) == $ControlBuyer) {
                            if ($IdBuyer == '') {
                                $IdBuyer = $t_c->getidcontact();
                            }
                        }
                        if (strtolower($rolelist->getdescription()) == 'seller') {
                            if ($IdSeller == '') {
                                $IdSeller = $t_c->getidcontact();
                            }
                        }
                        if (strtolower($rolelist->getdescription()) == 'lender') {
                            if ($IdLender == '') {
                                $IdLender = $t_c->getidcontact();
                            }
                        }
                    }
                }
            }
            /**/
            if ($IdBuyer) {
                if ($array['CashtoCloseFromToBuyer'] == 'From') {
                    /* Deletecheck */
                    $idpurchase = '';
                    if ($PurchasesTransaction) {
                        foreach ($PurchasesTransaction as $purchase) {
                            if ($purchase->gethudline() == 'CashToCloseBuyer') {
                                $idpurchase = $purchase->getidpurchase();
                            }
                        }
                        if ($idpurchase) {
                            $purchase_obj->deletepurchase($idpurchase);
                            $idpurchase = '';
                        }
                    }
                    /**/
                    /* Deposit */
                    if ($DepositsTransaction) {
                        foreach ($DepositsTransaction as $Deposit) {
                            $data = json_decode($Deposit->getdata(), true);
                            if ($data['hudline'] == 'CashToCloseBuyer') {
                                $iddeposit = $Deposit->getiddeposit();
                            }
                        }
                    }
                    if (!$iddeposit) {
                        $deposit_obj->setidtransaction($array['idtransaction']);
                        $iddeposit = $deposit_obj->getiddeposit();
                    }
                    $Amount = $array['CashtoCloseBuyer'];
                    $Amount = str_replace('$', '', $Amount);
                    $Amount = str_replace(',', '', $Amount);
                    /**/
                    $ArrayData = array();
                    $ArrayData['hudline'] = 'CashToCloseBuyer';
                    $ArrayData['Amount'] = $Amount;
                    $ArrayData['Description'] = 'Cash To Close From Buyer';
                    $ArrayData['PaymentMethodRef'] = 'Cash';
                    $ArrayData['PaymentForLender'] = '1';
                    $ArrayData['idcontact'] = $IdBuyer;
                    /**/
                    $deposit_obj->updatetotal_amount($iddeposit, $Amount);
                    $deposit_obj->updatedata($iddeposit, json_encode($ArrayData));
                    $deposit_obj->updatedeposittoaccountref($iddeposit, $cdhudTransaction->getbankaccount());
                    $deposit_obj->updatetxnDate($iddeposit, date('Y-m-d H:i:s'));
                    $deposit_obj->updateidlogin($iddeposit, $idlogin);
                    $deposit_obj->updateidtransaction($iddeposit, $array['idtransaction']);
                    $deposit_obj->updateidcontact($iddeposit, $IdBuyer);
                    $deposit_obj->updatecreated_at($iddeposit, date('Y-m-d H:i:s'));
                    /**/
                } else {
                    /* delete deposit */
                    $iddeposit = '';
                    if ($DepositsTransaction) {
                        foreach ($DepositsTransaction as $Deposit) {
                            $data = json_decode($Deposit->getdata(), tru);
                            if ($data['hudline'] == 'CashToCloseBuyer') {
                                $iddeposit = $Deposit->getiddeposit();
                            }
                        }
                    }
                    if ($iddeposit) {
                        $deposit_obj->deletedeposit($iddeposit);
                        $iddeposit = '';
                    }
                    /**/
                    /* Check */
                    $idpurchase = '';
                    if ($PurchasesTransaction) {
                        foreach ($PurchasesTransaction as $purchase) {
                            if ($purchase->gethudline() == 'CashToCloseBuyer') {
                                $idpurchase = $purchase->getidpurchase();
                            }
                        }
                    }
                    $AmountLine = $array['CashtoCloseBuyer'];
                    $AmountLine = str_replace('$', '', $AmountLine);
                    $AmountLine = str_replace(',', '', $AmountLine);
                    if (!$idpurchase) {
                        $purchase_obj->setidtransaction($array['idtransaction']);
                        $idpurchase = $purchase_obj->getidpurchase();
                        $purchase_obj->updatehudline($idpurchase, 'CashToCloseBuyer');
                    }
                    $Line = array('TypeContact' => 'Person', 'Amount' => $AmountLine, 'IdContact' => $IdBuyer);
                    $Lines = array(json_encode($Line));
                    $account = json_encode($Lines);
                    /* Update Data Purchase */
                    $purchase_obj->updateaccount($idpurchase, $account);
                    $purchase_obj->updateexpensedate($idpurchase, date('Y-m-d'));
                    $purchase_obj->updateidlogin($idpurchase, $idlogin);
                    $purchase_obj->updatedescription($idpurchase, 'Cash To Close From Buyer');
                    $purchase_obj->updateamount($idpurchase, $AmountLine);
                    $purchase_obj->updatebankaccount($idpurchase, $cdhudTransaction->getbankaccount());
                    /**/
                }
            } else {
                $ArrayReturn['CashToBuyer'] = 'Error : Cash to Buyer not Created : Not Have Buyer';
            }
            if ($IdSeller) {
                if ($array['CashtoCloseFromToSeller'] == 'From') {
                    /* Deletecheck */
                    $idpurchase = '';
                    if ($PurchasesTransaction) {
                        foreach ($PurchasesTransaction as $purchase) {
                            if ($purchase->gethudline() == 'CashToCloseSeller') {
                                $idpurchase = $purchase->getidpurchase();
                            }
                        }
                        if ($idpurchase) {
                            $purchase_obj->deletepurchase($idpurchase);
                            $idpurchase = '';
                        }
                    }
                    /**/
                    /* Deposit */
                    $iddeposit = '';
                    if ($DepositsTransaction) {
                        foreach ($DepositsTransaction as $Deposit) {
                            $data = json_decode($Deposit->getdata(), true);
                            if ($data['hudline'] == 'CashToCloseSeller') {
                                $iddeposit = $Deposit->getiddeposit();
                            }
                        }
                    }
                    if (!$iddeposit) {
                        $deposit_obj->setidtransaction($array['idtransaction']);
                        $iddeposit = $deposit_obj->getiddeposit();
                    }
                    $Amount = $array['CashtoCloseSeller'];
                    $Amount = str_replace('$', '', $Amount);
                    $Amount = str_replace(',', '', $Amount);
                    /**/
                    $ArrayData = array();
                    $ArrayData['hudline'] = 'CashToCloseSeller';
                    $ArrayData['Amount'] = $Amount;
                    $ArrayData['Description'] = 'Cash To Close From Seller';
                    $ArrayData['PaymentMethodRef'] = 'Cash';
                    $ArrayData['PaymentForLender'] = '1';
                    $ArrayData['idcontact'] = $IdSeller;
                    /**/
                    $deposit_obj->updatetotal_amount($iddeposit, $Amount);
                    $deposit_obj->updatedata($iddeposit, json_encode($ArrayData));
                    $deposit_obj->updatedeposittoaccountref($iddeposit, $cdhudTransaction->getbankaccount());
                    $deposit_obj->updatetxnDate($iddeposit, date('Y-m-d H:i:s'));
                    $deposit_obj->updateidlogin($iddeposit, $idlogin);
                    $deposit_obj->updateidtransaction($iddeposit, $array['idtransaction']);
                    $deposit_obj->updateidcontact($iddeposit, $IdSeller);
                    $deposit_obj->updatecreated_at($iddeposit, date('Y-m-d H:i:s'));
                    /**/
                } else {
                    /* delete deposit */
                    $iddeposit = '';
                    if ($DepositsTransaction) {
                        foreach ($DepositsTransaction as $Deposit) {
                            $data = json_decode($Deposit->getdata(), tru);
                            if ($data['hudline'] == 'CashToCloseSeller') {
                                $iddeposit = $Deposit->getiddeposit();
                            }
                        }
                    }
                    if ($iddeposit) {
                        $deposit_obj->deletedeposit($iddeposit);
                        $iddeposit = '';
                    }
                    /**/
                    /* Check */
                    $idpurchase = '';
                    if ($PurchasesTransaction) {
                        foreach ($PurchasesTransaction as $purchase) {
                            if ($purchase->gethudline() == 'CashToCloseSeller') {
                                $idpurchase = $purchase->getidpurchase();
                            }
                        }
                    }
                    $AmountLine = $array['CashtoCloseSeller'];
                    $AmountLine = str_replace('$', '', $AmountLine);
                    $AmountLine = str_replace(',', '', $AmountLine);
                    if (!$idpurchase) {
                        $purchase_obj->setidtransaction($array['idtransaction']);
                        $idpurchase = $purchase_obj->getidpurchase();
                        $purchase_obj->updatehudline($idpurchase, 'CashToCloseSeller');
                    }
                    $Line = array('TypeContact' => 'Person', 'Amount' => $AmountLine, 'IdContact' => $IdSeller);
                    $Lines = array(json_encode($Line));
                    $account = json_encode($Lines);
                    /* Update Data Purchase */
                    $purchase_obj->updateaccount($idpurchase, $account);
                    $purchase_obj->updateexpensedate($idpurchase, date('Y-m-d'));
                    $purchase_obj->updateidlogin($idpurchase, $idlogin);
                    $purchase_obj->updatedescription($idpurchase, 'Cash To Close From Seller');
                    $purchase_obj->updateamount($idpurchase, $AmountLine);
                    $purchase_obj->updatebankaccount($idpurchase, $cdhudTransaction->getbankaccount());
                    /**/
                }
            } else {
                $ArrayReturn['CashToSeller'] = 'Error : Cash to Seller not Created : Not Have Seller';
            }

            $TotalDeduct = 0;
            if ($cdhud_page245) {
                $cdhud_page245 = $cdhud_page245[0];
                $JsonDeducts = $cdhud_page245->getDeducts();
                $JsonProceds = $cdhud_page245->getProceds();
                if ($JsonDeducts) {
                    $ArrayReturn['Deducts'] = '';
                    $JsonDeducts = json_decode($JsonDeducts, true); //print_r($JsonDeducts);
                    $data = '';
                    foreach ($JsonDeducts as $CodeLine => $datade) {
                        $AmountTemp = explode('||', $datade);
                        $Amount = str_replace(array('$', ','), array('', ''), $AmountTemp[1]);
                        $TotalDeduct = $TotalDeduct + $Amount;
                        $data .= '<div class="row">
                                                        <div class="col col-md-1"><label class="label">' . $CodeLine . '</label></div>
                                                        <div class="col col-md-8"><label class="label">' . $AmountTemp[0] . '</label></div>
                                                        <div class="col col-md-2"><label class="label" style="text-align: right;padding-right: 10% !important;">' . str_replace('USD ', '$', money_format('%i', $Amount)) . '</label></div>
                                                        <div class="col col-md-1"><span href="javascript:void(0);" data-id="' . $CodeLine . '" data-target="dedut" class="btn btn-primary deletededuct"><i class="fa fa-trash-o"></i></span></div>
                                                   </div>';
                    }
                    //print_r($data);
                    $ArrayReturn['Deducts'] = $data;
                }
                if ($JsonProceds) {
                    $ArrayReturn['Proceds'] = '';
                    $JsonProceds = json_decode($JsonProceds, true);
                    foreach ($JsonProceds as $CodeLine => $datapre) {
                        $AmountTemp = explode('||', $datapre);
                        $Amount = str_replace(array('$', ','), array('', ''), $AmountTemp[1]);
                        $TotalDeduct = $TotalDeduct - $Amount;
                        $ArrayReturn['Proceds'] .= '<div class="row">
                                                        <div class="col col-md-1"><label class="label">' . $CodeLine . '</label></div>
                                                        <div class="col col-md-8"><label class="label">' . $AmountTemp[0] . '</label></div>
                                                        <div class="col col-md-2"><label class="label" style="text-align: right;padding-right: 10% !important;">-' . str_replace('USD ', '$', money_format('%i', $Amount)) . '</label></div>
                                                        <div class="col col-md-1"><span href="javascript:void(0);" data-id="' . $CodeLine . '" data-target="dedut" class="btn btn-primary deletededuct"><i class="fa fa-trash-o"></i></span></div>
                                                   </div>';
                    }
                }
            }
            /**/
            $ArrayReturn['TotalDeducts'] = '<div class="row fortotalded"><br>
                                                        <div class="col col-md-9"><h4 style="color:black;">TOTAL DEDUCT</h4></div>
                                                        <div class="col col-md-2"><h4 style="color:black;text-align: right;padding-right: 10% !important;">' . str_replace('USD ', '$', money_format('%i', $TotalDeduct)) . '</h4></div>
                                                   </div>';
            /**/
            $WireFromLender = 0;
            if ($array['L-02_1']) {
                if ($IdLender) {
                    $WireFromLender = str_replace(array('$', ','), array('', ''), $array['L-02_1']);
                    $WireFromLender = $WireFromLender - $TotalDeduct;
                    if ($WireFromLender > 0) {
                        /* Deposit */
                        if ($DepositsTransaction) {
                            foreach ($DepositsTransaction as $Deposit) {
                                $data = json_decode($Deposit->getdata(), true);
                                if ($data['hudlineDeposit'] == 'WireFromLender') {
                                    $iddeposit = $Deposit->getiddeposit();
                                }
                            }
                        }
                        if (!$iddeposit) {
                            $deposit_obj->setidtransaction($array['idtransaction']);
                            $iddeposit = $deposit_obj->getiddeposit();
                        }
                        /**/
                        $ArrayData = array();
                        $ArrayData['hudlineDeposit'] = 'WireFromLender';
                        $ArrayData['Amount'] = $WireFromLender;
                        $ArrayData['Description'] = 'Wire From Lender';
                        $ArrayData['PaymentMethodRef'] = 'wirefromlender';
                        $ArrayData['PaymentForLender'] = '4';
                        $ArrayData['idcontact'] = $IdLender;
                        /**/
                        $deposit_obj->updatetotal_amount($iddeposit, $WireFromLender);
                        $deposit_obj->updatedata($iddeposit, json_encode($ArrayData));
                        $deposit_obj->updatedeposittoaccountref($iddeposit, $cdhudTransaction->getbankaccount());
                        $deposit_obj->updatetxnDate($iddeposit, date('Y-m-d H:i:s'));
                        $deposit_obj->updateidlogin($iddeposit, $idlogin);
                        $deposit_obj->updateidtransaction($iddeposit, $array['idtransaction']);
                        $deposit_obj->updateidcontact($iddeposit, $IdLender);
                        $deposit_obj->updatecreated_at($iddeposit, date('Y-m-d H:i:s'));
                        /**/
                    } else {
                        $ArrayReturn['WirefromLender'] = 'Error : Wire From Lender Not Created : Wire is Negativ';
                    }
                } else {
                    $ArrayReturn['WirefromLender'] = 'Error : Wire From Lender Not Created : Not Have Lender';
                }
            } else {
                $ArrayReturn['LoanAmount'] = 'Error : Not Have Loan Amount';
            }
            if ($PurchasesTransaction) {
                $haveE = '';
                $idBlockE = '';
                foreach ($PurchasesTransaction as $purchase) {
                    if (strpos($purchase->gethudline(), 'E-')) {
                        $haveE .= ' ' . $purchase->gethudline();
                    }
                    if ($purchase->gethudline() == 'BlockE') {
                        $idBlockE = $purchase->getidpurchase();
                    }
                }
                if ($haveE == '') {
                    $AmountLine = $array['TotalBuyerE'];
                    $AmountLine = str_replace('$', '', $AmountLine);
                    $AmountLine = str_replace(',', '', $AmountLine);
                    $AmountLine2 = $array['TotalSellerE'];
                    $AmountLine2 = str_replace('$', '', $AmountLine2);
                    $AmountLine2 = str_replace(',', '', $AmountLine2);
                    if (!$AmountLine) {
                        $AmountLine = 0;
                    }
                    if (!$AmountLine2) {
                        $AmountLine2 = 0;
                    }
                    $AmountLine = $AmountLine + $AmountLine2;
                    if ($AmountLine > 0) {
                        $idContact = '';
                        if (!$idBlockE) {
                            $purchase_obj->setidtransaction($array['idtransaction']);
                            $idBlockE = $purchase_obj->getidpurchase();
                            $purchase_obj->updatehudline($idBlockE, 'BlockE');
                        } else {
                            $purchaseE = $purchase_obj->getpurchaseById($idBlockE);
                            if ($purchaseE) {
                                $account = json_decode($purchaseE->getaccount(), true);
                                $account = json_decode($account[0], true);
                                $idContact = $account['IdContact'];
                            }
                        }
                        if (!$idContact) {
                            $idContact = CreaContactForCheck('', '', 'Digaconsulting Inc.');
                        }
                        $Line = array('TypeContact' => 'Company', 'Amount' => $AmountLine, 'IdContact' => $idContact);
                        $Lines = array(json_encode($Line));
                        $account = json_encode($Lines);
                        /* Update Data Purchase */
                        $purchase_obj->updateaccount($idBlockE, $account);
                        $purchase_obj->updateexpensedate($idBlockE, date('Y-m-d'));
                        $purchase_obj->updateidlogin($idBlockE, $idlogin);
                        $purchase_obj->updatedescription($idBlockE, 'Cash To Close From Buyer');
                        $purchase_obj->updateamount($idBlockE, $AmountLine);
                        $purchase_obj->updatebankaccount($idBlockE, $cdhudTransaction->getbankaccount());
                    }
                } else {
                    $ArrayReturn['BlockE'] = 'Error : Not Create Check For Block E, You Have checks on lines : ' . trim($haveE);
                }
            } else {
                /* Create Bloque E */
                $AmountLine = $array['TotalBuyerE'];
                $AmountLine = str_replace('$', '', $AmountLine);
                $AmountLine = str_replace(',', '', $AmountLine);
                $AmountLine2 = $array['TotalSellerE'];
                $AmountLine2 = str_replace('$', '', $AmountLine2);
                $AmountLine2 = str_replace(',', '', $AmountLine2);
                if (!$AmountLine) {
                    $AmountLine = 0;
                }
                if (!$AmountLine2) {
                    $AmountLine2 = 0;
                }
                $AmountLine = $AmountLine + $AmountLine2;
                if ($AmountLine > 0) {
                    if (!$idBlockE) {
                        $purchase_obj->setidtransaction($array['idtransaction']);
                        $idBlockE = $purchase_obj->getidpurchase();
                        $purchase_obj->updatehudline($idBlockE, 'BlockE');
                    } else {
                        $purchaseE = $purchase_obj->getpurchaseById($idBlockE);
                        if ($purchaseE) {
                            $account = json_decode($purchaseE->getaccount(), true);
                            $account = json_decode($account[0], true);
                            $idContact = $account['IdContact'];
                        }
                    }
                    if (!$idContact) {
                        $idContact = CreaContactForCheck('', '', 'Digaconsulting Inc.');
                    }
                    $Line = array('TypeContact' => 'Company', 'Amount' => $AmountLine, 'IdContact' => $idContact);
                    $Lines = array(json_encode($Line));
                    $account = json_encode($Lines);
                    /* Update Data Purchase */
                    $purchase_obj->updateaccount($idBlockE, $account);
                    $purchase_obj->updateexpensedate($idBlockE, date('Y-m-d'));
                    $purchase_obj->updateidlogin($idBlockE, $idlogin);
                    $purchase_obj->updatedescription($idBlockE, 'Cash To Close From Buyer');
                    $purchase_obj->updateamount($idBlockE, $AmountLine);
                    $purchase_obj->updatebankaccount($idBlockE, $cdhudTransaction->getbankaccount());
                }
                /**/
            }
            $PurchasesTransaction = $purchase_obj->getAllpurchaseForColumnValue('idtransaction', $array['idtransaction']);
            $DepositsTransaction = $deposit_obj->getAlldepositForcolumnvalue('idtransaction', $array['idtransaction']);
            /* SetChecksandDeposits */
            $TotalChecks = 0;
            if ($PurchasesTransaction) {
                $ArrayChecks = '';
                foreach ($PurchasesTransaction as $Purchase) {
                    $account = json_decode($Purchase->getaccount(), true);
                    $account = json_decode($account[0], true);
                    $contact = $contact_obj->getcontactById($account['IdContact']);
                    if ($account['TypeContact'] == 'Person') {
                        $contact = $contact->getfirstname() . ' ' . $contact->getsurname();
                    } else {
                        $contact = $contact->getcompany();
                    }
                    if (strpos($Purchase->getdescription(), '[voided]') !== false) {
                        $void = '<span class="" style="float: right;font-weight: bold; " data-id="' . $Purchase->getidpurchase() . '">[VOIDED]</span>';
                        $void2 = '<span href="javascript:void(0);" class="btn btn-primary anvoid-check" hudline="' . $Purchase->gethudline() . '" data-id="' . $Purchase->getidq() . '" original-title="UN-VOID"><i class="fa fa-reply"></i></span>';
                    } else {
                        $void = '';
                        $void2 = '<span href="javascript:void(0);" docnumber="' . $account['DocNumber'] . '" class="btn btn-primary void-check" data-amount="' . str_replace('USD ', '$', money_format('%i', $Purchase->getamount())) . '" hudline="' . $Purchase->gethudline() . '" data-id="' . $Purchase->getidpurchase() . '" original-title="VOID"><i class="fa fa-times"></i></span>';
                    }
                    if (!hasLevel($login_users->getuser_level(), 105)) {
                        $void = '';
                    }
                    //$RePrint = '<a href="javascript:void(0);" data-type="' . $account['TypeContact'] . '" data-docnumber="' . $account['DocNumber'] . '" class="button-text tip-s RePrint-check" data-amount="$' . str_replace('USD ', '', money_format('%i', $Purchase->getamount())) . '" hudline="' . $Purchase->gethudline() . '" data-idq="' . $Purchase->getidq() . '" original-title="Print Check" data-id="' . $Purchase->getidpurchase() . '"><span>Re-Print</span></a>';
                    if (!$Purchase->getidq()) {
                        $RePrint = '';
                    }
                    $delete = '<span style="margin-left:5px;" href="javascript:void(0);" class="btn btn-primary deletecheck" original-title="DELETE" data-update="' . $Purchase->getidpurchase() . '"  data-amount="' . str_replace('USD ', '', money_format('%i', $Purchase->getamount())) . '" data-idcontact="' . $account['IdContact'] . '" data-id="' . $Purchase->getidpurchase() . '" data-hud="' . $Purchase->gethudline() . '"><i class="fa fa-trash-o"></i></span>';
                    $array_status = array('', 'RT-Receipt Pending', 'RR-Receipt Received', 'RC-Receipt Cleared', 'RB-Combined Receipt', 'RX-Receipt Offset', '-R-Receipts Eliminated', 'VR-Void Receipt', 'WT-Wire Receipt Pending', 'WR-Wire Received', 'WC-Wire Receipt Cleared', 'WV-Wire Receipt Void', 'Wire Out', 'DB-Check Pending', '**-Check Written', 'CL-Check Cleared', 'CL-Check Cleared', 'CB-Combined Entry', 'CK-Combined Check');
                    $options = '<select class="statuscheck" name="statuscheck_' . $Purchase->getidpurchase() . '">';
                    foreach ($array_status as $key_o => $val_o) {
                        if ($account['status'] == $key_o) {
                            $options .= '<option value="' . $key_o . '" selected="selected">' . $val_o . '</option>';
                        } else {
                            $options .= '<option value="' . $key_o . '">' . $val_o . '</option>';
                        }
                    }
                    $options = $options . '</select>';
                    $Checkid = $Purchase->getidq();
                    if (!$Checkid) {
                        $Checkid = $Purchase->getidpurchase();
                    }
                    $ArrayChecks .= '<tr><td>' . $Checkid . '</td><td>' . $Purchase->gethudline() . '</td><td>' . date("m/d/Y", strtotime($Purchase->getexpensedate())) . '</td><td>' . $contact . $void . '</td><td style="text-align: right;padding-right: 1% !important;">' . str_replace('USD ', '$', money_format('%i', $Purchase->getamount())) . '</td><td>' . $void2 . $RePrint . $delete . '</td><td>' . $options . '</td></tr>';
                    $TotalChecks = $TotalChecks + $Purchase->getamount();
                }
                $ArrayReturn['Checks'] = $ArrayChecks;
                $ArrayReturn['TotalCheck'] = str_replace('USD ', '$', money_format('%i', $TotalChecks));
            } else {
                $ArrayReturn['Checks'] = '';
            }
            $TotalDeposit = 0;
            if ($DepositsTransaction) {
                $ArrayChecks = '';
                foreach ($DepositsTransaction as $Deposit) {
                    $data = $Deposit->getdata();
                    if ($data) {
                        $data = json_decode($data, true);
                        $type = 'deposit';
                        if ($data['hudlineDeposit'] == 'WireFromLender') {
                            $type = 'WireFromLender';
                        }
                        if ($data['hudlineDeposit'] == 'L-01') {
                            $type = 'Escrow Deposit';
                        }
                        $contact = $contact_obj->getcontactById($account['IdContact']);
                        $name = $contact->getfirstname() . ' ' . $contact->getmiddlename() . ' ' . $contact->getsurname();
                        if (!trim($name)) {
                            $name = $contact->getcompany();
                        }
                    }
                    if ($Deposit->getidcontact()) {
                        $contact = $contact_obj->getcontactById($Deposit->getidcontact());
                        $name = $contact->getfirstname() . ' ' . $contact->getmiddlename() . ' ' . $contact->getsurname();
                        if (!trim($name)) {
                            $name = $contact->getcompany();
                        }
                    } else {
                        $name = '';
                    }
                    $ArrayChecks .= '<div class="row formouseoverdeposit" style="border-top: 1px solid black;" data-hudline=' . $data['hudline'] . '>
                                        <div class="col col-md-1" style="padding-right:0px!important"><label class="input"><input readonly="readonly" style="color: black;background: transparent;border: 0;" value="RCP-' . $Deposit->getiddeposit() . '" data-id="' . $Deposit->getiddeposit() . '"></label></div>
                                        <div class="col col-md-2"><label class="input"><input readonly="readonly" style="color: black;background: transparent;border: 0;" value="' . date('m/d/Y', strtotime($Deposit->getcreated_at())) . '" data-id="' . $Deposit->getiddeposit() . '"></label></div>
                                        <div class="col col-md-2"><label class="input"><input readonly="readonly" style="color: black;background: transparent;border: 0;" name="ch' . $Deposit->getiddeposit() . '" data-id="' . $Deposit->getiddeposit() . '" value="' . $type . '"></label></div>
                                        <div class="col col-md-4"><label class="input"><input readonly="readonly" style="color: black;background: transparent;border: 0;" value="' . $name . '" data-id="' . $Deposit->getiddeposit() . '"></label></div>
                                        <div class="col col-md-2"><label class="input"><input readonly="readonly" style="color: black;background: transparent;border: 0;text-align: right;padding-right: 10% !important;" value="' . str_replace('USD ', '', money_format('%i', $Deposit->gettotal_amount())) . '" data-id="' . $Deposit->getiddeposit() . '"></label></div>
                                        <div class="col col-md-1"><span data-hudline="' . $data['hudline'] . '" data-id="' . $Deposit->getiddeposit() . '" data-target="deposit" class="btn btn-primary deletepaydep" data-deplender=""><i class="fa fa-trash-o"></i></span></div>
                                     </div>';
                    $TotalDeposit = $TotalDeposit + $Deposit->gettotal_amount();
                }
                $ArrayChecks .= '<div class="row " style="border-top: 1px solid black;" data-hudline=' . $data['hudline'] . '><br>
                                        <div class="col col-md-9" style="padding-right:0px!important"><h4 style="color:black;">Total Deposits</h4></div>
                                        <div class="col col-md-2"><h4 style="color:black;text-align: right;padding-right: 10% !important;">' . str_replace('USD ', '$', money_format('%i', $TotalDeposit)) . '</h4></div>
                                        <div class="col col-md-1"></div>
                                     </div>';
                $ArrayReturn['Deposits'] = $ArrayChecks;
            } else {
                $ArrayReturn['Deposits'] = '';
            }
            $ArrayReturn['DisbAmount'] = str_replace('USD ', '$', money_format('%i', $TotalDeposit - $TotalChecks));
            $ArrayReturn['Bank'] = $cdhudTransaction->getbankaccount();
            $ArrayReturn['currentloanamount'] = $array['L-02_1'];
            $ArrayReturn['forwirefromlender'] = str_replace('USD ', '$', money_format('%i', $WireFromLender));
            $ArrayReturn['totalcheck'] = str_replace('USD ', '$', money_format('%i', $TotalChecks));
            $ArrayReturn['forloandeduct_total'] = str_replace('USD ', '$', money_format('%i', $WireFromLender + $TotalDeduct));
            /**/
            /**/
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $bank_obj = $GetClass->GetClass('bank');
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', "'" . $array['idtransaction'] . "'");
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
                //print_r($cdhudTransaction);
                $idb = $cdhudTransaction->getbankaccount();
                if ($idb) {
                    $bank = $bank_obj->getbankById($idb);
                    if ($bank) {
                        UpdateBanksBalances($idb);
                    }
                }
            }
            /**/
            echo json_encode($ArrayReturn);
        } else {
            echo 'Error : first Select Transaction';
        }
    } else {
        echo 'Error : An Array Has Expected';
    }
}

// 29
function DeleteVoids($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idtransaction']) {
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $cdhud_page245_obj = $GetClass->GetClass('cdhud_page245');
            $purchase_obj = $GetClass->GetClass('purchase');
            $deposit_obj = $GetClass->GetClass('deposit');
            switch ($array['Operation']) {
                case 'Deduct':
                    $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
                    if ($cdhud) {
                        $cdhud = $cdhud[0];
                        $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhud->getidcdhud());
                        if ($cdhud_page245) {
                            if (is_array($cdhud_page245)) {
                                $cdhud_page245 = $cdhud_page245[0];
                            }
                            $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
                            $JsonDeducts = $cdhud_page245->getDeducts();
                            $JsonProceds = $cdhud_page245->getProceds();
                            if ($JsonDeducts) {
                                $JsonDeducts = json_decode($JsonDeducts, true);
                                $NewJsonDeduct = array();
                                foreach ($JsonDeducts as $CodeLine => $datade) {
                                    if ($CodeLine != $array['IdOperation']) {
                                        $NewJsonDeduct[$CodeLine] = $datade;
                                    }
                                }
                            }
                            if ($JsonProceds) {
                                $JsonProceds = json_decode($JsonProceds, true);
                                $NewJsonProceds = array();
                                foreach ($JsonProceds as $CodeLine => $datapre) {
                                    if ($CodeLine != $array['IdOperation']) {
                                        $NewJsonProceds[$CodeLine] = $datapre;
                                    }
                                }
                            }
                        }
                        $cdhud_page245_obj->updateDeducts($cdhud_page245->getidcdhud_page245(), json_encode($NewJsonDeduct));
                        $cdhud_page245_obj->updateProceds($cdhud_page245->getidcdhud_page245(), json_encode($NewJsonProceds));
                        echo 'Deduct Delete Successfully';
                    } else {
                        echo 'Error : Deducts Not found, Please Refresh';
                    }
                    break;
                case 'Deposit':
                    $deposit = $deposit_obj->getdepositById($array['IdOperation']);
                    if ($deposit) {
                        if ($deposit->getidq()) {
                            /* Delete first on QB */

                            /**/
                        }
                        $deposit_obj->deletedeposit($array['IdOperation']);
                        echo 'Deposit Delete Successfully';
                    } else {
                        echo 'Deposit Delete Successfully';
                    }
                    break;
                case 'Purchase':
                    $purchase = $purchase_obj->getpurchaseById($array['IdOperation']);
                    if ($purchase) {
                        if ($purchase->getidq()) {
                            /* Delete first on QB */

                            /**/
                        }
                        $purchase_obj->deletepurchase($array['IdOperation']);
                        echo 'Check Delete Successfully';
                    } else {
                        echo 'Check Delete Successfully';
                    }
                    break;
                case 'Void':
                    $purchase = $purchase_obj->getpurchaseById($array['IdOperation']);
                    if ($purchase) {
                        if ($purchase->getidq()) {
                            /* Update first on QB */

                            /**/
                        }
                        $description = str_replace('[voided]', '', $purchase->getdescription());
                        $purchase_obj->updatedescription($array['IdOperation'], $description . '[voided]');
                        $purchase_obj->updateamount($array['IdOperation'], 0.00);
                        echo 'Check void Successfully';
                    } else {
                        echo 'Error : Check Not found, Please Refresh';
                    }
                    break;
                case 'AnVoid':
                    $purchase = $purchase_obj->getpurchaseById($array['IdOperation']);
                    if ($purchase) {
                        if ($purchase->getidq()) {
                            /* Update first on QB */

                            /**/
                        }
                        $description = str_replace('[voided]', '', $purchase->getdescription());
                        $purchase_obj->updatedescription($array['IdOperation'], $description);
                        echo 'Check Anvoid Successfully';
                    } else {
                        echo 'Error : Check Not found, Please Refresh';
                    }
                    break;
            }
            /**/
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $bank_obj = $GetClass->GetClass('bank');
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
                $idb = $cdhudTransaction->getbankaccount();
                $bank = $bank_obj->getbankById($idb);
                if ($bank) {
                    UpdateBanksBalances($idb);
                }
            }
            /**/
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 30
function CreatePartie($theData) {
    $jSEND = new jSEND();
    $data_p = $theData;
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //var_dump($array); checkwelcome
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $contact_obj = $GetClass->GetClass('contact');
        $transaction_obj = $GetClass->GetClass('transaction');
        $rolelist_obj = $GetClass->GetClass('rolelist');
        $transaction_contact_obj = $GetClass->GetClass('transaction_contact');

        if (!$array['idcontacticv']) {
            $idContact = CreaContactForCheck($array['firstname'], $array['lastname'], $array['companyname']);
            if ($array['email']) {
                $contact_obj->updateemail($idContact, $array['email']);
            }
            if ($array['mobile']) {
                $contact_obj->updatemobile($idContact, $array['mobile']);
            }
            if ($array['phone']) {
                $contact_obj->updatephone($idContact, $array['phone']);
            }
        } else {
            $idContact = $array['idcontacticv'];
        }

        //}
        if ($idContact) {
            $tempor = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $array['idtransaction']);
            if ($tempor) {
                foreach ($tempor as $k0) {
                    if ($k0->getidcontact() == $idContact && $k0->getidrole() == $array['id_role']) {
                        $existe = 'sisi';
                    }
                }
                if ($existe == 'sisi') {
                    die('E: Contact and Party Repeated');
                } else {
                    $transaction_contact_obj->setidtransaction($array['idtransaction']);
                    $transaction_contact_obj->updateidcontact($transaction_contact_obj->getidtransaction_contact(), $idContact);
                    $transaction_contact_obj->updateidrole($transaction_contact_obj->getidtransaction_contact(), $array['id_role']);
                    $transaction_contact_obj->updateidlogin($transaction_contact_obj->getidtransaction_contact(), $idlogin);
                    $transaction_contact_obj->updateside($transaction_contact_obj->getidtransaction_contact(), $array['side']);
                    echo $idContact;
                }
            } else {
                $transaction_contact_obj->setidtransaction($array['idtransaction']);
                $transaction_contact_obj->updateidcontact($transaction_contact_obj->getidtransaction_contact(), $idContact);
                $transaction_contact_obj->updateidrole($transaction_contact_obj->getidtransaction_contact(), $array['id_role']);
                $transaction_contact_obj->updateidlogin($transaction_contact_obj->getidtransaction_contact(), $idlogin);
                $transaction_contact_obj->updateside($transaction_contact_obj->getidtransaction_contact(), $array['side']);
                echo $idContact;
            }
        } else {
            echo 'Error : An Error Has Ocurred Contact Not Found o Error on Create';
        }
        $rolelist = $rolelist_obj->getrolelistById($array['id_role']);
        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
        if ($rolelist->getname() == 'buyer' || $rolelist->getname() == 'Buyer') {
            if (!$transaction->getidcontactbuyer()) {
                $transaction_obj->updateidcontactbuyer($array['idtransaction'], $idContact);
            }
        }
        if ($rolelist->getname() == 'seller' || $rolelist->getname() == 'Seller') {
            if (!$transaction->getidcontactseller()) {
                $transaction_obj->updateidcontactseller($array['idtransaction'], $idContact);
            }
        }
    }
}

// 31
function SaveRequirementList($theData) {
    $jSEND = new jSEND();
    $data_p = $theData;
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //var_dump($array);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        /**/
        $log = 'LogModel.log';
        file_put_contents($log, json_encode($array), FILE_APPEND);
        /**/
        $requeriment_list_obj = $GetClass->GetClass('requeriment_list');
        if (is_numeric($array['ReqUpdate'])) {
            $requeriment_list = $requeriment_list_obj->getrequeriment_listById($array['ReqUpdate']);
            if ($requeriment_list) {
                $idreq = $array['ReqUpdate'];
            } else {
                $requeriment_list_obj->setnamer('Model1');
                $idreq = $requeriment_list_obj->getidrequeriment_list();
            }
        } else {
            $requeriment_list_obj->setnamer('Model1');
            $idreq = $requeriment_list_obj->getidrequeriment_list();
        }
        $requeriment_list_obj->updaterequerimentsjson($idreq, $array['ReqData']);
        echo 'Model Save Successfully';
    } else {
        echo 'Error : not have array';
    }
}

// 32
function GetSaveTask($theData) {
    $jSEND = new jSEND();
    $data_p = $theData;
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //var_dump($array);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        /**/
        $general_config_obj = $GetClass->GetClass('general_config');
        $general_config = $general_config_obj->getgeneral_configById(1);
        $officeinfo = json_decode($general_config->getechosign(), true);
        $requeriment_list_obj = $GetClass->GetClass('requeriment_list');
        $task_obj = $GetClass->GetClass('task');
        $task = $task_obj->gettaskById($array['z_id']);
        if ($officeinfo['TaskType'] == 'NoModel') {
            if ($array['Function'] == 'Get') {
                $options = array('' => 'Choose', 'Completed' => 'Completed', 'Completed-Blue' => 'N/A', 'Orded' => 'Ordered');
                $optionstatus = '';
                foreach ($options as $opkey => $opval) {
                    if ($opkey == $task->getstatus()) {
                        $optionstatus .= '<option value="' . $opkey . '" selected="selected">' . $opval . '</option>';
                    } else {
                        $optionstatus .= '<option value="' . $opkey . '">' . $opval . '</option>';
                    }
                }
                $arrayReturn = array();
                //$optionstatus = '<option value="">' . $task->getstatus() . '</option>';
                $arrayReturn['Function'] = 'Get';
                $arrayReturn['ForOrderInformation'] = '';
                $arrayReturn['ForHelpTask'] = '';
                $arrayReturn['location'] = $task->getsubject();
                $arrayReturn['z_id'] = $array['z_id'];
                $arrayReturn['status'] = $optionstatus;
                $taskReq = $task;
                if ($taskReq) {
                    $arrayReturn['updateTask'] = $taskReq->getidtask();
                    if ($taskReq->getstart_date()) {
                        $arrayReturn['startdate'] = date("m/d/Y", strtotime($taskReq->getstart_date()));
                    } else {
                        $arrayReturn['startdate'] = '';
                    }
                    if ($taskReq->getend_date()) {
                        $arrayReturn['enddate'] = date("m/d/Y", strtotime($taskReq->getend_date()));
                    } else {
                        $arrayReturn['enddate'] = '';
                    }
                    if ($taskReq->getoderdate()) {
                        $arrayReturn['orderdate'] = date("m/d/Y", strtotime($taskReq->getoderdate()));
                    } else {
                        $arrayReturn['orderdate'] = '';
                    }
                    if ($taskReq->getreceiveddate()) {
                        $arrayReturn['receiveddate'] = date("m/d/Y", strtotime($taskReq->getreceiveddate()));
                    } else {
                        $arrayReturn['receiveddate'] = '';
                    }
                    $arrayReturn['note_icv_2241'] = $taskReq->getnote();
                }
                echo json_encode($arrayReturn);
            } else {
                
            }
        } else {
            $tasks = $task_obj->getAlltaskForColumnValue('idtransaction', $array['idtransaction']);
            $taskReq = '';
            $arrayReturn = array();
            $arrayTasks = array();
            foreach ($tasks as $task) {
                $arrayTasks[$task->getz_id()] = $task->getstatus();
                if ($task->getz_id() == $array['z_id']) {
                    $taskReq = $task;
                }
            }
            $requeriment_list = $requeriment_list_obj->getAllrequeriment_listForColumnValue('namer', '"Model1"');
            $requeriment_list = $requeriment_list[0];
            $json = json_decode($requeriment_list->getrequerimentsjson(), true);
            $order = json_decode($json['Order'], true);
            $content = json_decode($json['Content'], true);
            $condition = '';
            if ($content['ConditionItem']) {
                $condition = json_decode($content['ConditionItem'], true);
            }

            if ($array['Function'] == 'Get') {
                if ($requeriment_list) {
                    $content = json_decode($content['Data' . $array['z_id']], true);
                    /* Status */
                    $next = json_decode($content['ChildItem'], true);
                    $optionstatus = '<option value="">Choose</option>';
                    foreach ($next as $stat => $vl) {
                        $selected = '';
                        if ($stat == $arrayTasks[$array['z_id']]) {
                            $selected = 'selected="selected"';
                        }
                        $optionstatus .= '<option value="' . $stat . '" ' . $selected . '>' . $stat . '</option>';
                    }
                    /**/
                    $arrayReturn['Function'] = 'Get';
                    /* Get Information */
                    $arrayReturn['ForOrderInformation'] = '';
                    if ($taskReq) {
                        $apiorders_obj = $GetClass->GetClass('apiorders');
                        $apiorders = $apiorders_obj->getAllapiordersForColumnValue('idtransaction', $array['idtransaction']);
                        if ($apiorders) {
                            foreach ($apiorders as $apiorder) {
                                if ($apiorder->getidtask() == $array['z_id']) {
                                    $idorder = $apiorder->getorderid();
                                }
                            }
                        }
                        if ($idorder) {
                            include_once 'FunctionsHelper.php';
                            $DataInfo = array();
                            $DataInfo['Type'] = 'GetStatus';
                            $DataInfo['idtransaction'] = $array['idtransaction'];
                            $DataInfo['IdOrder'] = $idorder;
                            $DataInfo[''] = '';
                            $responseTest = GetInfoElite($DataInfo);
                            $arrayReturn['ForOrderInformation'] = $responseTest;
                        }
                    }
                    /**/
                    $arrayReturn['ForHelpTask'] = $content['HelpItem'];
                    $arrayReturn['location'] = $content['NameItem'];
                    $arrayReturn['z_id'] = $array['z_id'];
                    $arrayReturn['status'] = $optionstatus;
                    if ($taskReq) {
                        $arrayReturn['updateTask'] = $taskReq->getidtask();
                        if ($taskReq->getstart_date()) {
                            $arrayReturn['startdate'] = date("m/d/Y", strtotime($taskReq->getstart_date()));
                        } else {
                            $arrayReturn['startdate'] = '';
                        }
                        if ($taskReq->getend_date()) {
                            $arrayReturn['enddate'] = date("m/d/Y", strtotime($taskReq->getend_date()));
                        } else {
                            $arrayReturn['enddate'] = '';
                        }
                        if ($taskReq->getoderdate()) {
                            $arrayReturn['orderdate'] = date("m/d/Y", strtotime($taskReq->getoderdate()));
                        } else {
                            $arrayReturn['orderdate'] = '';
                        }
                        if ($taskReq->getreceiveddate()) {
                            $arrayReturn['receiveddate'] = date("m/d/Y", strtotime($taskReq->getreceiveddate()));
                        } else {
                            $arrayReturn['receiveddate'] = '';
                        }
                        $arrayReturn['note_icv_2241'] = $taskReq->getnote();
                    }
                    echo json_encode($arrayReturn);
                } else {
                    echo 'Error : This Client Not Have Model';
                }
            } else {
                
            }
        }
        /**/
    } else {
        echo 'Error : not have array';
    }
}

// 33
function GetExcrowLetter($theData) {
    $jSEND = new jSEND();
    $data_p = $theData;
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $array = json_decode($theData, true); //var_dump($array);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $transaction_obj = $GetClass->GetClass('transaction');
        $file_office_obj = $GetClass->GetClass('file_office');
        $property_obj = $GetClass->GetClass('property');
        $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
        $contact_obj = $GetClass->GetClass('contact');
        $rolelist_obj = $GetClass->GetClass('rolelist');
        $deposit_obj = $GetClass->GetClass('deposit');
        $login_users_obj = $GetClass->GetClass('login_users');
        $office_obj = $GetClass->GetClass('office');
        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
        $externalnumber = $transaction->gettransactionnumber();
        if (strpos($externalnumber, 'THTX') === false) {
            $escrowOfficer = 'Gustavo Perona';
        } else {
            $escrowOfficer = 'Brandon Jordan';
        }
        $idlogin = $_SESSION['jigowatt']['user_id']; //echo '2';
        $data['{from}'] = $array['from'];
        $data['{escrowofficer}'] = $escrowOfficer;
        $data['{amount}'] = $array['amount'];
        $data['{escrowdeposit}'] = str_replace('$', '', $data['{escrowdeposit}']);
        /* echo 'Error : ';
          print_r($array);
          print_r($transaction); */
        if ($transaction) {
            $file_office = $file_office_obj->getAllfile_officeForColumnValue('name', '"escrowTemplate"');
            if ($file_office) {
                $data = array();
                $property = $property_obj->getpropertyById($transaction->getidproperty());
                if (is_object($property)) {
                    $data['propertyaddress'] = $property->get_StreetAddress() . ', ' . $property->get_City() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode();
                }
                /* escrow amount */
                $deposit = $deposit_obj->getAlldepositForColumnValue('idtransaction', $array['idtransaction']);
                if ($deposit) {
                    foreach ($deposit as $dep) {
                        $data2 = $dep->getdata();
                        if ($data2) {
                            $data2 = json_decode($data2, true);
                            if ($data2['hudlineDeposit'] == 'L-01') {
                                $data['escrowdeposit'] = str_replace('USD ', '', money_format('%i', $dep->gettotal_amount()));
                            }
                        }
                    }
                }
                /**/
                /**/
                $login_users = $login_users_obj->getlogin_usersById($idlogin);
                $data['username'] = $login_users->getnameu();
                $data['useremail'] = $login_users->getemail();
                $data['externalname'] = $transaction->gettransactionnumber();

                /**/
                /* All Buyers */
                $data['allbuyers'] = '';
                $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $array['idtransaction']);
                if ($transaction_contact) {
                    foreach ($transaction_contact as $t_c) {
                        if ($t_c->getidcontact() && $t_c->getidrole()) {
                            $rolelist = $rolelist_obj->getrolelistById($t_c->getidrole());
                            if (strtolower($rolelist->getname()) == 'buyer') {
                                $contlist = $contact_obj->getcontactById($t_c->getidcontact());
                                if ($contlist->getcompany()) {
                                    $name = $contlist->getcompany();
                                } else {
                                    $name = $contlist->getfirstname() . ' ' . $contlist->getsurname();
                                }
                                if ($data['allbuyers'] == '') {
                                    $data['allbuyers'] = $name;
                                } else {
                                    $data['allbuyers'] .= ', ' . $name;
                                }
                            }
                        }
                    }
                }
                /**/
                /**/
                $file = $office_obj->getofficeById(1);
                $data['officename2'] = $file->getname();
                $data['officeaddress1'] = $file->getaddress();
                $data['officeaddress2'] = $file->getstate() . ', ' . $file->getcity() . ', ' . $file->getzip();
                $data['officephone'] = $file->getphone();
                /**/
                /**/
                $_search = array();
                $_replace = array();
                foreach ($data as $key => $value) {
                    if (true) {
                        $tempKey = str_replace('*', '', $key);
                        $_search[] = '{' . $tempKey . '}';
                        $_replace[] = htmlentities($value);
                    }
                }
                $_search[] = 'empty';
                $_replace[] = '_________';
                /* echo 'Error :';
                  print_r($_search);
                  print_r($_replace); */
                /**/
                $file_office = $file_office[0];
                $contenido = $file_office->getcontent();
                $extension = 'docx';
                $zip_folder = "temp/" . $dbname . '/zip';
                $file_folder = "temp/" . $dbname;
                $zip = new ZipArchive;
                $name = 'ClolsingPackage';
                $rand_name = rand(100000, 1000000);
                if (!file_exists($zip_folder)) {
                    mkdir($zip_folder, 0775, true);
                }
                $source = $file_folder . '/' . $rand_name . '.' . $extension;
                $fp = fopen($source, 'w') or die('Error : create Source');
                fwrite($fp, $contenido);
                fclose($fp);
                if ($zip->open($source) === TRUE) {
                    $zip->extractTo($zip_folder . '/' . $rand_name);
                    $zip->close();
                    $tmp_document = $zip_folder . '/' . $rand_name . '/word/document.xml';
                    if (file_exists($tmp_document)) {
                        $fp = fopen($tmp_document, 'r');
                        $content = fread($fp, filesize($tmp_document));
                        fclose($fp);
                        $content = str_replace($_search, $_replace, $content);
                        file_put_contents($tmp_document, $content);
                        libxml_use_internal_errors(true);
                        $doc = simplexml_load_string($content);
                        $xml = explode("\n", $content);
                        file_put_contents('temp/logxmldocxErrors.txt', '');
                        $logError = '';
                        if (!$doc) {
                            $idlogin = $_SESSION['jigowatt']['user_id'];
                            if ($idlogin == 1) {
                                $errors = libxml_get_errors();
                                foreach ($errors as $error) {
                                    $logError .= display_xml_error($error, $xml);
                                }
                                file_put_contents('temp/logxmldocxErrors.txt', $logError . "\n", FILE_APPEND);
                                if ($logError) {
                                    die("Error, One error has ocurred, please try again after five minutes.");
                                }
                            }
                            libxml_clear_errors();
                        }
                        Zip($zip_folder . '/' . $rand_name, $source);
                        $fp = fopen($source, 'r');
                        $content = fread($fp, filesize($source));
                        fclose($fp);
                        $contenido = $content;
                        exec('rm -rf ' . $zip_folder . '/' . $rand_name);
                        echo $source;
                    } else {
                        echo 'Error, To Try unzip file';
                    }
                } else {
                    echo 'Error, Failed Open zip...' . $source;
                }
            } else {
                echo 'Error : Escrow Template Not Found';
            }
        } else {
            echo 'Error : Transaction Not Found';
        }
    } else {
        echo 'Error : not have array';
    }
}

function Zip($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')))
                continue;

            $file = realpath($file);

            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

// 34
function ChangeUnderwriter($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $data_p = $theData;
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //var_dump($array);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
        $rolelist_obj = $GetClass->GetClass('rolelist');
        $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $array['idtransaction']);
        $AllRolesUnderwriter = $rolelist_obj->getAllrolelistForcolumnValue('name', "'underwriter'");
        $underwriterlist = array();
        foreach ($AllRolesUnderwriter as $Role) {
            array_push($underwriterlist, $Role->getidrolelist());
        }
        //print_r($underwriterlist);
        if ($transaction_contact) {
            foreach ($transaction_contact as $t_c) {
                if (in_array($t_c->getidrole(), $underwriterlist) && $t_c->getidcontact() == $array['LastUnderwriter']) {
                    $idt_c = $t_c->getidtransaction_contact();
                }
            }
            if ($idt_c) {
                $transaction_contact_obj->updateidcontact($idt_c, $array['NewUnderwriter']);
                echo 'Update Successfully';
            } else {
                $transaction_contact_obj->setidtransaction($array['idtransaction']);
                $transaction_contact_obj->updateidcontact($transaction_contact_obj->getidtransaction_contact(), $array['NewUnderwriter']);
                $transaction_contact_obj->updateidrole($transaction_contact_obj->getidtransaction_contact(), $underwriterlist[0]);
                echo 'Set Successfully';
            }
        } else {
            $transaction_contact_obj->setidtransaction($array['idtransaction']);
            $transaction_contact_obj->updateidcontact($transaction_contact_obj->getidtransaction_contact(), $array['NewUnderwriter']);
            $transaction_contact_obj->updateidrole($transaction_contact_obj->getidtransaction_contact(), $underwriterlist[0]);
            echo 'Set Successfully';
        }
    } else {
        echo 'Error : not have array';
    }
}

// 35
function ChangeStatusT($theData) {
    $jSEND = new jSEND();
    $data_p = $theData;
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //var_dump($array);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $transaction_obj = $GetClass->GetClass('transaction');
        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
        if ($array['OperationC'] == 'Cancelled') {
            $transaction_obj->updateevents($array['idtransaction'], '{"cancelled":"' . date('m/d/Y') . '"}');
            $transaction_obj->updatestatus($array['idtransaction'], 'Cancelled');
            /* $task_list = $task->getAlltaskForColumnValue('idtransaction', $array['idtransaction']); //echo var_dump($task_list);
              foreach ($task_list as $t) {
              $task->updatestatus($t->getidtask(), 'Canceled');
              } */
            echo 'Transaction Cancelled';
        } elseif ($array['OperationC'] == 'Closed' || $array['OperationC'] == 'Closed-Issues') {
            $transaction_obj->updatestatus($array['idtransaction'], $array['OperationC']);
            $transaction_obj->updateevents($array['idtransaction'], '{"closed":"' . date('m/d/Y') . '"}');
            /* $task_list = $task->getAlltaskForColumnValue('idtransaction', $array[idtransaction]); //echo var_dump($task_list);
              foreach ($task_list as $t) {
              $task->updatestatus($t->getidtask(), 'Closed');
              } */
            $amount = 0;
            $obj_gen_conf = $GetClass->GetClass('general_config');
            $obj_gen_conf = $obj_gen_conf->getgeneral_configById(1);

            if (is_object($obj_gen_conf)) {
                $amount = $obj_gen_conf->gettransaction_price();
            }
            $idlogin = $_SESSION['jigowatt']['user_id'];
            $billing = $GetClass->GetClass('billing');
            $list = $billing->getAllbillingForColumnValue('type_billing', "'closed_transaction'");
            $billing_present = false;
            if (is_array($list) && count($list) > 0) {
                foreach ($list as $key => $g_conf) {
                    if ($g_conf->getidtransaction() == $array['idtransaction']) {
                        $billing_present = true;
                    }
                }
            }
            if (!$billing_present) {
                $billing->settype_billing('closed_transaction');
                $billing->updateidtransaction($billing->getidbilling(), $array['idtransaction']);
                $billing->updateamount($billing->getidbilling(), $amount);
                $billing->updateidlogin($billing->getidbilling(), $idlogin);
                $billing->updatequantity($billing->getidbilling(), 1);
            }
            echo 'Transaction Closed';
        } elseif ($array['OperationC'] == 'Ready to Close') {
            $transaction_obj->updatestatus($array['idtransaction'], 'Ready to Close');
        } else {
            $transaction_obj->updateevents($array['idtransaction'], '{"actived":"' . date('m/d/Y') . '"}');
            $transaction_obj->updatestatus($array['idtransaction'], 'Re-Activated');
            /* $task_list = $task->getAlltaskForColumnValue('idtransaction', $array[idtransaction]);
              foreach ($task_list as $t) {
              $task->updatestatus($t->getidtask(), 'In Progress');
              } */
            echo 'Transaction ' . $array['OperationC'];
        }
        if ($array['OperationC'] == 'Closed' || $array['OperationC'] == 'Closed-Issues' || $array['OperationC'] == 'Cancelled') {
            /* Delete on QB */
            if (quickbook_e_d() == 'enabled') {
                if ($transaction->getidqaccount() && $quickbooks_is_connected) {
                    $AccountService = new QuickBooks_IPP_Service_Account();
                    $Accounts = $AccountService->query($Context, $realm, "SELECT * FROM Account WHERE Id = '" . $transaction->getidqaccount() . "' ");
                    $Account = $Accounts[0];
                    if (is_object($Account)) {
                        if ($Account->getCurrentBalance() != 0) {
                            $purchase_obj = $GetClass->GetClass('purchase');
                            $deposit_obj = $GetClass->GetClass('deposit');
                            $IdqContact = GetCreateIdqContact2('', '', 'ContactForInactiveQB');
                            /* Get Bank Account */
                            $bankAccount = '';
                            $hud2014_obj = $GetClass->GetClass('hud2014');
                            $hud2014 = $hud2014_obj->getAllhud2014ForColumnValue('idtransaction', $transaction->getidtransaction());
                            if ($hud2014) {
                                $hud2014 = $hud2014[0];
                                $json = $hud2014->getjsoncontent();
                                $json = json_decode($json, true);
                                $bankAccount = $json['name_bank'];
                            } else {
                                /* no se de donde mas si no esta en el hud */

                                /**/
                            }
                            /**/
                            if ($Account->getCurrentBalance() < 0) {
                                /* create Check */
                                include_once 'quickbook2/docs/partner_platform/example_app_ipp_v3/PurchaseHelper.php';
                                $tmp = array();
                                $Lines = array();
                                $tmp['amount'] = floatval(str_replace('-', '', $Account->getCurrentBalance()));
                                $tmp['description'] = 'Amount For Complete Balance on Closed Transaction';
                                $tmp['account'] = $transaction->getidqaccount();
                                $tmp['hudline'] = '';
                                $tmp['typecontact'] = 'company';
                                $Lines[] = $tmp;
                                $params = array(
                                    'Amount' => str_replace('-', '', $Account->getCurrentBalance()),
                                    'DetailType' => 'AccountBasedExpenseLineDetail',
                                    'Description' => 'Amount For Complete Balance on Closed Transaction',
                                    'AccountRef' => $bankAccount,
                                    'BillableStatus' => 'NotBillable',
                                    'EntityRef' => $IdqContact,
                                    'DocNumber' => '',
                                    'TxnDate' => formatDatemysql(date('m/d/Y')),
                                    'PrivateNote' => '',
                                    'lines' => json_encode($Lines)
                                );

                                $purchase_obj->setpayee($IdqContact);
                                $IdMyCheck = $purchase_obj->getidpurchase();
                                $purchase_obj->updatepayee($IdMyCheck, $IdqContact);
                                $purchase_obj->updatedescription($IdMyCheck, 'Amount For Complete Balance on Closed Transaction');
                                $purchase_obj->updateamount($IdMyCheck, str_replace('-', '', $Account->getCurrentBalance()));
                                $purchase_obj->updateaccount($IdMyCheck, json_encode($Lines));
                                $purchase_obj->updatebankaccount($IdMyCheck, $bankAccount);
                                $purchase_obj->updatememo($IdMyCheck, '');
                                $purchase_obj->updateexpensedate($IdMyCheck, date('Y-m-d'));
                                $purchase_obj->updateidlogin($IdMyCheck, '1');
                                $purchase_obj->updateidtransaction($IdMyCheck, $transaction->getidtransaction());

                                $response = CreatePurchase($params);

                                $purchase_obj->updateidq($IdMyCheck, $response);

                                $Accounts = $AccountService->query($Context, $realm, "SELECT * FROM Account WHERE Id = '" . $transaction->getidqaccount() . "' ");
                                $Account = $Accounts[0];
                                /**/
                            } else {
                                /* Create Deposit */
                                include_once 'quickbook2/docs/partner_platform/example_app_ipp_v3/DepositHelper.php';
                                $tmp = array();
                                $tmp_all = array();

                                $tmp['Amount'] = str_replace('-', '', $Account->getCurrentBalance());
                                $tmp['Description'] = 'Amount For Complete Balance on Closed Transaction';
                                $tmp['Entity'] = $IdqContact;
                                $tmp['PaymentMethodRef'] = 1;
                                $tmp['PaymentForLender'] = 1;
                                $tmp['Account'] = $transaction->getidqaccount();
                                $tmp['CheckNum'] = '';
                                $tmp['idcontact'] = '';
                                $tmp['hudline'] = '';
                                $sum_amount += str_replace('-', '', $Account->getCurrentBalance());
                                $tmp_all[] = $tmp;

                                $params = array(
                                    'TotalAmt' => str_replace('-', '', $Account->getCurrentBalance()),
                                    'DetailType' => 'DepositLineDetail',
                                    'DepositToAccountRef' => $bankAccount,
                                    'TxnDate' => formatDatemysql(date('m/d/Y')),
                                    'PrivateNote' => '',
                                    'lines' => json_encode($tmp_all)
                                );
                                $response = CreateDeposit($params);

                                $deposit_obj->settotal_amount(str_replace('-', '', $Account->getCurrentBalance()));
                                $deposit_obj->updatedata($deposit_obj->getiddeposit(), json_encode($tmp_all));
                                $deposit_obj->updatedeposittoaccountref($deposit_obj->getiddeposit(), $bankAccount);
                                $deposit_obj->updatetxnDate($deposit_obj->getiddeposit(), formatDatemysql(date('m/d/Y')));
                                $deposit_obj->updateidlogin($deposit_obj->getiddeposit(), $_SESSION['jigowatt']['user_id']);
                                $deposit_obj->updateidtransaction($deposit_obj->getiddeposit(), $transaction->getidtransaction());
                                $deposit_obj->updatecreated_at($deposit_obj->getiddeposit(), date('Y-m-d H:i:s'));
                                $deposit_obj->updateidq($deposit_obj->getiddeposit(), $response);
                                /**/
                                $Accounts = $AccountService->query($Context, $realm, "SELECT * FROM Account WHERE Id = '" . $transaction->getidqaccount() . "' ");
                                $Account = $Accounts[0];
                            }
                        }
                        $Account->setActive('false');
                        if ($AccountService->update($Context, $realm, $Account->getId(), $Account)) {
                            //$resp = 'Updated';
                        } else {
                            //$resp = 'Error: ' . $AccountService->lastError($Context);
                            /* Message Error on Update */
                            sendGeneralEmail('Error on Delete Transaction on QB', '', 'ivan@titlehost.com,gus@titlehost.com,info@titlehost.com', 'Transaction ' . $array['idtransaction'] . ': Error on QB (' . $AccountService->lastError($Context) . ')- on Closed Transaction');
                            //sendInformationEmail();
                            /**/
                        }
                    } else {
                        /* Message Error Account not found */
                        sendGeneralEmail('Error on Delete Transaction on QB', '', 'ivan@titlehost.com,gus@titlehost.com,info@titlehost.com', 'Transaction ' . $array['idtransaction'] . ': Account not found on QB - on Closed Transaction');
                        //sendInformationEmail('Error on Delete Transaction on QB', '', 'ivan@titlehost.com,gus@titlehost.com,info@titlehost.com', 'Transaction ' . $array['idtransaction'] . ': Account not found on QB - on Closed Transaction');
                        /**/
                    }
                } else {
                    /* Message Error con idq o QB not conected */
                    sendGeneralEmail('Error on Delete Transaction on QB', '', 'ivan@titlehost.com,gus@titlehost.com,info@titlehost.com', 'Transaction ' . $array['idtransaction'] . ': not have  idq or QB is not Connected - on Closed Transaction');
                    //sendInformationEmail('Error on Delete Transaction on QB', '', 'ivan@titlehost.com,gus@titlehost.com,info@titlehost.com', 'Transaction ' . $array['idtransaction'] . ': not have  idq or QB is not Connected - on Closed Transaction');
                    /**/
                }
            }
            /**/
        }
    } else {
        echo 'Error : not have array';
    }
}

// 36
function SaveLoanTerm($theData) {
    $jSEND = new jSEND();
    $data_p = $theData;
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //var_dump($array);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $transaction_obj = $GetClass->GetClass('transaction');
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $cdhud_page2_obj = $GetClass->GetClass('cdhud_page2');
        $cdhud_page245_obj = $GetClass->GetClass('cdhud_page245');
        $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
        if ($cdhud) {
            $cdhud = $cdhud[0];
            $LoanJson = $cdhud->getLoanAmount();
            if ($LoanJson) {
                $LoanJson = json_decode($LoanJson, true);
                $LoanJson = $LoanJson['LoanAmountDialog'];
            } else {
                $LoanJson = '';
            }
            $idcdhud = $cdhud->getidcdhud();
            $ArrayLoanTerm = $cdhud->getLoanTerm();
            $cdhud_page2b = $cdhud_page2_obj->getAllcdhud_page2ForColumnValue('idcdhud', $idcdhud);
            $LenderCredit = '';
            /**/
            $cdhud_page245b = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $idcdhud);
            if ($cdhud_page245b) {
                $cdhud_page245b = $cdhud_page245b[0];
                $cdpage5b = json_decode($cdhud_page245b->getcdpage5(), true);
            }
            /**/
            if ($cdhud_page2b) {
                $cdhud_page2b = $cdhud_page2b[0];
                $LenderCredit = $cdhud_page2b->getLenderCredit();
            }
            if ($ArrayLoanTerm) {//print_r($ArrayLoanTerm);
                $ArrayLoanTerm = json_decode($ArrayLoanTerm, true);
                if ($array['Function'] == 'Get') {
                    $ArrayLoanTerm['CreditCalcAmount'] = $LoanJson;
                    $ArrayLoanTerm['forlendercredits'] = $LenderCredit;
                    if ($cdpage5b) {
                        $ArrayLoanTerm['total_payments'] = $cdpage5b['t558'];
                        $ArrayLoanTerm['finance_charge'] = $cdpage5b['t559'];
                        $ArrayLoanTerm['amounted_financed'] = $cdpage5b['t560'];
                        $ArrayLoanTerm['annual_percentage_rate'] = $cdpage5b['t561'];
                    }
                    echo json_encode($ArrayLoanTerm);
                    exit();
                }
            } else {
                $ArrayLoanTerm = array();
                $ArrayLoanTerm['CreditCalcAmount'] = $LoanJson;
                $ArrayLoanTerm['forlendercredits'] = $LenderCredit;
                if ($cdpage5b) {
                    $ArrayLoanTerm['total_payments'] = $cdpage5b['t558'];
                    $ArrayLoanTerm['finance_charge'] = $cdpage5b['t559'];
                    $ArrayLoanTerm['amounted_financed'] = $cdpage5b['t560'];
                    $ArrayLoanTerm['annual_percentage_rate'] = $cdpage5b['t561'];
                }
                if ($array['Function'] == 'Get') {
                    echo json_encode($ArrayLoanTerm);
                    exit();
                }
            }
            $ArrayProjectedPayments = $cdhud->getProjectedPayments();
            if ($ArrayProjectedPayments) {
                $ArrayProjectedPayments = json_decode($ArrayProjectedPayments, true);
            } else {
                $ArrayProjectedPayments = array();
            }
        } else {
            $cdhud_obj->setidtransaction($array['idtransaction']);
            $idcdhud = $cdhud_obj->getidcdhud();
            $ArrayLoanTerm = array();
            $ArrayProjectedPayments = array();
            if ($array['Function'] == 'Get') {
                echo 'Empty';
                exit();
            }
        }
        foreach ($array as $key => $value) {
            $ArrayLoanTerm[$key] = $value;
        }
        $ArrayProjectedPayments['payment1_a'] = $array['CreditMonthlyinterest'];
        $cdhud_obj->updateLoanTerm($idcdhud, json_encode($ArrayLoanTerm));
        $cdhud_obj->updateProjectedPayments($idcdhud, json_encode($ArrayProjectedPayments));
        /**/
        $cdhud_page2 = $cdhud_page2_obj->getAllcdhud_page2ForColumnValue('idcdhud', $idcdhud);
        if ($cdhud_page2) {
            $cdhud_page2 = $cdhud_page2[0];
            $idcdhud_page2 = $cdhud_page2->getidcdhud_page2();
        } else {
            $cdhud_page2_obj->setidcdhud($idcdhud);
            $idcdhud_page2 = $cdhud_page2_obj->getidcdhud_page2();
        }
        $cdhud_page2_obj->updateLenderCredit($idcdhud_page2, $array['forlendercredits']);
        /**/
        /**/
        $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $idcdhud);
        if ($cdhud_page245) {
            $cdhud_page245 = $cdhud_page245[0];
            $idcdhud_page245 = $cdhud_page245->getidcdhud_page245();
            if ($cdhud_page245->getcdpage5()) {
                $cdpage5 = json_decode($cdhud_page245->getcdpage5(), true);
            } else {
                $cdpage5 = array();
            }
        } else {
            $cdhud_page245_obj->setidcdhud($idcdhud);
            $idcdhud_page245 = $cdhud_page245_obj->getidcdhud_page245();
            $cdpage5 = array();
        }
        $cdpage5['t558'] = $array['total_payments'];
        $cdpage5['t559'] = $array['finance_charge'];
        $cdpage5['t560'] = $array['amounted_financed'];
        $cdpage5['t561'] = $array['annual_percentage_rate'];
        $cdhud_page245_obj->updatecdpage5($idcdhud_page245, json_encode($cdpage5));
        echo 'ok';
        /**/
    } else {
        echo 'Error : not have array';
    }
}

// 37
function PrintCD($theData) {
    $jSEND = new jSEND();
    $data_p = $theData;
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //var_dump($array);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $transaction_obj = $GetClass->GetClass('transaction');
        $file_office_obj = $GetClass->GetClass('file_office');
        $requeriment_list_obj = $GetClass->GetClass('requeriment_list');
        $property_obj = $GetClass->GetClass('property');
        $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
        $contact_obj = $GetClass->GetClass('contact');
        $cdhud_page245_obj = $GetClass->GetClass('cdhud_page245');
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $rolelist_obj = $GetClass->GetClass('rolelist');
        $ArrayReturn = array();
        /* Aditional Data */
        $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
        if ($cdhud) {
            $cdhud = $cdhud[0];
            $cdhud_page245 = $cdhud_page245_obj->getAllcdhud_page245ForColumnValue('idcdhud', $cdhud->getidcdhud());
            if ($cdhud_page245) {
                $cdhud_page245 = $cdhud_page245[0];
                $array['json_title_ins'] = $cdhud_page245->getTitleIns();
                $array['json_commi'] = $cdhud_page245->getREComm();
            }
        } else {
            die('Error : CD Not Found');
        }
        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
        $array['FileNumber'] = $transaction->gettransactionnumber();
        $ArrayReturn['ForCheckPrint'] = $transaction->gethomeownerassoc();
        $array['ClosingDate'] = date("m/d/Y", strtotime($transaction->getclosingdate()));

        $requeriment_list = $requeriment_list_obj->getrequeriment_listById($transaction->getidrequirementslist());
        $array['Purpose'] = $requeriment_list->getname();

        //PHP_EOL
        $property = $property_obj->getpropertyById($transaction->getidproperty());
        $propertyAddress = $property->get_StreetAddress();
        if ($property->get_StreetAddress2()) {
            $propertyAddress .= ' ' . $property->get_StreetAddress2();
        }
        $array['PropertyAddressHUD'] = $propertyAddress;
        $propertyAddress .= PHP_EOL . $property->get_City() . ', ' . $property->get_State() . ' - ' . $property->get_PostalCode();
        $array['PropertyAddressHUDb'] = $property->get_City() . ', ' . $property->get_State() . ' - ' . $property->get_PostalCode();
        $array['PropertyAddress'] = $propertyAddress;

        $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $array['idtransaction']);
        if ($transaction_contact) {
            $buyerAddress = '';
            $sellerAddress = '';
            $countbuyers = 1;
            $countsellers = 1;
            foreach ($transaction_contact as $t_c) {
                if ($t_c->getidcontact() && $t_c->getidrole()) {
                    $party = $contact_obj->getcontactById($t_c->getidcontact());
                    $rolelist = $rolelist_obj->getrolelistById($t_c->getidrole());
                    $ControlBuyer = 'buyer';
                    if (strtolower($requeriment_list->getname()) == 'refi') {
                        $ControlBuyer = 'borrower';
                    }
                    if (strtolower($rolelist->getname()) == $ControlBuyer) {
                        $Name = $party->getfirstname() . ' ' . $party->getsurname();
                        if (!trim($Name)) {
                            $Name = $party->getcompany();
                        }
                        if ($array['Buyers']) {
                            $array['Buyers'] .= PHP_EOL . $Name;
                            $array['BuyersHUD'] .= PHP_EOL . $Name;
                        } else {
                            $array['Buyers'] = $Name;
                            $array['BuyersHUD'] = $Name;
                        }
                        $array['buyer' . $countbuyers . 'footer'] = $Name;

                        if (!$buyerAddress) {
                            $buyerAddress = $party->getaddress1();
                            if ($party->getaddress2()) {
                                $buyerAddress .= ', ' . $party->getaddress1();
                            }
                            $array['BuyersHUDadd1'] = $buyerAddress;
                            $buyerAddress .= ' ' . $party->getcity() . ', ' . $party->getstate() . ' - ' . $party->getzip();
                            $array['BuyersHUDadd2'] = $party->getcity() . ', ' . $party->getstate() . ' - ' . $party->getzip();
                            if (trim(str_replace(array(',', '-'), '', $buyerAddress))) {
                                $buyerAddress = $buyerAddress;
                            } else {
                                $buyerAddress = '';
                            }
                        }
                    }
                    if (strtolower($rolelist->getname()) == 'seller') {
                        $Name = $party->getfirstname() . ' ' . $party->getsurname();
                        if (!trim($Name)) {
                            $Name = $party->getcompany();
                        }
                        if ($array['Sellers']) {
                            $array['Sellers'] .= PHP_EOL . $Name;
                            $array['SellersHUD'] .= PHP_EOL . $Name;
                        } else {
                            $array['Sellers'] = $Name;
                            $array['SellersHUD'] = $Name;
                        }
                        $array['seller' . $countsellers . 'footer'] = $Name;
                        $array['SellerName' . $countsellers] = $Name;
                        $countsellers++;
                        if (!$sellerAddress) {
                            $sellerAddress = $party->getaddress1();
                            if ($party->getaddress2()) {
                                $sellerAddress .= ', ' . $party->getaddress1();
                            }
                            if (trim(str_replace(array(',', '-'), '', $sellerAddress))) {
                                $array['SellersHUDadd1'] = $sellerAddress;
                            }
                            if (trim(str_replace(array(',', '-'), '', $party->getcity() . ', ' . $party->getstate() . ' - ' . $party->getzip()))) {
                                $array['SellersHUDadd2'] = $party->getcity() . ', ' . $party->getstate() . ' - ' . $party->getzip();
                            }
                            $sellerAddress .= ' ' . $party->getcity() . ', ' . $party->getstate() . ' - ' . $party->getzip();
                            if (trim(str_replace(array(',', '-'), '', $sellerAddress))) {
                                $sellerAddress = $sellerAddress;
                            } else {
                                $sellerAddress = '';
                            }
                        }
                    }
                    if (strtolower($rolelist->getname()) == 'lender') {
                        $Name = $party->getfirstname() . ' ' . $party->getsurname();
                        if (!trim($Name)) {
                            $Name = $party->getcompany();
                        }
                        $array['Lender'] = $Name;
                    }
                }
            }
        }
        $preliminary = false;
        if (strpos($array['typeCD'], 'preliminary') !== false) {
            $preliminary = 'MarcadeAgua';
            $array['typeCD'] = str_replace('preliminary', '', $array['typeCD']);
        }
        /* For HUD */
        if (strpos($array['typeCD'], 'HUD') !== false) {
            if ($preliminary) {
                $preliminary = 'MarcadeAgua2';
            }
            $ArrayHud = ConvertCDtoHUD($array);
            /**/
            $ArrayHud = json_decode($ArrayHud, true);
            $arrayFaltantes = $ArrayHud['ForComplete'];
            $ArrayReturn['Faltantes'] = $arrayFaltantes;
            /**/
            $array = array_merge($array, $ArrayHud['HUD']);
        }
        /**/
        /**/
        $NameResult = 'CD' . $array['idtransaction'];
        $file = $file_office_obj->getAllfile_officeForColumnValue('name', "'" . $array['typeCD'] . ".pdf'");
        if ($file) {
            $file = $file[0];

            $type = $file->gettype();
            $name = $file->getname();
            $name = str_replace('.pdf', '', $name);
            $url = 'temp/' . trim($name) . trim($type);
            unlink($url);
            $fh = fopen($url, 'w') or die("can't open file");
            $stringData = $file->getcontent();
            fwrite($fh, $stringData);
            fclose($fh);
        } else {
            die('Error : CD Template Not found');
        }
        $pdf_form_url = $url;
        $fdf_data_strings = $array;
        $fdf_data_names = array();
        $fields_hidden = array();
        $fields_readonly = array();
        $ivan1 = forge_fdf($pdf_form_url, $fdf_data_strings, $fdf_data_names, $fields_hidden, $fields_readonly);
        $dir = "temp/" . $NameResult . ".fdf";
        file_put_contents($dir, $ivan1);
        $Executable = "pdftk temp/" . trim($name) . ".pdf fill_form temp/" . $NameResult . ".fdf output temp/" . $NameResult . ".pdf flatten";
        try {
            shell_exec($Executable);
            sleep(1);
            if (file_exists('temp/' . $NameResult . '.pdf')) {
                unlink($dir);
                unlink($url);
                if ($preliminary) {
                    $file_pre = $file_office_obj->getAllfile_officeForColumnValue('name', "'" . $preliminary . ".pdf'");
                    if ($file_pre) {
                        $file_pre = $file_pre[0];
                        $url = 'temp/MarcadeAgua.pdf';
                        unlink($url);
                        $fh = fopen($url, 'w') or die("can't open file");
                        $stringData = $file_pre->getcontent();
                        fwrite($fh, $stringData);
                        fclose($fh);
                        $exe5 = 'pdftk temp/' . $NameResult . '.pdf stamp temp/MarcadeAgua.pdf output temp/' . $NameResult . '-preliminary.pdf';
                        shell_exec($exe5);
                        $ArrayReturn['path'] = 'temp/' . $NameResult . '-preliminary.pdf';
                        echo json_encode($ArrayReturn);
                    } else {
                        //echo 'temp/' . $NameResult . '.pdf';
                        $ArrayReturn['path'] = 'temp/' . $NameResult . '.pdf';
                        echo json_encode($ArrayReturn);
                    }
                } else {
                    //echo 'temp/' . $NameResult . '.pdf';
                    $ArrayReturn['path'] = 'temp/' . $NameResult . '.pdf';
                    echo json_encode($ArrayReturn);
                }
            } else {
                echo'Error : Not found pdf';
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        /**/
    } else {
        echo 'Error : not have array';
    }
}

function ConvertCDtoHUD($array) {
    $GetClass = GetClassPsToDb();

    $ArrayForHud = array();
    switch ($array['LoanType']) {
        case 'FHA':$ArrayForHud['Radio Button1'] = 'FHA';
            break;
        case 'VA':$ArrayForHud['Radio Button1'] = 'VA';
            break;
        case 'CONVUNIN':$ArrayForHud['Radio Button1'] = 'CONVUNIN';
            break;
        case 'other':$ArrayForHud['Radio Button1'] = 'CONVINS';
            break;
    }
    $arrayFaltantes = array();
    $ArrayForHud['t5'] = $array['FileNumber'];
    $ArrayForHud['t15'] = $array['LoanID'];
    $ArrayForHud['Text428'] = $array['MIC'];
    $ArrayForHud['allbuyers'] = $array['BuyersHUD'];
    $ArrayForHud['allsellers'] = $array['SellersHUD'];
    $ArrayForHud['buyer5'] = $array['BuyersHUDadd1'];
    $ArrayForHud['buyer6'] = $array['BuyersHUDadd2'];
    $ArrayForHud['seller5'] = $array['SellersHUDadd1'];
    $ArrayForHud['seller6'] = $array['SellersHUDadd2'];
    $ArrayForHud['t10'] = $array['Lender'];
    $ArrayForHud['property_location'] = $array['PropertyAddressHUD'];
    $ArrayForHud['property_locationb'] = $array['PropertyAddressHUDb'];
    $ArrayForHud['t4'] = $array['SettlementAgent'];
    $ArrayForHud['Text434'] = ''; //$array[''];
    $ArrayForHud['t2'] = $array['ClosingDate'];

    $ArrayForHud['t414'] = $array['K-01_1'];
    $ArrayForHud['t415'] = $array['K-02_1'];
    $ArrayForHud['Text439'] = $array['K-03_1'];
    $ArrayForHud['Text440'] = $array['K-04_a'];
    $ArrayForHud['Text441'] = $array['K-04_1'];
    $ArrayForHud['Text442'] = $array['K-05_a'];
    $ArrayForHud['Text443'] = $array['K-05_1'];
    $ArrayForHud['Text442_1'] = $array['K-06_a'];
    $ArrayForHud['Text443_1'] = $array['K-06_1'];
    $ArrayForHud['Text442_2'] = $array['K-07_a'];
    $ArrayForHud['Text443_2'] = $array['K-07_1'];
    /* falta */

    $ArrayForHud['t422'] = $array['K-08_b'];
    $ArrayForHud['t423'] = $array['K-08_c'];
    $ArrayForHud['t424'] = $array['K-08_1'];
    $ArrayForHud['t425'] = $array['K-09_b'];
    $ArrayForHud['t426'] = $array['K-09_c'];
    $ArrayForHud['t427'] = $array['K-09_1'];
    $ArrayForHud['t428'] = $array['K-10_b'];
    $ArrayForHud['t429'] = $array['K-10_c'];
    $ArrayForHud['t430'] = $array['K-10_1'];
    $ArrayForHud['Text454'] = $array['K-11_a'] . ' ' . $array['K-11_b'] . ' to ' . $array['K-11_c'];
    $ArrayForHud['Text455'] = $array['K-11_1'];
    $ArrayForHud['Text456'] = $array['K-12_a'];
    $ArrayForHud['Text457'] = $array['K-12_1'];
    $ArrayForHud['Text458'] = $array['K-13_a'];
    $ArrayForHud['Text459'] = $array['K-13_1'];
    $ArrayForHud['Text460'] = $array['K-14_a'];
    $ArrayForHud['Text461'] = $array['K-14_1'];
    $ArrayForHud['Text460_1'] = $array['K-15_a'];
    $ArrayForHud['Text461_1'] = $array['K-15_1'];
    $ArrayForHud['Text462'] = $array['TotalK'];
    /* falta */

    $ArrayForHud['t478'] = $array['L-01_1'];
    $ArrayForHud['t480'] = $array['L-02_1'];
    $ArrayForHud['t481'] = $array['L-03_1'];
    $ArrayForHud['Text467'] = $array['L-04_a'];
    $ArrayForHud['Text468'] = $array['L-04_1'];
    $ArrayForHud['Text469'] = 'Seller Credits'; //$array[''];
    $ArrayForHud['Text470'] = $array['L-05_1'];
    $ArrayForHud['Text471'] = $array['L-06_a'];
    $ArrayForHud['Text472'] = $array['L-06_1'];
    $ArrayForHud['Text473'] = $array['L-07_a'];
    $ArrayForHud['Text474'] = $array['L-07_1'];
    $ArrayForHud['Text475'] = $array['L-08_a'];
    $ArrayForHud['Text476'] = $array['L-08_1'];
    $ArrayForHud['Text477'] = $array['L-09_a'];
    $ArrayForHud['Text478'] = $array['L-09_1'];
    $ArrayForHud['Text477_1'] = $array['L-10_a'];
    $ArrayForHud['Text478_1'] = $array['L-10_1'];
    $ArrayForHud['Text477_2'] = $array['L-11_a'];
    $ArrayForHud['Text478_2'] = $array['L-11_1'];
    /* falta */

    $ArrayForHud['t495'] = $array['L-12_b'];
    $ArrayForHud['t496'] = $array['L-12_c'];
    $ArrayForHud['t497'] = $array['L-12_1'];
    $ArrayForHud['t498'] = $array['L-13_b'];
    $ArrayForHud['t499'] = $array['L-13_c'];
    $ArrayForHud['t500'] = $array['L-13_1'];
    $ArrayForHud['t501'] = $array['L-14_b'];
    $ArrayForHud['t502'] = $array['L-14_c'];
    $ArrayForHud['t503'] = $array['L-14_1'];
    $ArrayForHud['Text489'] = $array['L-15_a'];
    $ArrayForHud['Text490'] = $array['L-15_1'];
    $ArrayForHud['Text491'] = $array['L-16_a'];
    $ArrayForHud['Text492'] = $array['L-16_1'];
    $ArrayForHud['Text493'] = $array['L-17_a'];
    $ArrayForHud['Text494'] = $array['L-17_1'];
    $ArrayForHud['Text503'] = $array['TotalL'];

    $ArrayForHud['Text505'] = $array['TotalK_2'];
    $ArrayForHud['Text506'] = $array['TotalL_2'];
    $ArrayForHud['Text509'] = $array['CashtoCloseBuyer'];
    if ($array['CashtoCloseFromToBuyer'] == 'From') {
        $ArrayForHud['Radio Button2'] = 'From';
    } else {
        $ArrayForHud['Radio Button2'] = 'To';
    }

    $ArrayForHud['t443'] = $array['M-01_1'];
    $ArrayForHud['t444'] = $array['M-02_1'];
    $ArrayForHud['Text512'] = $array['M-03_a'];
    $ArrayForHud['Text513'] = $array['M-03_1'];
    $ArrayForHud['Text514'] = $array['M-04_a'];
    $ArrayForHud['Text515'] = $array['M-04_1'];
    $ArrayForHud['Text516'] = $array['M-05_a'];
    $ArrayForHud['Text517'] = $array['M-05_1'];
    $ArrayForHud['Text516_1'] = $array['M-06_a'];
    $ArrayForHud['Text517_1'] = $array['M-06_1'];
    $ArrayForHud['Text516_2'] = $array['M-07_a'];
    $ArrayForHud['Text517_2'] = $array['M-07_1'];
    /* falta M08 */
    if ($array['M-08_1']) {
        $arrayFaltantes[] = 'M-08';
    }
    /**/
    $ArrayForHud['t457'] = $array['M-09_b'];
    $ArrayForHud['t458'] = $array['M-09_c'];
    $ArrayForHud['t459'] = $array['M-09_1'];
    $ArrayForHud['t460'] = $array['M-10_b'];
    $ArrayForHud['t461'] = $array['M-10_c'];
    $ArrayForHud['t462'] = $array['M-10_1'];
    $ArrayForHud['t463'] = $array['M-11_b'];
    $ArrayForHud['t464'] = $array['M-11_c'];
    $ArrayForHud['t465'] = $array['M-11_1'];
    $ArrayForHud['Text528'] = $array['M-12_a'] . ' ' . $array['M-12_b'] . ' to ' . $array['M-12_c'];
    $ArrayForHud['Text529'] = $array['M-12_1'];
    $ArrayForHud['Text530'] = $array['M-13_a'];
    $ArrayForHud['Text531'] = $array['M-13_1'];
    $ArrayForHud['Text532'] = $array['M-14_a'];
    $ArrayForHud['Text533'] = $array['M-14_1'];
    $ArrayForHud['Text534'] = $array['M-15_a'];
    $ArrayForHud['Text535'] = $array['M-15_1'];
    $ArrayForHud['Text534_1'] = $array['M-16_a'];
    $ArrayForHud['Text535_1'] = $array['M-16_1'];
    $ArrayForHud['Text536'] = $array['TotalM'];

    $ArrayForHud['Text575'] = $array['TotalN'];

    $ArrayForHud['t511'] = $array['N-01_1'];
    $ArrayForHud['Text539'] = $array['N-02_1'];
    $ArrayForHud['t513'] = $array['N-03_1'];
    $ArrayForHud['504_a'] = $array['N-04_a'];
    $ArrayForHud['t514'] = $array['N-04_1'];
    $ArrayForHud['505_a'] = $array['N-05_a'];
    $ArrayForHud['t515'] = $array['N-05_1'];
    $ArrayForHud['Text543'] = $array['N-06_a'];
    $ArrayForHud['Text544'] = $array['N-06_1'];
    $ArrayForHud['Text545'] = $array['N-07_a'];
    $ArrayForHud['Text546'] = $array['N-07_1'];
    $ArrayForHud['Text547'] = 'Seller Credits'; //$array[''];
    $ArrayForHud['Text548'] = $array['N-08_1'];
    $ArrayForHud['Text549'] = $array['N-09_a'];
    $ArrayForHud['Text550'] = $array['N-09_1'];
    $ArrayForHud['Text549_1'] = $array['N-10_a'];
    $ArrayForHud['Text550_1'] = $array['N-10_1'];
    $ArrayForHud['Text549_2'] = $array['N-11_a'];
    $ArrayForHud['Text550_2'] = $array['N-11_1'];
    $ArrayForHud['Text549_3'] = $array['N-12_a'];
    $ArrayForHud['Text550_3'] = $array['N-12_1'];
    /* falta N13 */
    if ($array['N-13_1']) {
        $arrayFaltantes[] = 'N-13';
    }
    /**/
    $ArrayForHud['t531'] = $array['N-14_b'];
    $ArrayForHud['t532'] = $array['N-14_c'];
    $ArrayForHud['t533'] = $array['N-14_1'];
    $ArrayForHud['t534'] = $array['N-15_b'];
    $ArrayForHud['t535'] = $array['N-15_c'];
    $ArrayForHud['t536'] = $array['N-15_1'];
    $ArrayForHud['t537'] = $array['N-16_b'];
    $ArrayForHud['t538'] = $array['N-16_c'];
    $ArrayForHud['t539'] = $array['N-16_1'];
    $ArrayForHud['Text561'] = $array['N-17_a'];
    $ArrayForHud['Text562'] = $array['N-17_1'];
    $ArrayForHud['Text563'] = $array['N-18_a'];
    $ArrayForHud['Text564'] = $array['N-18_1'];
    $ArrayForHud['Text565'] = $array['N-19_a'];
    $ArrayForHud['Text566'] = $array['N-19_1'];

    $ArrayForHud['Text577'] = $array['TotalM_2'];
    $ArrayForHud['Text578'] = $array['TotalN_2'];
    $ArrayForHud['Text581'] = $array['CashtoCloseSeller'];
    if ($array['CashtoCloseFromToSeller'] == 'From') {
        $ArrayForHud['Radio Button3'] = 'FromS';
    } else {
        $ArrayForHud['Radio Button3'] = 'ToS';
    }
    /* pagina 2 */
    $CamposDiscriminate = array();
    /* Recommm */
    $json_recomm = $array['json_commi'];
    if ($json_recomm) {
        $json_recomm = json_decode($json_recomm, true);
        if ($json_recomm) {
            $AmountComm1 = str_replace(array('$', ','), '', $json_recomm['AmountCommission1Real']);
            if (!$AmountComm1) {
                $AmountComm1 = 0;
            }

            $AmountComm2 = str_replace(array('$', ','), '', $json_recomm['AmountCommission2Real']);
            if (!$AmountComm2) {
                $AmountComm2 = 0;
            }

            $total = $AmountComm1 + $AmountComm2;
            $total = str_replace('USD ', '$', money_format('%i', ($total)));

            $ArrayForHud['Text582'] = str_replace('USD ', '', money_format('%i', ($AmountComm1)));
            if ($json_recomm['TypeContact3'] == 'Company') {
                $ArrayForHud['Text583'] = $json_recomm['CompanyName3'];
            } else {
                $ArrayForHud['Text583'] = $json_recomm['FirstName3'] . ' ' . $json_recomm['LastName3'];
            }

            $ArrayForHud['Text584'] = str_replace('USD ', '', money_format('%i', ($AmountComm2)));
            //$ArrayForHud['Text585'] = $json_recomm['inputbroker2b'];
            if ($json_recomm['TypeContact4'] == 'Company') {
                $ArrayForHud['Text585'] = $json_recomm['CompanyName4'];
            } else {
                $ArrayForHud['Text585'] = $json_recomm['FirstName4'] . ' ' . $json_recomm['LastName4'];
            }
            $amountBuyers = 0;
            $amountSellers = 0;
            if ($json_recomm['TargetCommission1'] == '1' || $json_recomm['TargetCommission1'] == '2') {
                $amountBuyers = $amountBuyers + $AmountComm1;
            } else {
                if ($json_recomm['TargetCommission1'] == '3' || $json_recomm['TargetCommission1'] == '4') {
                    $amountSellers = $amountSellers + $AmountComm1;
                }
            }
            if ($json_recomm['TargetCommission2'] == '1' || $json_recomm['TargetCommission2'] == '2') {
                $amountBuyers = $amountBuyers + $AmountComm2;
            } else {
                if ($json_recomm['TargetCommission2'] == '3' || $json_recomm['TargetCommission2'] == '4') {
                    $amountSellers = $amountSellers + $AmountComm2;
                }
            }
            if ($amountBuyers > 0) {
                $ArrayForHud['Text587'] = str_replace('USD ', '$', money_format('%i', ($amountBuyers)));
            }
            if ($amountSellers > 0) {
                $ArrayForHud['Text588'] = str_replace('USD ', '$', money_format('%i', ($amountSellers)));
            }

            /**/
            $texto_700 = '';
            $CuadroPorci = str_replace(array('$', ','), array('', ''), $json_recomm['ComissionAmount1']);
            if (!$CuadroPorci) {
                $CuadroPorci = 0;
            }
            $CuadroPorci2 = str_replace(array('$', ','), array('', ''), $json_recomm['ComissionAmount2']);
            if (!$CuadroPorci2) {
                $CuadroPorci2 = 0;
            }
            if ($json_recomm['CommisionType1'] == $json_recomm['CommisionType2']) {//$('#ctipo4').val() == $('#ctipo42').val()){
                if ($json_recomm['CommisionType1'] == 'percentage') {//limpia_string
                    $texto_700 = $array['SalesPrice'] . ' @ ' . ($CuadroPorci + $CuadroPorci2) . ' = ' . $total;
                } else {
                    $texto_700 = $array['SalesPrice'] . ' / ' . str_replace('USD ', '$', money_format('%i', ($CuadroPorci + $CuadroPorci2))) . ' = ' . $total;
                }
            } else {
                $texto_700 = $array['SalesPrice'] . ' / ' . str_replace('USD ', '$', money_format('%i', ($CuadroPorci + $CuadroPorci2))) . ' = ' . $total;
            }
            $ArrayForHud['Text4'] = $texto_700;
            /**/
            /* For Discriminate */
            array_push($CamposDiscriminate, $json_recomm['LineCommission1']);
            array_push($CamposDiscriminate, $json_recomm['LineCommission2']);
            /**/
        }
    }
    /**/
    /* Title Ins */
    $json_Title = $array['json_title_ins'];
    if ($json_Title) {
        $json_Title = json_decode($json_Title, true);
        if ($json_Title) {
            /* namecompany */
            $office_obj = $GetClass->GetClass('office');
            $office = $office_obj->getofficeById('1');
            $nameoffice = $office->getname();
            /**/
            /* company/under */
            if ($json_Title['totileins']) {
                $contact_obj = $GetClass->GetClass('contact');
                $under = $contact_obj->getcontactById($json_Title['totileins']);
                if ($under) {
                    $nameoffice = $nameoffice . '/' . $under->getcompany();
                }
            }
            /**/
            /* new last */
            $ArrayForHud['Text700'] = $office->getname();
            if ($under) {
                $ArrayForHud['Text704'] = $under->getcompany();
            }
            $ArrayForHud['Text700_1'] = $json_Title['value_foroffice'];
            $ArrayForHud['Text704_1'] = $json_Title['value_underw'];
            /**/
            $ownertarget = false;
            if (strpos($json_Title['selectins2a_target'], 'Buyer') !== false) {
                //$ArrayForHud['Text705'] = $json_Title['value_owner'];
                //$ArrayForHud['Text704'] = $nameoffice;
                //$ownertarget = true;
            }
            if (strpos($json_Title['selectins2a_target'], 'Seller') !== false) {
                //$ArrayForHud['Text706'] = $json_Title['value_owner'];
                //$ArrayForHud['Text704'] = $nameoffice;
                //$ownertarget = true;
            }
            if ($ownertarget) {
                foreach ($array_indices as $indice => $values) {
                    if ($json_Title['selectins2'] == $indice) {
                        //array_push($CamposDiscriminate,$indice);
                    }
                }
            }
            $lendertarget = false;
            if ($json_Title['ValuePredef']) {
                if ($json_Title['check_SDBC']) {
                    if (strpos($json_Title['selectins3a_target'], 'Buyer') !== false) {
                        //$ArrayForHud['extra42'] = $json_Title['ValuePredef'];
                        //$ArrayForHud['Text700'] = $nameoffice;
                        //$lendertarget = true;
                    }
                    if (strpos($json_Title['selectins3a_target'], 'Seller') !== false) {
                        //$ArrayForHud['extra43'] = $json_Title['ValuePredef'];
                        //$ArrayForHud['Text700'] = $nameoffice;
                        //$lendertarget = true;
                    }
                } else {
                    if (strpos($json_Title['selectins3a_target'], 'Buyer') !== false) {
                        //$ArrayForHud['extra42'] = $json_Title['value_lender'];
                        //$ArrayForHud['Text700'] = $nameoffice;
                        //$lendertarget = true;
                    }
                    if (strpos($json_Title['selectins3a_target'], 'Seller') !== false) {
                        //$ArrayForHud['extra43'] = $json_Title['value_lender'];
                        //$ArrayForHud['Text700'] = $nameoffice;
                        //$lendertarget = true;
                    }
                }
            } else {
                if (strpos($json_Title['selectins3a_target'], 'Buyer') !== false) {
                    //$ArrayForHud['extra42'] = $json_Title['value_lender'];
                    //$ArrayForHud['Text700'] = $nameoffice;
                    //$lendertarget = true;
                }
                if (strpos($json_Title['selectins3a_target'], 'Seller') !== false) {
                    //$ArrayForHud['extra43'] = $json_Title['value_lender'];
                    //$ArrayForHud['Text700'] = $nameoffice;
                    //$lendertarget = true;
                }
            }
            if ($lendertarget) {
                foreach ($array_indices as $indice => $values) {
                    if ($json_Title['selectins3'] == $indice) {
                        //array_push($CamposDiscriminate,$indice);
                    }
                }
            }
        }
    }
    /**/

    /* Bloque 800 */
    $L800 = 0;
    $L801 = array();
    $array800 = array(array('Text592', 'extra1', 'extra2'), array('Text594', 'extra3', 'extra4'), array('803_extra', 'Text601', 'extra5'), array('Text603', 'Text604', 'extra6'), array('Text606', 'Text607', 'extra7'), array('Text609', 'Text610', 'extra8'), array('Text612', 'Text613', 'extra9'), array('Text615', 'Text616', 'extra10'), array('Text618', 'Text619', 'extra11'), array('Text621', 'Text622', 'extra12'), array('Text624', 'Text625', 'extra13'));
    /* Search A */
    for ($i = 1; $i <= 10; $i++) {
        if ($i < 10) {
            $indc = '0' . $i;
        } else {
            $indc = $i;
        }
        if (!in_array('A-' . $indc, $CamposDiscriminate)) {
            if ($array['A-' . $indc . '_1'] || $array['A-' . $indc . '_3']) {
                if ($array800[$L800]) {
                    $ArrayForHud[$array800[$L800][1]] = MergeMoneda($array['A-' . $indc . '_1'], $array['A-' . $indc . '_2']);
                    $ArrayForHud[$array800[$L800][2]] = MergeMoneda($array['A-' . $indc . '_3'], $array['A-' . $indc . '_4']);
                    if ($indc == '01') {
                        $ArrayForHud[$array800[$L800][0]] = $array['A-' . $indc . '_a'] . ' % of Loan Amount (Points)';
                    } else {
                        $ArrayForHud[$array800[$L800][0]] = $array['A-' . $indc . '_a'];
                    }
                    $L800++;
                } else {
                    $arrayFaltantes[] = 'A-' . $indc;
                }
            }
        }
    }
    /**/
    /* Search B */
    for ($i = 1; $i <= 12; $i++) {
        if ($i < 10) {
            $indc = '0' . $i;
        } else {
            $indc = $i;
        }
        if (!in_array('B-' . $indc, $CamposDiscriminate)) {
            if ($array['B-' . $indc . '_1'] || $array['B-' . $indc . '_3']) {
                if ($array800[$L800]) {
                    $ArrayForHud[$array800[$L800][1]] = MergeMoneda($array['B-' . $indc . '_1'], $array['B-' . $indc . '_2']);
                    $ArrayForHud[$array800[$L800][2]] = MergeMoneda($array['B-' . $indc . '_3'], $array['B-' . $indc . '_4']);
                    $ArrayForHud[$array800[$L800][0]] = $array['B-' . $indc . '_a'] . ' to ' . $array['B-' . $indc . '_b'];
                    $L800++;
                } else {
                    $arrayFaltantes[] = 'B-' . $indc;
                }
            }
        }
    }
    /**/
    /* Search C (1100) */
    $array1100 = array(array('t1101', 'Text679', 'Text677', 'extra31'), array('t1102', 'Text679_2', 'extra32', 'Text682'), array('t1103', 'Text683', 'Text684', 'extra33'), array('t1104', 'Text686', 'extra34', 'extra35'), array('t1105', 'Text690', 'extra36', 'extra37'), array('t1106', 'Text693', 'extra38', 'extra39'), array('t1107', 'Text697', 'extra40', 'extra41'), array('Text707', '110_sus', 'Text708', 'Text709'), array('Text710', '111_sus', 'Text711', 'Text712'), array('1112text', '112_sus', '1112ta', '1112tb'));
    $L1100 = 0;
    for ($i = 1; $i <= 12; $i++) {
        if ($i < 10) {
            $indc = '0' . $i;
        } else {
            $indc = $i;
        }
        if (!in_array('C-' . $indc, $CamposDiscriminate)) {
            if ($array['C-' . $indc . '_1'] || $array['C-' . $indc . '_3']) {
                if ($array1100[$L1100]) {
                    $ArrayForHud[$array1100[$L1100][1]] = MergeMoneda($array['C-' . $indc . '_1'], $array['C-' . $indc . '_2']);
                    $ArrayForHud[$array1100[$L1100][2]] = MergeMoneda($array['C-' . $indc . '_3'], $array['C-' . $indc . '_4']);
                    $ArrayForHud[$array1100[$L1100][0]] = $array['C-' . $indc . '_a'] . ' to ' . $array['C-' . $indc . '_b'];
                    $L1100++;
                } else {
                    $arrayFaltantes[] = 'C-' . $indc;
                }
            }
        }
    }
    /**/
    /* Search H (1300) */
    $array1300 = array(array('Text737_a', 'Text738_a', 'Text735', 'extra49'), array('Text737', 'Text738', 'Text739', 'Text740'), array('Text741', 'Text742', 'Text743', 'Text744'), array('Text745', '1304_sus', 'Text746', 'Text747'), array('Text748', '1305_sus', 'Text749', 'Text750'), array('1306text', '1306_sus', '1306ta', '1306tb'), array('1307text', '1307_sus', '1307ta', '1307tb'), array('1308text', '1308_sus', '1308ta', '1308tb'), array('1308text_1', '1308_sus_1', '1308ta_1', '1308tb_1'), array('1308text_2', '1308_sus_2', '1308ta_2', '1308tb_2'));
    $L1300 = 0;
    //print_r($CamposDiscriminate);
    for ($i = 1; $i <= 12; $i++) {
        if ($i < 10) {
            $indc = '0' . $i;
        } else {
            $indc = $i;
        }
        if (!in_array('H-' . $indc, $CamposDiscriminate)) {
            if ($array['H-' . $indc . '_1'] || $array['H-' . $indc . '_3']) {
                if ($array1300[$L1300]) {
                    $ArrayForHud[$array1300[$L1300][2]] = MergeMoneda($array['H-' . $indc . '_1'], $array['H-' . $indc . '_2']);
                    $ArrayForHud[$array1300[$L1300][3]] = MergeMoneda($array['H-' . $indc . '_3'], $array['H-' . $indc . '_4']);
                    $ArrayForHud[$array1300[$L1300][0]] = $array['H-' . $indc . '_a'];
                    $ArrayForHud[$array1300[$L1300][1]] = $array['H-' . $indc . '_b'];
                    $L1300++;
                } else {
                    $arrayFaltantes[] = 'H-' . $indc;
                }
            }
        }
    }
    /**/
    /**/
    /* 900 */
    $array900 = array(array('Text633', 'Text634', 'Text631', 'extra14'), array('Text637', 'Text638', 'Text635', 'extra15'), array('Text641_1', 'Text639', 'extra16'), array('Text641', 'Text642', 'extra17'), array('Text641_b', 'Text642_b', 'extra17_b'), array('Text641_b_1', 'Text642_b_1', 'extra17_b_1'));
    $L900 = 0;

    for ($i = 1; $i <= 7; $i++) {
        if ($i < 10) {
            $indc = '0' . $i;
        } else {
            $indc = $i;
        }
        if (!in_array('A-' . $indc, $CamposDiscriminate)) {
            if ($array['F-' . $indc . '_1'] || $array['F-' . $indc . '_3']) {
                if ($array900[$L900]) {
                    if ($indc == '01' || $indc == '02') {
                        $ArrayForHud[$array900[$L900][2]] = MergeMoneda($array['F-' . $indc . '_1'], $array['F-' . $indc . '_2']);
                        $ArrayForHud[$array900[$L900][3]] = MergeMoneda($array['F-' . $indc . '_3'], $array['F-' . $indc . '_4']);
                        $ArrayForHud[$array900[$L900][0]] = $array['F-' . $indc . '_b'];
                        $ArrayForHud[$array900[$L900][1]] = $array['F-' . $indc . '_c'];
                    } else {
                        $ArrayForHud[$array900[$L900][1]] = MergeMoneda($array['F-' . $indc . '_1'], $array['F-' . $indc . '_2']);
                        $ArrayForHud[$array900[$L900][2]] = MergeMoneda($array['F-' . $indc . '_3'], $array['F-' . $indc . '_4']);
                        $ArrayForHud[$array900[$L900][0]] = $array['F-' . $indc . '_a'];
                    }
                    $L900++;
                } else {
                    $arrayFaltantes[] = 'F-' . $indc;
                }
            }
        }
    }

    /**/
    /* Search G (1000) */
    $array1000 = array(array('extra_1005_4', 'Text647_1', 'Text648_1', 'Text645', 'extra18'), array('extra_1005_3', 'Text647', 'Text648', 'extra19', 'extra20'), array('extra_1005_2', 'Text652', 'Text653', 'extra21', 'extra22'), array('extra_1005_1', 'Text657', 'Text658', 'extra23', 'extra24'), array('extra_1005', 'Text662', 'Text663', 'extra25', 'extra26'), array('extra_1006', 'Text667', 'Text668', 'extra27', 'extra28'), array('extra_1006_b', 'Text667_b', 'Text668_b', 'extra29', 'extra30'));
    $L1000 = 0;
    for ($i = 1; $i <= 12; $i++) {
        if ($i < 10) {
            $indc = '0' . $i;
        } else {
            $indc = $i;
        }
        if (!in_array('A-' . $indc, $CamposDiscriminate)) {
            if ($array['G-' . $indc . '_1'] || $array['G-' . $indc . '_3']) {
                if ($array1000[$L1000]) {
                    if ($L1000 < 8) {
                        $ArrayForHud[$array1000[$L1000][0]] = $array['G-' . $indc . '_a'];
                        $ArrayForHud[$array1000[$L1000][1]] = $array['G-' . $indc . '_b'];
                        $ArrayForHud[$array1000[$L1000][2]] = $array['G-' . $indc . '_c'];

                        $ArrayForHud[$array1000[$L1000][3]] = MergeMoneda($array['G-' . $indc . '_1'], $array['G-' . $indc . '_2']);
                        $ArrayForHud[$array1000[$L1000][4]] = MergeMoneda($array['G-' . $indc . '_3'], $array['G-' . $indc . '_4']);
                    } else {
                        if ($L1000 == 8) {
                            $ArrayForHud[$array1000[$L1000][0]] = 'Aggregate Adjustment';
                        } else {
                            $ArrayForHud[$array1000[$L1000][0]] = $array['G-' . $indc . '_a'];
                        }
                        $ArrayForHud[$array1000[$L1000][3]] = MergeMoneda($array['G-' . $indc . '_1'], $array['G-' . $indc . '_2']);
                        $ArrayForHud[$array1000[$L1000][4]] = MergeMoneda($array['G-' . $indc . '_3'], $array['G-' . $indc . '_4']);
                    }
                    $L1000++;
                } else {
                    $arrayFaltantes[] = 'G-' . $indc;
                }
            }
        }
    }
    /**/
    /* 1200 E */
    $L1200 = 0;

    if (($array['E-01_1'] || $array['E-01_3']) && $L1200 < 6) {
        $ArrayForHud['Text714'] = MergeMoneda($array['E-01_1'], $array['E-01_2']);
        $ArrayForHud['extra44'] = MergeMoneda($array['E-01_3'], $array['E-01_4']);
        $ArrayForHud['Text716'] = $array['E-01_a'];
        $ArrayForHud['Text717'] = $array['E-01_b'];
        $ArrayForHud['Text718'] = '';
    } else {
        if ($L1200 >= 6) {
            $arrayFaltantes[] = 'E-01';
        }
    }
    $array1200 = array(array('Text724', 'Text725', 'extra45', 'Text720'), array('Text728', 'Text729', 'Text722', 'extra46'), array('extra_1204a', 'Text725a', 'extra47', 'Text727'), array('extra_1206a', 'Text725b', 'extra48', 'Text731'), array('extra_1206a_1', 'Text725b_1', 'extra48_1', 'Text731_1'));

    for ($i = 2; $i <= 7; $i++) {
        if (($array['E-0' . $i . '_1'] || $array['E-0' . $i . '_3'])) {
            if ($array1200[$L1200]) {
                $ArrayForHud[$array1200[$L1200][2]] = MergeMoneda($array['E-0' . $i . '_1'], $array['E-0' . $i . '_2']);
                $ArrayForHud[$array1200[$L1200][3]] = MergeMoneda($array['E-0' . $i . '_3'], $array['E-0' . $i . '_4']);
                $ArrayForHud[$array1200[$L1200][0]] = $array['E-0' . $i . '_a'];
                $ArrayForHud[$array1200[$L1200][1]] = $array['E-0' . $i . '_b'];
            } else {
                $arrayFaltantes[] = 'E-0' . $i;
            }
            $L1200++;
        }
    }
    /**/
    $ArrayForHud['Text751'] = MergeMoneda($array['TotalJ_1'], $array['TotalJ_2']);
    $ArrayForHud['Text752'] = MergeMoneda($array['TotalJ_3'], $array['TotalJ_4']);
    //$ArrayForHud[''] = $array[''];

    $arrayReturn = array();
    $arrayReturn['HUD'] = $ArrayForHud;
    $arrayReturn['ForComplete'] = $arrayFaltantes;
    return json_encode($arrayReturn);
}

function MergeMoneda($amount1, $amount2) {
    $amount2 = 0;
    $amount1 = str_replace(array('$', ','), array('', ''), $amount1);
    $amount2 = str_replace(array('$', ','), array('', ''), $amount2);
    if (!$amount1) {
        $amount1 = 0;
    }
    if (!$amount2) {
        $amount2 = 0;
    }
    if ($amount1 + $amount2) {
        return str_replace('USD ', '$', money_format('%i', ($amount1 + $amount2)));
    } else {
        return '';
    }
}

function ReplaceString($String) {
    $return = preg_replace('/\s\s+/', ' ', $String);
    $return = str_replace('&amp;', 'and', $return);
    $return = str_replace('amp;', '', $return);
    //$return = preg_replace('/[^ A-Za-z0-9_-ñÑ\/\.\$\,]/', '', $return);
    $return = preg_replace('/\s\s+/', ' ', $return);
    $return2 = '';
    for ($i = 0; $i <= strlen($return) - 1; $i++) {
        $test = $return[$i];
        if (ctype_alpha($test) || is_numeric($test) || $test == ' ' || $test == '.' || $test == ',' || $test == '$' || $test == '/' || $test == '-' || $test == '_' || $test == '(' || $test == ')' || $test == '[' || $test == ']' || $test == '+' || $test == '*' || $test == '%' || $test == '#' || $test == '@' || $test == '"' || $test == '&' || $test == '=' || $test == '?' || $test == '¿' || $test == '¡' || $test == '!' || $test == ';' || $test == ':') {
            $return2 .= $return[$i];
        } else {
            $return2 .= ' ';
        }
    }
    $return = preg_replace('/\s\s+/', ' ', $return2);
    return $return;
}

function escape_pdf_string($ss) {
    $backslash = chr(0x5c);
    $ss_esc = '';
    $ss_len = strlen($ss);
    for ($ii = 0; $ii < $ss_len; ++$ii) {
        if (ord($ss[$ii]) == 0x28 || ord($ss[$ii]) == 0x29 || ord($ss[$ii]) == 0x5c) {
            $ss_esc .= $backslash . $ss[$ii];
        } else if (ord($ss[$ii]) < 32 || 126 < ord($ss[$ii])) {
            $ss_esc .= sprintf("\\%03o", ord($ss[$ii]));
        } else {
            $ss_esc .= $ss[$ii];
        }
    }
    return $ss_esc;
}

function escape_pdf_name($ss) {
    $ss_esc = '';
    $ss_len = strlen($ss);
    for ($ii = 0; $ii < $ss_len; ++$ii) {
        if (ord($ss[$ii]) < 33 || 126 < ord($ss[$ii]) || ord($ss[$ii]) == 0x23) {
            $ss_esc .= sprintf("#%02x", ord($ss[$ii]));
        } else {
            $ss_esc .= $ss[$ii];
        }
    }
    return $ss_esc;
}

function burst_dots_into_arrays(&$fdf_data_old) {
    $fdf_data_new = array();
    foreach ($fdf_data_old as $key => $value) {
        $key_split = explode('.', (string) $key, 2);
        if (count($key_split) == 2) {
            if (!array_key_exists((string) ($key_split[0]), $fdf_data_new)) {
                $fdf_data_new[(string) ($key_split[0])] = array();
            }
            if (gettype($fdf_data_new[(string) ($key_split[0])]) != 'array') {

                $fdf_data_new[(string) ($key_split[0])] = array('' => $fdf_data_new[(string) ($key_split[0])]);
            }

            $fdf_data_new[(string) ($key_split[0])][(string) ($key_split[1])] = $value;
        } else {
            if (array_key_exists((string) ($key_split[0]), $fdf_data_new) &&
                    gettype($fdf_data_new[(string) ($key_split[0])]) == 'array') {
                $fdf_data_new[(string) $key][''] = $value;
            } else {
                $fdf_data_new[(string) $key] = $value;
            }
        }
    }
    foreach ($fdf_data_new as $key => $value) {
        if (gettype($value) == 'array') {
            $fdf_data_new[(string) $key] = burst_dots_into_arrays($value); // recurse
        }
    }
    return $fdf_data_new;
}

function forge_fdf_fields_flags(&$fdf, $field_name, &$fields_hidden, &$fields_readonly) {
    if (in_array($field_name, $fields_hidden))
        $fdf .= "/SetF 2 ";
    else
        $fdf .= "/ClrF 2 ";

    if (in_array($field_name, $fields_readonly))
        $fdf .= "/SetFf 1 ";
    else
        $fdf .= "/ClrFf 1 ";
}

function forge_fdf_fields(&$fdf, &$fdf_data, &$fields_hidden, &$fields_readonly, $accumulated_name, $strings_b) {
    if (0 < strlen($accumulated_name)) {
        $accumulated_name .= '.';
    }

    foreach ($fdf_data as $key => $value) {
        $fdf .= "<< ";

        if (gettype($value) == 'array') {
            $fdf .= "/T (" . escape_pdf_string((string) $key) . ") ";
            $fdf .= "/Kids [ ";

            forge_fdf_fields($fdf, $value, $fields_hidden, $fields_readonly, $accumulated_name . (string) $key, $strings_b);

            $fdf .= "] ";
        } else {

            $fdf .= "/T (" . escape_pdf_string((string) $key) . ") ";

            if ($strings_b) {
                $fdf .= "/V (" . escape_pdf_string((string) $value) . ") ";
            } else {
                $fdf .= "/V /" . escape_pdf_name((string) $value) . " ";
            }

            forge_fdf_fields_flags($fdf, $accumulated_name . (string) $key, $fields_hidden, $fields_readonly);
        }
        $fdf .= ">> \x0d";
    }
}

function forge_fdf_fields_strings(&$fdf, &$fdf_data_strings, &$fields_hidden, &$fields_readonly) {
    return
            forge_fdf_fields($fdf, $fdf_data_strings, $fields_hidden, $fields_readonly, '', true); // true => strings data
}

function forge_fdf_fields_names(&$fdf, &$fdf_data_names, &$fields_hidden, &$fields_readonly) {
    return
            forge_fdf_fields($fdf, $fdf_data_names, $fields_hidden, $fields_readonly, '', false); // false => names data
}

function forge_fdf($pdf_form_url, &$fdf_data_strings, &$fdf_data_names, &$fields_hidden, &$fields_readonly) {
    $fdf = "%FDF-1.2\x0d%\xe2\xe3\xcf\xd3\x0d\x0a";
    $fdf .= "1 0 obj\x0d<< ";
    $fdf .= "\x0d/FDF << ";
    $fdf .= "/Fields [ ";

    $fdf_data_strings = burst_dots_into_arrays($fdf_data_strings);
    forge_fdf_fields_strings($fdf, $fdf_data_strings, $fields_hidden, $fields_readonly);

    $fdf_data_names = burst_dots_into_arrays($fdf_data_names);
    forge_fdf_fields_names($fdf, $fdf_data_names, $fields_hidden, $fields_readonly);

    $fdf .= "] \x0d";

    if ($pdf_form_url) {
        $fdf .= "/F (" . escape_pdf_string($pdf_form_url) . ") \x0d";
    }

    $fdf .= ">> \x0d";
    $fdf .= ">> \x0dendobj\x0d";

    $fdf .= "trailer\x0d<<\x0d/Root 1 0 R \x0d\x0d>>\x0d";
    $fdf .= "%%EOF\x0d\x0a";

    return $fdf;
}

// 38
function MergeFiles($theData) {
    $jSEND = new jSEND();
    $data_p = $theData;
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true); //var_dump($array);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $file_obj = $GetClass->GetClass('file');
        $FileList = $file_obj->getAllfilebyComnListwithFilterID('idtransaction', $array['idtransaction'], 'idfile,name,type,date,idsection,hash');
        $AllFiles = array();
        if ($FileList) {
            foreach ($FileList as $File) {
                $Files = array();
                if (strpos($File->gettype(), 'pdf') !== false) {
                    $Files[] = $File->getidfile();
                    $Files[] = $File->getname();
                    $Files[] = date("m/d/Y", strtotime($File->getdate()));
                    $Files[] = $File->gettype();
                    $AllFiles[] = $Files;
                }
            }
        }
        echo json_encode($AllFiles);
        /**/
    } else {
        echo 'Error : not have array';
    }
}

//39
function mergeDocuments($theData) {
    /*  error_reporting(E_ALL);
      ini_set('display_errors', 1); */
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $files = explode(',', $array['files']);
        $final_name = $_SESSION['jigowatt']['user_id'] . date('YmdHis');

        $array_merge = array();
        $array_merge2 = array();
        $counter_files = 0;
        foreach ($files as $key => $idfile) {
            $myfile = $GetClass->GetClass('file');
            $myfile = $myfile->getfileById($idfile);
            if (is_object($myfile)) {

                $fh = fopen('temp/merge_temp' . $final_name . $key . '.pdf', 'w') or die("can't open file");
                $stringData = $myfile->getcontent();
                fwrite($fh, $stringData);
                fclose($fh);
                $counter_files++;
                if ($counter_files <= 10) {
                    $array_merge[] = 'temp/merge_temp' . $final_name . $key . '.pdf';
                } else {
                    $array_merge2[] = 'temp/merge_temp' . $final_name . $key . '.pdf';
                }
            }
        }

        if (count($array_merge) > 0 && count($array_merge2) == 0) {
            $vari = "pdftk " . implode(' ', $array_merge) . ' cat output temp/merge_' . $final_name . '.pdf';
            //$cat = 'temp/merge1_'.$final_name.'.pdf ';
            shell_exec($vari);
            $file_temp_obj = $GetClass->GetClass('file_temp');
            $temp = 'temp/merge_' . $final_name . '.pdf';
            $fp = fopen($temp, 'r');
            $contenido = fread($fp, filesize($temp)) or die('Error :cant open file');
            fclose($fp);
            if (is_object($myfile)) {
                $myfile->setname('merge_' . $final_name);
                $myfile->updatetype($myfile->getidfile_temp(), '.pdf'); //echo 'entra 2';
                $myfile->updatecontent($myfile->getidfile_temp(), $contenido); //echo 'entra 1';
                $myfile->updateidlogin($myfile->getidfile_temp(), $idlogin); //echo 'entra 1';
                $ida = $myfile->getidfile_temp();
            }
            unlink($temp);
            echo $ida;
            //echo 'merge_' . $final_name . '.pdf';
        } else if (count($array_merge) > 0 && count($array_merge2) > 0) {
            $vari = "pdftk " . implode(' ', $array_merge) . ' cat output temp/merge1_' . $final_name . '.pdf';
            $cat = 'temp/merge1_' . $final_name . '.pdf ';
            shell_exec($vari);

            $vari = "pdftk " . implode(' ', $array_merge2) . ' cat output temp/merge2_' . $final_name . '.pdf';
            $cat .= 'temp/merge2_' . $final_name . '.pdf ';
            shell_exec($vari);

            $vari = "pdftk " . $cat . ' cat output temp/merge_' . $final_name . '.pdf';
            shell_exec($vari);

            if (file_exists('temp/merge1_' . $final_name . '.pdf '))
                unlink('temp/merge1_' . $final_name . '.pdf ');
            if (file_exists('temp/merge2_' . $final_name . '.pdf '))
                unlink('temp/merge2_' . $final_name . '.pdf ');
            $myfile = $GetClass->GetClass('file_temp');
            $temp = 'temp/merge_' . $final_name . '.pdf';
            $fp = fopen($temp, 'r');
            $contenido = fread($fp, filesize($temp)) or die('Error :cant open file');
            fclose($fp);
            if (is_object($myfile)) {
                $myfile->setname('merge_' . $final_name);
                $myfile->updatetype($myfile->getidfile_temp(), '.pdf'); //echo 'entra 2';
                $myfile->updatecontent($myfile->getidfile_temp(), $contenido); //echo 'entra 1';
                $myfile->updateidlogin($myfile->getidfile_temp(), $idlogin); //echo 'entra 1';
                $ida = $myfile->getidfile_temp();
            }
            unlink($temp);
            echo $ida;
        }

        foreach ($array_merge as $key => $value) {
            if (file_exists($value)) {
                unlink($value);
            }
        }
        foreach ($array_merge2 as $key => $value) {
            if (file_exists($value)) {
                unlink($value);
            }
        }
    } else {
        echo "Error, One array expected";
    }
}

//40
function AddDeleteAlert($theData) {
    /*  error_reporting(E_ALL);
      ini_set('display_errors', 1); */
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $alert_obj = $GetClass->GetClass('alert');
        if (!$array['idtransaction']) {
            die('Error : Not Have Transaction Selected');
        }
        $ArrayReturn = array('Function' => $array['Function']);
        $ArrayAlerts = array();
        $ArrayNotes = array();
        //print_r($array);
        switch ($array['Function']) {
            case 'Get':
                $AllAlerts = $alert_obj->getAllalertForColumnValue('idtransaction', $array['idtransaction']);
                if ($AllAlerts) {
                    foreach ($AllAlerts as $alert) {
                        if ($alert->gettype() == 'new') {
                            $JsonData = $alert->getdata();
                            if ($JsonData) {
                                $JsonData = json_decode($JsonData, true);
                                $JsonData = $JsonData['TituloAlert'];
                            }
                            $ArrayNotes[] = json_encode(array('idalert' => $alert->getidalert(), 'classe' => 'new', 'DateAlert' => $alert->getdatealert(), 'Text' => $alert->gettext(), 'Title' => $JsonData));
                        }
                        if ($alert->gettype() == 'important' || $alert->gettype() == 'warning' || $alert->gettype() == 'system') {
                            $JsonData = $alert->getdata();
                            if ($JsonData) {
                                $JsonData = json_decode($JsonData, true);
                                $JsonData = $JsonData['TituloAlert'];
                            }
                            $ArrayAlerts[] = json_encode(array('idalert' => $alert->getidalert(), 'classe' => $alert->gettype(), 'DateAlert' => $alert->getdatealert(), 'Text' => $alert->gettext(), 'Title' => $JsonData));
                        }
                    }
                }
                $ArrayReturn['Alerts'] = json_encode($ArrayAlerts);
                $ArrayReturn['Notes'] = json_encode($ArrayNotes);
                echo json_encode($ArrayReturn);
                break;
            case 'Add':
                $alert_obj->setidtransaction($array['idtransaction']);
                $alert_obj->updatetype($alert_obj->getidalert(), $array['addalertselect']);
                $alert_obj->updatetext($alert_obj->getidalert(), $array['BodyAlert']);
                $alert_obj->updatedatealert($alert_obj->getidalert(), $array['DateAlert']);
                $alert_obj->updateidlogin($alert_obj->getidalert(), $idlogin);
                $alert_obj->updatedata($alert_obj->getidalert(), json_encode(array('TituloAlert' => $array['TituloAlert'])));
                echo json_encode(array('msj' => 'Alert Insert Successfully'));
                break;
            case 'Delete':
                $alert = $alert_obj->getalertById($array['idalert']);
                if ($alert) {
                    $alert_obj->deletealert($array['idalert']);
                    $alert = $alert_obj->getalertById($array['idalert']);
                    if ($alert) {
                        echo 'Error : An Error has Ocurred, Please Try Again';
                    } else {
                        echo json_encode(array('msj' => 'Alert Deleted Successfully'));
                    }
                } else {
                    echo '';
                    echo json_encode(array('msj' => 'Alert Deleted Successfully'));
                }
                break;
            default : echo 'Error : Function ' . $array['Function'] . ' Not Found';
                break;
        }
    } else {
        echo "Error, One array expected";
    }
}

//41
function DeleteParty($theData) {
    /*  error_reporting(E_ALL);
      ini_set('display_errors', 1); */
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
        if ($array['id']) {
            $transaction_contact = $transaction_contact_obj->gettransaction_contactById($array['id']);
            if ($transaction_contact) {
                $transaction_contact_obj->deletetransaction_contact($array['id']);
                $transaction_contact = $transaction_contact_obj->gettransaction_contactById($array['id']);
                if ($transaction_contact) {
                    echo 'Error : An Error Has Ocurred Please Try Again';
                } else {
                    echo 'Party Deleted Successfully';
                }
            } else {
                echo 'Party Deleted Successfully';
            }
        } else {
            echo 'Error : Please Choose a Party';
        }
    } else {
        echo "Error, One array expected";
    }
}

//42
function DisbursmentPdf($theData) {
    /*  error_reporting(E_ALL);
      ini_set('display_errors', 1); */
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    if (!isset($_SESSION)) {
        session_start();
    }
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $transaction_contact_obj = $GetClass->GetClass('transaction_contact');

        class PDF2 extends FPDF {

            function Header() {
                $this->SetTextColor(0);
                //$this->Image('Server/developer/images/logo.png', 155, 6, 50);
                $this->SetFont('Arial', 'B', 15);
                $this->Cell(194, 10, 'DISBURSEMENT STATEMENT', 0, 0, 'L');
                $this->Ln(20);
            }

            function llena2($check_list, $payment_list, $deposit_list, $arrayhud, $id_transaction) {
                $GetClass = GetClassPsToDb();

                $contact_obj = $GetClass->GetClass('contact');
                $customer_obj = $GetClass->GetClass('customer');
                $this->SetDrawColor(128, 132, 151);
                $this->SetDrawColor(0, 0, 0);
                $this->SetFont('helvetica', 'B', 10);
                $this->SetLineWidth(.3);
                $this->SetFillColor(221, 221, 221);
                $this->SetTextColor(0);
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(34, 6, 'BANK : ', 'TL', 0, 'L', true);
                $this->SetFont('helvetica', '', 10);
                $this->Cell(162, 6, $arrayhud['name_bank_name'], 'TR', 0, 'L', true);
                $this->Ln();
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(34, 6, 'DATE : ', 'L', 0, 'L', true);
                $this->SetFont('helvetica', '', 10);
                $this->Cell(34, 6, date('m/d/Y'), 0, 0, 'L', true);
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(34, 6, 'TIME : ', 0, 0, 'L', true);
                $this->SetFont('helvetica', '', 10);
                $this->Cell(94, 6, date('h:i A'), 'R', 0, 'L', true);
                $this->Ln();
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(34, 6, 'File Number : ', 'L', 0, 'L', true);
                $this->SetFont('helvetica', '', 10);
                $this->Cell(162, 6, $arrayhud['file_number'], 'R', 0, 'L', true);
                $this->Ln();
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(34, 6, 'Seller(s) : ', 'L', 0, 'L', true);
                $this->SetFont('helvetica', '', 10);
                $this->Cell(162, 6, ucwords(strtolower(trim($arrayhud['seller_s']))), 'R', 0, 'L', true);
                $this->Ln();
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(34, 6, 'Buyer(s) : ', 'L', 0, 'L', true);
                $this->SetFont('helvetica', '', 10);
                $this->Cell(162, 6, ucwords(strtolower(trim($arrayhud['buyer_s']))), 'R', 0, 'L', true);
                $this->Ln();
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(34, 6, 'Property Location : ', 'LB', 0, 'L', true);
                $this->SetFont('helvetica', '', 10);
                $this->Multicell(162, 6, $arrayhud['address_s'], 'RB', 'L', true);
                $this->Ln();
                $num2 = 10;
                $entra = true;
                $y_5 = $this->GetY();
                for ($num = 10; $num <= 206; $num = $num + 1) {
                    if ($entra) {
                        $this->Line($num, $y_5, $num + 1, $y_5);
                        $entra = false;
                        $num2 = $num2 + 1;
                    } else {
                        $num2 = $num2 + 1;
                        $entra = true;
                    }
                }
                $this->Ln();
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(34, 8, 'LOAN AMOUNT', 'LTB', 0, 'L', true);
                $this->Cell(162, 8, $arrayhud['t17'], 'RTB', 0, 'R', true);
                $loan_amount = $arrayhud['t17'];
                $loan_amount = str_replace('$', '', $loan_amount);
                $loan_amount = str_replace(',', '', $loan_amount);
                if (!$loan_amount) {
                    $loan_amount = 0;
                }

                $this->Ln();
                $this->Ln(5);
                $this->SetFont('helvetica', 'B', 12);
                $this->Cell(196, 8, 'DEDUCT FROM LOAN PROCESSDS', 0, 0, 'C', false);
                $this->SetFont('helvetica', 'B', 10);
                $this->Ln();
                $this->Cell(82, 6, 'DESCRIPTION', 'LBT', 0, 'L', true);
                $this->Cell(80, 6, '', 'BT', 0, 'L', true);
                $this->Cell(34, 6, 'AMOUNT', 'RBT', 0, 'R', true);
                $this->Ln();
                $total_deduct = 0;
                if ($arrayhud['json_deducts'] && $arrayhud['json_deducts'] != '{}') {
                    $deduct = json_decode($arrayhud['json_deducts'], true);
                    foreach ($deduct as $key_deduct => $value_decuct) {
                        //sacahud($arrayhud,$type,$value);
                        if ($this->sacahud($arrayhud, 'amount', $key_deduct) && $this->sacahud($arrayhud, 'amount', $key_deduct) != '$ 0.00') {
                            /**/
                            $amount_deductLimpio = $this->limpiastring($this->sacahud($arrayhud, 'amount', $key_deduct));
                            $value_decuct_b = $value_decuct;
                            if (strpos($value_decuct, '_pocl') !== false) {
                                $value_decuct_b = str_replace('_pocl', '', $value_decuct);
                                $amount_deductLimpio = $amount_deductLimpio * -1;
                            }
                            $amount_deduct = '' . str_replace('USD ', '', money_format('%i', $amount_deductLimpio));
                            /**/
                            $this->Cell(80, 6, ucwords(strtolower(ReplaceString($value_decuct_b))), 'LB', 0, 'L', false);
                            $this->Cell(82, 6, '', 'B', 0, 'L', false);
                            //$this->Cell(82, 6, ucwords(strtolower($this->sacahud($arrayhud,'desc',$key_deduct))), 'LB', 0, 'L', false);
                            $this->Cell(34, 6, $amount_deduct, 'RB', 0, 'R', false);
                            $total_deduct = $total_deduct + $amount_deductLimpio;
                            $this->Ln();
                        }
                    }
                } else {
                    $this->Cell(196, 6, '', 'T', 0, 'L', false);
                    $this->Ln();
                }
                $this->Cell(162, 8, 'TOTAL DEDUCT FROM LOAN PROCEES', 'LBT', 0, 'L', true);
                $this->Cell(34, 8, '' . str_replace('USD ', '', money_format('%i', $total_deduct)), 'RBT', 0, 'R', true);
                $this->Ln();
                /* aqui falta el total de los wire */
                $this->Ln(5);
                $this->Cell(162, 8, 'Wire From Lender', 'LBT', 0, 'L', true);
                foreach ($deposit_list as $deposit) {
                    $json_lines = $deposit->getdata();
                    $json_lines = json_decode($json_lines, true);
                    if ($json_lines) {
                        foreach ($json_lines as $key => $value) {
                            $PaymentForLender = $value['PaymentForLender'];
                        }
                    }
                    if ($PaymentForLender == 'wirefromlender') {
                        $wireformlender = $deposit->gettotal_amount();
                    }
                    $PaymentForLender = '';
                }
                $this->Cell(34, 8, '' . str_replace('USD ', '', money_format('%i', $wireformlender)), 'RBT', 0, 'R', true);
                $this->Ln();

                $this->Ln(5);
                //$total_wiredeductloan = $loan_amount - ($total_deduct + $wireformlender);
                $total_wiredeductloan = ($total_deduct + $wireformlender);
                $this->Cell(162, 8, 'FUNDS / WIRE FROM LENDER', 'LBT', 0, 'L', true);
                $this->Cell(34, 8, '' . str_replace('USD ', '', money_format('%i', $total_wiredeductloan)), 'RBT', 0, 'R', true);
                $this->Ln();
                $this->Ln(5);
                $num2 = 10;
                $entra = true;
                $y_5 = $this->GetY();
                for ($num = 10; $num <= 206; $num = $num + 1) {
                    if ($entra) {
                        $this->Line($num, $y_5, $num + 1, $y_5);
                        $entra = false;
                        $num2 = $num2 + 1;
                    } else {
                        $num2 = $num2 + 1;
                        $entra = true;
                    }
                }
                $this->Ln();
                /**/
                $this->SetFont('helvetica', 'B', 12);
                $this->Cell(196, 8, 'DEPOSITS', 0, 0, 'C', false);
                $this->Ln();
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(15, 6, 'DEP#', 'LBT', 0, 'L', true);
                $this->Cell(23, 6, 'DATE', 'BT', 0, 'L', true);
                $this->Cell(135, 6, 'NAME', 'BT', 0, 'L', true);
                $this->Cell(23, 6, 'AMOUNT', 'RBT', 0, 'R', true);
                $this->Ln();
                $this->SetFont('helvetica', '', 10);
                $total_deposit = 0;
                $monto = 0;
                foreach ($deposit_list as $deposit) {
                    $json_lines = $deposit->getdata();
                    $json_lines = json_decode($json_lines, true);
                    if ($json_lines) {
                        foreach ($json_lines as $key => $value) {
                            if ($value['idcontact']) {
                                $contact_list = $contact_obj->getcontactById($value['idcontact']);
                                if ($contact_list) {
                                    $name = $contact_list->getcompany();
                                    if (!trim($name)) {
                                        $name = $contact_list->getfirstname() . ' ' . $contact_list->getmiddlename() . ' ' . $contact_list->getsurname();
                                    }
                                }
                            }
                        }
                    }
                    //$temp = explode('_',$k);
                    if ($deposit->getidq()) {
                        $this->Cell(15, 6, $deposit->getidq(), 'LB', 0, 'L', false);
                        $this->Cell(23, 6, date('m/d/Y', strtotime($deposit->getcreated_at())), 'B', 0, 'L', false);
                        $this->Cell(135, 6, ucwords(strtolower($name)), 'B', 0, 'L', false);
                        $this->Cell(23, 6, '' . str_replace('USD ', '', money_format('%i', $deposit->gettotal_amount())), 'BR', 0, 'R', false);
                        $this->Ln();
                        $monto = $deposit->gettotal_amount();
                        $monto = str_replace('$', '', $monto);
                        $monto = str_replace(',', '', $monto);
                        $total_deposit = $total_deposit + $monto;
                    }
                }
                $total_dep_2 = $total_deposit; //- $total_wiredeductloan;
                $this->SetFont('helvetica', 'B', 12);
                $this->Cell(173, 6, 'TOTAL DEPOSIT', 'LB', 0, 'L', true);
                $this->Cell(23, 6, '' . str_replace('USD ', '', money_format('%i', $total_dep_2)), 'BR', 0, 'R', true);
                $this->SetFont('helvetica', '', 10);
                $this->Ln();
                $this->Ln(5);
                $num2 = 10;
                $entra = true;
                $this->Ln(5);
                $y_5 = $this->GetY();
                for ($num = 10; $num <= 206; $num = $num + 1) {
                    if ($entra) {
                        $this->Line($num, $y_5, $num + 1, $y_5);
                        $entra = false;
                        $num2 = $num2 + 1;
                    } else {
                        $num2 = $num2 + 1;
                        $entra = true;
                    }
                }
                $this->Ln();
                /* check */
                $this->Ln(5);
                $this->SetFont('helvetica', 'B', 12);
                $this->Cell(196, 8, 'CHECKS', 0, 0, 'C', false);
                $this->Ln();
                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(23, 6, 'CHECK#', 'LTB', 0, 'L', true);
                $this->Cell(23, 6, 'DATE', 'TB', 0, 'L', true);
                $this->Cell(23, 6, 'STATUS', 'TB', 0, 'L', true);
                $this->Cell(104, 6, 'NAME', 'TB', 0, 'L', true);
                $this->Cell(23, 6, 'AMOUNT', 'TRB', 0, 'R', true);
                $this->Ln();
                $totalcheck = 0;
                $this->SetFont('helvetica', '', 10);
                $array_status = array('', 'RT-Receipt Pending', 'RR-Receipt Received', 'RC-Receipt Cleared', 'RB-Combined Receipt', 'RX-Receipt Offset', '-R-Receipts Eliminated', 'VR-Void Receipt', 'WT-Wire Receipt Pending', 'WR-Wire Received', 'WC-Wire Receipt Cleared', 'WV-Wire Receipt Void');

                foreach ($check_list as $k) {
                    if ($k->getdescription() != 'reajust') {
                        /**/
                        $accountcheck = $k->getaccount();
                        $accountcheck = json_decode($accountcheck, true);
                        $accountcheck = $accountcheck[0];
                        $descriptioncheck = $accountcheck['description'];
                        $typecontact = $accountcheck['typecontact'];
                        $DocNumber = $accountcheck['DocNumber'];
                        $accountcheck = $accountcheck['hudline'];
                        $contact_list = $contact_obj->getcontactById($k->getidcontact());
                        if ($typecontact == 'company') {
                            $nombre = $contact_list->getcompany();
                            if (!trim($nombre)) {
                                $nombre = $contact_list->getfirstname() . ' ' . $contact_list->getsurname();
                            }
                        } else {
                            $nombre = $contact_list->getfirstname() . ' ' . $contact_list->getsurname();
                            if (!trim($nombre)) {
                                $nombre = $contact_list->getcompany();
                            }
                        }
                        /**/

                        if ($contact_list->getfirstname() && $contact_list->getsurname()) {
                            
                        } else {
                            
                        }
                        $this->Cell(23, 6, $DocNumber, 'LB', 0, 'L', false);
                        $this->Cell(23, 6, date('m/d/Y', strtotime($k->getexpensedate())), 'B', 0, 'L', false);
                        if (strpos($k->getdescription(), '[voided]') !== false) {
                            $this->Cell(23, 6, ' [voided]', 'B', 0, 'L', false);
                        } else {
                            $this->Cell(23, 6, '', 'B', 0, 'L', false);
                        }
                        if ($array_status[$arrayhud['statuscheck_' . $k->getidq()]]) {
                            $this->Cell(104, 6, ucwords(strtolower($nombre)) . ' [' . $array_status[$arrayhud['statuscheck_' . $k->getidq()]] . ']', 'B', 0, 'L', false);
                        } else {
                            $this->Cell(104, 6, ucwords(strtolower(ReplaceString($nombre))), 'B', 0, 'L', false);
                        }
                        $this->Cell(23, 6, '' . str_replace('USD ', '', money_format('%i', $k->getamount())), 'BR', 0, 'R', false);
                        $this->Ln();
                        $monto = $k->getamount();
                        $monto = str_replace('$', '', $monto);
                        $monto = str_replace(',', '', $monto);
                        $totalcheck = $totalcheck + $monto;
                    }
                }
                $this->SetFont('helvetica', 'B', 12);
                $this->Cell(173, 6, 'TOTAL CHECK', 'LTB', 0, 'L', true);
                $this->Cell(23, 6, '' . str_replace('USD ', '', money_format('%i', $totalcheck)), 'BTR', 0, 'R', true);
                $this->SetFont('helvetica', '', 10);
                $this->Ln();
                $totalreceipt = 0;
                $this->SetFont('helvetica', '', 10);
                $this->Ln();
                $num2 = 10;
                $entra = true;
                $this->Ln(5);
                $y_5 = $this->GetY();
                for ($num = 10; $num <= 206; $num = $num + 1) {
                    if ($entra) {
                        $this->Line($num, $y_5, $num + 1, $y_5);
                        $entra = false;
                        $num2 = $num2 + 1;
                    } else {
                        $num2 = $num2 + 1;
                        $entra = true;
                    }
                }

                $this->Ln(10);
                $this->Line(10, $this->GetY(), 205, $this->GetY());
                $this->Ln(1);
                $this->SetFont('helvetica', 'B', 14);
                $this->Cell(170, 8, 'Current Balance For Account', 0, 0, 'C', false);
                //$this->SetFont('helvetica', '', 10);
                //if($total_dep_2 == $totalcheck){
                //    $total_disb = 0;
                //}else{
                $total_disb = number_format($total_dep_2, 2) - number_format($totalcheck, 2);
                //}
                if (!$total_disb) {
                    $total_disb = 0.00;
                }
                /* require_once "quickbook/front/qb_helper.php";
                  $ttert = new transaction($dbname);
                  $transa = $ttert->gettransactionById($id_transaction); */
                $this->Cell(25, 8, '' . str_replace('USD ', '', money_format('%i', str_replace('-', '', $total_disb))), 0, 0, 'L', false);
                $this->Ln();
            }

            function sacahud($arrayhud, $type, $value) {
                if ($arrayhud['json_deducts'] && $arrayhud['json_deducts'] != '{}') {
                    $deduct = json_decode($arrayhud['json_deducts'], true);
                } else {
                    $deduct = array();
                }
                switch ($value) {
                    case 'A-01':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t46']) + /* $this->limpiastring($arrayhud['t47']) + */ $this->limpiastring($arrayhud['t48']) + $this->limpiastring($arrayhud['t49']) + $this->limpiastring($arrayhud['t50']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['A-01'];
                        }
                        if ($type == 'desc') {
                            return '';
                        }
                        break;
                    case 'A-02':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t51']) + /* $this->limpiastring($arrayhud['t52']) + */$this->limpiastring($arrayhud['t53']) + $this->limpiastring($arrayhud['t54']) + $this->limpiastring($arrayhud['t55']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['A-02'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t61_a'];
                        }
                        break;
                    case 'A-03':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t56']) + /* $this->limpiastring($arrayhud['t57']) + */ $this->limpiastring($arrayhud['t58']) + $this->limpiastring($arrayhud['t59']) + $this->limpiastring($arrayhud['t60']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['A-03'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t61_b'];
                        }
                        break;
                    case 'A-04':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t62']) + /* $this->limpiastring($arrayhud['t63']) + */$this->limpiastring($arrayhud['t64']) + $this->limpiastring($arrayhud['t65']) + $this->limpiastring($arrayhud['t66']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['A-04'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t61'];
                        }
                        break;
                    case 'A-05':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t68']) + /* $this->limpiastring($arrayhud['t69']) + */$this->limpiastring($arrayhud['t70']) + $this->limpiastring($arrayhud['t71']) + $this->limpiastring($arrayhud['t72']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['A-06'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t67'];
                        }
                        break;
                    case 'A-06':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t74']) + /* $this->limpiastring($arrayhud['t75']) + */$this->limpiastring($arrayhud['t76']) + $this->limpiastring($arrayhud['t77']) + $this->limpiastring($arrayhud['t78']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['A-06'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t73'];
                        }
                        break;
                    case 'A-07':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t80']) + /* $this->limpiastring($arrayhud['t81']) + */$this->limpiastring($arrayhud['t82']) + $this->limpiastring($arrayhud['t83']) + $this->limpiastring($arrayhud['t84']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['A-07'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t79'];
                        }
                        break;
                    case 'A-08':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t86']) + /* $this->limpiastring($arrayhud['t87']) + */$this->limpiastring($arrayhud['t88']) + $this->limpiastring($arrayhud['t89']) + $this->limpiastring($arrayhud['t90']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['A-08'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t85'];
                        }
                        break;
                    /* B */
                    case 'B-01':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t95']) + /* $this->limpiastring($arrayhud['t96']) + */$this->limpiastring($arrayhud['t97']) + $this->limpiastring($arrayhud['t98']) + $this->limpiastring($arrayhud['t99']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['B-01'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t94_a'];
                        }
                        break;
                    case 'B-02':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t101']) + /* $this->limpiastring($arrayhud['t102']) + */$this->limpiastring($arrayhud['t103']) + $this->limpiastring($arrayhud['t104']) + $this->limpiastring($arrayhud['t105']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['B-02'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t100_a'];
                        }
                        break;
                    case 'B-03':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t107']) + /* $this->limpiastring($arrayhud['t108']) + */$this->limpiastring($arrayhud['t109']) + $this->limpiastring($arrayhud['t110']) + $this->limpiastring($arrayhud['t111']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['B-03'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t106_a'];
                        }
                        break;
                    case 'B-04':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t113']) + /* $this->limpiastring($arrayhud['t114']) + */$this->limpiastring($arrayhud['t115']) + $this->limpiastring($arrayhud['t116']) + $this->limpiastring($arrayhud['t117']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['B-04'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t112_a'];
                        }
                        break;
                    case 'B-05':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t119']) + /* $this->limpiastring($arrayhud['t120']) + */$this->limpiastring($arrayhud['t121']) + $this->limpiastring($arrayhud['t122']) + $this->limpiastring($arrayhud['t123']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['B-05'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t118_a'];
                        }
                        break;
                    case 'B-06':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t125']) + /* $this->limpiastring($arrayhud['t126']) + */$this->limpiastring($arrayhud['t127']) + $this->limpiastring($arrayhud['t128']) + $this->limpiastring($arrayhud['t129']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['B-06'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t124_a'];
                        }
                        break;
                    case 'B-07':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t131']) + /* $this->limpiastring($arrayhud['t132']) + */$this->limpiastring($arrayhud['t133']) + $this->limpiastring($arrayhud['t134']) + $this->limpiastring($arrayhud['t135']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['B-07'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t130_a'];
                        }
                        break;
                    case 'B-08':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t137']) + /* $this->limpiastring($arrayhud['t138']) + */$this->limpiastring($arrayhud['t139']) + $this->limpiastring($arrayhud['t140']) + $this->limpiastring($arrayhud['t141']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['B-08'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t136_a'];
                        }
                        break;
                    case 'B-09':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t143']) + /* $this->limpiastring($arrayhud['t144']) + */$this->limpiastring($arrayhud['t145']) + $this->limpiastring($arrayhud['t146']) + $this->limpiastring($arrayhud['t147']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['B-09'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t142_a'];
                        }
                        break;
                    case 'B-10':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t149']) + /* $this->limpiastring($arrayhud['t150']) + */$this->limpiastring($arrayhud['t151']) + $this->limpiastring($arrayhud['t152']) + $this->limpiastring($arrayhud['t153']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['B-10'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t148_a'];
                        }
                        break;
                    /* F */
                    case 'F-01':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t228']) + /* $this->limpiastring($arrayhud['t229']) + */$this->limpiastring($arrayhud['t230']) + $this->limpiastring($arrayhud['t231']) + $this->limpiastring($arrayhud['t232']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['F-01'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud[''];
                        }
                        break;
                    case 'F-02':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t234']) + /* $this->limpiastring($arrayhud['t235']) + */$this->limpiastring($arrayhud['t236']) + $this->limpiastring($arrayhud['t237']) + $this->limpiastring($arrayhud['t238']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['F-02'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud[''];
                        }
                        break;
                    case 'F-03':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t242']) + /* $this->limpiastring($arrayhud['t243']) + */$this->limpiastring($arrayhud['t244']) + $this->limpiastring($arrayhud['t245']) + $this->limpiastring($arrayhud['t246']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['F-03'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud[''];
                        }
                        break;
                    case 'F-04':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t248']) + /* $this->limpiastring($arrayhud['t249']) + */$this->limpiastring($arrayhud['t250']) + $this->limpiastring($arrayhud['t251']) + $this->limpiastring($arrayhud['t252']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['F-04'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud[''];
                        }
                        break;
                    case 'F-05':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t253']) + /* $this->limpiastring($arrayhud['t254']) + */$this->limpiastring($arrayhud['t255']) + $this->limpiastring($arrayhud['t256']) + $this->limpiastring($arrayhud['t257']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['F-05'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['add2'];
                        }
                        break;
                    case 'F-06':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['F_06_1']) + /* $this->limpiastring($arrayhud['F_06_2']) + */$this->limpiastring($arrayhud['F_06_3']) + $this->limpiastring($arrayhud['F_06_4']) + $this->limpiastring($arrayhud['F_06_5']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['F-06'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['F_06'];
                        }
                        break;
                    case 'F-07':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['F_07_1']) + /* $this->limpiastring($arrayhud['F_07_2']) + */$this->limpiastring($arrayhud['F_07_3']) + $this->limpiastring($arrayhud['F_07_4']) + $this->limpiastring($arrayhud['F_07_5']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['F-07'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['F_07'];
                        }
                        break;
                    /* G */
                    case 'G-01':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t264']) + /* $this->limpiastring($arrayhud['t265']) + */$this->limpiastring($arrayhud['t266']) + $this->limpiastring($arrayhud['t267']) + $this->limpiastring($arrayhud['t268']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['G-01'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t262_a'];
                        }
                        break;
                    case 'G-02':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t271']) + /* $this->limpiastring($arrayhud['t272']) + */$this->limpiastring($arrayhud['t273']) + $this->limpiastring($arrayhud['t274']) + $this->limpiastring($arrayhud['t275']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['G-02'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t269_a'];
                        }
                        break;
                    case 'G-03':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t278']) + /* $this->limpiastring($arrayhud['t279']) + */$this->limpiastring($arrayhud['t280']) + $this->limpiastring($arrayhud['t281']) + $this->limpiastring($arrayhud['t282']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['G-03'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t276_a'];
                        }
                        break;
                    case 'G-04':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t284']) + /* $this->limpiastring($arrayhud['t285']) + */$this->limpiastring($arrayhud['t286']) + $this->limpiastring($arrayhud['t287']) + $this->limpiastring($arrayhud['t288']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['G-04'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t283'];
                        }
                        break;
                    case 'G-05':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t290']) + /* $this->limpiastring($arrayhud['t291']) + */$this->limpiastring($arrayhud['t292']) + $this->limpiastring($arrayhud['t293']) + $this->limpiastring($arrayhud['t294']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['G-05'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t289'];
                        }
                        break;
                    case 'G-06':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t296']) + /* $this->limpiastring($arrayhud['t297']) + */$this->limpiastring($arrayhud['t298']) + $this->limpiastring($arrayhud['t299']) + $this->limpiastring($arrayhud['t300']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['G-06'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t295'];
                        }
                        break;
                    case 'G-07':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t302']) + /* $this->limpiastring($arrayhud['t303']) + */$this->limpiastring($arrayhud['t304']) + $this->limpiastring($arrayhud['t305']) + $this->limpiastring($arrayhud['t306']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['G-07'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['t301'];
                        }
                        break;
                    case 'G-08':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t307']) + /* $this->limpiastring($arrayhud['t308']) + */$this->limpiastring($arrayhud['t309']) + $this->limpiastring($arrayhud['t310']) + $this->limpiastring($arrayhud['t311']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['G-08'];
                        }
                        if ($type == 'desc') {
                            return 'Aggregate Adjustment';
                        }
                        break;
                    case 'G-09':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['G_09_1']) + /* $this->limpiastring($arrayhud['G_09_2']) + */$this->limpiastring($arrayhud['G_09_3']) + $this->limpiastring($arrayhud['G_09_4']) + $this->limpiastring($arrayhud['G_09_5']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['G-09'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['G_09'];
                        }
                        break;
                    case 'G-10':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['G_10_1']) + /* $this->limpiastring($arrayhud['G_10_2']) + */$this->limpiastring($arrayhud['G_10_3']) + $this->limpiastring($arrayhud['G_10_4']) + $this->limpiastring($arrayhud['G_10_5']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['G-10'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud['G_10'];
                        }
                        break;
                    /* N */
                    case 'N-04':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t514']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['N-04'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud[''];
                        }
                        break;
                    case 'N-05':
                        if ($type == 'amount') {
                            $amount = $amount + $this->limpiastring($arrayhud['t515']);
                            return '' . str_replace('USD ', '', money_format('%i', $amount));
                        }
                        if ($type == 'name') {
                            return $deduct['N-05'];
                        }
                        if ($type == 'desc') {
                            return $arrayhud[''];
                        }
                        break;
                }
            }

            function limpiastring($amount) {
                $amount = str_replace('$', '', $amount);
                $amount = str_replace(',', '', $amount);
                if ($amount) {
                    return $amount;
                } else {
                    return '0';
                }
            }

            function Footer() {
                $this->SetTextColor(0);
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->Cell(194, .3, '', 'T', 'J', false);
                $this->Ln();
                $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
            }
        }

        $pdf = new PDF2();
        $pdf->SetFont('helvetica', '', 10);
        $pdf->AliasNbPages();
        $pdf->AddPage('P', 'Letter');
    } else {
        echo "Error, One array expected";
    }
}

// 43
function UpdateOrCreateCheck($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idtransaction']) {
            $purchase_obj = $GetClass->GetClass('purchase');
            $AllPurchasesTransaction = $purchase_obj->getAllpurchaseForColumnvalue('idtransaction', $array['idtransaction']);
            $Return = '';
            if ($AllPurchasesTransaction) {
                if (quickbook_e_d() == 'enabled') {
                    $QBFunctions_obj = $GetClass->GetClass('QBFunctions');
                }
                $transaction_obj = $GetClass->GetClass('transaction');
                $contact_obj = $GetClass->GetClass('contact');
                $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                $ClosingDate = date("Y-m-d H:i:s", strtotime($transaction->getclosingdate()));
                foreach ($AllPurchasesTransaction as $Purchase) {
                    $Account = json_decode($Purchase->getaccount(), true);
                    $ArrayLine = array();
                    $ArrayLines = array();
                    $idqContact = '';
                    $DescriptionShow = '';
                    foreach ($Account as $ForLine) {
                        $ForLineCheck = json_decode($ForLine, true);
                        if ($ForLineCheck['Description']) {
                            $ArrayLine['description'] = $ForLineCheck['Description'];
                            $DescriptionShow .= $ForLineCheck['hudline'] . ' : ' . $ForLineCheck['Description'] . ' For ' . str_replace('USD ', '$', money_format('%i', $ForLineCheck['Amount'])) . '<br>';
                        } else {
                            $ArrayLine['description'] = $Purchase->getdescription();
                            $DescriptionShow = $Purchase->gethudline() . ' : ' . $Purchase->getdescription() . ' For ' . str_replace('USD ', '$', money_format('%i', $Purchase->getamount()));
                        }
                        $ArrayLine['amount'] = $ForLineCheck['Amount'];
                        $ArrayLine['account'] = $transaction->getidqaccount();
                        $ArrayLines[] = $ArrayLine;
                        $idqContact = $ForLineCheck['IdContact'];
                        $TypeContact = $ForLineCheck['TypeContact'];
                    }
                    $contact = $contact_obj->getcontactById($idqContact);
                    $idqContact = $contact->getidq();
                    $Parameters = array();
                    $Parameters['Description'] = $Purchase->getdescription();
                    $Parameters['AccountRef'] = $Purchase->getbankaccount();
                    $Parameters['EntityRef'] = $idqContact;
                    //$Parameters['DocNumber'] = $Purchase->gettype();
                    $Parameters['TxnDate'] = $ClosingDate;
                    $Parameters['PrivateNote'] = '';
                    $Parameters['lines'] = json_encode($ArrayLines);
                    if (quickbook_e_d() == 'enabled') {
                        if ($Purchase->getidq()) {
                            $Parameters['idq'] = $Purchase->getidq();
                            $Response = $QBFunctions_obj->UpdatePurchaseQB($Parameters);
                        } else {
                            $Response = $QBFunctions_obj->CreatePurchaseQB($Parameters);
                        }
                    } else {
                        $Response = 'Only System';
                    }
                    if ($TypeContact == 'Company') {
                        $NameContact = $contact->getcompany();
                    } else {
                        $NameContact = $contact->getfirstname() . ' ' . $contact->getsurname();
                    }
                    if (quickbook_e_d() == 'enabled') {
                        $ArrayBanks = $QBFunctions_obj->GetDataBanks();
                        $nameBAnk = $ArrayBanks[$Purchase->getbankaccount()];
                    } else {
                        //$nameBAnk = 'Only System';
                    }
                    /**/
                    //$bank_obj = new bank($dbname);
                    $bank_obj = $GetClass->GetClass('bank');
                    $Allbank = $bank_obj->getAllbanks();
                    foreach ($Allbank as $ban) {
                        $ArrayBanks[$ban->getidbank()] = $ban->getbankname();
                    }
                    /**/
                    $Return .= '<tr>
                                <td ' . quickbook_e_d() . ' >' . $Purchase->getidpurchase() . '</td>
                                <td>' . $NameContact . '</td>
                                <td>' . date('m/d/Y', strtotime($Purchase->getexpensedate())) . '</td>
                                <td>' . $ArrayBanks[$Purchase->getbankaccount()] . '</td>
                                <td>' . $DescriptionShow . '</td>
                                <td>' . $Purchase->getmemo() . '</td>
                                <td><label class="btn btn-default PreviewCheck" data-idcontact="' . $idqContact . '" data-desc="' . $DescriptionShow . '" data-namecontact="' . $NameContact . '" data-id="' . $Purchase->getidpurchase() . '" data-docnumber="' . $Purchase->gettype() . '">Preview</label></td>
                               </tr>';
                    if (strpos($Response, 'Error') !== false) {
                        echo $Response;
                        die();
                    } else {
                        if (is_numeric($Response)) {
                            $purchase_obj->updateidq($Purchase->getidpurchase(), $Response);
                        }
                    }
                }
                echo $Return;
            }
            /**/
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $bank_obj = $GetClass->GetClass('bank');
            $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
            if ($cdhudTransaction) {
                $cdhudTransaction = $cdhudTransaction[0];
                $idb = $cdhudTransaction->getbankaccount();
                if ($idb) {
                    $bank = $bank_obj->getbankById($idb);
                    if ($bank) {
                        UpdateBanksBalances($idb);
                    }
                }
            }
            /**/
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 44
function PreviewCheckOne($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['IdPurchasePreview']) {
            $purchase_obj = $GetClass->GetClass('purchase');
            if (quickbook_e_d() == 'enabled') {
                $QBFunctions_obj = $GetClass->GetClass('QBFunctions');
            }
            $Purchase = $purchase_obj->getpurchaseById($array['IdPurchasePreview']);
            if ($Purchase) {
                $purchase_obj->updatetype($array['IdPurchasePreview'], $array['DocNumberPreview']);
                /**/
                if (quickbook_e_d() == 'enabled') {
                    $QBFunctions_obj->UpdatePurchaseEspecificQB(array('idq' => $Purchase->getidq(), 'data' => 'DocNumber', 'value' => $array['DocNumberPreview']));
                }
                /**/
                $transaction_obj = $GetClass->GetClass('transaction');
                $contact_obj = $GetClass->GetClass('contact');
                $office_obj = $GetClass->GetClass('office');
                $property_obj = $GetClass->GetClass('property');
                $rolelist_obj = $GetClass->GetClass('rolelist');
                $transaction_contact_obj = $GetClass->GetClass('transaction_contact');

                $Transaction = $transaction_obj->gettransactionById($Purchase->getidtransaction());
                $property = $property_obj->getpropertyById($Transaction->getidproperty());
                $office = $office_obj->getofficeById(1);
                /**/
                $Account = json_decode($Purchase->getaccount(), true);
                $idqContact = '';
                $DescriptionShow = '';
                foreach ($Account as $ForLine) {
                    $ForLineCheck = json_decode($ForLine, true);
                    if ($ForLineCheck['Description']) {
                        $DescriptionShow .= $ForLineCheck['hudline'] . ' : ' . $ForLineCheck['Description'] . PHP_EOL;
                    } else {
                        $DescriptionShow = $Purchase->gethudline() . ' : ' . $Purchase->getdescription();
                    }
                    $idqContact = $ForLineCheck['IdContact'];
                    $TypeContact = $ForLineCheck['TypeContact'];
                }
                $contact = $contact_obj->getcontactById($idqContact);
                if ($TypeContact == 'Company') {
                    $NameContact = $contact->getcompany();
                } else {
                    $NameContact = $contact->getfirstname() . ' ' . $contact->getsurname();
                }
                $AddressContact = '';
                if ($contact->getaddress1()) {
                    $AddressContact = $NameContact . "\n" . $contact->getaddress1();
                    if ($contact->getaddress2()) {
                        $AddressContact .= ', ' . $contact->getaddress2();
                    }
                    if (trim($contact->getcity() . $contact->getzip())) {
                        $AddressContact .= "\n" . $contact->getcity() . ',' . $contact->getstate() . '-' . $contact->getzip();
                    }
                }
                $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $Purchase->getidtransaction());
                $AllBuyers = '';
                $AllSellers = '';
                foreach ($transaction_contact as $t_c) {
                    if (is_numeric($t_c->getidrole()) && is_numeric($t_c->getidcontact())) {
                        $rolelist = $rolelist_obj->getrolelistById($t_c->getidrole());
                        if ($rolelist) {
                            if (($rolelist->getname() == 'buyer' || $rolelist->getname() == 'Buyer')) {
                                $contact_tc = $contact_obj->getcontactById($t_c->getidcontact());
                                if ($AllBuyers) {
                                    $AllBuyers .= ', ' . $contact_tc->getname();
                                } else {
                                    $AllBuyers .= $contact_tc->getname();
                                }
                            }
                            if (($rolelist->getname() == 'seller' || $rolelist->getname() == 'Seller')) {
                                $contact_tc = $contact_obj->getcontactById($t_c->getidcontact());
                                if ($AllSellers) {
                                    $AllSellers .= ', ' . $contact_tc->getname();
                                } else {
                                    $AllSellers .= $contact_tc->getname();
                                }
                            }
                        }
                    }
                }
                /**/
                $ArrayCheck = array();
                $ArrayCheck['ExternalName'] = $Transaction->gettransactionnumber();
                $ArrayCheck['Date'] = date('Y-m-d');
                $ArrayCheck['VendorName'] = $NameContact;
                $ArrayCheck['TotalCheckAmount'] = $Purchase->getamount();
                $ArrayCheck['AddressContact'] = $AddressContact;
                $ArrayCheck['OfficeName'] = $office->getname();
                $ArrayCheck['AllSellers'] = $AllSellers;
                $ArrayCheck['AllBuyers'] = $AllBuyers;
                $ArrayCheck['propertyaddress'] = $property->get_StreetAddress() . ' ' . $property->get_City() . ', ' . $property->get_State() . '-' . $property->get_PostalCode();
                $ArrayCheck['HudLineIcv'] = $DescriptionShow;
                $ArrayCheck['NamePdf'] = 'temp/CheckNumber' . $array['IdPurchasePreview'] . '.pdf';
                $path = PDFCheck($ArrayCheck);
                /* Add Signature */
                if ($array['ForSignature']) {
                    $newpath = 'temp/CheckNumber' . $array['IdPurchasePreview'] . 'Signature.pdf';
                    $exe5 = 'pdftk ' . $path . ' stamp source/FSGP.pdf output ' . $newpath;
                    shell_exec($exe5);
                    $path = $newpath;
                }
                /**/
                echo $path;
            } else {
                echo "Error,check Not Found";
            }
        } else {
            echo "Error, First Select a Check";
        }
    } else {
        echo "Error, An array expected";
    }
}

function PDFCheck($array = array()) {
    require_once ('./Server/developer/fpdf.php');
    $PdfCheck = new FPDF();
    $PdfCheck->AddPage('P', 'Letter');
    $PdfCheck->SetY(3);
    $PdfCheck->SetFont('Arial', 'B', 8);
    $PdfCheck->Cell(90, 5, '', 0, 0, 'C');
    $PdfCheck->Cell(40, 5, '', 0, 0, 'C');
    $PdfCheck->SetFont('Arial', 'B', 12);
    $PdfCheck->Cell(60, 10, '', 0, 1, 'R');

    $PdfCheck->Cell(30, 5, '', 0, 0, 'C');
    $PdfCheck->SetFont('Arial', 'B', 10);
    $PdfCheck->Cell(60, 5, '', 0, 0, 'C');
    $PdfCheck->Cell(40, 5, '', 0, 1, 'C');
    $PdfCheck->SetX($PdfCheck->GetX() + 30);

    $PdfCheck->SetFont('Arial', '', 8, 'C');
    $PdfCheck->Cell(60, 3, '', 0, 0, 'C');
    $PdfCheck->Cell(40, 3, '', 0, 1, 'C');
    $PdfCheck->SetX($PdfCheck->GetX() + 30);
    $PdfCheck->Cell(60, 3, '', 0, 0, 'C');
    $PdfCheck->Cell(40, 3, '', 0, 1, 'C');
    $PdfCheck->SetY($PdfCheck->GetY() - 5);
    $PdfCheck->SetFont('Arial', '', 10);
    $PdfCheck->Cell(100, 10, '', '', 0);
    $PdfCheck->Cell(30, 10, 'File #: ' . $array['ExternalName'], '', 0);
    $PdfCheck->Cell(30, 10, '', '', 0, 'C');
    $PdfCheck->Cell(30, 5, date('m/d/Y', strtotime($array['Date'])), '', 1, 'C');

    $PdfCheck->SetY($PdfCheck->GetY() + 5);
    $PdfCheck->SetFont('Arial', '', 8);
    $PdfCheck->Cell(30, 3, '', '', 1, 'L');
    $PdfCheck->Cell(30, 5, '', '', 0, 'L');
    $PdfCheck->SetFont('Arial', '', 10);
    $PdfCheck->Cell(125, 5, $array['VendorName'], '', 0, '');
    $PdfCheck->Cell(5, 5, '', '', 0, 'C');
    $PdfCheck->Cell(30, 5, '**' . number_format(floatval($array['TotalCheckAmount']), 2, ".", ","), '', 1, 'C');

    $PdfCheck->Cell(5, 3, '', '', 1, '');
    $PdfCheck->Cell(10, 5, '', '', 0, '');
    $PdfCheck->Cell(160, 5, strtoupper(convertNumber($array['TotalCheckAmount'])) . ' ***', '', 0, '');
    $PdfCheck->Cell(20, 7, '', '', 0, 'C');

    $PdfCheck->Cell(30, 15, '', '', 0, 'C');
    $PdfCheck->Cell(120, 15, '', '', 1, 'L');

    $PdfCheck->Cell(120, 5, '', '', 0, '');
    $PdfCheck->Cell(70, 5, '', '', 1, '');

    $PdfCheck->SetFont('Arial', '', 9);
    $ypreview = $PdfCheck->GetY();
    $PdfCheck->SetY($PdfCheck->GetY() - 10);
    $PdfCheck->SetX($PdfCheck->GetX() + 15);
    $PdfCheck->MultiCell(80, 4, $array['AddressContact'], 0);
    $PdfCheck->SetY($ypreview - 5);
    $PdfCheck->SetX($PdfCheck->GetX() + 15);

    $Memo = str_replace(array(chr(194), '&amp;'), array('', '&'), $array['Memo']);

    //$PdfCheck->MultiCell(80, 3, $array['Memo0'] . ' :' . "\n" . $Memo, 0);
    $PdfCheck->MultiCell(80, 3, '', 0);
    $PdfCheck->SetFont('Arial', 'B', 8);
    $PdfCheck->Cell(70, 3, '', '', 1, 'C');

    $PdfCheck->SetY($PdfCheck->GetY() + 20);
    $PdfCheck->SetFont('Arial', 'B', 10);
    $PdfCheck->Cell(100, 5, $array['OfficeName'], '', 0, '');
    $PdfCheck->Cell(70, 5, '', '', 0, '');
    $PdfCheck->Cell(20, 5, '', 0, 1, 'R');
    $PdfCheck->SetFont('Arial', '', 10);

    $PdfCheck->Cell(15, 5, 'FILE #:', '', 0, '');
    $PdfCheck->Cell(35, 5, $array['ExternalName'], '', 0, '');

    $PdfCheck->Cell(15, 5, 'AMOUNT:', '', 0, '');
    $PdfCheck->Cell(45, 5, '$ ' . number_format(floatval($array['TotalCheckAmount']), 2, ".", ","), '', 0, 'R');

    $PdfCheck->Cell(10, 5, '', '', 0, '');
    $PdfCheck->Cell(10, 5, 'DATE: ', '', 0, 'R');
    $PdfCheck->Cell(30, 5, date('m/d/Y', strtotime($array['Date'])), '', 0, '');

    //$PdfCheck->Cell(160, 5, 'CODE: ', '', 0, 'R');
    $PdfCheck->Cell(10, 5, 'CODE: ', '', 0, 'R');
    $PdfCheck->Cell(30, 5, '', '', 1, '');

    //$PdfCheck->Cell(40, 5, 'CK #:', '', 0, 'R');
    $PdfCheck->Cell(120, 5, '', '', 0, '');
    $PdfCheck->Cell(10, 5, 'CK #:', '', 0, 'R');
    $PdfCheck->Cell(30, 5, '', '', 0, 'R');

    $PdfCheck->Cell(10, 5, 'ALT:', '', 0, '');
    $PdfCheck->Cell(30, 5, '', '', 1, '');

    $PdfCheck->Cell(30, 10, 'SELLER (S)', '', 0, '');
    $PdfCheck->Cell(160, 10, ' -- ' . $array['AllSellers'], '', 1, '');
    $PdfCheck->Cell(30, 10, 'BUYER (S)', '', 0, '');
    $PdfCheck->Cell(160, 10, ' -- ' . $array['AllBuyers'], '', 1, '');
    $PdfCheck->Cell(60, 5, 'PROPERTY LOCATION --', '', 1, '');
    $PdfCheck->Cell(190, 5, $array['propertyaddress'], '', 1, '');
    $PdfCheck->Cell(190, 5, 'CD : ' . $array['HudLineIcv'], '', 1, '');
    $PdfCheck->Output($array['NamePdf']);
    return $array['NamePdf'];
}

function convertNumber($number) {
    if (is_string($number)) {
        $number = floatval(str_replace(array(',', '$', ' '), '', $number));
    }
    list($integer, $fraction) = explode(".", (string) $number);

    $output = "";

    if ($integer[0] == "-") {
        $output = "negative ";
        $integer = ltrim($integer, "-");
    } else if ($integer[0] == "+") {
        $output = "positive ";
        $integer = ltrim($integer, "+");
    }

    if ($integer[0] == "0") {
        $output .= "zero";
    } else {
        $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
        $group = rtrim(chunk_split($integer, 3, " "), " ");
        $groups = explode(" ", $group);

        $groups2 = array();
        foreach ($groups as $g) {
            $groups2[] = convertThreeDigit($g[0], $g[1], $g[2]);
        }

        for ($z = 0; $z < count($groups2); $z++) {
            if ($groups2[$z] != "") {
                $output .= $groups2[$z] . convertGroup(11 - $z) . (
                        $z < 11 && !array_search('', array_slice($groups2, $z + 1, -1)) && $groups2[11] != '' && $groups[11][0] == '0' ? " and " : ", "
                        );
            }
        }

        $output = rtrim($output, ", ");
    }

    /* if ($fraction > 0)
      {
      $output .= " point";
      for ($i = 0; $i < strlen($fraction); $i++)
      {
      $output .= " " . convertDigit($fraction{$i});
      }
      } */
    $fraction = (intval($fraction)) ? strval($fraction) : '00';
    $output .= ' and ' . $fraction . '/100 dollars';

    return $output;
}

function convertGroup($index) {
    switch ($index) {
        case 11:
            return " decillion";
        case 10:
            return " nonillion";
        case 9:
            return " octillion";
        case 8:
            return " septillion";
        case 7:
            return " sextillion";
        case 6:
            return " quintrillion";
        case 5:
            return " quadrillion";
        case 4:
            return " trillion";
        case 3:
            return " billion";
        case 2:
            return " million";
        case 1:
            return " thousand";
        case 0:
            return "";
    }
}

function convertThreeDigit($digit1, $digit2, $digit3) {
    $buffer = "";

    if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0") {
        return "";
    }

    if ($digit1 != "0") {
        $buffer .= convertDigit($digit1) . " hundred";
        if ($digit2 != "0" || $digit3 != "0") {
            $buffer .= " and ";
        }
    }

    if ($digit2 != "0") {
        $buffer .= convertTwoDigit($digit2, $digit3);
    } else if ($digit3 != "0") {
        $buffer .= convertDigit($digit3);
    }

    return $buffer;
}

function convertTwoDigit($digit1, $digit2) {
    if ($digit2 == "0") {
        switch ($digit1) {
            case "1":
                return "ten";
            case "2":
                return "twenty";
            case "3":
                return "thirty";
            case "4":
                return "forty";
            case "5":
                return "fifty";
            case "6":
                return "sixty";
            case "7":
                return "seventy";
            case "8":
                return "eighty";
            case "9":
                return "ninety";
        }
    } else if ($digit1 == "1") {
        switch ($digit2) {
            case "1":
                return "eleven";
            case "2":
                return "twelve";
            case "3":
                return "thirteen";
            case "4":
                return "fourteen";
            case "5":
                return "fifteen";
            case "6":
                return "sixteen";
            case "7":
                return "seventeen";
            case "8":
                return "eighteen";
            case "9":
                return "nineteen";
        }
    } else {
        $temp = convertDigit($digit2);
        switch ($digit1) {
            case "2":
                return "twenty-$temp";
            case "3":
                return "thirty-$temp";
            case "4":
                return "forty-$temp";
            case "5":
                return "fifty-$temp";
            case "6":
                return "sixty-$temp";
            case "7":
                return "seventy-$temp";
            case "8":
                return "eighty-$temp";
            case "9":
                return "ninety-$temp";
        }
    }
}

function convertDigit($digit) {
    switch ($digit) {
        case "0":
            return "zero";
        case "1":
            return "one";
        case "2":
            return "two";
        case "3":
            return "three";
        case "4":
            return "four";
        case "5":
            return "five";
        case "6":
            return "six";
        case "7":
            return "seven";
        case "8":
            return "eight";
        case "9":
            return "nine";
    }
}

// 45
function CheckAllTransaction($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        if ($array['idTransaction']) {
            $purchase_obj = $GetClass->GetClass('purchase');
            $transaction_obj = $GetClass->GetClass('transaction');
            if (quickbook_e_d() == 'enabled') {
                $QBFunctions_obj = $GetClass->GetClass('QBFunctions');
            }
            $Purchases = $purchase_obj->getAllpurchaseForColumnValue('idtransaction', $array['idTransaction']);
            $transaction = $transaction_obj->gettransactionById($array['idTransaction']);
            $ClosingDate = date("Y-m-d H:i:s", strtotime($transaction->getclosingdate()));
            if ($Purchases) {
                /* Combined */
                $Purchases = $purchase_obj->getAllpurchaseForColumnValue('idtransaction', $array['idTransaction']);
                /**/
                /* Clean Doc Number */
                foreach ($Purchases as $purch) {
                    if ($purch->getidq() && quickbook_e_d() == 'enabled') {
                        $QBFunctions_obj->UpdatePurchaseEspecificQB(array('idq' => $purch->getidq(), 'data' => 'DocNumber', 'value' => ''));
                    }
                }
                $DocNumberInit = trim($array['CheckNumberInit']);
                foreach ($Purchases as $purch) {
                    if (quickbook_e_d() == 'enabled') {
                        $QBFunctions_obj->UpdatePurchaseEspecificQB(array('idq' => $purch->getidq(), 'data' => 'DocNumber', 'value' => $DocNumberInit));
                    }
                    $purchase_obj->updatetype($purch->getidpurchase(), $DocNumberInit);
                    $DocNumberInit++;
                }
                /**/
                $transaction_obj = $GetClass->GetClass('transaction');
                $contact_obj = $GetClass->GetClass('contact');
                $office_obj = $GetClass->GetClass('office');
                $property_obj = $GetClass->GetClass('property');
                $rolelist_obj = $GetClass->GetClass('rolelist');
                $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
                $Transaction = $transaction_obj->gettransactionById($array['idTransaction']);
                $property = $property_obj->getpropertyById($Transaction->getidproperty());
                $office = $office_obj->getofficeById(1);
                $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $array['idTransaction']);
                $AllBuyers = '';
                $AllSellers = '';
                foreach ($transaction_contact as $t_c) {
                    if (is_numeric($t_c->getidrole()) && is_numeric($t_c->getidcontact())) {
                        $rolelist = $rolelist_obj->getrolelistById($t_c->getidrole());
                        if ($rolelist) {
                            if (($rolelist->getname() == 'buyer' || $rolelist->getname() == 'Buyer')) {
                                $contact_tc = $contact_obj->getcontactById($t_c->getidcontact());
                                if ($AllBuyers) {
                                    $AllBuyers .= ', ' . $contact_tc->getname();
                                } else {
                                    $AllBuyers .= $contact_tc->getname();
                                }
                            }
                            if (($rolelist->getname() == 'seller' || $rolelist->getname() == 'Seller')) {
                                $contact_tc = $contact_obj->getcontactById($t_c->getidcontact());
                                if ($AllSellers) {
                                    $AllSellers .= ', ' . $contact_tc->getname();
                                } else {
                                    $AllSellers .= $contact_tc->getname();
                                }
                            }
                        }
                    }
                }
                /**/
                $arrayPdfChecks = array();
                foreach ($Purchases as $Purchase) {
                    $Account = json_decode($Purchase->getaccount(), true);
                    $idqContact = '';
                    $DescriptionShow = '';
                    foreach ($Account as $ForLine) {
                        $ForLineCheck = json_decode($ForLine, true);
                        if ($ForLineCheck['Description']) {
                            $DescriptionShow .= $ForLineCheck['hudline'] . ' : ' . $ForLineCheck['Description'] . PHP_EOL;
                        } else {
                            $DescriptionShow = $Purchase->gethudline() . ' : ' . $Purchase->getdescription();
                        }
                        $idqContact = $ForLineCheck['IdContact'];
                        $TypeContact = $ForLineCheck['TypeContact'];
                    }
                    //print_r('*'.$idqContact.'*');
                    $contact = $contact_obj->getcontactById($idqContact);
                    $NameContact = '';
                    if ($TypeContact == 'Company') {
                        $NameContact = $contact->getcompany();
                    } else {
                        $NameContact = $contact->getfirstname() . ' ' . $contact->getsurname();
                    }
                    $AddressContact = '';
                    if ($contact->getaddress1()) {
                        $AddressContact = $NameContact . "\n" . $contact->getaddress1();
                        if ($contact->getaddress2()) {
                            $AddressContact .= ', ' . $contact->getaddress2();
                        }
                        if (trim($contact->getcity() . $contact->getzip())) {
                            $AddressContact .= "\n" . $contact->getcity() . ',' . $contact->getstate() . '-' . $contact->getzip();
                        }
                    }

                    /**/
                    $ArrayCheck = array();
                    $ArrayCheck['ExternalName'] = $Transaction->gettransactionnumber();
                    $ArrayCheck['Date'] = date('Y-m-d');
                    $ArrayCheck['VendorName'] = $NameContact;
                    $ArrayCheck['TotalCheckAmount'] = $Purchase->getamount();
                    $ArrayCheck['AddressContact'] = $AddressContact;
                    $ArrayCheck['OfficeName'] = $office->getname();
                    $ArrayCheck['AllSellers'] = $AllSellers;
                    $ArrayCheck['AllBuyers'] = $AllBuyers;
                    $ArrayCheck['propertyaddress'] = $property->get_StreetAddress() . ' ' . $property->get_City() . ', ' . $property->get_State() . '-' . $property->get_PostalCode();
                    $ArrayCheck['HudLineIcv'] = $DescriptionShow;
                    $ArrayCheck['NamePdf'] = 'temp/CheckNumber' . $Purchase->getidpurchase() . '.pdf';
                    $path = PDFCheck($ArrayCheck);
                    $arrayPdfChecks[] = $path;
                }
                /**/
                /* Merge Pdfs */
                $allpdfs = implode(' ', $arrayPdfChecks);
                $outFile = 'temp/AllChecksTransaction' . $array['idTransaction'] . '.pdf';
                exec("pdftk " . $allpdfs . " cat output " . $outFile . " ");
                /**/
                /**/
                $Return = '';
                foreach ($Purchases as $Purchase) {
                    $Account = json_decode($Purchase->getaccount(), true);
                    $ArrayLine = array();
                    $ArrayLines = array();
                    $idqContact = '';
                    $DescriptionShow = '';
                    foreach ($Account as $ForLine) {
                        $ForLineCheck = json_decode($ForLine, true);
                        if ($ForLineCheck['Description']) {
                            $ArrayLine['description'] = $ForLineCheck['Description'];
                            $DescriptionShow .= $ForLineCheck['hudline'] . ' : ' . $ForLineCheck['Description'] . ' For ' . str_replace('USD ', '$', money_format('%i', $ForLineCheck['Amount'])) . '<br>';
                        } else {
                            $ArrayLine['description'] = $Purchase->getdescription();
                            $DescriptionShow = $Purchase->gethudline() . ' : ' . $Purchase->getdescription() . ' For ' . str_replace('USD ', '$', money_format('%i', $Purchase->getamount()));
                        }
                        $ArrayLine['amount'] = $ForLineCheck['Amount'];
                        $ArrayLine['account'] = $transaction->getidqaccount();
                        $ArrayLines[] = $ArrayLine;
                        $idqContact = $ForLineCheck['IdContact'];
                        $TypeContact = $ForLineCheck['TypeContact'];
                    }
                    $contact = $contact_obj->getcontactById($idqContact);
                    $idqContact = $contact->getidq();

                    if ($TypeContact == 'Company') {
                        $NameContact = $contact->getcompany();
                    } else {
                        $NameContact = $contact->getfirstname() . ' ' . $contact->getsurname();
                    }
                    if (quickbook_e_d() == 'enabled') {
                        $ArrayBanks = $QBFunctions_obj->GetDataBanks();
                    } else {
                        $ArrayBanks = array();
                        $bank_obj = $GetClass->GetClass('bank');
                        $Allbank = $bank_obj->getAllbanks();
                        foreach ($Allbank as $ban) {
                            $ArrayBanks[$ban->getidbank()] = $ban->getbankname();
                        }
                    }
                    $Return .= '<tr>
                                <td>' . $Purchase->getidpurchase() . '</td>
                                <td>' . $NameContact . '</td>
                                <td>' . date('m/d/Y', strtotime($Purchase->getexpensedate())) . '</td>
                                <td>' . $ArrayBanks[$Purchase->getbankaccount()] . '</td>
                                <td>' . $DescriptionShow . '</td>
                                <td>' . $Purchase->getmemo() . '</td>
                                <td><label class="btn btn-default PreviewCheck" data-idcontact="' . $idqContact . '" data-desc="' . $DescriptionShow . '" data-namecontact="' . $NameContact . '" data-id="' . $Purchase->getidpurchase() . '" data-docnumber="' . $Purchase->gettype() . '">Preview</label></td>
                               </tr>';
                }
                /**/
                $arrayReturn = array();
                $arrayReturn['path'] = $outFile;
                $arrayReturn['content'] = $Return;
                //echo $outFile;
                /**/
                $cdhud_obj = $GetClass->GetClass('cdhud');
                $bank_obj = $GetClass->GetClass('bank');
                $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idTransaction']);
                if ($cdhudTransaction) {
                    $cdhudTransaction = $cdhudTransaction[0];
                    $idb = $cdhudTransaction->getbankaccount();
                    $bank = $bank_obj->getbankById($idb);
                    if ($bank) {
                        UpdateBanksBalances($idb);
                    }
                }
                /**/
                echo json_encode($arrayReturn);
            } else {
                echo "Error,check Not Found";
            }
        } else {
            echo "Error, First Select a Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 46
function AddressVerification($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idtransaction']) {
            $purchase_obj = $GetClass->GetClass('purchase');
            $transaction_obj = $GetClass->GetClass('transaction');
            $AllPurchasesTransaction = $purchase_obj->getAllpurchaseForColumnvalue('idtransaction', $array['idtransaction']);
            $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
            $ArrayContacts = array();
            if ($AllPurchasesTransaction) {
                $contact_obj = $GetClass->GetClass('contact');
                $arrayIDS = array();
                foreach ($AllPurchasesTransaction as $Purchase) {
                    $Account = json_decode($Purchase->getaccount(), true);
                    $idqContact = '';
                    foreach ($Account as $ForLine) {
                        $ForLineCheck = json_decode($ForLine, true);

                        $idqContact = $ForLineCheck['IdContact'];
                        $TypeContact = $ForLineCheck['TypeContact'];
                    }
                    $contact = $contact_obj->getcontactById($idqContact);

                    if ($TypeContact == 'Company') {
                        $NameContact = $contact->getcompany();
                    } else {
                        $NameContact = $contact->getfirstname() . ' ' . $contact->getsurname();
                    }
                    $Address = $contact->getaddress1();
                    if ($contact->getaddress2()) {
                        $Address .= ', ' . $contact->getaddress1();
                    }
                    $Address .= ' ' . $contact->getcity() . ',' . $contact->getstate() . '-' . $contact->getzip();
                    $AddressTemp = str_replace(array(',', '-'), '', $Address);
                    if (!in_array($idqContact, $arrayIDS)) {
                        array_push($arrayIDS, $idqContact);
                        if (trim($AddressTemp)) {
                            $ArrayContacts[] = $NameContact . ': ' . $AddressTemp;
                        } else {
                            $ArrayContacts[] = $NameContact . ': THIS PARTY NOT HAVE ADDRESS';
                        }
                    }
                }
                require_once ('./Server/developer/fpdf.php');
                $pdf = new FPDF();
                $pdf->AddPage('P', 'Letter');
                /**/
                $general_config_obj = $GetClass->GetClass('general_config');
                $idgeneral_config = 1;
                $general_config = $general_config_obj->getgeneral_configById($idgeneral_config);
                if ($general_config->getlogo1()) {
                    $pathlogo = 'temp/Logo' . $dbname . '.png';
                    $fh = fopen($pathlogo, 'w') or die("can't open file");
                    $stringData = $general_config->getlogo1();
                    fwrite($fh, $stringData);
                }
                /**/
                if (file_exists($pathlogo) && filesize($pathlogo) > 2) {
                    //print_r(filesize($pathlogo));
                    $pdf->Image($pathlogo, 155, 6, 50);
                }

                $pdf->SetFont('Arial', 'B', 15);
                $pdf->Cell(80, 10, date("m/d/Y"), 0, 0, 'L');
                $pdf->Ln(10);
                $pdf->MultiCell(196, 20, 'Address Parties for ' . $transaction->getname() . ' (' . $transaction->gettransactionnumber() . ') ', 0, 'C', false);
                $pdf->Ln(10);
                $pdf->SetFont('Arial', '', 10);
                foreach ($ArrayContacts as $DataContact) {
                    $pdf->Rect($pdf->GetX() + 2, $pdf->GetY() + 2, '4', '4', 'D');
                    $pdf->MultiCell(0, 9, '         ' . $DataContact, 'LTR', 'L', false);
                }
                $pdf->MultiCell(0, 9, '', 'T', 'L', false);
                $nombrepdf = 'temp/ContactList' . $array['idtransaction'] . '.pdf';
                $pdf->Output($nombrepdf, '');
                echo $nombrepdf;
            }
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 47
function GetAllContacts($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['idtransaction']) {
            $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
            $contact_obj = $GetClass->GetClass('contact');
            $transaction_contact = $transaction_contact_obj->getAlltransaction_contactForColumnValue('idtransaction', $array['idtransaction']);
            $options = '<option value="">--Choose Party--</option>';
            foreach ($transaction_contact as $t_c) {
                if ($t_c->getidcontact()) {
                    $contact = $contact_obj->getcontactById($t_c->getidcontact());
                    if ($contact) {
                        $uname = $contact->getfirstname() . ' ' . $contact->getsurname();
                        $company = $contact->getcompany();
                        if ($company) {
                            $uname = $company;
                        }
                        $options .= '<option value="' . $contact->getidcontact() . '" data-firstname="' . $contact->getfirstname() . '" data-lastname="' . $contact->getsurname() . '" data-company="' . $contact->getcompany() . '">' . $uname . '</option>';
                    }
                }
            }
            echo trim($options);
        } else {
            echo "Error, First Select an Transaction";
        }
    } else {
        echo "Error, An array expected";
    }
}

// 48
function EmailPartySend($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        if ($array['EmailToP']) {
            //echo senderGeneralEmail($array['EmailSubjectP'], $array['EmailFromP'], $array['EmailToP'], $array['bodyP'], '', $array['EmailCCP'], $array['EmailBCCP']);
            echo sendGeneralEmail($array['EmailSubjectP'], $array['EmailFromP'], $array['EmailToP'], $array['bodyP'], $array['EmailCCP'], '', $array['EmailBCCP']);
        } else {
            echo "Error, First Select a Party";
        }
    } else {
        echo "Error, An array expected";
    }
}

function ReviewBalance($Amount) {
    $Amount = str_replace(array('$', ','), '', $Amount);
    $GetClass = GetClassPsToDb();
    $idlogin = $_SESSION['jigowatt']['user_id'];
    $iduserperinsert = 1;
    $balance_obj = $GetClass->GetClass('balance');
    $balance_list = $balance_obj->getAllbalanceForColumnValue('iduser', $iduserperinsert);
    if ($balance_list) {
        $balance_list = $balance_list[0];
        $current_balance = $balance_list->getbalance();
        $idbalance = $balance_list->getidbalance();
    } else {
        $balance_obj->setiduser($iduserperinsert);
        $balance_obj->updatetype_account($balance_obj->getidbalance(), 'user');
        $balance_obj->updateidlogin($balance_obj->getidbalance(), $idlogin);
        $balance_obj->updatebalance($balance_obj->getidbalance(), 0.00);
        $balance_obj->updateupdated_at($balance_obj->getidbalance(), date('Y-m-d H:i:s'));
        $current_balance = 0.00;
        $idbalance = $balance_obj->getidbalance();
    }
    if ($current_balance > $Amount) {
        return true;
    } else {
        return false;
    }
}

function DeductCharge($AmountDebit, $typeD, $Description) {
    $GetClass = GetClassPsToDb();
    $idlogin = $_SESSION['jigowatt']['user_id'];
    $current_balance = 0.00;
    $idbalance = 0;
    $balance_obj = $GetClass->GetClass('balance');
    $authorize_config_obj = $GetClass->GetClass('authorize_config');
    $authorize_config = $authorize_config_obj->getauthorize_configById(1);
    MinautoChar();
    if (is_object($authorize_config)) {
        $moneyperuser = $authorize_config->getmoneyperuser();
        if ($moneyperuser == 'true') {
            $type = 'user';
            $iduserperinsert = $idlogin;
        } else {
            $iduserperinsert = 1;
            $type = 'office';
        }
        $balance_list = $balance_obj->getAllbalanceForColumnValue('iduser', $iduserperinsert);
        if ($balance_list) {
            $balance_list = $balance_list[0];
            $current_balance = $balance_list->getbalance();
            $idbalance = $balance_list->getidbalance();
        } else {
            $balance_obj->setiduser($iduserperinsert);
            $balance_obj->updatetype_account($balance_obj->getidbalance(), 'user');
            $balance_obj->updateidlogin($balance_obj->getidbalance(), $idlogin);
            $balance_obj->updatebalance($balance_obj->getidbalance(), 0.00);
            $balance_obj->updateupdated_at($balance_obj->getidbalance(), date('Y-m-d H:i:s'));
            $current_balance = 0.00;
            $idbalance = $balance_obj->getidbalance();
        }
    }
    if ($current_balance > $AmountDebit) {
        $totalAmount = $current_balance - $AmountDebit;
        $balance_obj->updatebalance($idbalance, $totalAmount);
        /**/
        $charge_obj = $GetClass->GetClass('charge');
        $charge_obj->settype($typeD);
        $charge_obj->updateamount($charge_obj->getidcharge(), $AmountDebit);
        $charge_obj->updatedescription($charge_obj->getidcharge(), $Description);
        $charge_obj->updateidlogin($charge_obj->getidcharge(), $idlogin);
        $charge_obj->updatedate($charge_obj->getidcharge(), date('m/d/Y H:i'));
        $charge_obj->updatemethod($charge_obj->getidcharge(), 'Debit');
        /**/
        return true;
    } else {
        die('Error : Insufficient Balance');
    }
}

function ReviewBalancePrev($Amount) {
    $Amount = str_replace(array('$', ','), '', $Amount);
    $GetClass = GetClassPsToDb();

    $idlogin = $_SESSION['jigowatt']['user_id'];
    $current_balance = 0.00;
    $idbalance = 0;
    $balance_obj = $GetClass->GetClass('balance');
    $authorize_config_obj = $GetClass->GetClass('authorize_config');
    //$authorize_config = $authorize_config_obj->getauthorize_configById(1);
    //MinautoChar();
    /* if (is_object($authorize_config)) {
      $moneyperuser = $authorize_config->getmoneyperuser();
      if ($moneyperuser == 'true') {
      $type = 'user';
      $iduserperinsert = $idlogin;
      } else {
      $iduserperinsert = 1;
      $type = 'office';
      }
      $balance_list = $balance_obj->getAllbalanceForColumnValue('iduser', $iduserperinsert);
      if ($balance_list) {
      $balance_list = $balance_list[0];
      $current_balance = $balance_list->getbalance();
      $idbalance = $balance_list->getidbalance();
      } else {
      $balance_obj->setiduser($iduserperinsert);
      $balance_obj->updatetype_account($balance_obj->getidbalance(), 'user');
      $balance_obj->updateidlogin($balance_obj->getidbalance(), $idlogin);
      $balance_obj->updatebalance($balance_obj->getidbalance(), 0.00);
      $balance_obj->updateupdated_at($balance_obj->getidbalance(), date('Y-m-d H:i:s'));
      $current_balance = 0.00;
      $idbalance = $balance_obj->getidbalance();
      }
      } */
    /**/
    $iduserperinsert = 1;
    $balance_list = $balance_obj->getAllbalanceForColumnValue('iduser', $iduserperinsert);
    if ($balance_list) {
        $balance_list = $balance_list[0];
        $current_balance = $balance_list->getbalance();
        $idbalance = $balance_list->getidbalance();
    } else {
        $balance_obj->setiduser($iduserperinsert);
        $balance_obj->updatetype_account($balance_obj->getidbalance(), 'user');
        $balance_obj->updateidlogin($balance_obj->getidbalance(), $idlogin);
        $balance_obj->updatebalance($balance_obj->getidbalance(), 0.00);
        $balance_obj->updateupdated_at($balance_obj->getidbalance(), date('Y-m-d H:i:s'));
        $current_balance = 0.00;
        $idbalance = $balance_obj->getidbalance();
    }
    /**/
    if ($current_balance > $Amount) {
        return true;
    } else {
        return false;
    }
}

function DeductChargePrev($AmountDebit, $typeD, $Description) {
    $GetClass = GetClassPsToDb();

    $idlogin = $_SESSION['jigowatt']['user_id'];
    $current_balance = 0.00;
    $idbalance = 0;
    $balance_obj = $GetClass->GetClass('balance');
    $authorize_config_obj = $GetClass->GetClass('authorize_config');
    $authorize_config = $authorize_config_obj->getauthorize_configById(1);
    MinautoChar();
    if (is_object($authorize_config)) {
        $moneyperuser = $authorize_config->getmoneyperuser();
        if ($moneyperuser == 'true') {
            $type = 'user';
            $iduserperinsert = $idlogin;
        } else {
            $iduserperinsert = 1;
            $type = 'office';
        }
        $balance_list = $balance_obj->getAllbalanceForColumnValue('iduser', $iduserperinsert);
        if ($balance_list) {
            $balance_list = $balance_list[0];
            $current_balance = $balance_list->getbalance();
            $idbalance = $balance_list->getidbalance();
        } else {
            $balance_obj->setiduser($iduserperinsert);
            $balance_obj->updatetype_account($balance_obj->getidbalance(), 'user');
            $balance_obj->updateidlogin($balance_obj->getidbalance(), $idlogin);
            $balance_obj->updatebalance($balance_obj->getidbalance(), 0.00);
            $balance_obj->updateupdated_at($balance_obj->getidbalance(), date('Y-m-d H:i:s'));
            $current_balance = 0.00;
            $idbalance = $balance_obj->getidbalance();
        }
    }
    if ($current_balance > $AmountDebit) {
        $totalAmount = $current_balance - $AmountDebit;
        $balance_obj->updatebalance($idbalance, $totalAmount);
        /**/
        $charge_obj = $GetClass->GetClass('charge');
        $charge_obj->settype($typeD);
        $charge_obj->updateamount($charge_obj->getidcharge(), $AmountDebit);
        $charge_obj->updatedescription($charge_obj->getidcharge(), $Description);
        $charge_obj->updateidlogin($charge_obj->getidcharge(), $idlogin);
        $charge_obj->updatedate($charge_obj->getidcharge(), date('m/d/Y H:i'));
        $charge_obj->updatemethod($charge_obj->getidcharge(), 'Debit');
        /**/
        return true;
    } else {
        die('Error : Insufficient Balance');
    }
}

// 49
function smsToMobile($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $mytwilio_obj = $GetClass->GetClass('mytwilio');
        $mytwilio = $mytwilio_obj->getmytwilioById('1');
        if (is_object($mytwilio)) {
            $SmsHelper = $GetClass->GetClass('SmsHelper');
            $Response = $SmsHelper->SendSms($array['to'], $array['body']);
            if ($Response) {
                $Response = json_decode($Response);
                if ($Response->Code == 200) {
                    $idsms = $Response->Msj;
                } else {
                    die('An error has ocurred - ' . $Response->Msj);
                }
            } else {
                die('An error has ocurred -');
            }

            echo 'Sms Sent Successfully';

            //echo $message;
            if (is_array($array) && $idsms != '') {
                $msj = $GetClass->GetClass('phone_message');
                if (is_object($msj)) {
                    if ($array['update'] == "insert") {
                        $msj->setiduser($array['idlogin']);
                        $msj->updatemessage($msj->getidphone_message(), $array['body']);
                        $msj->updateinbound_outbound($msj->getidphone_message(), $array['tipo']);
                        $msj->updatedestination_phone($msj->getidphone_message(), $array['to']);
                        $msj->updatesource_phone($msj->getidphone_message(), $array['from']);
                        $msj->updatesid($msj->getidphone_message(), $idsms);
                        if ($array['idtransaction']) {
                            $msj->updateidtransaction($msj->getidphone_message(), $array['idtransaction']);
                        }
                        if ($array['idcontact']) {
                            $msj->updateidcontact($msj->getidphone_message(), $array['idcontact']);
                        }
                        $msj->updatestatus($msj->getidphone_message(), 'sending');
                        $msj->updatemyread($msj->getidphone_message(), 'unread');
                    }
                }
            } else {
                echo "Error, sms not send";
            }
        } else {
            echo 'Error : Phone not Configured';
        }
    } else {
        echo "Error, An array expected";
    }
}

// 50
function CreateLink($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $params = array();
        $login_users_obj = $GetClass->GetClass('login_users');
        $login_users = $login_users_obj->getlogin_usersById($array['idlogin']);
        $login_users_obj->updateweb($array['idlogin'], 'FormNotComplete');
        $params['idtransaction'] = $array['idtransaction'];
        $params['idlogin'] = $array['idlogin'];
        $params['typeuser'] = $array['typeuser'];
        $idlogin = $_SESSION['jigowatt']['user_id'];
        $params['usersend'] = $idlogin;
        $params = json_encode($params);
        $params = base64_encode($params);
        $link = 'http://' . $dbname . '.titlehost.com/mrt/rex_formBuyerSeller2.php?c=' . $params;
        $array_return = array();
        $array_return['lnk'] = $link;
        if ($login_users->getemail2()) {
            $array_return['email'] = $login_users->getemail2();
        } else {
            $contact_obj = $GetClass->GetClass('contact');
            $contact = $contact_obj->getAllcontactForcolumnValue('iduser', $array['idlogin']);
            if ($contact) {
                $contact = $contact[0];
                if ($contact->getemail()) {
                    $array_return['email'] = $contact->getemail();
                }
            }
        }
        /* firma */
        $obj_office = $GetClass->GetClass('office');
        $obj_office = $obj_office->getofficeById(1);
        $Sign = "<b>Best regards</b><br>";
        $Sign .= '<b>' . ucfirst($login_users->getfirstname()) . ' ' . ucfirst($login_users->getlastname()) . '</b>' . '<br>';
        $Sign .= '<b>' . ucfirst($obj_office->getname()) . '</b><br>';
        $Sign .= '<b>' . ucfirst($obj_office->getaddress()) . '</b><br>';
        $Sign .= '<b>' . ucfirst($obj_office->getcity()) . ', ' . ucfirst($obj_office->getstate()) . $obj_office->getzip() . '</b><br>';
        $Sign .= '<b>Phone: ' . $obj_office->getphone() . '</b><br>';
        $Sign .= '<b>Fax: ' . $obj_office->getfax() . '</b>';
        //$array_return['email'] = $contact->getemail();
        /**/
        $array_return['Sign'] = $Sign;
        echo json_encode($array_return);
    } else {
        echo "Error, An array expected";
    }
}

// 51
function BankUpdNewDelete($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $bank_obj = $GetClass->GetClass('bank');
        if ($array['Type'] == 'Get') {
            $bank = $bank_obj->getbankById($array['Id']);
            if (!$bank) {
                die('Error :  Bank Not Found');
            }
            $arrayResponse = array();
            $arrayResponse['BankName'] = $bank->getbankname();
            $arrayResponse['AccName'] = $bank->getaccname();
            $arrayResponse['Address'] = $bank->getaddress();
            $arrayResponse['Address2'] = $bank->getaddress2();
            $arrayResponse['City'] = $bank->getcity();
            $arrayResponse['State'] = $bank->getstate();
            $arrayResponse['Zip'] = $bank->getzip();
            $arrayResponse['AccNumber'] = $bank->getaccnumber();
            $arrayResponse['RouNumber'] = $bank->getroutnumber();
            $arrayResponse['update'] = $array['Id'];
            echo json_encode($arrayResponse);
            exit();
        }
        if ($array['Type'] == 'Delete') {
            $bank = $bank_obj->getbankById($array['Id']);
            if ($bank) {
                $bank_obj->deletebank($array['Id']);
            }
            echo '|*|Bank Deleted';
            exit();
        }
        if ($array['update'] == 'insert') {
            $bank_obj->setbankname($array['BankName']);
            $idbank = $bank_obj->getidbank();
            $msj = '|*|Create Successfully';
        } else {
            $idbank = $array['update'];
            $msj = '|*|Update Successfully';
        }
        $bank_obj->updatebankname($idbank, $array['BankName']);
        $bank_obj->updateaccname($idbank, $array['AccName']);
        $bank_obj->updateaddress($idbank, $array['Address']);
        $bank_obj->updateaddress2($idbank, $array['Address2']);
        $bank_obj->updatecity($idbank, $array['City']);
        $bank_obj->updatestate($idbank, $array['State']);
        $bank_obj->updatezip($idbank, $array['Zip']);
        $bank_obj->updateaccnumber($idbank, $array['AccNumber']);
        $bank_obj->updateroutnumber($idbank, $array['RouNumber']);

        echo $msj;
    } else {
        echo "Error, An array expected";
    }
}

// 52
function BankDetails($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $Response = '';
        $purchase_obj = $GetClass->GetClass('purchase');
        $deposit_obj = $GetClass->GetClass('deposit');
        $escrow_obj = $GetClass->GetClass('escrow');
        $transaction_obj = $GetClass->GetClass('transaction');
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $bank_obj = $GetClass->GetClass('bank');
        $bank = $bank_obj->getbankById($array['Id']);
        $CDList = $cdhud_obj->getAllcdhudForColumnValue('bankaccount', '"' . $array['Id'] . '"');
        if ($CDList) {
            $Balance = 0;
            foreach ($CDList as $cdl) {
                $idtransaction = $cdl->getidtransaction();
                $transaction = $transaction_obj->gettransactionById($idtransaction);
                $purchase = $purchase_obj->getAllpurchaseForColumnValue('idtransaction', '"' . $idtransaction . '"');
                $deposit = $deposit_obj->getAlldepositForColumnValue('idtransaction', '"' . $idtransaction . '"');
                $AmountPurchase = 0;
                $AmountDeposit = 0;
                $AmountDepositEscrow = 0;
                if ($purchase) {
                    foreach ($purchase as $pur) {
                        $TempAmount = trim(str_replace(array('$', ','), '', $pur->getamount()));
                        $AmountPurchase = $AmountPurchase + $TempAmount;
                    }
                }
                if ($deposit) {
                    foreach ($deposit as $dep) {
                        $data = $dep->getdata();
                        if ($data) {
                            $data = json_decode($data, true);
                        } else {
                            $data = array();
                        }
                        if ($data['hudlineDeposit'] == 'L-01') {
                            $TempAmount = trim(str_replace(array('$', ','), '', $dep->gettotal_amount()));
                            $AmountDepositEscrow = $AmountDepositEscrow + $TempAmount;
                        } else {
                            $TempAmount = trim(str_replace(array('$', ','), '', $dep->gettotal_amount()));
                            $AmountDeposit = $AmountDeposit + $TempAmount;
                        }
                    }
                }
                $Balance += ($AmountDeposit + $AmountDepositEscrow) - $AmountPurchase;
                $Response .= '<div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $idtransaction . '"> Transaction ' . $idtransaction . ' : ' . $transaction->getname() . '  <span style="float:right">Balance : ' . str_replace('USD ', '$', money_format('%i', ($AmountDeposit + $AmountDepositEscrow) - $AmountPurchase)) . '</span></a> </h4>
                                </div>
                                <div id="collapse' . $idtransaction . '" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col col-md-4">
                                                <label class="label">Total Checks : ' . str_replace('USD ', '$', money_format('%i', $AmountPurchase)) . '</label>
                                            </div>
                                            <div class="col col-md-4">
                                                <label class="label">Total Deposits : ' . str_replace('USD ', '$', money_format('%i', $AmountDeposit)) . '</label>
                                            </div>
                                            <div class="col col-md-4">
                                                <label class="label">Total Escrow : ' . str_replace('USD ', '$', money_format('%i', $AmountDepositEscrow)) . '</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </div>';
            }
            $bank_obj->updatebalance($array['Id'], str_replace('USD ', '$', money_format('%i', $Balance)));
            echo '<center><h3>Bank : ' . $bank->getbankname() . '  Balance ' . str_replace('USD ', '$', money_format('%i', $Balance)) . '</h3></center><br>' . $Response;
        } else {
            echo '<center><h3>This Bank not have Transactions Associates</h3></center>';
        }
    } else {
        echo "Error, An array expected";
    }
}

function UpdateBanksBalances($idbank) {
    $GetClass = GetClassPsToDb();

    $purchase_obj = $GetClass->GetClass('purchase');
    $deposit_obj = $GetClass->GetClass('deposit');
    $escrow_obj = $GetClass->GetClass('escrow');
    $transaction_obj = $GetClass->GetClass('transaction');
    $cdhud_obj = $GetClass->GetClass('cdhud');
    $bank_obj = $GetClass->GetClass('bank');
    $bank = $bank_obj->getbankById($idbank);
    $CDList = $cdhud_obj->getAllcdhudForColumnValue('bankaccount', '"' . $idbank . '"');
    if ($CDList) {
        $Balance = 0;
        foreach ($CDList as $cdl) {
            $idtransaction = $cdl->getidtransaction();
            $purchase = $purchase_obj->getAllpurchaseForColumnValue('idtransaction', '"' . $idtransaction . '"');
            $deposit = $deposit_obj->getAlldepositForColumnValue('idtransaction', '"' . $idtransaction . '"');
            $AmountPurchase = 0;
            $AmountDeposit = 0;
            $AmountDepositEscrow = 0;
            if ($purchase) {
                foreach ($purchase as $pur) {
                    $TempAmount = trim(str_replace(array('$', ','), '', $pur->getamount()));
                    $AmountPurchase = $AmountPurchase + $TempAmount;
                }
            }
            if ($deposit) {
                foreach ($deposit as $dep) {
                    $data = $dep->getdata();
                    if ($data) {
                        $data = json_decode($data, true);
                    } else {
                        $data = array();
                    }
                    if ($data['hudlineDeposit'] == 'L-01') {
                        $TempAmount = trim(str_replace(array('$', ','), '', $dep->gettotal_amount()));
                        $AmountDepositEscrow = $AmountDepositEscrow + $TempAmount;
                    } else {
                        $TempAmount = trim(str_replace(array('$', ','), '', $dep->gettotal_amount()));
                        $AmountDeposit = $AmountDeposit + $TempAmount;
                    }
                }
            }
            $Balance += ($AmountDeposit + $AmountDepositEscrow) - $AmountPurchase;
        }
        $bank_obj->updatebalance($idbank, str_replace('USD ', '$', money_format('%i', $Balance)));
    }
}

function MinautoChar() {
    $GetClass = GetClassPsToDb();

    $idlogin = $_SESSION['jigowatt']['user_id'];
    /**/
    $balance_obj = $GetClass->GetClass('balance');
    $authorize_config_obj = $GetClass->GetClass('authorize_config');
    $authorize_config = $authorize_config_obj->getauthorize_configById(1);
    if (is_object($authorize_config)) {
        $moneyperuser = $authorize_config->getmoneyperuser();
        if ($moneyperuser == 'true') {
            $type = 'user';
            $iduserperinsert = $idlogin;
        } else {
            $iduserperinsert = 1;
            $type = 'office';
        }
        $balance_list = $balance_obj->getAllbalanceForColumnValue('iduser', $iduserperinsert);
        if ($balance_list) {
            $balance_list = $balance_list[0];
            $current_balance = $balance_list->getbalance();
            $idbalance = $balance_list->getidbalance();
        } else {
            $balance_obj->setiduser($iduserperinsert);
            $balance_obj->updatetype_account($balance_obj->getidbalance(), 'user');
            $balance_obj->updateidlogin($balance_obj->getidbalance(), $idlogin);
            $balance_obj->updatebalance($balance_obj->getidbalance(), 0.00);
            $balance_obj->updateupdated_at($balance_obj->getidbalance(), date('Y-m-d H:i:s'));
            $current_balance = 0.00;
            $idbalance = $balance_obj->getidbalance();
        }
    }
    /**/
    $obj = $GetClass->GetClass('general_config');
    $obj = $GetClass->GetClass('general_config');
    $idgeneral_config = 1;
    $config = $obj->getgeneral_configById($idgeneral_config);
    if ($config->getleads()) {
        $json_leads = $config->getleads();
        if ($json_leads) {
            $json_leads = json_decode($json_leads, true);
        }
    }
    if ($json_leads['MinAutoCharge']) {
        if ($current_balance < $json_leads['MinAutoCharge']) {//AmountRecharge
            AutoCharge($json_leads['AmountRecharge'], true);
        }
    }
}

function AutoCharge($Amount, $Auto) {
    $Amount = str_replace(array('$', ','), '', $Amount);
    $GetClass = GetClassPsToDb();

    $idlogin = $_SESSION['jigowatt']['user_id'];
    /**/
    $balance_obj = $GetClass->GetClass('balance');
    $authorize_config_obj = $GetClass->GetClass('authorize_config');
    $authorize_config = $authorize_config_obj->getauthorize_configById(1);
    if (is_object($authorize_config)) {
        $moneyperuser = $authorize_config->getmoneyperuser();
        if ($moneyperuser == 'true') {
            $type = 'user';
            $iduserperinsert = $idlogin;
        } else {
            $iduserperinsert = 1;
            $type = 'office';
        }
        $balance_list = $balance_obj->getAllbalanceForColumnValue('iduser', $iduserperinsert);
        if ($balance_list) {
            $balance_list = $balance_list[0];
            $current_balance = $balance_list->getbalance();
            $idbalance = $balance_list->getidbalance();
        } else {
            $balance_obj->setiduser($iduserperinsert);
            $balance_obj->updatetype_account($balance_obj->getidbalance(), 'user');
            $balance_obj->updateidlogin($balance_obj->getidbalance(), $idlogin);
            $balance_obj->updatebalance($balance_obj->getidbalance(), 0.00);
            $balance_obj->updateupdated_at($balance_obj->getidbalance(), date('Y-m-d H:i:s'));
            $current_balance = 0.00;
            $idbalance = $balance_obj->getidbalance();
        }
    }
    /**/
    //setConfigAuthorize();
    //if (AUTHORIZENET_API_LOGIN_ID == "") {
    if (false) {
        die('Enter your merchant credentials in config before running the sample app.');
    } else {
        $METHOD_TO_USE = "AIM";
        if ($METHOD_TO_USE == "AIM") {
            /* Completa Data */
            $customer = $GetClass->GetClass('customer');
            $customerlist = $customer->getAllcustomerForColumnValue('occupation', '"MainOfficeTM"');
            if ($customerlist) {
                $customerl = $customerlist[0];
            } else {
                die('Error : Office Information is Not Configure');
            }
            $exp = explode('/', $customerl->getcard_expire_date());
            $array['billing_address'] = $customerl->getbilling_address();
            $array['billing_city'] = $customerl->getbilling_city();
            $array['billing_company'] = $customerl->getbilling_company();
            $array['billing_country'] = 'US';
            $array['billing_email'] = $customerl->getbilling_email();
            $array['billing_fax'] = $customerl->getbilling_fax();
            $array['billing_firstname'] = $customerl->getbilling_firstname();
            $array['billing_lastname'] = $customerl->getbilling_lastname();
            $array['billing_state'] = $customerl->getbilling_state();
            $array['billing_telephone'] = $customerl->getbilling_telephone();
            $array['billing_zip'] = $customerl->getbilling_country();
            $array['card_name'] = $customerl->getcard_name();
            $array['card_number'] = decryptData($customerl->getcard_number());
            $array['card_type'] = $customerl->getcard_type();
            $array['referrerID'] = $customerl->getreferrerID();
            $array['ccv'] = decryptData($customerl->getcard_ccv());
            $array['charge_method'] = 'card';
            $array['date_execute[]'] = date('Y-m-d');
            $array['id_select'] = $idlogin;
            $array['idbalance'] = $idbalance;
            $array['month'] = $exp[0];
            $array['select_type'] = $customerl->getcard_type();
            $array['type_pay'] = 'Unique';
            $array['update'] = 'insert';
            $array['x_total_amount'] = $Amount;
            $array['year'] = $exp[1];
            if (!$Auto) {
                $array['idlogin'] = $idlogin;
            }
            /**/
            $date_ex = $array['date_execute[]'];
            $resp = '';
            $total_amount = $array['x_total_amount'];
            /* New Payment */
            if (quickbook_e_d() == 'enabled') {
                $QBFunctions_obj = $GetClass->GetClass('QBFunctions');
                $resp = $QBFunctions_obj->PaymentQB($array);
            }
            /* echo 'Error :';
              print_r($array); */
            if (strpos($resp, 'Error') === false) {
                if (isset($idbalance) && $idbalance > 0) {
                    $balance_obj = $GetClass->GetClass('balance');
                    $balance = $balance_obj->getbalanceById($idbalance);
                    if (is_object($balance)) {
                        $current_balance = $balance->getbalance();
                        $new_balance = $current_balance + $total_amount;
                        $balance_obj->updatebalance($idbalance, $new_balance);
                    }
                }
            }
            /**/
            //$resp = saveRegAndExecute($array, $date_ex, $total_amount);
            return $resp;
        }
    }
}

// 53
function ReChargeCredit($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        echo AutoCharge($array['Amnt']);
    } else {
        echo "Error, An array expected";
    }
}

function saveRegAndExecute($array1, $date_execute, $amount) {
    $GetClass = GetClassPsToDb();

    $transaction = new AuthorizeNetAIM;
    $transaction->setSandbox(AUTHORIZENET_SANDBOX);
    $exp_date = $array1['month'] . '/' . $array1['year'];
    if ($array1['charge_method'] == 'card') {
        $transaction->setFields(
                array(
                    'amount' => $amount,
                    'card_num' => $array1['card_number'],
                    'exp_date' => $exp_date,
                    'first_name' => $array1['billing_firstname'],
                    'last_name' => $array1['billing_lastname'],
                    'address' => $array1['billing_address'],
                    'company' => $array1['billing_company'],
                    'phone' => $array1['billing_telephone'],
                    'fax' => $array1['billing_fax'],
                    'city' => $array1['billing_city'],
                    'state' => $array1['billing_state'],
                    'country' => $array1['billing_country'],
                    'zip' => $array1['billing_zip'],
                    'email' => $array1['billing_email'],
                    'card_code' => $array1['ccv']
                )
        );
    } else {
        $transaction->setFields(
                array(
                    'amount' => $amount,
                    'method' => 'echeck',
                    'bank_aba_code' => $array1['bank_aba_code'],
                    'bank_acct_num' => $array1['bank_acct_num'],
                    'bank_acct_type' => $array1['bank_acct_type'],
                    'bank_name' => $array1['bank_name'],
                    'bank_acct_name' => $array1['bank_acct_name'],
                    'echeck_type' => 'WEB',
                )
        );
    }
    //$obj_a = new authorize($dbname);
    $obj_a = $GetClass->GetClass('authorize');
    if ($array1['update'] == 'insert') {
        $obj_a->setcard_number(encryptData($array1['card_number']));
        /* echo 'Error ';
          print_r($obj_a); */
        //---------- save in db ------------
        if ($array1['suscription_11'] == 'yes') {
            $plan = $GetClass->GetClass('plan');
            $plan->setiduser($_SESSION['jigowatt']['user_id']);
            $plan->updatedata($plan->getidplan(), json_encode($array1));
            $plan->updatetype($plan->getidplan(), $array1['suscription']);
            $plan->updateidauthorize($plan->getidplan(), $obj_a->getidauthorize());
            $plan->updatedescription($plan->getidplan(), 'suscription');
            $obj_gc = $GetClass->GetClass('general_config');
            $obj_gc = $obj_gc->getgeneral_configById(1);
            if (is_object($obj_gc)) {
                $gc_data = json_decode($obj_gc->getfax(), true);
                $fax_ip = (isset($gc_data['ip'])) ? $gc_data['ip'] : '';
                $fax_email_send = (isset($gc_data['emailFaxSend'])) ? $gc_data['emailFaxSend'] : '';
                $fax_email_receive = (isset($gc_data['emailFaxReceive'])) ? $gc_data['emailFaxReceive'] : '';
                $fax_email_receive_pass = (isset($gc_data['emailFaxReceivePass'])) ? $gc_data['emailFaxReceivePass'] : '';
                $fax_iax = (isset($gc_data['IAX'])) ? $gc_data['IAX'] : '';
                $fax_phone = (isset($gc_data['faxphone'])) ? $gc_data['faxphone'] : '';
                $fax_peruser = (isset($gc_data['faxperuser'])) ? $gc_data['faxperuser'] : '';
                $user = $GetClass->GetClass('login_users');
                $user = $user->getlogin_usersById($_SESSION['jigowatt']['user_id']);
                if ($fax_peruser == 'yes') {
                    $zserver = $GetClass->GetClass('zserver');
                    $server = $zserver->getzserverById(1);
                    $tmp_array = json_decode(trim($server->getjsonconfig()), true);
                    $cal_user = $tmp_array['zuser'];
                    $cal_pass = $tmp_array['zpass'];
                    $raw = array(
                        'serv' => $server->getzurl(),
                        'domain' => $server->getdomain(),
                        'acco' => $user->getemail(),
                        'psw' => $user->getpassz(),
                        'cal_account' => $cal_user,
                        'cal_psw' => base64_encode($cal_pass)
                    );

                    $array_new = array();
                    $array_new['email'] = $user->getusername() . '_' . $fax_email_receive;
                    $array_new['password'] = $fax_email_receive_pass;
                    $array_new['firstname'] = $user->getfirstname();
                    $array_new['lastname'] = $user->getlastname();
                    $array_new['restricted'] = '0';
                    $array_new['update'] = 'insert';
                    $array_new['user_level'] = '2';
                    $array_new['username'] = $user->getusername();
                    $array_new['zcalid'] = '';
                    $array_new['zid'] = '';
                    $array_new['ztaskid'] = '';
                    $array_new['mobile'] = '';
                    $array_new['phone'] = '';
                    if (is_array($array_new)) {
                        $raw = array_merge($raw, $array_new);
                    }
                    $data_i = json_encode($raw);
                    $data_i = AesCtr::encrypt($data_i, 'Ideas1700221106101215', 256);
                    $data_i = str_replace("/", "-12-", $data_i);
                    $data_i = str_replace("+", "-23-", $data_i);
                    $data_i = array();
                    $data_i['action'] = 'result';
                    $data_i['input'] = '06' . $data_i;
                    $info_i = '';
                    $output_i = postZimbraProxy($data_i);
                    $output_i = json_decode($output_i, true);
                }
            }
        }
        $obj_a->updatecard_type($obj_a->getidauthorize(), $array1['card_type']);
        $obj_a->updatecard_name($obj_a->getidauthorize(), $array1['card_name']);
        $obj_a->updateexecute_date($obj_a->getidauthorize(), formatDatemysql($date_execute));
        $obj_a->updatestatus($obj_a->getidauthorize(), 'pending');
        $obj_a->updatetype_payment($obj_a->getidauthorize(), $array1['type_pay']);
        $obj_a->updatecard_expire_date($obj_a->getidauthorize(), $array1['month'] . '/' . $array1['year']);
        $obj_a->updatecard_ccv($obj_a->getidauthorize(), encryptData($array1['ccv']));
        $obj_a->updatebilling_firstname($obj_a->getidauthorize(), $array1['billing_firstname']);
        $obj_a->updatebilling_lastname($obj_a->getidauthorize(), $array1['billing_lastname']);
        $obj_a->updatebilling_address($obj_a->getidauthorize(), $array1['billing_address']);
        $obj_a->updatebilling_city($obj_a->getidauthorize(), $array1['billing_city']);
        $obj_a->updatebilling_state($obj_a->getidauthorize(), $array1['billing_state']);
        $obj_a->updatebilling_zip($obj_a->getidauthorize(), $array1['billing_zip']);
        $obj_a->updatebilling_country($obj_a->getidauthorize(), $array1['billing_country']);
        $obj_a->updatebilling_fax($obj_a->getidauthorize(), $array1['billing_fax']);
        $obj_a->updatebilling_telephone($obj_a->getidauthorize(), $array1['billing_telephone']);
        $obj_a->updatebilling_email($obj_a->getidauthorize(), $array1['billing_email']);
        $obj_a->updatebilling_company($obj_a->getidauthorize(), $array1['billing_company']);
        $obj_a->updateamount($obj_a->getidauthorize(), $amount);
        if ($array1['select_type'] == 'customer') {
            $obj_a->updateidcustomer($obj_a->getidauthorize(), $array1['id_select']);
        }
        $obj_a->updateidcontact($obj_a->getidauthorize(), $array1['idcontact']);
        $obj_a->updateidlogin($obj_a->getidauthorize(), $_SESSION['jigowatt']['user_id']);
        $obj_a->updateidtransaction($obj_a->getidauthorize(), $array1['idtransaction']);
        if ($array1['charge_method'] == 'echeck') {
            $obj_a->updatebank_aba_code($obj_a->getidauthorize(), $array1['x_bank_aba_code']);
            $obj_a->updatebank_account_name($obj_a->getidauthorize(), $array1['x_bank_acct_name']);
            $obj_a->updatebank_account_number($obj_a->getidauthorize(), $array1['x_bank_acct_num']);
            $obj_a->updatebank_name($obj_a->getidauthorize(), $array1['x_bank_name']);
            $obj_a->updatemethod($obj_a->getidauthorize(), 'echeck');
        }

        //---------- save in db ------------
    } else {
        $obj_a->updatecard_number($array1['update'], encryptData($array1['card_number']));
        //---------- save in db ------------
        $obj_a->updatecard_type($array1['update'], $array1['card_type']);
        $obj_a->updatecard_name($array1['update'], $array1['card_name']);
        $obj_a->updateexecute_date($array1['update'], formatDatemysql($date_execute));
        $obj_a->updatestatus($array1['update'], 'pending');
        $obj_a->updatetype_payment($array1['update'], $array1['type_pay']);
        $obj_a->updatecard_expire_date($array1['update'], $array1['month'] . '/' . $array1['year']);
        $obj_a->updatecard_ccv($array1['update'], encryptData($array1['ccv']));
        $obj_a->updatebilling_firstname($array1['update'], $array1['billing_firstname']);
        $obj_a->updatebilling_lastname($array1['update'], $array1['billing_lastname']);
        $obj_a->updatebilling_address($array1['update'], $array1['billing_address']);
        $obj_a->updatebilling_city($array1['update'], $array1['billing_city']);
        $obj_a->updatebilling_state($array1['update'], $array1['billing_state']);
        $obj_a->updatebilling_zip($array1['update'], $array1['billing_zip']);
        $obj_a->updatebilling_country($array1['update'], $array1['billing_country']);
        $obj_a->updatebilling_fax($array1['update'], $array1['billing_fax']);
        $obj_a->updatebilling_telephone($array1['update'], $array1['billing_telephone']);
        $obj_a->updatebilling_email($array1['update'], $array1['billing_email']);
        $obj_a->updatebilling_company($array1['update'], $array1['billing_company']);
        $obj_a->updateamount($array1['update'], $amount);

        if ($array1['charge_method'] == 'echeck') {
            $obj_a->updatebank_aba_code($array1['update'], $array1['x_bank_aba_code']);
            $obj_a->updatebank_account_name($array1['update'], $array1['x_bank_acct_name']);
            $obj_a->updatebank_account_number($array1['update'], $array1['x_bank_acct_num']);
            $obj_a->updatebank_name($array1['update'], $array1['x_bank_name']);
            $obj_a->updatemethod($array1['update'], 'echeck');
        }
        //$obj_a->updateidcustomer($array1['update'], $array1['idcustomer']);
        //$obj_a->updateidcontact($array1['update'], $array1['idcontact']);
        //$obj_a->updateidlogin($array1['update'], $_SESSION['jigowatt']['user_id']);
        //$obj_a->updateidtransaction($array1['update'], $array1['idtransaction']);
        //---------- save in db ------------
        echo 'Payment update suscesfully';
    }


    $date = new DateTime(); //this returns the current date time
    $result_d = $date->format('Y-m-d');
    //echo "$result_d == $date_execute";
    if ($result_d == formatDatemysql($date_execute)) {
        $response = $transaction->authorizeAndCapture();
        if ($response->approved) {
            // Transaction approved! Do your logic here.
            $obj_a->updatestatus($obj_a->getidauthorize(), 'approved');
            $obj_a->updateresponse_transaction_id($obj_a->getidauthorize(), $response->transaction_id);
            //header('Location: thank_you_page.php?transaction_id=' . $response->transaction_id);
            //echo json_encode(array('result' => '200', 'text' => 'Done'));
            if (isset($array1['idbalance']) && $array1['idbalance'] > 0) {
                //$obj_bl = new balance($dbname);
                $obj_bl = $GetClass->GetClass('balance');
                $obj_bl = $obj_bl->getbalanceById($array1['idbalance']);
                if (is_object($obj_bl)) {
                    /* if($array1['charge_method']=='card'){
                      $temp = 0;
                      $temp = (4/$amount)*100;
                      $amount = $amount - $temp;
                      }
                      if($array1['charge_method']=='echeck'){
                      $temp = 0;
                      $temp = (1/$amount)*100;
                      $amount = $amount - $temp;
                      } */
                    $current_balance = $obj_bl->getbalance();
                    $new_balance = $current_balance + $amount;
                    //$obj_bl1 = new balance($dbname);
                    $obj_bl1 = $GetClass->GetClass('balance');
                    $obj_bl1->updatebalance($obj_bl->getidbalance(), $new_balance);
                }
            }
            return $obj_a->getidauthorize();
        } else {
            $obj_a->updatestatus($obj_a->getidauthorize(), 'failed');
            $obj_a->updateresponse_code($obj_a->getidauthorize(), $response->response_code);
            $obj_a->updateresponse_reason_code($obj_a->getidauthorize(), $response->response_reason_code);
            $obj_a->updateresponse_reason_text($obj_a->getidauthorize(), $response->response_reason_text);
            //header('Location: error_page.php?response_reason_code='.$response->response_reason_code.'&response_code='.$response->response_code.'&response_reason_text=' .$response->response_reason_text);
            //echo json_encode(array('result' => '500', 'text' => $response->response_reason_code.' - '.$response->response_reason_text ));
            $body = '<html><body><p>Insert Authorize with id: ' . $obj_a->getidauthorize() . ' has the failure</p></body></html>';
            ///anclaivan
            sendGeneralEmail('Error : Authorize Failure', "", 'notification', "$body");
            return "ERROR : " . $response->response_reason_text;
        }
    } else {
        return "Payment scheduled";
    }
}

function encryptData($str) {
    $GetClass = GetClassPsToDb();

    $obj_ac = $GetClass->GetClass('authorize_config');
    $config = $obj_ac->getauthorize_configById(1);
    if (is_object($config)) {
        $data = $str;
        $data = AesCtr::encrypt($data, $config->getmykey(), 256);
        $data = str_replace("/", "-12-", $data);
        $data = str_replace("+", "-23-", $data);
        return $data;
    } else {
        echo "Error object expected";
        return '';
    }
}

function decryptData($str) {
    $GetClass = GetClassPsToDb();

    //$obj_ac = $GetClass->GetClass('authorize_config');
    $obj_ac = $GetClass->GetClass('authorize_config');
    $config = $obj_ac->getauthorize_configById(1);
    if (is_object($config)) {
        $data = str_replace("-12-", "/", $str);
        $data = str_replace("-23-", "+", $data);
        $data = AesCtr::decrypt($data, $config->getmykey(), 256);
        return $data;
    } else {
        //echo "Error object expected";
        return '';
    }
}

function formatDatemysql($originalDate) {
    $newDate = date("Y-m-d", strtotime($originalDate));
    return $newDate;
}

function setConfigAuthorize() {
    //------------------ authorize config  ------------------
    require_once 'Server/anet_php_sdk/AuthorizeNet.php';
    $METHOD_TO_USE = "AIM";
    $GetClass = GetClassPsToDb();

    //$obj_ac = $GetClass->GetClass('authorize_config');
    $obj_ac = $GetClass->GetClass('authorize_config');
    $config = $obj_ac->getauthorize_configById(1);

    $login_id = $config->getlogin_id();
    $transaction_key = $config->gettransaction_key();
    $sandbox = ($config->getproduction() == 'true') ? true : false;

    define("AUTHORIZENET_API_LOGIN_ID", $login_id);    // Add your API LOGIN ID
    define("AUTHORIZENET_TRANSACTION_KEY", $transaction_key); // Add your API transaction key
    define("AUTHORIZENET_SANDBOX", $sandbox);       // Set to false to test against production
    define("TEST_REQUEST", "FALSE");           // You may want to set to true if testing against production
    // You only need to adjust the two variables below if testing DPM
    // define("AUTHORIZENET_MD5_SETTING","");                // Add your MD5 Setting.
    // $site_root = "http://YOURDOMAIN/samples/your_store/"; // Add the URL to your site
    //------------------ authorize config  ------------------
}

// 54
function RecoveryPassword($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $login_users_obj = $GetClass->GetClass('login_users');
        $emailmessage_obj = $GetClass->GetClass('emailmessage');
        $login_user = $login_users_obj->getAlllogin_usersForColumnValue('username', '"' . $array['username2'] . '"');
        if ($login_user) {
            $login_user = $login_user[0];
            if (!$login_user->getemail()) {
                echo 'Error : This User not Have a Email, for instructions, please Contact with the Admin';
                exit();
            }
            $iduser = $login_user->getuser_id();
            $NewPass = $array['username2'] . '#F' . rand(10, 999999999);
            $pass = md5($NewPass);
            $pass2 = base64_encode($NewPass);
            $login_users_obj->updatepassword($iduser, $pass);
            $login_users_obj->updatepassz($iduser, $pass2);
            /* Email */
            $email = $emailmessage_obj->getAllemailmessageForColumnValue('type', '"Recovery Password"');
            if ($email) {
                $email = $email[0];
                $body = $email->getbody();
                $subject = $email->getsubject();
            } else {
                $body = '<p>Welcome #UserName# <br>Your Tempory Password is #NewPassword#</p>';
                $subject = 'Recovery Password';
            }
            $body = str_replace(array('#NewPassword#'), $NewPass, $body);
            $body = str_replace(array('#UserName#'), $array['username2'], $body);
            sendGeneralEmail($subject, "info@" . $dbname . ".com", $login_user->getemail(), $body);
            /**/
            echo 'Your Password has Reset, please review your email for more instructions';
        } else {
            echo 'Error : The Username has Invalid, please Review and Re-Send';
        }
    } else {
        echo "Error, An array expected";
    }
}

// 55
function UpdateCreateEmailtemplate($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $emailmessage_obj = $GetClass->GetClass('emailmessage');
        $response = 'Email Template Update Successfully';
        if ($array['update'] == 'insert') {
            $emailmessage_obj->settype($array['type']);
            $array['update'] = $emailmessage_obj->getidemailmessage();
            $response = $array['update'];
        }
        $emailmessage_obj->updatesubject($array['update'], $array['subject']);
        $emailmessage_obj->updatebody($array['update'], $array['body']);
        echo $response;
    } else {
        echo "Error, An array expected";
    }
}

// 56
function Generateics() {
    return '';
    $GetClass = GetClassPsToDb();
    // PHP_EOL
    $event_obj = $GetClass->GetClass('event');
    $transaction_obj = $GetClass->GetClass('transaction');
    $AllEvent = $event_obj->getAllevents();
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $Events = '';
    foreach ($AllEvent as $event) {
        if ($event->getidtransaction()) {
            $transaction = $transaction_obj->gettransactionById($event->getidtransaction());
            $StartDate = explode(' ', $event->getstart_date());
            $StartHour = explode(':', $StartDate[1]);
            $StartDate = explode('-', $StartDate[0]);
            $EndDate = explode(' ', $event->getend_date());
            $EndHour = explode(':', $EndDate[1]);
            $EndDate = explode('-', $EndDate[0]);
            $subject = $event->getsubject() . ' (' . $transaction->gettransactionnumber() . ')';
            $Events .= "BEGIN:VEVENT" . PHP_EOL .
                    "CLASS:PUBLIC" . PHP_EOL .
                    "DTEND:" . $EndDate[0] . $EndDate[1] . $EndDate[2] . 'T' . $EndHour[0] . $EndHour[1] . $EndHour[2] . 'Z' . PHP_EOL .
                    "UID:" . $dbname . "-Ev" . $event->getidevent() . PHP_EOL .
                    "DTSTAMP:" . dateToCal(date('Y-m-d H:i:s')) . PHP_EOL .
                    "LAST-MODIFIED:" . dateToCal(date('Y-m-d H:i:s')) . PHP_EOL .
                    "SUMMARY:" . htmlspecialchars($event->getsubject()) . PHP_EOL .
                    "LOCATION:" . htmlspecialchars($event->getlocation()) . PHP_EOL .
                    "TRANSP:TRANSPARENT" . PHP_EOL .
                    "DESCRIPTION:" . htmlspecialchars($event->getbody_text()) . PHP_EOL .
                    "DTSTART:" . $StartDate[0] . $StartDate[1] . $StartDate[2] . 'T' . $StartHour[0] . $StartHour[1] . $StartHour[2] . 'Z' . PHP_EOL .
                    "END:VEVENT" . PHP_EOL;
        }
    }
    $load = "BEGIN:VCALENDAR" . PHP_EOL .
            "VERSION:2.0" . PHP_EOL .
            "PRODID:-//TS/ivan//NONSGML v2.0//EN" . PHP_EOL .
            "CALSCALE:GREGORIAN" . PHP_EOL .
            "METHOD:PUBLISH" . PHP_EOL .
            "CALNAME:TSCalendar" . PHP_EOL .
            $Events .
            "END:VCALENDAR";
    $path = 'Calendar';
    if (!file_exists($path)) {
        mkdir($path, 0775, true);
        chmod($path, 0775);
    }
    $filename = $path . "/Events" . $dbname . '.ics';
    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    //echo $load;
    $path = $filename;
    $fp = fopen($path, 'w') or die('no');
    fwrite($fp, $load);
    fclose($fp);
    chmod($filename, 0775);
    return $path;
}

function dateToCal($timestamp) {
    return date('Ymd\Tgis\Z', strtotime($timestamp));
}

function escapeString($string) {
    return preg_replace('/([\,;])/', '\\\$1', $string);
}

function GetAllEvents($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $event_obj = $GetClass->GetClass('event');
        $transaction_obj = $GetClass->GetClass('transaction');
        if ($array['id'] == 'All') {
            //$AllEvent = $event_obj->getAlleventForColumnValue('iduser',$idlogin);

            $AllEvent = $event_obj->getAllevents();
            $ArrayReturn = array();
            foreach ($AllEvent as $event) {
                $transaction = $transaction_obj->gettransactionById($event->getidtransaction());
                $StartDate = explode(' ', $event->getstart_date());
                $StartHour = $StartDate[1];
                $StartDate = $StartDate[0];
                $EndDate = explode(' ', $event->getend_date());
                $EndHour = $EndDate[1];
                $EndDate = $EndDate[0];
                $subject = $event->getsubject() . ' (' . $transaction->gettransactionnumber() . ')';
                $ArrayReturn[$event->getidevent()] = $subject . '||' . str_replace('-', '||', $StartDate) . '||' . str_replace(':', '||', $StartHour) . '||' . str_replace('-', '||', $EndDate) . '||' . str_replace(':', '||', $EndHour);
            }
            echo json_encode($ArrayReturn);
        }
        if ($array['id'] == 'export') {
            echo Generateics();
        }
    } else {
        echo "Error, An array expected";
    }
}

// 57
function GetEvents($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $event_obj = $GetClass->GetClass('event');
        $transaction_obj = $GetClass->GetClass('transaction');
        $Eventday = $event_obj->geteventById($array['id']);
        $ArrayReturn = array();
        if ($Eventday) {
            $ArrayReturn['Subject'] = $Eventday->getsubject();
            $ArrayReturn['Location'] = $Eventday->getlocation();
            $ArrayReturn['Body'] = $Eventday->getbody_text();
            $DataTransaction = '';
            if ($Eventday->getidtransaction()) {
                $transaction = $transaction_obj->gettransactionById($Eventday->getidtransaction());
                if ($transaction) {
                    $DataTransaction = $transaction->getname() . ' - ' . $transaction->gettransactionnumber();
                }
            }
            $ArrayReturn['Transaction'] = $DataTransaction;
        }
        echo json_encode($ArrayReturn);
    } else {
        echo "Error, An array expected";
    }
}

// 58
function SaveEmailConfig($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $emailcnf_obj = $GetClass->GetClass('emailcnf');
        $emailcnf = $emailcnf_obj->getemailcnfById(1);
        if($emailcnf){
            $idemaicnf = 1;
        }else{
            $emailcnf_obj->settype($array['type']);
            $idemaicnf = $emailcnf_obj->getidemailcnf();
        }
        $emailcnf_obj->updatetype($idemaicnf,$array['Type']);
        $emailcnf_obj->updateusername($idemaicnf,$array['Username']);
        $emailcnf_obj->updatepass($idemaicnf,$array['Password']);
        $emailcnf_obj->updatehost($idemaicnf,$array['Host']);
        $emailcnf_obj->updateport($idemaicnf,$array['Port']);
        $emailcnf_obj->updatesecure($idemaicnf,$array['Secure']);
        
        echo 'ok';
    } else {
        echo "Error, An array expected";
    }
}

// 59
function UploadPaymentErec($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $erec_recording_obj = $GetClass->GetClass('erec_recording');
        if ($array['type'] == 'get') {
            $erec_recording = $erec_recording_obj->geterec_recordingById($array['id']);
            $path = 'temp/paymenterec' . $array['id'] . '.pdf';
            $fp = fopen($path, 'w') or die('no');
            fwrite($fp, $erec_recording->getpaymentdata());
            fclose($fp);
            echo $path;
        } else {
            $path = 'Server/' . trim($array['path']);
            $fp = fopen($path, "r") or die("can't open File");
            $contenido = fread($fp, filesize($path));
            $erec_recording_obj->updatepayment($array['IdErecRecordingPayment'], '1');
            $erec_recording_obj->updatepaymentdata($array['IdErecRecordingPayment'], $contenido);
            fclose($fp);
            echo 'Update Successfully';
        }
    } else {
        echo "Error, An array expected";
    }
}

// 60
function ChangeStatusErec($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $erec_recording_obj = $GetClass->GetClass('erec_recording');
        $erec_recording_obj->updatestatus($array['id'], $array['value']);
    } else {
        echo "Error, An array expected";
    }
}

// 61
function WFGJackets($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $general_config_obj = $GetClass->GetClass('general_config');
        $general_config = $general_config_obj->getgeneral_configById(1);
        $data = json_decode($general_config->getofficeinfo(), true);
        $data = $data['wfg'];
        $data = $data['test'];
        $urlData = $data['URLDataJacket'];
        $client = new SoapClient("$urlData?wsdl");
        $arraydata = array();
        $arraydata['AgentNumber'] = $data['username'];
        $arraydata['AuthorizationCode'] = $data['password'];
        $dataAgency['AuthenticationData'] = $arraydata;
        if ($array['GetTypes'] == 'GetAgencies') {
            $dataAgency['RequestType'] = 'GetJacketAgenciesRequest';
            $response = $client->GetData($dataAgency);
            $optionsAgencies = '<option value="">Select an Agency</option>';
            if ($response->ResponseType == 'Succeded') {
                $lista = $response->Results->ListValue; //print_r($lista);
                foreach ($response->Results->ListValue as $datos) {
                    $location = $datos->LocationData;
                    //print_r($datos->DisplayText);
                    $optionsAgencies .= '<option value="' . $datos->Value . '" data-address="' . $location->LocationAddress1 . ' ' . $location->LocationCityName . ' ' . $location->LocationStateCode . ' ' . $location->LocationZip . '">' . $datos->DisplayText . '</option>';
                }
            } else {
                $optionsAgencies = '<option value="Error">An Error has Ocurred please Refresh</option>';
            }
            echo $optionsAgencies;
        }
        if ($array['GetTypes'] == 'GetStateCAID') {
            $dataAgency['RequestType'] = 'GetStatesForCAIDRequest';
            $dataAgency['CAID'] = $array['CAID'];
            $response = $client->GetData($dataAgency);
            $optionsState = '<option value="">Select an Agency</option>';
            if ($response->ResponseType == 'Succeded') {
                $lista = $response->Results->ListValue; //print_r($lista);
                foreach ($response->Results->ListValue as $datos) {
                    $optionsState .= '<option value="' . $datos->Value . '" >' . $datos->DisplayText . '</option>';
                }
            } else {
                $optionsState = '<option value="Error">' . $response->ErrorMessage . '</option>';
            }
            echo $optionsState;
        }
    } else {
        echo "Error, An array expected";
    }
}

// 62
function WFGCPLapi($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $general_config_obj = $GetClass->GetClass('general_config');
        $general_config = $general_config_obj->getgeneral_configById(1);
        $data = json_decode($general_config->getofficeinfo(), true);
        $data = $data['wfg'];
        $data = $data['test'];
        $urlData = $data['URLCPL'];
        $client = new SoapClient("$urlData?wsdl");
        $arraydata = array();
        $arraydata['AgentNumber'] = $data['username'];
        $arraydata['AuthorizationCode'] = $data['password'];
        $dataAgency = array();
        $dataAgency['AuthenticationData'] = $arraydata;
        if ($array['GetTypes'] == 'GetAgencies') {
            $dataAgency['RequestType'] = 'GetCplAgenciesRequest';
            $response = $client->GetCplData($dataAgency);
            $optionsAgencies = '<option value="">Select an Agency</option>';
            if ($response->ResponseType == 'Succeded') {
                $lista = $response->Results->ListValue; //print_r($lista);
                foreach ($response->Results->ListValue as $datos) {
                    $location = $datos->LocationData;
                    $optionsAgencies .= '<option value="' . $datos->Value . '" data-address="' . $location->LocationAddress1 . ' ' . $location->LocationCityName . ' ' . $location->LocationStateCode . ' ' . $location->LocationZip . '">' . $datos->DisplayText . '</option>';
                }
            } else {
                $optionsAgencies = '<option value="Error">An Error has Ocurred please Refresh</option>';
            }
            echo $optionsAgencies;
        }
        if ($array['GetTypes'] == 'GetStateCAID') {
            $dataAgency['RequestType'] = 'GetCplPropertyStateRequest';
            $dataAgency['CAID'] = $array['CAID'];
            $response = $client->GetCplData($dataAgency);
            $optionsState = '<option value="">Select an Agency</option>';
            if ($response->ResponseType == 'Succeded') {
                $lista = $response->Results->ListValue; //print_r($lista);
                foreach ($response->Results->ListValue as $datos) {
                    $optionsState .= '<option value="' . $datos->Value . '" >' . $datos->DisplayText . '</option>';
                }
            } else {
                $optionsState = '<option value="Error">' . $response->ErrorMessage . '</option>';
            }
            echo $optionsState;
        }
        if ($array['GetTypes'] == 'GetDocTypes') {
            $dataAgency['RequestType'] = 'GetCplDocumentTypeRequest';
            $dataAgency['CAID'] = $array['CAID'];
            $dataAgency['State'] = $array['State'];
            $response = $client->GetCplData($dataAgency);
            $optionsState = '<option value="">Select an Document Type</option>';
            if ($response->ResponseType == 'Succeded') {
                $lista = $response->Results->ListValue; //print_r($lista);
                foreach ($response->Results->ListValue as $datos) {
                    $optionsState .= '<option value="' . $datos->Value . '" >' . $datos->DisplayText . '</option>';
                }
            } else {
                $optionsState = '<option value="Error">' . $response->ErrorMessage . '</option>';
            }
            echo $optionsState;
        }
    } else {
        echo "Error, An array expected";
    }
}

// 63 
function RequestCancelation($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $transaction_obj = $GetClass->GetClass('transaction');
        $login_users_obj = $GetClass->GetClass('login_users');
        $login_users = $login_users_obj->getlogin_usersById($idlogin);
        $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
        if ($transaction) {
            $processor = $login_users_obj->getlogin_usersById($transaction->getidprocessor());
            $body = 'The User ' . $processor->getnameu() . ' Request Cancel Transaction : ' . $transaction->getidtransaction() . '-' . $transaction->getname();
            echo sendGeneralEmail('Request Cancel Transaction', $login_users->getemail(), $processor->getemail(), $body);
        } else {
            echo 'Error : Please first Select a Valid Transaction';
        }
    } else {
        echo "Error, An array expected";
    }
}

// 64 
function RefreshTokenTwilio($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $myt = $GetClass->GetClass('mytwilio');
        $mytwilio_obj = $GetClass->GetClass('mytwilio');
        $mytwilio = $mytwilio_obj->getmytwilioById('1');
        if (is_object($mytwilio)) {

            $accountSid = $mytwilio->getaccountSid();
            $authToken = $mytwilio->getauthToken();
            $appSid = $mytwilio->getappSid();
            $clientName = $mytwilio->getaccname();
        }
        $capability = new Services_Twilio_Capability($accountSid, $authToken);
        $capability->allowClientOutgoing($appSid);
        //$clientName = true;
        if ($clientName != '') {
            $capability->allowClientIncoming($clientName);
        }
        $token = $capability->generateToken();
        echo $token;
    } else {
        echo "Error, An array expected";
    }
}

// 65 
function Refresh1003($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $loanapp_obj = $GetClass->GetClass('loanapp');
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $loanapp = $loanapp_obj->getAllloanappForColumnValue('idtransaction', "'" . $array['idtransaction'] . "'");
        $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
        $data = array('idtransaction' => $array['idtransaction']);
        if ($loanapp) {
            $loanapp = $loanapp[0];
            if ($loanapp->getdata()) {
                $data = json_decode($loanapp->getdata(), true);
            }
        } else {
            $loanapp_obj->setidtransaction($array['idtransaction']);
            $loanapp = $loanapp_obj->getloanappById($loanapp_obj->getidloanapp());
        }
        if ($array['type'] == 'Get') {
            /**/
            if ($cdhu) {
                
            }
            /**/
            echo json_encode($data);
            exit();
        } else {
            foreach ($array as $key => $value) {
                $data[$key] = $value;
            }
            $loanapp_obj->updatedata($loanapp->getidloanapp(), json_encode($data));
            echo 'Update Successfully';
            exit();
        }
    } else {
        echo "Error, An array expected";
    }
}

// 66 
function RefreshFees($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $fees_obj = $GetClass->GetClass('fees');
        $fees = $fees_obj->getAllfeesForColumnValue('idtransaction', "'" . $array['idtransaction'] . "'");
        $data = array('idtransaction' => $array['idtransaction']);
        if ($fees) {
            $fees = $fees[0];
            if ($fees->getdata()) {
                $data = json_decode($fees->getdata(), true);
            }
        } else {
            $fees_obj->setidtransaction($array['idtransaction']);
            $fees = $fees_obj->getfeesById($fees_obj->getidfees());
        }
        if ($array['type'] == 'Get') {
            echo json_encode($data);
            exit();
        } else {
            foreach ($array as $key => $value) {
                $data[$key] = $value;
            }
            $fees_obj->updatedata($fees->getidfees(), json_encode($data));
            echo 'Update Successfully';
            exit();
        }
    } else {
        echo "Error, An array expected";
    }
}

// 67 
function RefreshIncome($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $income_obj = $GetClass->GetClass('income');
        $income = $income_obj->getAllincomeForColumnValue('idtransaction', "'" . $array['idtransaction'] . "'");
        $data = array('idtransaction' => $array['idtransaction']);
        if ($income) {
            $income = $income[0];
            if ($income->getdata()) {
                $data = json_decode($income->getdata(), true);
            }
        } else {
            $income_obj->setidtransaction($array['idtransaction']);
            $income = $income_obj->getincomeById($income_obj->getidincome());
        }
        if ($array['type'] == 'Get') {
            echo json_encode($data);
            exit();
        } else {
            foreach ($array as $key => $value) {
                $data[$key] = $value;
            }
            $income_obj->updatedata($income->getidincome(), json_encode($data));
            echo 'Update Successfully';
            exit();
        }
    } else {
        echo "Error, An array expected";
    }
}

// 68 
function importloanapp($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $loanapp_obj = $GetClass->GetClass('loanapp');
        $transaction_obj = $GetClass->GetClass('transaction');
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $cdhud = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
        $loanapp = $loanapp_obj->getAllloanappForColumnValue('idtransaction', "'" . $array['idtransaction'] . "'");
        $loanap = $loanapp_obj->getloanappById($array['idloanapp']);
        if ($loanap) {
            $data = json_decode($loanap->getdata(), true);
            $data = json_decode($data['DataPost'], true);
            $datatemploan = '{"idtransaction":"' . $array['idtransaction'] . '","LoanAmountDialog":"' . $data['MortgageAmount'] . '"}';
            $datatempsale = '{"idtransaction":"' . $array['idtransaction'] . '","SalesPriceDialog":"' . $data['PropertyValue'] . '"}';
            if ($cdhud) {
                $cdhud = $cdhud[0];
                $idcdhud = $cdhud->getidcdhud();
            } else {
                $cdhud_obj->setidtransaction($array['idtransaction']);
                $idcdhud = $cdhud_obj->getidcdhud();
            }
            $cdhud_obj->updateLoanAmount($idcdhud, ($datatemploan));
            $cdhud_obj->updateSalesPrice($idcdhud, ($datatempsale));
        } else {
            $data = array();
        }
        if ($loanapp) {
            $loanapp = $loanapp[0];
            $idloanapp = $loanapp->getidloanapp();
            $dataoriginal = json_decode($loanapp->getdata(), true);
        } else {
            $loanapp_obj->setidtransaction($array['idtransaction']);
            $idloanapp = $loanapp_obj->getidloanapp();
            $dataoriginal = array();
        }
        foreach ($data as $k => $v) {
            $dataoriginal[$k] = $v;
        }
        $loanapp_obj->updatedata($idloanapp, json_encode($dataoriginal));
        echo 'Update Data Successfully';
    } else {
        echo "Error, An array expected";
    }
}

// 69 
function fill1003fortransaction($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $loanapp_obj = $GetClass->GetClass('loanapp');
        $file_obj = $GetClass->GetClass('file');
        $loanapp = $loanapp_obj->getAllloanappForColumnValue('idtransaction', "'" . $array['idtransaction'] . "'");
        $files = $file_obj->getAllfilebyComnListwithFilterID('idtransaction', "'" . $array['idtransaction'] . "'", 'idfile,name');
        if ($loanapp) {
            $loanapp = $loanapp[0];
            $idloanapp = $loanapp->getidloanapp();
            $dataoriginal = json_decode($loanapp->getdata(), true);
            if ($dataoriginal['DataPost']) {
                $dataoriginal = json_decode($dataoriginal['DataPost'], true);
            }
        } else {
            $loanapp_obj->setidtransaction($array['idtransaction']);
            $idloanapp = $loanapp_obj->getidloanapp();
            $dataoriginal = json_encode(array());
        }
        $namefile = 'Form1003' . $array['idtransaction'];
        $idfile = '';
        foreach ($files as $file) {
            if ($file->getname() == $namefile) {
                $idfile = $file->getidfile();
            }
        }
        if ($idfile == '') {
            $file_obj->setidtransaction($array['idtransaction']);
            $idfile = $file_obj->getidfile();
            $file_obj->updateidsection($idfile, 1);
            $file_obj->updatename($idfile, $namefile);
            $file_obj->updatetype($idfile, 'pdf');
        }
        $path = FillPdf2($dataoriginal);
        if (file_exists($path)) {
            chmod($path, 0775);
            $fp = fopen($path, "r") or die("can't open File");
            $content = fread($fp, filesize($path));
            $file_obj->updatecontent($idfile, $content);
            echo $idfile;
        } else {
            echo 'Error : File not found ' . $path;
        }
    } else {
        echo "Error, An array expected";
    }
}

function FillPdf2($data) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    if ($data) {//print_r($data);
        //$data = json_decode($data, true);
        /* Target Signs */
        if (!trim($data['RefererRealtor'])) {
            $data['RefererRealtor'] = 'Not Declared';
        }
        if (trim($data['RealtorCompany'])) {
            if (trim($data['RefererRealtor'])) {
                $data['RefererRealtor'] .= ' - ' . $data['RealtorCompany'];
            } else {
                $data['RefererRealtor'] = $data['RealtorCompany'];
            }
        }
        if (trim($data['RealtorPhone'])) {
            if (trim($data['RefererRealtor'])) {
                $data['RefererRealtor'] .= ' - ' . $data['RealtorPhone'];
            } else {
                $data['RefererRealtor'] = $data['RealtorPhone'];
            }
        }
        if (trim($data['RealtorEmail'])) {
            if (trim($data['RefererRealtor'])) {
                $data['RefererRealtor'] .= ' - ' . $data['RealtorEmail'];
            } else {
                $data['RefererRealtor'] = $data['RealtorEmail'];
            }
        }
        if ($data['TransactionType']) {
            $data['PurposeofLoan'] = $data['TransactionType'];
        }
        if ($data['SamePrimaryCoBorrower']) {
            $data['CoBorrower_Street'] = $data['Borrower_Street'];
            $data['CoBorrower_City'] = $data['Borrower_City'];
            $data['CoBorrower_State'] = $data['Borrower_State'];
            $data['CoBorrower_Zip'] = $data['Borrower_Zip'];
        }
        /* Coborrower */
        if ($data['Have_CoBorrower']) {
            $namepdf1003 = 'FormApplication1003Co.pdf';
        } else {
            $namepdf1003 = 'FormApplication1003.pdf';
        }
        /**/
        /* IncomeSourceAmountTotal */
        $num1 = 0;
        $num2 = 0;
        $num3 = 0;
        if ($data['B_C_1_Amount']) {
            $num1 = str_replace(array('$', ','), '', $data['B_C_1_Amount']);
        }
        if ($data['B_C_2_Amount']) {
            $num2 = str_replace(array('$', ','), '', $data['B_C_2_Amount']);
        }
        if ($data['B_C_3_Amount']) {
            $num3 = str_replace(array('$', ','), '', $data['B_C_3_Amount']);
        }
        $data['IncomeSourceAmountTotal'] = $num1 + $num2 + $num3;
        /**/
        $num1 = 0;
        $num2 = 0;
        $num3 = 0;
        if ($data['CoBorrowerB_C_1_Amount']) {
            $num1 = str_replace(array('$', ','), '', $data['CoBorrowerB_C_1_Amount']);
        }
        if ($data['CoBorrowerB_C_2_Amount']) {
            $num2 = str_replace(array('$', ','), '', $data['CoBorrowerB_C_2_Amount']);
        }
        if ($data['CoBorrowerB_C_3_Amount']) {
            $num3 = str_replace(array('$', ','), '', $data['CoBorrowerB_C_3_Amount']);
        }
        $data['CoBorrowerIncomeSourceAmountTotal'] = $num1 + $num2 + $num3;
        /**/
        $totalAssets = 0;
        for ($i = 1; $i <= 5; $i++) {
            if ($data['AssetAmount' . $i]) {
                $totalAssets = $totalAssets + str_replace(array('$', ','), '', $data['AssetAmount' . $i]);
            }
        }
        $data['AssetAmountTotal'] = $totalAssets;
        /**/
        /* Complete Data */
        $data['Text9'] = $data['Street'] . ' ' . $data['City'] . ' ' . $data['State'] . ' ' . $data['Zip'];
        $AgesBorrower = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($data['Borrower_Ages_' . $i]) {
                if ($AgesBorrower == '') {
                    $AgesBorrower .= $data['Borrower_Ages_' . $i];
                } else {
                    $AgesBorrower .= ',' . $data['Borrower_Ages_' . $i];
                }
            }
        }

        $AgesCoBorrower = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($data['CoBorrower_Ages_' . $i]) {
                if ($AgesBorrower == '') {
                    $AgesCoBorrower .= $data['CoBorrower_Ages_' . $i];
                } else {
                    $AgesCoBorrower .= ',' . $data['CoBorrower_Ages_' . $i];
                }
            }
        }
        if ($data['PropertyAdressOrigen'] == 'Have') {
            //$data['Zip'] = $data['PropertyZip2'];
        }
        $data['DependentAgeYearsCount'] = $AgesBorrower;
        $data['CoBorrowerDependentAgeYearsCount'] = $AgesCoBorrower;

        $data['Borrower_Address'] = $data['Borrower_Street'] . ' ' . $data['Borrower_City'] . ' ' . $data['Borrower_State'] . ' ' . $data['Borrower_Zip'];
        $data['CoBorrower_Address'] = $data['CoBorrower_Street'] . ' ' . $data['CoBorrower_City'] . ' ' . $data['CoBorrower_State'] . ' ' . $data['CoBorrower_Zip'];

        $data['Borrower_Mailing_Address'] = $data['Borrower_MailingStreet'] . ' ' . $data['Borrower_MailingCity'] . ' ' . $data['Borrower_MailingState'] . ' ' . $data['Borrower_MailingZip'];
        $data['CoBorrower_Mailing_Address'] = $data['CoBorrower_MailingStreet'] . ' ' . $data['CoBorrower_MailingCity'] . ' ' . $data['CoBorrower_MailingState'] . ' ' . $data['CoBorrower_MailingZip'];

        $data['Borrower_FormerAddress'] = $data['Borrower_FormerStreet'] . ' ' . $data['Borrower_FormerCity'] . ' ' . $data['Borrower_FormerState'] . ' ' . $data['Borrower_FormerZip'];
        $data['CoBorrower_FormerAddress'] = $data['CoBorrower_FormerStreet'] . ' ' . $data['CoBorrower_FormerCity'] . ' ' . $data['CoBorrower_FormerState'] . ' ' . $data['CoBorrower_FormerZip'];

        $data['Borrower_Former2Address'] = $data['Borrower_Former2Street'] . ' ' . $data['Borrower_Former2City'] . ' ' . $data['Borrower_Former2State'] . ' ' . $data['Borrower_Former2Zip'];
        $data['CoBorrower_Former2Address'] = $data['CoBorrower_Former2Street'] . ' ' . $data['CoBorrower_Former2City'] . ' ' . $data['CoBorrower_Former2State'] . ' ' . $data['CoBorrower_Former2Zip'];

        $data['Borrower_Former2Address'] = $data['Borrower_Former2Street'] . ' ' . $data['Borrower_Former2City'] . ' ' . $data['Borrower_Former2State'] . ' ' . $data['Borrower_Former2Zip'];
        $data['CoBorrower_Former2Address'] = $data['CoBorrower_Former2Street'] . ' ' . $data['CoBorrower_Former2City'] . ' ' . $data['CoBorrower_Former2State'] . ' ' . $data['CoBorrower_Former2Zip'];

        $data['Name_Borrower'] = $data['Name_TitleBorrower'] . ' ' . $data['Name_FirstBorrower'] . ' ' . $data['Name_MiddleBorrower'] . ' ' . $data['Name_LastBorrower'];
        $data['Name_CoBorrower'] = $data['Name_TitleCoBorrower'] . ' ' . $data['Name_FirstCoBorrower'] . ' ' . $data['Name_MiddleCoBorrower'] . ' ' . $data['Name_LastCoBorrower'];
        /**/
        for ($i = 1; $i <= 20; $i++) {
            $data['BorrowerNameFooter' . $i] = $data['Name_Borrower'];
        }
        $data['LoanName'] = 'A Class Loans LLC';
        $data['LoanAddress'] = '1600 S Dixie Hwy #500A, Boca Raton, FL 33432';
        $data['LoanNMLS'] = '1930600';
        $data['LoanId'] = '';
        $data['LoanOriginationName'] = 'Glenn Fradera';
        $data['LoanOriginationNMLS'] = '373200';
        $data['LoanStateId'] = '';
        $data['LoanEmail'] = 'info@aclassloans.com';
        $data['LoanPhone1'] = '561';
        $data['LoanPhone2'] = '220';
        $data['LoanPhone3'] = '4722';
        $data['LoanName1'] = 'A Class Loans LLC';
        $data['LoanAddress1'] = '1600 S Dixie Hwy #500A, Boca Raton, FL 33432';
        $data['LoanNMLS1'] = '1930600';
        $data['LoanId1'] = '';
        $data['LoanOriginationName1'] = 'Glenn Fradera';
        $data['LoanOriginationNMLS1'] = '373200';
        $data['LoanStateId1'] = '';
        $data['LoanEmail1'] = 'info@aclassloans.com';
        $data['LoanPhone11'] = '561';
        $data['LoanPhone21'] = '220';
        $data['LoanPhone31'] = '4722';

        /**/
        /* Dates */
        $data['Borrower_Dates_1'] = $data['StarBorrower_1'] . ' to ' . $data['EndBorrower_1'];
        $data['Borrower_Dates_2'] = $data['StarBorrower_2'] . ' to ' . $data['EndBorrower_2'];
        $data['Borrower_Dates_3'] = $data['StarBorrower_3'] . ' to ' . $data['EndBorrower_3'];
        $data['Borrower_Dates_4'] = $data['StarBorrower_4'] . ' to ' . $data['EndBorrower_4'];

        $data['CoBorrower_Dates_1'] = $data['CoStarBorrower_1'] . ' to ' . $data['CoEndBorrower_1'];
        $data['CoBorrower_Dates_2'] = $data['CoStarBorrower_2'] . ' to ' . $data['CoEndBorrower_2'];
        $data['CoBorrower_Dates_3'] = $data['CoStarBorrower_3'] . ' to ' . $data['CoEndBorrower_3'];
        $data['CoBorrower_Dates_4'] = $data['CoStarBorrower_4'] . ' to ' . $data['CoEndBorrower_4'];
        /**/
        //$data['Borrower_Address_Employer'] = $data['Borrower_Address_Employer'] . ' ' . $data['Borrower_City_Employer'] . ' ' . $data['Borrower_State_Employer'] . ' ' . $data['Borrower_Zip_Employer'];
        //$data['CoBorrower_Address_Employer'] = $data['CoBorrower_Address_Employer'] . ' ' . $data['CoBorrower_City_Employer'] . ' ' . $data['CoBorrower_State_Employer'] . ' ' . $data['CoBorrower_Zip_Employer'];
        for ($i = 1; $i <= 4; $i++) {
            $data['Borrower_Address_Employer_' . $i] = $data['Borrower_Address1_Employer_' . $i] . ' ' . $data['Borrower_City_Employer_' . $i] . ' ' . $data['Borrower_State_Employer_' . $i] . ' ' . $data['Borrower_Zip_Employer_' . $i];
            $data['CoBorrower_Address_Employer_' . $i] = $data['CoBorrower_Address1_Employer_' . $i] . ' ' . $data['CoBorrower_City_Employer_' . $i] . ' ' . $data['CoBorrower_State_Employer_' . $i] . ' ' . $data['CoBorrower_Zip_Employer_' . $i];
        }
        $data['Liability_Address'] = $data['Liability_Address1'] . ' ' . $data['Liability_City'] . ' ' . $data['Liability_State'] . ' ' . $data['Liability_Zip'];
        $data['Liability_Payment'] = $data['Liability_Payment1'] . '/' . $data['Liability_PaymentMonth'];
        for ($i = 1; $i <= 5; $i++) {
            $data['Liability_Address_' . $i] = $data['Liability_Address1_' . $i] . ' ' . $data['Liability_City_' . $i] . ' ' . $data['Liability_State_' . $i] . ' ' . $data['Liability_Zip_' . $i];
            $data['Liability_Payment_' . $i] = $data['Liability_Payment1_' . $i] . '/' . $data['Liability_PaymentMonth_' . $i];
        }
        /**/
        $languaje = '';
        if ($data['NewLanguage'] == 'Spanish') {
            $languaje = 'Spanish';
        }
        $namefdf = 'Aplication' . date('YmdHis');
        $pdf_form_url = "source/" . $namepdf1003;
        if ($data['BorrowerResidencyDurationYearsCount']) {
            $DetailPrev = $data['BorrowerResidencyDurationYearsCount'];
            $Detail = explode('-', $data['BorrowerResidencyDurationYearsCount']);
            if ($Detail[1] == 'm') {
                $monthcount = $Detail[0];
            } else {
                $monthcount = $Detail[0] * 12;
            }
            //$data['BorrowerResidencyDurationYearsCount'] = $monthcount;
        }
        if ($data['CoBorrowerResidencyDurationYearsCount']) {
            $DetailPrevCo = $data['CoBorrowerResidencyDurationYearsCount'];
            $Detail = explode('-', $data['CoBorrowerResidencyDurationYearsCount']);
            if ($Detail[1] == 'm') {
                $monthcount = $Detail[0];
            } else {
                $monthcount = $Detail[0] * 12;
            }
            //$data['BorrowerResidencyDurationYearsCount'] = $monthcount;
        }
        /* tel */
        $phone = $data['Borrower_Phone'];
        $phone = explode('-', $phone);
        $data['Borrower_Phone1'] = $phone[0];
        $data['Borrower_Phone2'] = $phone[1];
        $data['Borrower_Phone3'] = $phone[2];
        $phone = $data['CoBorrower_Phone'];
        $phone = explode('-', $phone);
        $data['CoBorrower_Phone1'] = $phone[0];
        $data['CoBorrower_Phone2'] = $phone[1];
        $data['CoBorrower_Phone3'] = $phone[2];
        /**/
        /* empphone1 */
        if ($data['Borrower_Bissiness_Phone']) {
            $temp = $data['Borrower_Bissiness_Phone'];
            $temp = explode('-', $temp);
            $data['Borrower_Phone_Employer1'] = $temp[0];
            $data['Borrower_Phone_Employer2'] = $temp[1];
            $data['Borrower_Phone_Employer3'] = $temp[2];
        }
        if ($data['CoBorrower_Bissiness_Phone']) {
            $temp = $data['CoBorrower_Bissiness_Phone'];
            $temp = explode('-', $temp);
            $data['CoBorrower_Phone_Employer1'] = $temp[0];
            $data['CoBorrower_Phone_Employer2'] = $temp[1];
            $data['CoBorrower_Phone_Employer3'] = $temp[2];
        }
        /**/
        /* empphone2 */
        if ($data['Borrower_Bissiness_Phone1']) {
            $temp = $data['Borrower_Bissiness_Phone1'];
            $temp = explode('-', $temp);
            $data['Borrower_Phone_Employer11'] = $temp[0];
            $data['Borrower_Phone_Employer12'] = $temp[1];
            $data['Borrower_Phone_Employer13'] = $temp[2];
        }
        if ($data['CoBorrower_Bissiness_Phone1']) {
            $temp = $data['CoBorrower_Bissiness_Phone1'];
            $temp = explode('-', $temp);
            $data['CoBorrower_Phone_Employer11'] = $temp[0];
            $data['CoBorrower_Phone_Employer12'] = $temp[1];
            $data['CoBorrower_Phone_Employer13'] = $temp[2];
        }
        /**/
        /* StartDate */
        if ($data['Borrower_StartDate']) {
            $temp = $data['Borrower_StartDate'];
            $temp = explode('/', $temp);
            $data['Borrower_StartDate1_Employer'] = $temp[0];
            $data['Borrower_StartDate2_Employer'] = $temp[1];
            $data['Borrower_StartDate3_Employer'] = $temp[2];
        }
        if ($data['CoBorrower_StartDate']) {
            $temp = $data['CoBorrower_StartDate'];
            $temp = explode('/', $temp);
            $data['CoBorrower_StartDate1_Employer'] = $temp[0];
            $data['CoBorrower_StartDate2_Employer'] = $temp[1];
            $data['CoBorrower_StartDate3_Employer'] = $temp[2];
        }
        /**/
        /* StartDate2 */
        if ($data['Borrower_StartDate1']) {
            $temp = $data['Borrower_StartDate1'];
            $temp = explode('/', $temp);
            $data['Borrower_StartDate1_Employer1'] = $temp[0];
            $data['Borrower_StartDate2_Employer1'] = $temp[1];
            $data['Borrower_StartDate3_Employer1'] = $temp[2];
        }
        if ($data['CoBorrower_StartDate1']) {
            $temp = $data['CoBorrower_StartDate1'];
            $temp = explode('/', $temp);
            $data['CoBorrower_StartDate1_Employer1'] = $temp[0];
            $data['CoBorrower_StartDate2_Employer1'] = $temp[1];
            $data['CoBorrower_StartDate3_Employer1'] = $temp[2];
        }
        /**/
        /* ssn */
        if ($data['Borrower_SSN']) {
            $ssn = $data['Borrower_SSN'];
            $ssn = explode('-', $ssn);
            $data['Borrower_SSN1'] = $ssn[0];
            $data['Borrower_SSN2'] = $ssn[1];
            $data['Borrower_SSN3'] = $ssn[2];
        }
        if ($data['CoBorrower_SSN']) {
            $ssn = $data['CoBorrower_SSN'];
            $ssn = explode('-', $ssn);
            $data['CoBorrower_SSN1'] = $ssn[0];
            $data['CoBorrower_SSN2'] = $ssn[1];
            $data['CoBorrower_SSN3'] = $ssn[2];
        }
        /**/
        /* Birtday */
        if ($data['BorrowerBirthDate']) {
            $BirthDate = $data['BorrowerBirthDate'];
            $BirthDate = explode('/', $BirthDate);
            $data['BorrowerBirthDate1'] = $BirthDate[0];
            $data['BorrowerBirthDate2'] = $BirthDate[1];
            $data['BorrowerBirthDate3'] = $BirthDate[2];
        }
        if ($data['CoBorrowerBirthDate']) {
            $BirthDate = $data['CoBorrowerBirthDate'];
            $BirthDate = explode('/', $BirthDate);
            $data['CoBorrowerBirthDate1'] = $BirthDate[0];
            $data['CoBorrowerBirthDate2'] = $BirthDate[1];
            $data['CoBorrowerBirthDate3'] = $BirthDate[2];
        }
        /**/
        $fdf_data_strings = $data;
        if ($data['BorrowerResidencyDurationYearsCount']) {
            //$data['BorrowerResidencyDurationYearsCount'] = $DetailPrev;
        }
        if ($data['CoBorrowerResidencyDurationYearsCount']) {
            //$data['CoBorrowerResidencyDurationYearsCount'] = $DetailPrevCo;
        }
        //print_r($data);
        if (!file_exists('temp')) {
            /* mkdir('temp', 775, true);
              $file = 'Test.php';
              $fh = fopen($file, 'w') or die("can't open file");
              fwrite($fh, "mkdir('temp', 775, true);");
              fclose($fh);
              die('creando'); */
        }
        //mkdir('temp',775,true);
        $fdf_data_names = array();
        $fields_hidden = array();
        $fields_readonly = array();
        $resultado_fdf = forge_fdf($pdf_form_url, $fdf_data_strings, $fdf_data_names, $fields_hidden, $fields_readonly);
        $newPath = "temp/" . $namefdf . ".fdf";
        file_put_contents($newPath, $resultado_fdf);
        //chmod($newPath, 0775);
        /**/

        /**/
        $vari = "pdftk source/" . $namepdf1003 . " fill_form temp/" . $namefdf . ".fdf output temp/" . $namefdf . "2.pdf 2>&1";
        $return = shell_exec($vari);
        //print_r($return);
        $path = 'temp/' . $namefdf . '2.pdf';
        if (file_exists($path)) {
            unlink("temp/" . $namefdf . ".fdf");
            return $path;
        } else {
            return 'no ' . $return . ' -';
        }
    }
}

// 70 
function GetDataLoanEstimate($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $loanapp_obj = $GetClass->GetClass('loanapp');
        $file_obj = $GetClass->GetClass('file');
        $array['path'] = 'Server/' . $array['path'];
        if ($array['path']) {
            if (file_exists($array['path'])) {
                $pathDirectory = 'Dec' . date('mdYHis');
                mkdir('temp/' . $pathDirectory, 0775);
                if (file_exists('temp/' . $pathDirectory)) {
                    $pathFile = 'File' . date('mdYHis') . rand(1, 999) . '.pdf';
                    $pathFileXml = 'temp/' . $pathDirectory . '/' . str_replace('.pdf', '.xml', $pathFile);
                    if (rename($array['path'], 'temp/' . $pathDirectory . '/' . $pathFile)) {
                        chmod('temp/' . $pathDirectory . '/' . $pathFile, 0775);
                        $vari = "pdftohtml -enc UTF-8 -xml temp/$pathDirectory/$pathFile 2>&1";
                        $exec = shell_exec($vari);
                        //var_dump(shell_exec('ls temp/'.$pathDirectory));
                        //sleep(2);
                        //chmod($pathFileXml, 0777);
                        //exit();
                        if (file_exists($pathFileXml)) {
                            $fp = fopen($pathFileXml, "r") or die("can't open File xml");
                            $content = fread($fp, filesize($pathFileXml));
                            fclose($fp);
                            $exe = new SimpleXMLElement($content);
                            $xml = $exe->asXML();
                            $xml = simplexml_load_string($xml);
                            $json = json_encode($xml);
                            $json = str_replace('@attributes', 'attributes', $json);
                            $json = json_decode($json, true);
                            if ($idlogin == 1) {
                                //print_r($json);
                                /* $exectes = 'tesseract --version 2>&1';
                                  $test = shell_exec($exectes);
                                  print_r($test); */
                            }
                            if (!is_array($json['page'][0]['text'][0])) {
                                if ($idlogin == 1) {
                                    PdfNumber3($json);
                                } else {
                                    PdfNumber2($json);
                                }
                            } else {
                                PdfNumber1($json);
                            }
                            rmDir_rf('temp/' . $pathDirectory);
                            exit();
                        } else {
                            rmDir_rf('temp/' . $pathDirectory);
                            echo 'Error : Not Found xml';
                        }
                    } else {
                        echo 'Error : Not Move File Moved';
                    }
                } else {
                    echo 'Error : Not Create Folder';
                }
            } else {
                echo 'Error : Not Found File';
            }
        } else {
            echo 'Error : Not Have Path';
        }
    } else {
        echo "Error, An array expected";
    }
}

function PdfNumber1($json) {
    $Array_returnpage1 = array();
    $Array_returnpage2 = array();
    $Array_returnpage3 = array();
    $indice = '';
    foreach ($json['page'][0]['text'] as $key => $value) {
        if ($value == 'Principal & Interest' || $value == 'Mortgage Insurance' || $value == 'Estimated Escrow') {
            $add = 1;
            if ($value == 'Mortgage Insurance') {
                $add = 2;
            }
            if ($value == 'Estimated Escrow') {
                $add = 3;
            }
            $Array_returnpage1[$value] .= $json['page'][0]['text'][$key + $add];
        } else {
            if (is_array($value)) {
                if ($value['b']) {
                    $indice = $value['b'];
                }
            } else {
                if ($value != 'Closing Information' && $value != 'Transaction Information' && $value != 'Loan Information') {
                    if ($indice == 'Years 1-30') {
                        $indice = '';
                    }
                    if ($indice && $value != 'Principal & Interest') {
                        $Array_returnpage1[$indice] .= ' ' . $value;
                        $indice = '';
                    }
                    //print_r($value);
                }
            }
        }
    }
    $array_inits = array('A', 'B', 'C', 'E', 'F', 'G', 'H');
    $indice_now = '';
    $indiceline = 1;
    foreach ($json['page'][1]['text'] as $key => $value) {
        if (is_array($value)) {
            if ($value['b']) {
                $Name = $value['b'];
                $Name = explode('.', $Name);
                if (in_array($Name[0], $array_inits)) {
                    $Array_returnpage2['titulo_' . $Name[0]] = $value['b'];
                    $indice_now = $Name[0];
                    $indiceline = 1;
                }
            }
        } else {
            if ($indiceline < 10) {
                $indiceline = '0' . $indiceline;
            }
            if ($value == $indiceline) {
                $indiceline++;
                //$Array_returnpage2[$indice_now . '_' . $value] = $indice;
                if ($indice_now == 'E') {
                    $Column = 1;
                    $valorCorrelativo = 1;
                    for ($i = 1; $i <= 10; $i++) {
                        //echo $json['page'][1]['text'][$key+$i]['b'].'******';
                        if ($json['page'][1]['text'][$key + $i] != $indiceline) {
                            if (is_array($json['page'][1]['text'][$key + $i])) {
                                if ($json['page'][1]['text'][$key + $i]['b']) {
                                    $i = 11;
                                } else {
                                    $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = '';
                                }
                            } else {
                                if (trim($json['page'][1]['text'][$key + $i]) == 'Deed:') {
                                    if (!isNumeric($json['page'][1]['text'][$key + $i + 1])) {
                                        $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = $json['page'][1]['text'][$key + $i];
                                        $valorCorrelativo++;
                                        $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = '';
                                    } else {
                                        $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = $json['page'][1]['text'][$key + $i];
                                    }
                                } else {
                                    if (strpos($json['page'][1]['text'][$key + $i], 'to ') > 5) {
                                        $text = explode('to ', $json['page'][1]['text'][$key + $i]);
                                        $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = $text[0];
                                        $valorCorrelativo++;
                                        $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = 'to ' . $text[1];
                                    } else {
                                        $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = $json['page'][1]['text'][$key + $i];
                                    }
                                    //$Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = $json['page'][1]['text'][$key+$i];
                                }
                            }
                        } else {
                            $i = 11;
                        }
                        $valorCorrelativo++;
                    }
                } else {
                    if ($indice_now == 'G') {
                        $stringG = '';
                        for ($i = 1; $i <= 16; $i++) {
                            //echo $json['page'][1]['text'][$key+$i] .'-'. $indiceline.'*******';
                            if ($json['page'][1]['text'][$key + $i] != $indiceline) {
                                if (is_array($json['page'][1]['text'][$key + $i])) {
                                    if ($json['page'][1]['text'][$key + $i]['b']) {
                                        $i = 17;
                                    } else {
                                        $stringG .= ' BlankCamp';
                                    }
                                } else {
                                    $stringG .= ' ' . $json['page'][1]['text'][$key + $i];
                                }
                                $Array_returnpage2[$indice_now . '_' . $value] = $stringG;
                            } else {
                                $i = 17;
                            }
                        }
                    } else {
                        $Column = 1;
                        $valorCorrelativo = 1;
                        $limite = 3;
                        for ($i = 1; $i <= $limite; $i++) {
                            if ($json['page'][1]['text'][$key + $i] != $indiceline) {
                                if (is_array($json['page'][1]['text'][$key + $i])) {
                                    $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = '';
                                } else {
                                    if (trim($json['page'][1]['text'][$key + $i]) == 'mo.)') {
                                        $limite = 8;
                                    }
                                    if (strpos($json['page'][1]['text'][$key + $i], 'to ') > 5) {
                                        $text = explode('to ', $json['page'][1]['text'][$key + $i]);
                                        $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = $text[0];
                                        $valorCorrelativo++;
                                        $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = 'to ' . $text[1];
                                    } else {
                                        $Array_returnpage2[$indice_now . '_' . $value . '_' . $valorCorrelativo] = $json['page'][1]['text'][$key + $i];
                                    }
                                }
                            }
                            $valorCorrelativo++;
                        }
                    }
                }
            }
        }
    }
    $array_inits = array('K', 'L', 'M', 'N');
    foreach ($json['page'][2]['text'] as $key => $value) {//echo '|'.$value.'-';
        if (is_array($value)) {
            if ($value['b']) {
                $Name = $value['b'];
                $Name = explode('.', $Name);
                if (in_array($Name[0], $array_inits) && count($Name) > 1) {//print_r($value);
                    $Array_returnpage3['titulo_' . $Name[0]] = $value['b'];
                    $indice_now = $Name[0];
                    $indiceline = 1;
                }
            }
        } else {//echo $value .'*******';
            if ($indiceline < 10) {
                $indiceline = '0' . $indiceline;
            }

            if ($value == $indiceline) {
                $indiceline++;
                $valorCorrelativo = 1;
                $string3 = '';
                for ($i = 1; $i <= 10; $i++) {
                    //echo $json['page'][2]['text'][$key + $i] .'-'. $indiceline.'******';

                    if ($json['page'][2]['text'][$key + $i] != $indiceline) {
                        if (is_array($json['page'][2]['text'][$key + $i])) {
                            if ($json['page'][2]['text'][$key + $i]['b']) {
                                $i = 11;
                            } else {
                                $string3 .= ' BlankCamp';
                            }
                        } else {
                            $string3 .= ' ' . $json['page'][2]['text'][$key + $i];
                        }
                        $Array_returnpage3[$indice_now . '_' . $value] = $string3;
                    } else {
                        $i = 17;
                    }
                    $valorCorrelativo++;
                }
            }
        }
    }
    //print_r($Array_returnpage3);
    $array_return = array();
    $array_return['Page1'] = $Array_returnpage1;
    $array_return['Page2'] = $Array_returnpage2;
    $array_return['Page3'] = $Array_returnpage3;
    print_r(json_encode($array_return));
}

function PdfNumber2($json) {
    $Array_returnpage1 = array();
    $Array_returnpage2 = array();
    $Array_returnpage3 = array();
    /* page1 */
    //print_r($json['page'][0]['text']);
    //print_r($json['page'][1]['text']);
    /**/
    /* page 2 */
    $array_inits = array('A', 'B', 'C', 'E', 'F', 'G', 'H');
    $array_initsNO = array('D', 'I', 'J');
    $indice = '';
    $counterColumn = 1;
    $indice_now = '';
    $StringforG = '';
    /* print_r($json['page'][1]['text']);
      exit(); */
    $TitleColumn = '';
    $fila = 1;
    $IndiceCol = '';
    $temporal = '';
    $temporalG = '';
    $InitCol = 1;
    $flag = true;
    $flagG = 1;
    foreach ($json['page'][1]['text'] as $key => $value) {
        if (is_array($value) && $value['b']) {
            $Name = $value['b'];
            $Name = explode('. ', $Name);
            //print_r($Name);
            if (in_array($Name[0], $array_inits)) {
                $IndiceCol = $Name[0];
                $Array_returnpage2['Title_' . $IndiceCol] = $value['b'];
                //print_r($value['b']);
                //print_r($Array_returnpage2);
                $InitCol = 1;
            } else {
                if (in_array($Name[0], $array_initsNO)) {
                    $IndiceCol = '';
                }
            }
        } else {
            if ($IndiceCol == 'G') {
                /**/
                if (!is_array($value)) {
                    if ($flagG <= 2) {
                        /* if ($InitCol < 10) {
                          $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_1'] = $value;
                          } else {
                          $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_1'] = $value;
                          } */
                        $temporalG = trim($temporalG . ' ' . $value);
                        $flagG++;
                    } else {
                        $Amountvalue = str_replace(array('$', ',', '-'), '', $value);
                        //print_r('**'.$Amountvalue.'**');
                        if (is_numeric($Amountvalue)) {
                            if ($InitCol < 10) {
                                $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_1'] = $temporalG;
                                $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_2'] = $value;
                            } else {
                                $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_1'] = $value;
                                $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_2'] = $value;
                            }
                            $InitCol++;
                            $flagG = 1;
                        } else {
                            $temporalG = $value;
                            $flagG = 2;
                        }
                    }
                }
                /**/
            } else {
                if ($IndiceCol && in_array($IndiceCol, $array_inits)) {
                    if (!is_array($value)) {
                        if ($flag) {
                            /* if ($InitCol < 10) {
                              $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_1'] = $value;
                              } else {
                              $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_1'] = $value;
                              } */
                            $temporal = $value;
                            $flag = false;
                        } else {
                            $Amountvalue = str_replace(array('$', ',', '-'), '', $value);
                            //print_r('**'.$Amountvalue.'**');
                            if (is_numeric($Amountvalue)) {
                                if ($InitCol < 10) {
                                    $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_1'] = $temporal;
                                    $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_2'] = $value;
                                } else {
                                    $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_1'] = $value;
                                    $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_2'] = $value;
                                }
                                $InitCol++;
                                $flag = true;
                            } else {
                                $temporal = $value;
                                $flag = false;
                            }
                        }
                    }
                }
            }
        }
    }
    $array_return = array();
    $array_return['Page1'] = $Array_returnpage1;
    $array_return['Page2'] = $Array_returnpage2;
    $array_return['Page3'] = $Array_returnpage3;
    print_r(json_encode($array_return));
    exit();
    foreach ($json['page'][1]['text'] as $key => $value) {
        if (is_array($value)) {
            if ($value['b']) {
                $Name = $value['b'];
                $Name = explode('.', $Name);
                if ($indice_now == 'G') {
                    if (trim($StringforG)) {
                        if ($counterColumn > 1) {
                            if ($indice != '08') {
                                $ind = 7;
                            } else {
                                $ind = 6;
                            }
                            for ($i = $counterColumn; $i <= $ind; $i++) {
                                //if(!$Array_returnpage2[$indice_now . '_' . $indice . '_' . $i]){
                                $StringforG .= ' BlankCamp';
                                //}
                            }
                        }
                        $Array_returnpage2[$indice_now . '_' . $indice] = $StringforG;
                    }
                    $StringforG = '';
                }
                if ($indice_now == 'E' && $indice == '02') {
                    if ($counterColumn < 7) {
                        //$altI = str_replace('0','',$indiceline)+1;
                        for ($i = $counterColumn; $i <= 7; $i++) {
                            $Array_returnpage2[$indice_now . '_' . $indice . '_' . $i] = '';
                        }
                    }
                }

                if (in_array($Name[0], $array_inits) && count($Name) > 1) {//print_r($value);
                    $Array_returnpage2['titulo_' . $Name[0]] = $value['b'];
                    $indice_now = $Name[0];
                    $indiceline = 1;
                }

                if (in_array($Name[0], $array_initsNO) && count($Name) > 1) {
                    if ($counterColumn > 1) {
                        //$altI = str_replace('0','',$indiceline)+1;
                        for ($i = $counterColumn; $i <= 7; $i++) {
                            if ($indice_now != 'G') {
                                $Array_returnpage2[$indice_now . '_' . $indice . '_' . $i] = '';
                            }
                        }
                    }
                    $indice_now = '';
                }

                $counterColumn = 1;
            }
        } else {
            if ($indice_now) {
                if ($indiceline < 9) {
                    $indiceline = str_replace('0', '', $indiceline);
                    $indiceline = '0' . $indiceline;
                }
                if (trim($value) == $indiceline) {
                    if ($indice_now == 'G') {
                        if ($counterColumn > 1) {
                            for ($i = $counterColumn; $i <= 7; $i++) {
                                //if(!$Array_returnpage2[$indice_now . '_' . $indice . '_' . $i]){
                                $StringforG .= ' BlankCamp';
                                //}
                            }
                        }
                        if (trim($StringforG)) {
                            $Array_returnpage2[$indice_now . '_' . $indice] = $StringforG;
                        }
                        $StringforG = '';
                    }
                    if ($counterColumn > 1) {
                        $limite = 7;
                        if ($indice_now == 'E' && $indice == '01') {
                            $limite = 10;
                        } else {
                            $limite = 7;
                        }
                        for ($i = $counterColumn; $i <= $limite; $i++) {
                            if ($indice_now != 'G') {
                                $Array_returnpage2[$indice_now . '_' . $indice . '_' . $i] = '';
                            }
                        }
                    }
                    $indice = $indiceline;
                    $indiceline++;
                    $counterColumn = 1;

                    //$Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                    //$counterColumn++;
                } else {
                    if ($indice_now == 'G') {
                        if ($StringforG == '') {
                            $StringforG = $value;
                        } else {
                            $StringforG .= ' ' . $value;
                        }
                    } else {
                        if ($counterColumn == 2) {
                            if (strpos($value, 'to ') === false) {
                                if ($indice_now != 'E' && $indice_now != 'F' && $indice_now != 'G') {
                                    $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = '';
                                    $counterColumn = $counterColumn + 1;
                                    $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                                } else {
                                    if ($indice_now == 'E' && $indice == '01') {
                                        $tempE = trim($value);
                                        $tempE = explode('  ', $tempE);
                                        $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = 'Deed:';
                                        $counterColumn = $counterColumn + 1;
                                        $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = trim(str_replace('Deed:', '', $tempE[0]));
                                        $counterColumn = $counterColumn + 1;
                                        $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = 'Mortgage:';
                                        $counterColumn = $counterColumn + 1;
                                        $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = trim(str_replace('Mortgage:', '', $tempE[0]));
                                        //print_r($Array_returnpage2);
                                    } else {
                                        $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                                    }
                                }
                            } else {
                                $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                            }
                        } else {
                            if ($indice_now == 'F') {
                                if (strpos($value, 'to ') !== false) {
                                    $tempF = explode('to ', $value);
                                    $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $tempF[0];
                                    $counterColumn = $counterColumn + 1;
                                    $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = 'to ' . $tempF[1];
                                } else {
                                    $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                                }
                            } else {
                                $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                            }
                            //$Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                        }
                    }
                    //print_r($Array_returnpage2);
                    //$Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                    $counterColumn++;
                }
            }
        }
    }
    /**/

    /**/
    $array_return = array();
    $array_return['Page1'] = $Array_returnpage1;
    $array_return['Page2'] = $Array_returnpage2;
    $array_return['Page3'] = $Array_returnpage3;
    print_r(json_encode($array_return));
}

function PdfNumber3($json) {
    $Array_returnpage1 = array();
    $Array_returnpage2 = array();
    $Array_returnpage3 = array();
    /* page1 */
    //print_r($json['page'][0]['text']);
    //print_r($json['page'][1]['text']);
    /**/
    /* page 2 */
    $array_inits = array('A', 'B', 'C', 'E', 'F', 'G', 'H');
    $array_initsNO = array('D', 'I', 'J');
    $indice = '';
    $counterColumn = 1;
    $indice_now = '';
    $StringforG = '';
    print_r($json['page'][1]['text']);
    exit();
    $TitleColumn = '';
    $fila = 1;
    $IndiceCol = '';
    $temporal = '';
    $temporalG = '';
    $InitCol = 1;
    $flag = true;
    $flagG = 1;
    foreach ($json['page'][1]['text'] as $key => $value) {
        if (is_array($value) && $value['b']) {
            $Name = $value['b'];
            $Name = explode('. ', $Name);
            //print_r($Name);
            if (in_array($Name[0], $array_inits)) {
                $IndiceCol = $Name[0];
                $Array_returnpage2['Title_' . $IndiceCol] = $value['b'];
                //print_r($value['b']);
                //print_r($Array_returnpage2);
                $InitCol = 1;
            } else {
                if (in_array($Name[0], $array_initsNO)) {
                    $IndiceCol = '';
                }
            }
        } else {
            if ($IndiceCol == 'G') {
                /**/
                if (!is_array($value)) {
                    if ($flagG <= 2) {
                        /* if ($InitCol < 10) {
                          $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_1'] = $value;
                          } else {
                          $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_1'] = $value;
                          } */
                        $temporalG = trim($temporalG . ' ' . $value);
                        $flagG++;
                    } else {
                        $Amountvalue = str_replace(array('$', ',', '-'), '', $value);
                        //print_r('**'.$Amountvalue.'**');
                        if (is_numeric($Amountvalue)) {
                            if ($InitCol < 10) {
                                $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_1'] = $temporalG;
                                $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_2'] = $value;
                            } else {
                                $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_1'] = $value;
                                $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_2'] = $value;
                            }
                            $InitCol++;
                            $flagG = 1;
                        } else {
                            $temporalG = $value;
                            $flagG = 2;
                        }
                    }
                }
                /**/
            } else {
                if ($IndiceCol && in_array($IndiceCol, $array_inits)) {
                    if (!is_array($value)) {
                        if ($flag) {
                            /* if ($InitCol < 10) {
                              $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_1'] = $value;
                              } else {
                              $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_1'] = $value;
                              } */
                            $temporal = $value;
                            $flag = false;
                        } else {
                            $Amountvalue = str_replace(array('$', ',', '-'), '', $value);
                            //print_r('**'.$Amountvalue.'**');
                            if (is_numeric($Amountvalue)) {
                                if ($InitCol < 10) {
                                    $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_1'] = $temporal;
                                    $Array_returnpage2[$IndiceCol . '-0' . $InitCol . '_2'] = $value;
                                } else {
                                    $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_1'] = $value;
                                    $Array_returnpage2[$IndiceCol . '-' . $InitCol . '_2'] = $value;
                                }
                                $InitCol++;
                                $flag = true;
                            } else {
                                $temporal = $value;
                                $flag = false;
                            }
                        }
                    }
                }
            }
        }
    }
    $array_return = array();
    $array_return['Page1'] = $Array_returnpage1;
    $array_return['Page2'] = $Array_returnpage2;
    $array_return['Page3'] = $Array_returnpage3;
    print_r(json_encode($array_return));
    exit();
    foreach ($json['page'][1]['text'] as $key => $value) {
        if (is_array($value)) {
            if ($value['b']) {
                $Name = $value['b'];
                $Name = explode('.', $Name);
                if ($indice_now == 'G') {
                    if (trim($StringforG)) {
                        if ($counterColumn > 1) {
                            if ($indice != '08') {
                                $ind = 7;
                            } else {
                                $ind = 6;
                            }
                            for ($i = $counterColumn; $i <= $ind; $i++) {
                                //if(!$Array_returnpage2[$indice_now . '_' . $indice . '_' . $i]){
                                $StringforG .= ' BlankCamp';
                                //}
                            }
                        }
                        $Array_returnpage2[$indice_now . '_' . $indice] = $StringforG;
                    }
                    $StringforG = '';
                }
                if ($indice_now == 'E' && $indice == '02') {
                    if ($counterColumn < 7) {
                        //$altI = str_replace('0','',$indiceline)+1;
                        for ($i = $counterColumn; $i <= 7; $i++) {
                            $Array_returnpage2[$indice_now . '_' . $indice . '_' . $i] = '';
                        }
                    }
                }

                if (in_array($Name[0], $array_inits) && count($Name) > 1) {//print_r($value);
                    $Array_returnpage2['titulo_' . $Name[0]] = $value['b'];
                    $indice_now = $Name[0];
                    $indiceline = 1;
                }

                if (in_array($Name[0], $array_initsNO) && count($Name) > 1) {
                    if ($counterColumn > 1) {
                        //$altI = str_replace('0','',$indiceline)+1;
                        for ($i = $counterColumn; $i <= 7; $i++) {
                            if ($indice_now != 'G') {
                                $Array_returnpage2[$indice_now . '_' . $indice . '_' . $i] = '';
                            }
                        }
                    }
                    $indice_now = '';
                }

                $counterColumn = 1;
            }
        } else {
            if ($indice_now) {
                if ($indiceline < 9) {
                    $indiceline = str_replace('0', '', $indiceline);
                    $indiceline = '0' . $indiceline;
                }
                if (trim($value) == $indiceline) {
                    if ($indice_now == 'G') {
                        if ($counterColumn > 1) {
                            for ($i = $counterColumn; $i <= 7; $i++) {
                                //if(!$Array_returnpage2[$indice_now . '_' . $indice . '_' . $i]){
                                $StringforG .= ' BlankCamp';
                                //}
                            }
                        }
                        if (trim($StringforG)) {
                            $Array_returnpage2[$indice_now . '_' . $indice] = $StringforG;
                        }
                        $StringforG = '';
                    }
                    if ($counterColumn > 1) {
                        $limite = 7;
                        if ($indice_now == 'E' && $indice == '01') {
                            $limite = 10;
                        } else {
                            $limite = 7;
                        }
                        for ($i = $counterColumn; $i <= $limite; $i++) {
                            if ($indice_now != 'G') {
                                $Array_returnpage2[$indice_now . '_' . $indice . '_' . $i] = '';
                            }
                        }
                    }
                    $indice = $indiceline;
                    $indiceline++;
                    $counterColumn = 1;

                    //$Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                    //$counterColumn++;
                } else {
                    if ($indice_now == 'G') {
                        if ($StringforG == '') {
                            $StringforG = $value;
                        } else {
                            $StringforG .= ' ' . $value;
                        }
                    } else {
                        if ($counterColumn == 2) {
                            if (strpos($value, 'to ') === false) {
                                if ($indice_now != 'E' && $indice_now != 'F' && $indice_now != 'G') {
                                    $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = '';
                                    $counterColumn = $counterColumn + 1;
                                    $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                                } else {
                                    if ($indice_now == 'E' && $indice == '01') {
                                        $tempE = trim($value);
                                        $tempE = explode('  ', $tempE);
                                        $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = 'Deed:';
                                        $counterColumn = $counterColumn + 1;
                                        $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = trim(str_replace('Deed:', '', $tempE[0]));
                                        $counterColumn = $counterColumn + 1;
                                        $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = 'Mortgage:';
                                        $counterColumn = $counterColumn + 1;
                                        $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = trim(str_replace('Mortgage:', '', $tempE[0]));
                                        //print_r($Array_returnpage2);
                                    } else {
                                        $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                                    }
                                }
                            } else {
                                $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                            }
                        } else {
                            if ($indice_now == 'F') {
                                if (strpos($value, 'to ') !== false) {
                                    $tempF = explode('to ', $value);
                                    $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $tempF[0];
                                    $counterColumn = $counterColumn + 1;
                                    $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = 'to ' . $tempF[1];
                                } else {
                                    $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                                }
                            } else {
                                $Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                            }
                            //$Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                        }
                    }
                    //print_r($Array_returnpage2);
                    //$Array_returnpage2[$indice_now . '_' . $indice . '_' . $counterColumn] = $value;
                    $counterColumn++;
                }
            }
        }
    }
    /**/

    /**/
    $array_return = array();
    $array_return['Page1'] = $Array_returnpage1;
    $array_return['Page2'] = $Array_returnpage2;
    $array_return['Page3'] = $Array_returnpage3;
    print_r(json_encode($array_return));
}

function isNumeric($value) {
    $value = str_replace('$', '', $value);
    $value = str_replace(',', '', $value);
    if (is_numeric($value)) {
        return true;
    } else {
        return false;
    }
}

function rmDir_rf($carpeta) {
    foreach (glob($carpeta . "/*") as $archivos_carpeta) {
        if (is_dir($archivos_carpeta)) {
            rmDir_rf($archivos_carpeta);
        } else {
            unlink($archivos_carpeta);
        }
    }
    rmdir($carpeta);
}

// 71 
function SaveDataLoanEstimate($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();

    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $cdhud_page2_obj = $GetClass->GetClass('cdhud_page2');
        $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $array['idtransaction']);
        $Fline = 1;
        if ($cdhudTransaction) {
            $cdhudTransaction = $cdhudTransaction[0];
            $idcdhud = $cdhudTransaction->getidcdhud();
        } else {
            $cdhud_obj->setidtransaction($array['idtransaction']);
            $idcdhud = $cdhud_obj->getidcdhud();
        }
        if ($cdhudTransaction) {
            $cdhud_page2 = $cdhud_page2_obj->getAllcdhud_page2ForColumnValue('idcdhud', $idcdhud);
            if ($cdhud_page2) {
                $cdhud_page2 = $cdhud_page2[0];
                $idcdhudpage2 = $cdhud_page2->getidcdhud_page2();
            } else {
                $cdhud_page2_obj->setidcdhud($cdhudTransaction->getidcdhud());
                $idcdhudpage2 = $cdhud_page2_obj->getidcdhud_page2();
            }
            foreach ($array as $key => $value) {
                if ($key != 'idtransaction') {
                    $idskeys = explode('-', $key);
                    $idskeys2 = explode('_', $idskeys[1]);
                    $ArraySave = array();
                    $idCDline = $idskeys[0] . '-' . $idskeys2[0];
                    //print_r($idCDline);
                    if ($idskeys2[1] == '1') {
                        $ArraySave['Page2Line'] = $idCDline;
                        $ArraySave['idtransaction'] = $array['idtransaction'];
                        if ($idskeys[0] == 'A') {
                            if ($idskeys2[0] == '01') {
                                $points = explode('%', $value);
                                $ArraySave['AmountPoints'] = $points[0];
                            } else {
                                $ArraySave['Description'] = $value;
                            }
                            $ArraySave['AmountD21'] = $array[str_replace('_1', '_2', $key)];
                        } else {
                            if ($idskeys[0] == 'E') {
                                $increment = ((int) $idskeys2[0]) + 2;
                                $idCDline = $idskeys[0] . '-0' . $increment;
                                $ArraySave['Page2Line'] = $idCDline;
                                $ArraySave['Description'] = $value;
                                $ArraySave['AmountD21'] = $array[str_replace('_1', '_2', $key)];
                            } else {
                                if ($idskeys[0] == 'F') {
                                    $idCDline = $idskeys[0] . '-0' . $Fline;
                                    $ArraySave['Page2Line'] = $idCDline;
                                    if ($Fline == 1) {
                                        if (strpos($value, 'Homeowner') === false) {
                                            $Fline++;
                                            $increment = ((int) $Fline);
                                            $idCDline = $idskeys[0] . '-0' . $increment;
                                            $ArraySave['Page2Line'] = $idCDline;
                                        }
                                    }
                                    if ($Fline == 2) {
                                        if (strpos($value, 'Mortgage Insurance') === false) {
                                            $Fline++;
                                            $increment = ((int) $Fline);
                                            $idCDline = $idskeys[0] . '-0' . $increment;
                                            $ArraySave['Page2Line'] = $idCDline;
                                        }
                                    }
                                    if ((int) $Fline < 3) {
                                        $temp = explode('(', $value);
                                        $temp = explode('months', $temp[1]);
                                        $months = (int) trim($temp[0]);
                                        $amount = str_replace(array('$', ','), '', $array[str_replace('_1', '_2', $key)]);
                                        $prorrateo = $amount / $months;
                                        $ArraySave['Description'] = $value;
                                        $ArraySave['AmountProration'] = $prorrateo;
                                        $ArraySave['MonthsProration'] = $months;
                                        $ArraySave['ProrationAmount'] = "PerMonth";
                                        $ArraySave['TargetProration'] = "Buyer";
                                        $ArraySave['amountforgtotal'] = $array[str_replace('_1', '_2', $key)];
                                        $ArraySave['AmountD21'] = $array[str_replace('_1', '_2', $key)];
                                    } else {
                                        $ArraySave['Description'] = $value;
                                        $ArraySave['AmountD21'] = $array[str_replace('_1', '_2', $key)];
                                    }
                                    $Fline++;
                                    /* print_r($ArraySave);
                                      die('Error : '); */
                                } else {
                                    if ($idskeys[0] == 'G') {
                                        $temp = explode('$', $value);
                                        $desc = $temp[0];
                                        $temp = explode('per', $temp[1]);
                                        $prorrateo = trim($temp[0]);
                                        $temp = explode('for', $temp[1]);
                                        $months = trim(str_replace('mo.', '', $temp[1]));
                                        $ArraySave['Description'] = $desc;
                                        $ArraySave['AmountProration'] = $prorrateo;
                                        $ArraySave['MonthsProration'] = $months;
                                        $ArraySave['ProrationAmount'] = "PerMonth";
                                        $ArraySave['TargetProration'] = "Buyer";
                                        $ArraySave['amountforgtotal'] = $array[str_replace('_1', '_2', $key)];
                                        $ArraySave['AmountD21'] = $array[str_replace('_1', '_2', $key)];
                                    } else {
                                        $ArraySave['Description'] = $value;
                                        $ArraySave['AmountD21'] = $array[str_replace('_1', '_2', $key)];
                                    }
                                }
                            }
                        }
                        $update = 'update' . str_replace('-', '', $idCDline);
                        $cdhud_page2_obj->$update($idcdhudpage2, json_encode($ArraySave));
                        //print_r($idCDline);
                        //print_r($ArraySave);
                    }
                }
            }
            echo 'Update Successfully';
        }
    } else {
        echo "Error, An array expected";
    }
}

//72
function getprogresstransactions($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $task_obj = $GetClass->GetClass('task');
        $transaction_obj = $GetClass->GetClass('transaction');
        $requeriment_list_obj = $GetClass->GetClass('requeriment_list');
        $idstransaction = explode(',', $array['ids']);
        if ($idstransaction) {
            $arrayResponse = array();
            foreach ($idstransaction as $idt) {
                $arrayResponse[$idt] = 0;
                $alltask = $task_obj->getAlltaskForColumnValue('idtransaction', $idt);
                $transaction = $transaction_obj->gettransactionById($idt);
                $reqtasklist = array();
                if ($transaction->getidrequirementslist()) {
                    $requeriment_list = $requeriment_list_obj->getrequeriment_listById($transaction->getidrequirementslist());
                    if ($requeriment_list) {
                        if ($requeriment_list->getnamer() != 'Model1') {
                            $reqlist = json_decode($requeriment_list->getrequerimentsjson(), true);
                            if ($reqlist) {
                                foreach ($reqlist as $k => $v) {
                                    $temp = explode('_', $k);
                                    if ($temp[1] == 'Name') {
                                        $reqtasklist[$v] = $reqlist[str_replace('Name', 'porcentaje', $k)];
                                    }
                                }
                            }
                        }
                    }
                    if ($alltask) {
                        foreach ($alltask as $task) {
                            if (strpos($task->getstatus(), 'omplet') !== false) {
                                if ($reqtasklist[$task->getsubject()]) {
                                    if ($arrayResponse[$idt]) {
                                        $arrayResponse[$idt] = $arrayResponse[$idt] + $reqtasklist[$task->getsubject()];
                                    } else {
                                        $arrayResponse[$idt] = $reqtasklist[$task->getsubject()];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            echo json_encode($arrayResponse);
        } else {
            echo "Error, not have ids";
        }
    } else {
        echo "Error, An array expected";
    }
}

function SelectYear($DateCompare) {
    $dateEnd = $DateCompare;
    $date = date('Y-m-d');
    $fechaInicio = new DateTime($dateEnd);
    $fechaFin = new DateTime($date);
    $intervalo = $fechaInicio->diff($fechaFin);
    if ($intervalo->invert == 1) {
        return date('Y', strtotime(date('Y') . "- 1 year"));
    } else {
        return date('Y');
    }
}

//73 
function GetDataTransactionCMR($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $task_obj = $GetClass->GetClass('task');
        $transaction_obj = $GetClass->GetClass('transaction');
        $cdhud_obj = $GetClass->GetClass('cdhud');
        $lead_obj = $GetClass->GetClass('lead');
        $general_config_obj = $GetClass->GetClass('general_config');
        $general_config = $general_config_obj->getgeneral_configById(1);
        if (is_object($general_config)) {
            $OfficeInfo = json_decode($general_config->getofficeinfo(), true);
            $MortFee = $OfficeInfo['LoanFee'];
        }
        $quater = $array['SelectQua'];
        $MonthEnd = $quater * 3;
        $MonthInit = $MonthEnd - 2;
        if ($MonthEnd < 10) {
            $MonthEnd = '0' . $MonthEnd;
        }
        if ($MonthInit < 10) {
            $MonthInit = '0' . $MonthInit;
        }
        $year = SelectYear(date("t", strtotime('01-' . $MonthEnd . '-' . date('Y'))) . '-' . $MonthEnd . '-' . date('Y'));
        $dateIni = '01-' . $MonthInit . '-' . $year;
        $dateEnd = date("t", strtotime('01-' . $MonthEnd . '-' . $year)) . '-' . $MonthEnd . '-' . $year;

        $transactionAll = $transaction_obj->getAlltransactions();
        $leadAll = $lead_obj->getAllleads();
        $LeadList = array();
        foreach ($leadAll as $lead) {
            $search = $transaction_obj->getAlltransactionForColumnValue('idloan', $lead->getidlead());
            if (!$search) {
                $description = explode('_', $lead->getdescription());
                if ($description[0] == 'ApplicationRequest') {
                    $LeadList[] = $lead;
                }
            }
        }
        $ArrayYellow = array(); //todos los pendientes en proceso tb los anteriores a los 3 meses
        $ArrayRed = array(); //cancelados
        $ArrayGrey = array(); //cerrados en los 3 meses
        $ArrayGreen = array(); //abiertos en los 3 meses ind de su status
        if ($LeadList) {
            foreach ($LeadList as $Lead) {
                $data = $Lead->getdata();
                if ($data) {
                    $data = json_decode($data, true);
                } else {
                    $data = array();
                }
                $dateverify = $Lead->gettimecreated();
                if (DatePrev($dateIni, $dateverify)) {
                    $datadenied = explode('(', $data['status_6']);
                    $datadenied = str_replace(')', '', $datadenied[1]);
                    if (!$datadenied) {
                        $ArrayYellow[] = $Lead;
                    } else {
                        /**/
                        $datadenied = explode('(', $data['status_6']);
                        $datadenied = str_replace(')', '', $datadenied[1]);
                        if (VerifyRango($dateIni, $dateEnd, $datadenied)) {
                            $ArrayRed[] = $Lead;
                        }
                        /**/
                    }
                } else {
                    if (VerifyRango($dateIni, $dateEnd, $dateverify)) {
                        $datadenied = explode('(', $data['status_6']);
                        $datadenied = str_replace(')', '', $datadenied[1]);
                        if (!$datadenied) {
                            $ArrayGreen[] = $Lead;
                        } else {
                            $datadenied = explode('(', $data['status_6']);
                            $datadenied = str_replace(')', '', $datadenied[1]);
                            if (VerifyRango($dateIni, $dateEnd, $datadenied)) {
                                $ArrayRed[] = $Lead;
                            }
                        }
                    }
                }
            }
        }
        if ($transactionAll) {
            foreach ($transactionAll as $transaction) {
                $dateverify = $transaction->getdate();
                /**/
                $dateverifyLead = '';
                $ispreview = false;
                if ($transaction->getidloan()) {
                    $islead = $lead_obj->getleadById($transaction->getidloan());
                    if ($islead) {
                        $dateverifyLead = $islead->gettimecreated();
                    }
                }
                /**/
                if ($dateverifyLead) {
                    if (DatePrev($dateIni, $dateverifyLead)) {
                        if ($transaction->getstatus() != 'Cancelled' && $transaction->getstatus() != 'Closed') {
                            $ArrayYellow[] = $transaction;
                            $ispreview = true;
                        }
                    }
                } else {
                    if (DatePrev($dateIni, $dateverify)) {
                        if ($transaction->getstatus() != 'Cancelled' && $transaction->getstatus() != 'Closed') {
                            $ArrayYellow[] = $transaction;
                            $ispreview = true;
                        }
                    }
                }
                if (!$ispreview) {
                    $eventsdate = $transaction->getevents();
                    if ($eventsdate) {
                        $eventsdate = json_decode($eventsdate, true);
                    }
                    if ($transaction->getstatus() == 'Cancelled') {
                        if (VerifyRango($dateIni, $dateEnd, $eventsdate['cancelled'])) {
                            $ArrayRed[] = $transaction;
                            /* if (VerifyRango($dateIni, $dateEnd, $transaction->getdate())) {
                              $ArrayGreen[] = $transaction;
                              } else {
                              $ArrayRed[] = $transaction;
                              } */
                        }
                    } else {
                        if ($transaction->getstatus() == 'Closed') {
                            if (VerifyRango($dateIni, $dateEnd, $transaction->getclosingdate())) {
                                //if (VerifyRango($dateIni, $dateEnd, $eventsdate['closed'])) {
                                $ArrayGrey[] = $transaction;
                            } else {
                                if (DatePos($dateEnd, $transaction->getclosingdate())) {
                                    if ($dateverifyLead) {
                                        if (VerifyRango($dateIni, $dateEnd, $dateverifyLead)) {
                                            $ArrayGreen[] = $transaction;
                                        }
                                    } else {
                                        if (VerifyRango($dateIni, $dateEnd, $transaction->getdate())) {
                                            $ArrayGreen[] = $transaction;
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($dateverifyLead) {
                                if (VerifyRango($dateIni, $dateEnd, $dateverifyLead)) {
                                    $ArrayGreen[] = $transaction;
                                }
                            } else {
                                if (VerifyRango($dateIni, $dateEnd, $transaction->getdate())) {
                                    $ArrayGreen[] = $transaction;
                                }
                            }
                        }
                    }
                }
            }
            /**/
            $ArrayYellowF = array(); //todos los pendientes en proceso tb los anteriores a los 3 meses
            $ArrayRedF = array(); //cancelados
            $ArrayGreyF = array(); //cerrados en los 3 meses
            $ArrayGreenF = array(); //abiertos en los 3 meses ind de su status
            $AC010 = 0; //loanamount yellow+green
            $AC010C = 0;
            $AC020 = 0; //loanamount green
            $AC020C = 0;
            $AC050 = 0; //loan amount red
            $AC050C = 0;
            $AC070 = 0; //loan amount grey
            $AC070C = 0;
            //$AC080 = 0;//66-70
            $AC100 = 0; //todos los  son conv del grey, excepto los fha todos menos los fha
            $AC100C = 0;
            $AC110 = 0; //todos los grey fha
            $AC110C = 0;
            $AC300 = 0; //grey que sea home purchase
            $AC300C = 0;
            $AC320 = 0; //grey que sea refinance
            $AC320C = 0;
            $AC1000 = 0; //fees grey
            $AC1000C = 0;
            if ($ArrayYellow) {
                foreach ($ArrayYellow as $transa) {
                    if (method_exists($transa, 'getidlead')) {
                        $data = $transa->getdata();
                        if ($data) {
                            $data = json_decode($data, true);
                            $data = $data ['dataapp'];
                            if ($data) {
                                $data = json_decode($data, true);
                            } else {
                                $data = array();
                            }
                        } else {
                            $data = array();
                        }
                        if ($data) {
                            $arraytemp = array();
                            $arraytemp['Name'] = '(lead ' . $transa->getidlead() . ') ' . $data['Name_LastBorrower'] . ' ' . $data['Name_FirstBorrower'] . ' <span style="float:right;">' . date("m/d/Y", strtotime($transa->gettimecreated())) . '</span>';
                            $arraytemp['LoanAmount'] = $data['MortgageAmount'];
                            $percentage = $MortFee;
                            $percentage = $percentage / 100;
                            $loanamount = str_replace(array('$', ',', ' '), '', $data['MortgageAmount']);
                            $AC010 = $AC010 + $loanamount;
                            $AC010C++;
                            $fee = $loanamount * $percentage;
                            $arraytemp['Fee'] = $fee;
                            $ArrayYellowF[] = json_encode($arraytemp);
                        }
                    } else {
                        $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $transa->getidtransaction());
                        if ($cdhudTransaction) {
                            $cdhudTransaction = $cdhudTransaction[0];
                            $LoanJson = json_decode($cdhudTransaction->getLoanAmount(), true);
                            $arraytemp = array();
                            $arraytemp['Name'] = '(trns ' . $transa->getidtransaction() . ') ' . $transa->getname() . ' <span style="float:right;">' . date("m/d/Y", strtotime($transa->getdate())) . '</span>';
                            $arraytemp['LoanAmount'] = $LoanJson['LoanAmountDialog'];
                            /**/
                            $clasification = json_decode($transa->getclasification(), true);
                            $percentage = $MortFee;
                            if ($clasification['PercentageFee']) {
                                $percentage = $clasification['PercentageFee'];
                            }
                            if ($percentage && $LoanJson['LoanAmountDialog']) {
                                $percentage = $percentage / 100;
                                $loanamount = str_replace(array('$', ',', ' '), '', $LoanJson['LoanAmountDialog']);
                                $AC010 = $AC010 + $loanamount;
                                $AC010C++;
                                $fee = $loanamount * $percentage;
                                $arraytemp['Fee'] = $fee;
                                $ArrayYellowF[] = json_encode($arraytemp);
                            }
                            /**/
                        }
                    }
                }
            }
            if ($ArrayRed) {
                foreach ($ArrayRed as $transa) {//print_r($transa);//print_r(method_exists($transa, 'getidlead'));
                    if (method_exists($transa, 'getidlead')) {
                        $data = $transa->getdata();
                        if ($data) {
                            $data = json_decode($data, true);
                            $dataoriginal = $data;
                            $data = $data ['dataapp'];
                            if ($data) {
                                $data = json_decode($data, true);
                            } else {
                                $data = array();
                            }
                        } else {
                            $data = array();
                        }
                        if ($data) {
                            $arraytemp = array();
                            $datadenied = explode('(', $dataoriginal['status_6']);
                            $datadenied = str_replace(')', '', $datadenied[1]);
                            if ($datadenied) {
                                $dateshow = $datadenied;
                            } else {
                                $dateshow = $transa->gettimecreated();
                            }
                            $arraytemp['Name'] = '(lead ' . $transa->getidlead() . ') ' . $data['Name_LastBorrower'] . ' ' . $data['Name_FirstBorrower'] . ' <span style="float:right;">' . date("m/d/Y", strtotime($dateshow)) . '</span>';
                            $arraytemp['LoanAmount'] = $data['MortgageAmount'];
                            $percentage = $MortFee;
                            $percentage = $percentage / 100;
                            $loanamount = str_replace(array('$', ',', ' '), '', $data['MortgageAmount']);
                            $AC050 = $AC050 + $loanamount;
                            $AC050C++;
                            $fee = $loanamount * $percentage;
                            $arraytemp['Fee'] = $fee;
                            $ArrayRedF[] = json_encode($arraytemp);
                        }
                    } else {
                        $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $transa->getidtransaction());
                        if ($cdhudTransaction) {
                            $cdhudTransaction = $cdhudTransaction[0];
                            $LoanJson = json_decode($cdhudTransaction->getLoanAmount(), true);
                            $arraytemp = array();
                            $arraytemp['Name'] = '(trns ' . $transa->getidtransaction() . ') ' . $transa->getname() . ' <span style="float:right;">' . date("m/d/Y", strtotime($transa->getdate())) . '</span>';
                            $arraytemp['LoanAmount'] = $LoanJson['LoanAmountDialog'];
                            /**/
                            $clasification = json_decode($transa->getclasification(), true);
                            $percentage = $MortFee;
                            if ($clasification['PercentageFee']) {
                                $percentage = $clasification['PercentageFee'];
                            }
                            if ($percentage && $LoanJson['LoanAmountDialog']) {
                                $percentage = $percentage / 100;
                                $loanamount = str_replace(array('$', ',', ' '), '', $LoanJson['LoanAmountDialog']);
                                $AC050 = $AC050 + $loanamount;
                                $AC050C++;
                                $fee = $loanamount * $percentage;
                                $arraytemp['Fee'] = $fee;
                                $ArrayRedF[] = json_encode($arraytemp);
                            }
                            /**/
                        }
                    }
                }
            }
            if ($ArrayGrey) {
                foreach ($ArrayGrey as $transa) {
                    if (method_exists($transa, 'getidlead')) {
                        $data = $transa->getdata();
                        if ($data) {
                            $data = json_decode($data, true);
                            $data = $data ['dataapp'];
                            if ($data) {
                                $data = json_decode($data, true);
                            } else {
                                $data = array();
                            }
                        } else {
                            $data = array();
                        }
                        if ($data) {
                            $arraytemp = array();
                            $arraytemp['Name'] = '(lead ' . $transa->getidlead() . ') ' . $data['Name_LastBorrower'] . ' ' . $data['Name_FirstBorrower'] . ' <span style="float:right;">' . date("m/d/Y", strtotime($transa->gettimecreated())) . '</span>';
                            $arraytemp['LoanAmount'] = $data['MortgageAmount'];
                            $AC110 = $AC110 + $loanamount;
                            $AC110C++;
                            $percentage = $MortFee;
                            $percentage = $percentage / 100;
                            $loanamount = str_replace(array('$', ',', ' '), '', $data['MortgageAmount']);
                            $AC070 = $AC070 + $loanamount;
                            $AC070C++;
                            $Program = strtolower($data['TransactionType']);
                            if (strpos($Program, 'purchase') !== false) {
                                /* $AC300 = $AC300 + $loanamount;
                                  $AC300C++; */
                            }
                            if (strpos($Program, 'refi') !== false) {
                                $AC320 = $AC320 + $loanamount; //print_r($transa);print_r($data['MortgageAmount']);
                                $AC320C++;
                            } else {
                                $AC300 = $AC300 + $loanamount;
                                $AC300C++;
                            }
                            $fee = round(($loanamount * $percentage), 2);
                            $arraytemp['Fee'] = $fee;
                            $AC1000 = $AC1000 + $fee;
                            $AC1000C++;
                            $ArrayGreyF[] = json_encode($arraytemp);
                        }
                    } else {
                        $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $transa->getidtransaction());
                        if ($cdhudTransaction) {
                            $cdhudTransaction = $cdhudTransaction[0];
                            $LoanJson = json_decode($cdhudTransaction->getLoanAmount(), true);
                            $arraytemp = array();
                            $arraytemp['Name'] = '(trns ' . $transa->getidtransaction() . ') ' . $transa->getname() . ' <span style="float:right;">' . date("m/d/Y", strtotime($transa->getclosingdate())) . '</span>';
                            $arraytemp['LoanAmount'] = $LoanJson['LoanAmountDialog'];
                            $loanamount = str_replace(array('$', ',', ' '), '', $LoanJson['LoanAmountDialog']);
                            /**/
                            $clasification = json_decode($transa->getclasification(), true);
                            $percentage = $MortFee;
                            if ($clasification['PercentageFee']) {
                                $percentage = $clasification['PercentageFee'];
                            }
                            if ($percentage && $LoanJson['LoanAmountDialog']) {
                                $Type = strtolower($clasification['TypeOfLoan']);
                                if (strpos($Type, 'fha') === false) {
                                    $AC100 = $AC100 + $loanamount;
                                    $AC100C++;
                                } else {
                                    $AC110 = $AC110 + $loanamount;
                                    $AC110C++;
                                }
                                $Program = strtolower($clasification['ProgramLoan']);
                                if (strpos($Program, 'purchase') !== false) {
                                    /* $AC300 = $AC300 + $loanamount;
                                      $AC300C++; */
                                }
                                if (strpos($Program, 'refi') !== false) {
                                    $AC320 = $AC320 + $loanamount; //print_r($transa);print_r($LoanJson);
                                    $AC320C++;
                                } else {
                                    $AC300 = $AC300 + $loanamount;
                                    $AC300C++;
                                }
                                $percentage = $percentage / 100;
                                $loanamount = str_replace(array('$', ',', ' '), '', $LoanJson['LoanAmountDialog']);
                                $AC070 = $AC070 + $loanamount;
                                $AC070C++;
                                $fee = round(($loanamount * $percentage), 2);
                                $arraytemp['Fee'] = $fee;
                                $AC1000 = $AC1000 + $fee;
                                $AC1000C++;
                                $ArrayGreyF[] = json_encode($arraytemp);
                            }
                            /**/
                        }
                    }
                }
            }
            if ($ArrayGreen) {
                foreach ($ArrayGreen as $transa) {
                    if (method_exists($transa, 'getidlead')) {
                        $data = $transa->getdata();
                        if ($data) {
                            $data = json_decode($data, true);
                            $data = $data ['dataapp'];
                            if ($data) {
                                $data = json_decode($data, true);
                            } else {
                                $data = array();
                            }
                        } else {
                            $data = array();
                        }
                        if ($data) {
                            $arraytemp = array();
                            $arraytemp['Name'] = '(lead ' . $transa->getidlead() . ') ' . $data['Name_LastBorrower'] . ' ' . $data['Name_FirstBorrower'] . ' <span style="float:right;">' . date("m/d/Y", strtotime($transa->gettimecreated())) . '</span>';
                            $arraytemp['LoanAmount'] = $data['MortgageAmount'];
                            $percentage = $MortFee;
                            $percentage = $percentage / 100;
                            $loanamount = str_replace(array('$', ',', ' '), '', $data['MortgageAmount']);
                            $AC010 = $AC010 + $loanamount;
                            $AC010C++;
                            $AC020 = $AC020 + $loanamount;
                            $AC020C++;
                            $fee = $loanamount * $percentage;
                            $arraytemp['Fee'] = $fee;
                            $ArrayGreenF[] = json_encode($arraytemp);
                        }
                    } else {
                        $cdhudTransaction = $cdhud_obj->getAllcdhudForColumnValue('idtransaction', $transa->getidtransaction());
                        if ($cdhudTransaction) {
                            $cdhudTransaction = $cdhudTransaction[0];
                            $LoanJson = json_decode($cdhudTransaction->getLoanAmount(), true);
                            $arraytemp = array();
                            $arraytemp['Name'] = '(trns ' . $transa->getidtransaction() . ') ' . $transa->getname() . ' <span style="float:right;">' . date("m/d/Y", strtotime($transa->getdate())) . '</span>';
                            $arraytemp['LoanAmount'] = $LoanJson['LoanAmountDialog'];
                            /**/
                            $clasification = json_decode($transa->getclasification(), true);
                            $percentage = $MortFee;
                            if ($clasification['PercentageFee']) {
                                $percentage = $clasification['PercentageFee'];
                            }
                            if ($percentage && $LoanJson['LoanAmountDialog']) {
                                $percentage = $percentage / 100;
                                $loanamount = str_replace(array('$', ',', ' '), '', $LoanJson['LoanAmountDialog']);
                                $AC010 = $AC010 + $loanamount;
                                $AC010C++;
                                $AC020 = $AC020 + $loanamount;
                                $AC020C++;
                                $fee = $loanamount * $percentage;
                                $arraytemp['Fee'] = $fee;
                                $ArrayGreenF[] = json_encode($arraytemp);
                            }
                            /**/
                        }
                    }
                }
            }
            $ArrayReturn = array();
            $ArrayReturn['Yellow'] = json_encode($ArrayYellowF);
            $ArrayReturn['Red'] = json_encode($ArrayRedF);
            $ArrayReturn['Grey'] = json_encode($ArrayGreyF);
            $ArrayReturn['Green'] = json_encode($ArrayGreenF);
            $ArrayReturn['data'] = '{"AC010":"' . $AC010 . '||' . $AC010C . '","AC020":"' . $AC020 . '||' . $AC020C . '","AC050":"' . $AC050 . '||' . $AC050C . '","AC070":"' . $AC070 . '||' . $AC070C . '","AC100":"' . $AC100 . '||' . $AC100C . '","AC110":"' . $AC110 . '||' . $AC110C . '","AC300":"' . $AC300 . '||' . $AC300C . '","AC320":"' . $AC320 . '||' . $AC320C . '","AC1000_back":"' . $AC1000 . '||' . $AC1000C . '","AC1100":"' . $AC1000 . '||' . $AC1000C . '","AC900":"' . $AC1000 . '||' . $AC1000C . '"}';
            echo json_encode($ArrayReturn);
            /**/
        } else {
            echo 'Error : not have transactions';
        }
    } else {
        echo "Error, An array expected";
    }
}

function DatePrev($DateInit, $DateVerify) {
    $DateInit = strtotime($DateInit);
    $DateVerify = strtotime($DateVerify);
    if (($DateVerify < $DateInit)) {
        return true;
    } else {
        return false;
    }
}

function DatePos($DateEnd, $DateVerify) {
    $DateEnd = strtotime($DateEnd);
    $DateVerify = strtotime($DateVerify);
    if (($DateVerify > $DateEnd)) {
        return true;
    } else {
        return false;
    }
}

function VerifyRango($DateInit, $DateEnd, $DateVerify) {
    $DateInit = strtotime($DateInit);
    $DateEnd = strtotime($DateEnd);
    $DateVerify = strtotime($DateVerify);
    if (($DateVerify >= $DateInit) && ($DateVerify <= $DateEnd)) {
        return true;
    } else {
        return false;
    }
}

//74 
function GetXMLCMR($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        echo CMRXML($array);
    } else {
        echo "Error, An array expected";
    }
}

function trimestre($datetime) {
    $mes = date("m", strtotime($datetime));
    $mes = is_null($mes) ? date('m') : $mes;
    $trim = floor(($mes - 1) / 3) + 1;
    return $trim;
}

function LimpiaMoney($Amount) {
    $Amount = str_replace(array('$', ',', ' '), '', $Amount);
    return round($Amount);
}

function CMRXML($data) {
    //$Init = '<Mcr type="S" year="' . date('Y') . '" formVersion="v5" periodType="MCRQ' . trimestre(date('Y-m-d')) . '" reportingDate="' . date('Y-m-d') . '"></Mcr>';
    $Init = '<Mcr type="S" year="' . date('Y') . '" formVersion="v5" periodType="MCRQ' . $data['SelectQua'] . '" reportingDate="' . date('Y-m-d') . '"></Mcr>';
    $CMRXML = new SimpleXMLElement($Init);
    $Rmla = $CMRXML->addChild('Rmla');
    $Rmla->addAttribute('stateCode', 'FL');
    $SectionISection = $Rmla->addChild('SectionISection');
    $arraySections = array('AC010', 'AC020', 'AC030', 'AC040', 'AC050', 'AC060', 'AC062', 'AC064');
    foreach ($arraySections as $val) {
        for ($i = 1; $i <= 4; $i++) {
            $key = $val . '_' . $i;
            $dato = LimpiaMoney($data[$key]);
            if ($dato) {
                $SectionISection->addChild($key, $dato);
            } else {
                //$SectionISection->addChild($key, '0');
            }
        }
    }
    /**/
    $key = 'AC065_1';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC065_3';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    /**/
    $arraySections = array('AC070', 'AC080');
    foreach ($arraySections as $val) {
        for ($i = 1; $i <= 4; $i++) {
            $key = $val . '_' . $i;
            $dato = LimpiaMoney($data[$key]);
            if ($dato) {
                $SectionISection->addChild($key, $dato);
            } else {
                //$SectionISection->addChild($key, '0');
            }
        }
    }
    $arraySections = array('AC100', 'AC110', 'AC120', 'AC130', 'AC200', 'AC210', 'AC220', 'AC300', 'AC310', 'AC320', 'AC400', 'AC500', 'AC510', 'AC520');
    foreach ($arraySections as $val) {
        for ($i = 1; $i <= 6; $i++) {
            $key = $val . '_' . $i;
            $dato = LimpiaMoney($data[$key]);
            if ($dato) {
                $SectionISection->addChild($key, $dato);
            } else {
                //$SectionISection->addChild($key, '0');
            }
        }
    }
    $key = 'AC600_1';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC610_3';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC610_5';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $arraySections = array('AC700', 'AC710', 'AC720', 'AC800', 'AC810');
    foreach ($arraySections as $val) {
        for ($i = 1; $i <= 6; $i++) {
            $key = $val . '_' . $i;
            $dato = LimpiaMoney($data[$key]);
            if ($dato) {
                $SectionISection->addChild($key, $dato);
            } else {
                //$SectionISection->addChild($key, '0');
            }
        }
    }
    $key = 'AC620_1';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC630_3';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC630_5';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC900_2';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC910_4';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC910_6';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $arraySections = array('AC920', 'AC930', 'AC940');
    foreach ($arraySections as $val) {
        for ($i = 1; $i <= 6; $i++) {
            $key = $val . '_' . $i;
            $dato = LimpiaMoney($data[$key]);
            if ($dato) {
                $SectionISection->addChild($key, $dato);
            } else {
                //$SectionISection->addChild($key, '0');
            }
        }
    }
    $key = 'AC1000_1';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC1000_2';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC1100_1';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }

    $key = 'AC1200_1';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC1200_2';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC1210_1';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $key = 'AC1210_2';
    $dato = LimpiaMoney($data[$key]);
    if ($dato) {
        $SectionISection->addChild($key, $dato);
    } else {
        //$SectionISection->addChild($key, '0');
    }
    $ListSectionOfSectionIMlosItem = $Rmla->addChild('ListSectionOfSectionIMlosItem');
    $DetailItemList = $ListSectionOfSectionIMlosItem->addChild('DetailItemList');
    for ($i = 1; $i <= 3; $i++) {
        $SectionIMlosItem = $DetailItemList->addChild('SectionIMlosItem');
        $SectionIMlosItem->addChild('ACMLO', $i);
        for ($j = 2; $j <= 3; $j++) {
            $key = 'ACMLO' . $i . '_' . $j;
            $dato = LimpiaMoney($data[$key]);
            if ($dato) {
                $SectionIMlosItem->addChild($key, $dato);
            } else {
                //$SectionIMlosItem->addChild($key, '0');
            }
        }
    }
    $MISMIXML = trim(str_replace('<?xml version="1.0"?>', '', $CMRXML->asXML()));
    //print_r($MISMIXML);
    $name = date('Ymd') . '-date-mismo';
    $type = '.xml';
    $path = 'temp/' . $name . $type;
    $fh = fopen($path, 'w') or die("can't open file");
    $stringData = $MISMIXML;
    fwrite($fh, $stringData);
    fclose($fh);
    return $path;
}

//75 
function SaveAuth($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $transaction_obj = $GetClass->GetClass('transaction');
        if ($array['idtransaction']) {
            if ($array['type'] == 'get') {
                $transaction = $transaction_obj->gettransactionById($array['idtransaction']);
                if ($transaction) {
                    echo $transaction->gethomeownerassoc();
                } else {
                    echo 'Error : Transaction not found';
                }
            } else {
                $transaction_obj->updatehomeownerassoc($array['idtransaction'], json_encode($array));
                echo 'ok';
            }
        } else {
            echo 'Error : Id Trasnsaction is Required';
        }
    } else {
        echo "Error, An array expected";
    }
}
