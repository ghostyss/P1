<?php

error_reporting(E_ERROR);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    header("Location: index.php");
}
include_once('security/classes/check.class.php');
include_once ('Server/jsend.class.php');
include_once ('Server/developer/fpdf.php');
date_default_timezone_set('America/New_York');
include_once 'Server/QBHelper.php';

/* UseGoogleVision */
require 'vendor/autoload.php';

//include_once 'vendor/google/cloud/Storage/src/StorageClient.php';
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Vision\V1\AnnotateFileResponse;
use Google\Cloud\Vision\V1\AsyncAnnotateFileRequest;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\GcsDestination;
use Google\Cloud\Vision\V1\GcsSource;
use Google\Cloud\Vision\VisionHelpersTrait;
use Google\Cloud\Vision\V1\Gapic\ImageAnnotatorGapicClient;
use InvalidArgumentException;

//use Google\Cloud\Vision\V1\ImageAnnotatorClient;
class ImageAnnotatorClient extends ImageAnnotatorGapicClient {

    use VisionHelpersTrait;

    public function createImageObject($imageInput) {
        return $this->createImageHelper(Image::class, ImageSource::class, $imageInput);
    }

    public function annotateImage($image, $features, $optionalArgs = []) {
        $image = $this->createImageObject($image);
        return $this->annotateImageHelper(
                        [$this, 'batchAnnotateImages'],
                        AnnotateImageRequest::class,
                        $image,
                        $features,
                        $optionalArgs
        );
    }

    public function faceDetection($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::FACE_DETECTION,
                        $optionalArgs
        );
    }

    public function landmarkDetection($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::LANDMARK_DETECTION,
                        $optionalArgs
        );
    }

    public function logoDetection($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::LOGO_DETECTION,
                        $optionalArgs
        );
    }

    public function labelDetection($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::LABEL_DETECTION,
                        $optionalArgs
        );
    }

    public function textDetection($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::TEXT_DETECTION,
                        $optionalArgs
        );
    }

    public function documentTextDetection($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::DOCUMENT_TEXT_DETECTION,
                        $optionalArgs
        );
    }

    public function safeSearchDetection($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::SAFE_SEARCH_DETECTION,
                        $optionalArgs
        );
    }

    public function imagePropertiesDetection($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::IMAGE_PROPERTIES,
                        $optionalArgs
        );
    }

    public function cropHintsDetection($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::CROP_HINTS,
                        $optionalArgs
        );
    }

    public function webDetection($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::WEB_DETECTION,
                        $optionalArgs
        );
    }

    public function objectLocalization($image, $optionalArgs = []) {
        return $this->annotateSingleFeature(
                        $image,
                        Type::OBJECT_LOCALIZATION,
                        $optionalArgs
        );
    }

    public function productSearch($image, ProductSearchParams $productSearchParams, $optionalArgs = []) {
        if (isset($optionalArgs['imageContext']) && $optionalArgs['imageContext'] instanceof ImageContext) {
            $optionalArgs['imageContext']->setProductSearchParams($productSearchParams);
        } else {
            $optionalArgs['imageContext'] = (new ImageContext)
                    ->setProductSearchParams($productSearchParams);
        }

        return $this->annotateSingleFeature(
                        $image,
                        Type::PRODUCT_SEARCH,
                        $optionalArgs
        );
    }

    private function annotateSingleFeature($image, $featureType, $optionalArgs) {
        return $this->annotateImage($image, [$featureType], $optionalArgs);
    }
}

use Google\Cloud\Vision\V1\InputConfig;
use Google\Cloud\Vision\V1\OutputConfig;

/**/

if (!isset($_SESSION)) {
    session_start();
}
protect("*");
$action = isset($_POST['action']) ? $_POST['action'] : "";
if ($action != "") {
    call_user_func($action, $_POST['input']);
}

function result() {
    if (isset($_POST['input'])) {
        $myVal = $_POST['input'];
        $keyOperation = substr($myVal, 0, 2);
        $datalen = strlen($myVal);
        $dataresult = substr($myVal, 2, $datalen);
    }
    switch ($keyOperation) {
        case "01":
            UploadContractDocument($dataresult);
            break;
        case "02":
            VerifyAddress($dataresult);
            break;
        case "03":
            GetPropertyReport($dataresult);
            break;
        case "04":
            GetPropertyData($dataresult);
            break;
        case "05":
            CreateTransaction($dataresult);
            break;
        case "06":
            TransactionSumary($dataresult);
            break;
        case "07":

            break;
        case "08":

            break;
        case "09":

            break;
        case "10":

            break;
        case "11":

            break;
        case "12":

            break;
        case "13":

            break;
        case "14":

            break;
        case "15":

            break;
        case "16":

            break;
        case "17":

            break;
        case "18":

            break;
        case "19":

            break;
        case "20":

            break;
        case "21":

            break;
        case "22":

            break;
        case "23":

            break;
        case "24":

            break;
        case "25":

            break;
        case "26":

            break;
        case "27":

            break;
        case "28":

            break;
        case "29":

            break;
        case "30":

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
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $genconf = $GetClass->GetClass('general_config');
    $generalconfig = $genconf->getgeneral_configById(1);
    $gc_data = json_decode($generalconfig->getofficeinfo(), true);
    $ena_disa = $gc_data['ZimbraServer'];
    return $ena_disa;
}

function postZimbraProxy($data) {
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
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

function posZpstodb($op, $data_p) {
    $m = new dbname();
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $data = postToZb($data_p); //var_dump($data);exit();
    $datajson = json_decode($data, true);
    $msj = $datajson['result']; //echo $msj;
    if ($msj != '500') {
        $zserver = $GetClass->GetClass('zserver');
        $server = $zserver->getzserverById(1);
        $ch = curl_init();
        //$url = $server->getzproxy();
        $url = $server->getzproxy();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //curl_setopt($ch, CURLOPT_HEADER, true);
        /* $result = curl_exec($ch);
          var_dump($result);exit(); */
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "action=result&input=$op" . urlencode($data));
        curl_setopt($ch, CURLOPT_REFERER, $url);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo "Return code is {$httpCode} \n".curl_error($ch);
        curl_close($ch);
        if ($httpCode == 200) {
            $respuesta = json_decode($result, true);
            if ($respuesta['result'] == 200) {
                return $result;
            } else {
                die('Error : ' . $respuesta['text']);
            }
        } else {
            die('Error : ' . $httpCode . ' ' . $result . ' ' . $url);
        }
    } else {
        die($datajson['text']);
    }
}

function postToZb($dataresult) {

    $jSEND = new jSEND();
    $theData = $jSEND->getData($dataresult);
    $theData = str_replace(' & ', ' and ', $theData);
    $array = json_decode($theData, true); //var_dump($array);

    $m = new dbname();
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $user = $GetClass->GetClass('login_users');
    //$user->updateemail('1','admin@realtorexclusive.com');
    $owner_id = (isset($array[iduser]) && $array[iduser] != '') ? $array[iduser] : $array[uinc];
    $owner_id = ($owner_id) ? $owner_id : $_SESSION['jigowatt']['user_id'];
    $user = $user->getlogin_usersById($owner_id);

    $zserver = $GetClass->GetClass('zserver');
    $server = $zserver->getzserverById(1);

    if (is_array($array) && is_object($user) && is_object($server)) {
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
        ); //var_dump($raw);
        //echo $user->getuser_level(). '    a:1:{i:0;s:1:"10";}';
        //echo $server->getdomain().$user->getemail();
        if ($dbname == 'king' && $_SESSION['jigowatt']['user_id'] == '1') {
            $forzar = true;
        } else {
            $forzar = false;
        }
        if (strpos($user->getemail(), $server->getdomain()) !== false || $user->getuser_level() == 'a:1:{i:0;s:2:"10";}' || $user->getuser_level() == 'a:1:{i:0;s:2:"11";}' || $user->getuser_level() == 'a:1:{i:0;s:2:"12";}' || $user->getuser_level() == 'a:1:{i:0;s:2:"13";}' || $forzar) {
            if ($user->getuser_level() == 'a:1:{i:0;s:2:"10";}') {
                $raw['psw'] = 'dummy';
            }
            if (is_array($array)) {
                $raw = array_merge($raw, $array);
            }
            $data = json_encode($raw);
            $data = AesCtr::encrypt($data, 'Ideas1700221106101215', 256);
            $data = str_replace("/", "-12-", $data);
            $data = str_replace("+", "-23-", $data);
            return "$data";
        } else {
            return json_encode(array('result' => '500', 'text' => 'Error, domain not match'));
        }
    } else {
        return json_encode(array('result' => '500', 'text' => 'Invalid account'));
    }
}

//------------- end general functions -------------
function quickbook_e_d() {
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $genconf = $GetClass->GetClass('general_config');
    $generalconfig = $genconf->getgeneral_configById(1);
    $gc_data = json_decode($generalconfig->getechosign(), true);
    $ena_disa = $gc_data['quickbook'];
    return $ena_disa;
}

function sendGeneralEmail($subject, $from = "", $to, $body, $path = "", $cc = "", $bcc = "") {
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $Mailer = GetClass('MailerHelper', $dbname);
    /* $From = '', $To = '', $Subject = '', $Body = '', $Path = '', $Cc = '', $Bcc = '' */
    $emails = array('gus@titlehost.com', 'ivan@titlehost.com');
    if ($to == 'notification') {
        $to = implode(',', $emails);
    }
    $Response = $Mailer->SenderEmail($from, $to, $subject, $body, $path, $cc, $bcc);
    $Response = json_decode($Response);
    if ($Response->Code == 200) {
        return $Response->Msj;
    } else {
        return 'Error : ' . $Response->Msj;
    }
    //return senderGeneralEmail($subject, $from, $to, $body, $path, $cc, $bcc);
}

/**/

function GetClass($MyClass, $dbname) {
    if (class_exists($MyClass)) {
        if ($dbname) {
            return new $MyClass($dbname);
        } else {
            return new $MyClass();
        }
    } else {
        if (file_exists('Server/' . $MyClass . '.php')) {
            include_once ('Server/' . $MyClass . '.php');
            if ($dbname) {
                return new $MyClass($dbname);
            } else {
                return new $MyClass();
            }
        } else {
            return false;
        }
    }
}

function CreaContactForCheck($First, $Middle, $Last, $Company) {
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $contact_obj = $GetClass->GetClass('contact');
    $FirstName = trim(ucwords(strtolower($First)));
    $LastName = trim(ucwords(strtolower($Last)));
    $CompanyName = trim(ucwords(strtolower($Company)));
    $ContactExist = '';

    $contact = $contact_obj->getAllcontactForColumnValue('company', '"' . $CompanyName . '"');
    if ($contact) {
        foreach ($contact as $cont) {
            if ($cont->getfirstname() == $FirstName && $cont->getsurname() == $LastName) {
                $ContactExist = $cont;
            }
        }
        //$ContactExist = $contact[0];
    } else {
        $contact = $contact_obj->getAllcontactForColumnValue('surname', '"' . $LastName . '"');
        foreach ($contact as $cont) {
            if ($cont->getfirstname() == $FirstName) {
                $ContactExist = $cont;
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
        $idqcontact = CreateContactQB($array['TypeContact'], $FirstName, $LastName, $CompanyName);
        if (is_numeric($idqcontact)) {
            $contact_obj->updateidq($contact_obj->getidcontact(), $idqcontact);
        }
        return $contact_obj->getidcontact();
    }
}

function CreateContactQB($TypeContact, $FirstName, $LastName, $CompanyName) {
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    if (quickbook_e_d() == 'enabled') {
        $QBFunctions_obj = GetClass('QBFunctions', $dbname);
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

function ConvertMonth($OldMonth) {
    $OldMonth = strtolower(trim(str_replace('.', '', $OldMonth)));
    switch ($OldMonth) {
        case 'january':return '01';
            break;
        case 'jan':return '01';
            break;
        case 'february':return '02';
            break;
        case 'feb':return '02';
            break;
        case 'march':return '03';
            break;
        case 'mar':return '03';
            break;
        case 'april':return '04';
            break;
        case 'apr':return '04';
            break;
        case 'may':return '05';
            break;
        case 'june':return '06';
            break;
        case 'jun':return '06';
            break;
        case 'july':return '07';
            break;
        case 'jul':return '07';
            break;
        case 'august':return '08';
            break;
        case 'aug':return '08';
            break;
        case 'september':return '09';
            break;
        case 'sep':return '09';
            break;
        case 'october':return '10';
            break;
        case 'oct':return '10';
            break;
        case 'november':return '11';
            break;
        case 'nov':return '11';
            break;
        case 'december':return '12';
            break;
        case 'dec':return '12';
            break;
    }
}

// 01 

function UploadContractDocument($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    /**/

    /**/
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $file_obj = $GetClass->GetClass('file');
        $DocumentPath = 'temp/' . $array['path'];
        $ArrayReturn = array();
        if (file_exists($DocumentPath)) {
            try {
                /* ForTest */
                /* $ArrayReturn['IdFileContract'] = '1';
                  $ArrayReturn['Response'] = ReadJson(6, 'temp/json1098_');
                  echo json_encode($ArrayReturn);
                  exit(); */
                /**/
                /* Data Auth */
                $BucketName = 'titlehost-1';
                $FileNameTarget = 'THContract';
                /**/
                /* Insert File Contract */
                $NameFile = explode('.', $array['path']);
                if (count($NameFile) > 2) {
                    $NewNameFile = '';
                    foreach ($NameFile as $Key => $PartName) {
                        if ($Key > (count($NameFile) - 2)) {
                            $NewNameFile .= '.' . $PartName;
                        }
                    }
                    $NameFile = array($NewNameFile, 'pdf');
                }
                $fp = fopen($DocumentPath, "r") or die("can't open File");
                $DocumentContent = fread($fp, filesize($DocumentPath));
                fclose($fp);
                $file_obj->setname($NameFile[0]);
                $file_obj->updatetype($file_obj->getidfile(), $NameFile[1]);
                $file_obj->updatecontent($file_obj->getidfile(), $DocumentContent);
                $file_obj->updateidsection($file_obj->getidfile(), '1');
                $ArrayReturn['IdFileContract'] = $file_obj->getidfile();
                $FileNameTarget = $FileNameTarget . $file_obj->getidfile();
                /**/
                /* UploadDocument */
                $config = array();
                $StorageClient = new StorageClient($config);
                $File = fopen($DocumentPath, 'r') or die('Error : Cant Read Document');
                $Bucket = $StorageClient->bucket($BucketName);
                $Object = $Bucket->upload($File, [
                    'name' => $FileNameTarget
                ]);
                /**/
                /* OCR */
                $FolderOutput = 'Result' . $file_obj->getidfile();
                $output = 'gs://' . $BucketName . '/' . $FolderOutput . '/';
                $feature = (new Feature())->setType(Type::DOCUMENT_TEXT_DETECTION);
                $gcsSource = (new GcsSource())->setUri('gs://' . $BucketName . '/' . $FileNameTarget);
                $mimeType = 'application/pdf';
                $inputConfig = (new InputConfig())->setGcsSource($gcsSource)->setMimeType($mimeType);
                $gcsDestination = (new GcsDestination())->setUri($output);
                $batchSize = 2;
                $outputConfig = (new OutputConfig())->setGcsDestination($gcsDestination)->setBatchSize($batchSize);
                $request = (new AsyncAnnotateFileRequest())->setFeatures([$feature])->setInputConfig($inputConfig)->setOutputConfig($outputConfig);
                $requests = [$request];
                $imageAnnotator = new ImageAnnotatorClient();
                $operation = $imageAnnotator->asyncBatchAnnotateFiles($requests);
                $operation->pollUntilComplete();
                /**/
                /* DownloadDocument */
                $StorageClientB = new StorageClient();
                $bucket = $StorageClientB->bucket($BucketName);
                $options = ['prefix' => $FolderOutput . '/'];
                $objects = $bucket->objects($options);
                $CountPages = 0;
                foreach ($objects as $object) {
                    $CountPages++;
                    $target = __DIR__ . '/temp/json' . $file_obj->getidfile() . '_' . $CountPages . '.json';
                    $object->downloadToFile($target);
                }
                $ArrayReturn['Response'] = ReadJson($CountPages, 'temp/json' . $file_obj->getidfile() . '_');
                echo json_encode($ArrayReturn);
                /**/
            } catch (Exception $e) {
                //print_r();
                echo 'Error : Error con Class Storage ' . $e->getMessage();
            }
        } else {
            echo 'Error : Document Not Found';
        }
    } else {
        echo "Error : An array expected";
    }
}

function ReadJson($NumberPages, $Name, $show) {//print_r($NumberPages);exit();
    $ArrayReturn = array();
    $ArrayCompanyTypes = array('company', 'corp', 'inc', 'llc', 'lc', 'co', 'ltd', 'pllc', 'lp', 'llp', 'lllp', 'pc', 'general partnership', 'sole propietorship');
    if ($NumberPages > 0) {
        $ArrayAddress = array();
        $ArrayAmounts = array();
        $ArrayTransaction = array();
        $ArrayParties = array();
        $ArrayOthers = array();
        for ($i = 1; $i <= $NumberPages; $i++) {
            $path = $target = __DIR__ . '/' . $Name . $i . '.json';
            //echo $path.'***********'.PHP_EOL;
            if (file_exists($path)) {

                $fp = fopen($path, "r") or die("can't open File");
                $content = fread($fp, filesize($path));
                fclose($fp);
                $Array_content = json_decode($content, true);
                /* print_r(count($Array_content));
                  echo '-------------------------'; */
                $TextContract = '';
                foreach ($Array_content as $key => $value) {
                    if ($key == 'responses') {
                        foreach ($value as $text) {
                            $TextContract .= $text['fullTextAnnotation']['text'];
                        }
                    }
                }
                $TextContract = explode(PHP_EOL, $TextContract);
                foreach ($TextContract as $key => $value) {
                    if (strpos($value, 'PROPERTY DESCRIPTION') !== false) {
                        if (!$ArrayAddress['Address']) {
                            $keyAddress = $key + 1;
                            $Address = $TextContract[$keyAddress];
                            $Address2 = '';
                            for ($j = 1; $j <= 10; $j++) {
                                if (strpos($TextContract[$keyAddress + $j], 'Located in') === false && $Address2 == '') {
                                    $Address .= ' ' . $TextContract[$keyAddress + $j];
                                } else {
                                    if (strpos($TextContract[$keyAddress + $j], 'Real Property') === false) {
                                        $Address2 .= ' ' . $TextContract[$keyAddress + $j];
                                    } else {
                                        $j = 11;
                                    }
                                }
                            }
                            $Address = explode(':', $Address); //print_r($Address);
                            $Address1 = trim($Address[1]);
                            $Address2 = explode(':', $Address2);
                            $isAdd = false;

                            if (trim($Address1)) {
                                $Address1 = str_replace(',', ' ', $Address1);
                                $Address = explode(' ', str_replace(array('    ', '   ', '  '), ' ', $Address1)); //print_r($Address);
                                if (count($Address) == 8) {
                                    $ArrayAddress['Zip'] = VerifyZip(trim($Address[count($Address) - 1]));
                                    $ArrayAddress['State'] = VerifyState(trim($Address[count($Address) - 2]));
                                    $ArrayAddress['City'] = VerifyState(trim($Address[count($Address) - 4])) . ' ' . VerifyState(trim($Address[count($Address) - 3]));
                                    $ArrayAddress['Address'] = trim($Address[count($Address) - 5]) . ' ' . trim($Address[count($Address) - 6]) . ' ' . trim($Address[count($Address) - 7]) . ' ' . trim($Address[count($Address) - 8]);
                                } else {
                                    $ArrayAddress['Zip'] = VerifyZip(trim($Address[count($Address) - 1]));
                                    $ArrayAddress['State'] = VerifyState(trim($Address[count($Address) - 2]));
                                    $ArrayAddress['City'] = VerifyState($Address[count($Address) - 3]);
                                    $ArrayAddress['Address'] = trim($Address[count($Address) - 4]) . ' ' . trim($Address[count($Address) - 5]) . ' ' . trim($Address[count($Address) - 6]) . ' ' . trim($Address[count($Address) - 7]);
                                }
                                //$AddRev = array_reverse($Address);
                                /* if ($AddRev) {
                                  foreach ($AddRev as $key2 => $Add) {
                                  if ($key2 == 0) {
                                  $ArrayAddress['Zip'] = VerifyZip(trim($Add));
                                  } else {
                                  //echo '*****'.strlen(trim($Add)).'->'.trim($Add).'******';
                                  if ($key2 == 1) {
                                  $ArrayAddress['State'] = VerifyState(trim($Add));
                                  } else {
                                  $temp = trim(str_replace(',', ' ', $Add));
                                  if (!$ArrayAddress['City']) {
                                  $ArrayAddress['City'] = trim($temp);
                                  } else {
                                  $ArrayAddress['Address'] .= ' ' . trim($temp);
                                  }
                                  }
                                  }
                                  }
                                  } */
                            }//print_r($Address2);
                            if (count($Address2) == 2) {
                                $tempTX = explode('#', $Address2[1]);
                                $Address2[1] = $tempTX[0];
                                $Address2[2] = $tempTX[1];
                            }
                            if (count($Address2) == 3) {
                                $ForCounty = explode('County', $Address2[1]);
                                $ArrayAddress['County'] = trim($ForCounty[0]); //print_r($ArrayAddress['State']);
                                if (!trim($ArrayAddress['State'])) {//print_r($ForCounty);
                                    $ForState = explode('Property Tax', $ForCounty[1]); //print_r($ForState);
                                    $ArrayAddress['State'] = VerifyState(trim(str_replace(array(',', '.'), array('', ''), $ForState[0])));
                                }
                                if (trim($Address2[2])) {
                                    $ArrayAddress['TaxId'] = VerifyPhone(trim($Address2[2]));
                                } else {
                                    if ($isAdd) {
                                        $ForTax = $TextContract[$key + 4];
                                    } else {
                                        $ForTax = $TextContract[$key + 3];
                                    }
                                    if (strpos($ForTax, 'Real Property') === false) {
                                        $ArrayAddress['TaxId'] = VerifyPhone(trim($ForTax));
                                    } else {
                                        $ArrayAddress['TaxId'] = '';
                                    }
                                }
                            }
                        }
                    }
                    if (strpos($value, 'PURCHASE PRICE AND CLOSING') !== false) {
                        if (!$ArrayAmounts['PurchasePrice']) {
                            if (strpos($TextContract[$key + 1], 'currency)') !== false) {
                                $forAdd = 1;
                            } else {
                                if (strpos($TextContract[$key + 2], 'currency)') !== false) {
                                    $forAdd = 2;
                                } else {
                                    $forAdd = 3;
                                }
                            }
                            //print_r($forAdd);
                            $Pprice = explode('currency)', trim(str_replace(array('.', ':'), array('', ''), $TextContract[$key + $forAdd]))); //print_r($Pprice);

                            if (trim(str_replace(array('.'), array(''), $Pprice[1]))) {
                                $ArrayAmounts['PurchasePrice'] = $Pprice[1];
                                $Deposit = explode('$', trim($TextContract[$key + $forAdd + 1]));
                                if (trim($Deposit[1])) {
                                    $ArrayAmounts['DepositEscrow'] = '$' . trim(str_replace('$', '', $Deposit[1]));
                                } else {
                                    $Deposit = explode('$', trim($TextContract[$key + $forAdd + 2]));
                                    $ArrayAmounts['DepositEscrow'] = '$' . trim(str_replace('$', '', $Deposit[1]));
                                }
                            } else {//print_r($Pprice);
                                $ArrayAmounts['PurchasePrice'] = trim(str_replace(array('.$', ' ', "'"), array('$', '', ''), $TextContract[$key + $forAdd + 1])); //print_r($ArrayAmounts);
                                $Deposit = explode('$', trim($TextContract[$key + $forAdd + 2]));
                                if (trim($Deposit[1])) {
                                    $ArrayAmounts['DepositEscrow'] = '$' . trim(str_replace('$', '', $Deposit[1]));
                                } else {
                                    $Deposit = explode('$', trim($TextContract[$key + $forAdd + 3]));
                                    $ArrayAmounts['DepositEscrow'] = '$' . trim(str_replace('$', '', $Deposit[1]));
                                }
                            }
                        }
                    }
                    if (strpos($value, 'Loan Amount') !== false) {
                        if (!$ArrayAmounts['LoanAmount']) {//print_r($TextContract[$key+1]);//print_r($TextContract[$value+1]);print_r($TextContract[$value+2]);
                            $LoanAmount = explode('see Paragraph 8', trim($value));
                            $LoanAmount[1] = str_replace(array('-', '_'), '', $LoanAmount[1]);
                            $Add2 = $key;
                            if (trim(str_replace('.', '', $LoanAmount[1])) && !$ArrayAmounts['LoanAmount']) {
                                $ArrayAmounts['LoanAmount'] = trim(str_replace('$', '', $LoanAmount[1]));
                            } else {
                                if (!$ArrayAmounts['LoanAmount']) {
                                    $LoanAmount = explode('$', trim($TextContract[$key + 1]));
                                    $Add2 = $key + 1;
                                    $ArrayAmounts['LoanAmount'] = trim(str_replace('$', '', $LoanAmount[count($LoanAmount) - 1]));
                                }
                            }
                            $OtherClosingDesc = $TextContract[$Add2];
                            $OtherClosingDesc = explode('Other', $OtherClosingDesc);
                            $ArrayOthers['OtherClosingDescription'] = str_replace(':', '', $OtherClosingDesc[1]);
                            $OtherClosingAmount = $TextContract[$Add2 + 1];
                            $OtherClosingAmount = explode('$', $OtherClosingAmount);
                            $ArrayOthers['OtherClosingAmount'] = str_replace(array('', '', ''), array('', '', ''), $OtherClosingAmount[1]);
                        }
                    }
                    if (strpos($value, 'CLOSING DATE') !== false) {
                        $ClosingDate = explode('on', trim($TextContract[$key + 2]));
                        if (trim($ClosingDate[1]) && !$ArrayTransaction['ClosingDate']) {
                            //$ArrayTransaction['ClosingDate'] = trim($ClosingDate[1]);
                            if (strpos($ClosingDate[1], '/') !== false) {
                                $ArrayTransaction['ClosingDate'] = verifyDate(trim($ClosingDate[1]));
                            } else {
                                $ClosingDate = explode(' ', trim(str_replace(',', ' ', $ClosingDate[1])));
                                if (strlen($ClosingDate[0]) > 2) {
                                    $ClosingDate[0] = ConvertMonth($ClosingDate[0]);
                                }
                                if (trim($ClosingDate[2])) {
                                    $ArrayTransaction['ClosingDate'] = verifyDate(trim($ClosingDate[0] . '/' . $ClosingDate[1] . '/' . $ClosingDate[2]));
                                } else {
                                    if (trim($ClosingDate[3])) {
                                        $ArrayTransaction['ClosingDate'] = verifyDate(trim($ClosingDate[0] . '/' . $ClosingDate[1] . '/' . $ClosingDate[3]));
                                    } else {
                                        $ArrayTransaction['ClosingDate'] = verifyDate(trim($ClosingDate[0] . '/' . $ClosingDate[1] . '/' . $ClosingDate[4]));
                                    }
                                }
                            }
                        } else {
                            if (!$ArrayTransaction['ClosingDate']) {
                                if (strpos(trim($TextContract[$key + 3])) !== false) {
                                    $ClosingDate = explode('(', trim($TextContract[$key + 3])); //print_r($ClosingDate);
                                    if (strpos($ClosingDate[0], '/') !== false) {
                                        $ArrayTransaction['ClosingDate'] = verifyDate(trim($ClosingDate[0]));
                                    } else {
                                        $ClosingDate = explode(' ', trim(str_replace(',', '', $ClosingDate[0]))); //print_r($ClosingDate);
                                        if (strlen($ClosingDate[0]) > 2) {
                                            $ClosingDate[0] = ConvertMonth($ClosingDate[0]);
                                        }
                                        $ArrayTransaction['ClosingDate'] = verifyDate(trim($ClosingDate[0] . '/' . $ClosingDate[1] . '/' . $ClosingDate[2]));
                                    }
                                } else {
                                    $ArrayTransaction['ClosingDate'] = '';
                                }
                            }
                        }
                    }
                    if (strpos($value, 'TIME FOR ACCEPTANCE OF OFFER') !== false) {
                        $FullExecutedDate = explode('this offer shall', trim($TextContract[$key + 2]));
                        if (trim($FullExecutedDate[0]) && !$ArrayTransaction['FullExecutedDate']) {
                            $FullExecutedDate = $FullExecutedDate[0];
                            if (strpos($FullExecutedDate, '/') !== false) {
                                if (strpos($FullExecutedDate, 'by')) {
                                    $FullExecutedDate = explode('by', $FullExecutedDate);
                                    $FullExecutedDate = trim($FullExecutedDate[0]);
                                }
                                $ArrayTransaction['FullExecutedDate'] = verifyDate(trim($FullExecutedDate));
                            } else {
                                $FullExecutedDate = explode(' ', trim(str_replace(',', '', $FullExecutedDate)));
                                if (strlen($FullExecutedDate[0]) > 2) {
                                    $FullExecutedDate[0] = ConvertMonth($FullExecutedDate[0]);
                                }
                                $ArrayTransaction['FullExecutedDate'] = verifyDate(trim($FullExecutedDate[0] . '/' . $FullExecutedDate[1] . '/' . $FullExecutedDate[2]));
                            }
                        }
                    }
                    if (strpos($value, 'FINANCING:') !== false) {
                        $LoanApproval = explode('within', trim($TextContract[$key + 6])); //print_r($LoanApproval);
                        if (trim($LoanApproval[1]) && !$ArrayTransaction['LoanApproval']) {
                            if (strpos($LoanApproval[1], '(') === false) {
                                $ArrayTransaction['LoanApproval'] = trim(str_replace('_', '', $LoanApproval[1]));
                            } else {
                                $LoanApproval = explode('(', $LoanApproval[1]);
                                if (trim(str_replace('_', '', $LoanApproval[0]))) {
                                    $ArrayTransaction['LoanApproval'] = trim(str_replace('_', '', $LoanApproval[0]));
                                } else {
                                    $ArrayTransaction['LoanApproval'] = '30';
                                }
                            }
                        } else {
                            $LoanApproval = explode('(', trim($TextContract[$key + 7]));
                            if (trim(str_replace('_', '', $LoanApproval[0])) && !$ArrayTransaction['LoanApproval'] && strpos(trim($TextContract[$key + 7]), 'if left blank') !== false) {
                                $ArrayTransaction['LoanApproval'] = trim(str_replace('_', '', $LoanApproval[0]));
                            } else {
                                if (!$ArrayTransaction['LoanApproval']) {
                                    $ArrayTransaction['LoanApproval'] = '30';
                                }
                            }
                        }
                    }
                    if (strpos($value, 'PROPERTY INSPECTIONS AND RIGHT TO') !== false) {
                        $InspectionPeriod = explode('shall have', trim($value)); //print_r($InspectionPeriod);
                        $InspectionPeriod = explode('(', $InspectionPeriod[1]); //print_r($InspectionPeriod);print_r($ArrayTransaction);
                        $InspectionPeriod = str_replace('_', '', trim($InspectionPeriod[0]));
                        if (VerifyNumeric(trim($InspectionPeriod)) && !$ArrayTransaction['InspectionPeriod']) {
                            $ArrayTransaction['InspectionPeriod'] = trim($InspectionPeriod);
                        } else {
                            if (!$ArrayTransaction['InspectionPeriod']) {
                                $ArrayTransaction['InspectionPeriod'] = '15';
                            }
                        }
                    }
                    if (strpos($value, 'PARTIES:') !== false) {
                        if (!$ArrayParties['Seller1LastName']) {
                            $Sellers = trim(str_replace('Current Owner:', '', $value)); //print_r($Sellers);
                            $Buyers = '';
                            for ($j = 1; $j <= 10; $j++) {
                                if (strpos($TextContract[$key + $j], 'Seller') === false && $Buyers == '' && strpos($Sellers, 'Seller') === false) {
                                    $Sellers .= ', ' . $TextContract[$key + $j];
                                } else {
                                    if ($Buyers == '') {
                                        $j = $j + 1;
                                    }
                                    if (strpos($TextContract[$key + $j], 'Buyer') === false) {
                                        //if(trim($TextContract[$key + $j]) != 'and'){
                                        $Buyers .= ', ' . $TextContract[$key + $j];
                                        //}
                                    } else {
                                        //$Buyers .= ', ' . $TextContract[$key + $j];
                                        $j = 11;
                                    }
                                }
                            }
                            //print_r($Buyers);
                            $Sellers = explode('PARTIES:', trim(str_replace('Current Owner:', '', $Sellers)));
                            $Sellers = $Sellers[1];
                            $Sellers = strtolower(trim($Sellers));
                            if (trim($Sellers)) {
                                if (strpos($Sellers, ' and ') !== false) {
                                    $Sellers = explode('and', trim($Sellers));
                                } else {
                                    if (strpos($Sellers, ' & ') !== false) {
                                        $Sellers = explode('&', trim($Sellers));
                                    } else {
                                        $Sellers = explode(',', trim($Sellers));
                                    }
                                }
                                if ($Sellers) {
                                    $count = 1;
                                    foreach ($Sellers as $keySeller => $ValueSeller) {
                                        if (trim($ValueSeller)) {
                                            $Sellers[$keySeller] = trim($ValueSeller);
                                        }
                                    }
                                    $LastNamePreDom = ''; //print_r($Sellers);
                                    foreach ($Sellers as $Seller) {
                                        $Seller = trim(str_replace(',', '', $Seller));
                                        if (trim(str_replace(',', '', $Seller))) {
                                            $ExplodeSeller = explode(' ', $Seller);
                                            /* iscompany */
                                            $isCompany = false;
                                            foreach ($ExplodeSeller as $comp) {
                                                $temp = str_replace('.', '', trim($comp));
                                                $temp = str_replace(',', '', trim($temp));
                                                foreach ($ArrayCompanyTypes as $type) {
                                                    if (strtolower($temp) == $type) {
                                                        $isCompany = true;
                                                    }
                                                }
                                            }
                                            /**/
                                            if ($isCompany) {
                                                $ArrayParties['Seller' . $count . 'Company'] = ucwords($Seller);
                                            } else {
                                                if (count($ExplodeSeller) > 4) {
                                                    $ArrayParties['Seller' . $count . 'Company'] = ucwords($Seller);
                                                }
                                                if (count($ExplodeSeller) == 4) {
                                                    $ArrayParties['Seller' . $count . 'FirstName'] = VerifyName(ucfirst($ExplodeSeller[0]));
                                                    $ArrayParties['Seller' . $count . 'MiddleName'] = VerifyName(ucfirst($ExplodeSeller[1]));
                                                    $ArrayParties['Seller' . $count . 'LastName'] = VerifyName(ucfirst($ExplodeSeller[2])) . ' ' . VerifyName(ucfirst($ExplodeSeller[3]));
                                                    $LastNamePreDom = VerifyName(ucfirst($ExplodeSeller[2]));
                                                }
                                                if (count($ExplodeSeller) == 3) {
                                                    $ArrayParties['Seller' . $count . 'FirstName'] = VerifyName(ucfirst($ExplodeSeller[0]));
                                                    $ArrayParties['Seller' . $count . 'MiddleName'] = VerifyName(ucfirst($ExplodeSeller[1]));
                                                    $ArrayParties['Seller' . $count . 'LastName'] = VerifyName(ucfirst($ExplodeSeller[2]));
                                                    $LastNamePreDom = VerifyName(ucfirst($ExplodeSeller[2]));
                                                }
                                                if (count($ExplodeSeller) == 2) {
                                                    $ArrayParties['Seller' . $count . 'FirstName'] = VerifyName(ucfirst($ExplodeSeller[0]));
                                                    $ArrayParties['Seller' . $count . 'LastName'] = VerifyName(ucfirst($ExplodeSeller[1]));
                                                    $LastNamePreDom = VerifyName(ucfirst($ExplodeSeller[1]));
                                                }
                                                if (count($ExplodeSeller) == 1) {
                                                    $ArrayParties['Seller' . $count . 'FirstName'] = VerifyName(ucfirst($ExplodeSeller[0]));
                                                }
                                            }
                                            $count++;
                                        }
                                    }
                                    for ($j = 1; $j <= $count - 1; $j++) {
                                        if (!$ArrayParties['Seller' . $j . 'LastName'] && $LastNamePreDom && !$ArrayParties['Seller' . $count . 'Company']) {
                                            $ArrayParties['Seller' . $j . 'LastName'] = $LastNamePreDom;
                                        }
                                    }
                                }
                            }
                            //print_r($Buyers);
                            $Buyers = explode('and', $Buyers); //print_r($Buyers);
                            $tempbuyer = '';
                            foreach ($Buyers as $bu) {
                                if (str_replace(',', '', trim($bu))) {
                                    $tempbuyer .= ' and ' . $bu;
                                }
                            }
                            $Buyers = $tempbuyer; //print_r((strtolower($Buyers[0].$Buyers[1].$Buyers[2])));
                            $Buyers = trim($Buyers); //print_r((strtolower($Buyers[0].$Buyers[1].$Buyers[2])));
                            //print_r(substr($Buyers,3));
                            if (strtolower($Buyers[0] . $Buyers[1] . $Buyers[2]) == 'and') {
                                $Buyers = substr($Buyers, 3);
                            }
                            //print_r($Buyers);
                            //$Buyers = trim($Buyers[1]);//print_r($Buyers);
                            if (trim($Buyers)) {
                                if (strpos($Buyers, ' and ') !== false) {
                                    $Buyers = explode('and', trim($Buyers));
                                } else {
                                    if (strpos($Buyers, ' & ') !== false) {
                                        $Buyers = explode('&', trim($Buyers));
                                    } else {
                                        $Buyers = explode(',', trim($Buyers));
                                    }
                                }//print_r($Buyers);
                                if ($Buyers) {
                                    $count = 1; //print_r($Buyers);
                                    foreach ($Buyers as $keyBuyer => $ValueBuyer) {
                                        if (trim($ValueBuyer)) {
                                            $Buyers[$keyBuyer] = trim($ValueBuyer);
                                        }
                                    }
                                    $LastNamePreDom = ''; //print_r($Buyers);
                                    foreach ($Buyers as $Buyer) {
                                        $Buyer = trim(str_replace(',', '', $Buyer));
                                        if (trim(str_replace(',', '', $Buyer))) {
                                            $ExplodeBuyer = explode(' ', $Buyer);
                                            /* iscompany */
                                            $isCompany = false;
                                            foreach ($ExplodeBuyer as $comp) {
                                                $temp = str_replace('.', '', trim($comp));
                                                $temp = str_replace(',', '', trim($temp));
                                                foreach ($ArrayCompanyTypes as $type) {
                                                    if (strtolower($temp) == $type) {
                                                        $isCompany = true;
                                                    }
                                                }
                                            }
                                            /**/
                                            if ($isCompany) {
                                                $ArrayParties['Buyer' . $count . 'Company'] = ucwords($Buyer);
                                            } else {
                                                if (count($ExplodeBuyer) == 4) {
                                                    $ArrayParties['Buyer' . $count . 'FirstName'] = VerifyName(ucfirst($ExplodeBuyer[0]));
                                                    $ArrayParties['Buyer' . $count . 'MiddleName'] = VerifyName(ucfirst($ExplodeBuyer[1]));
                                                    $ArrayParties['Buyer' . $count . 'LastName'] = VerifyName(ucfirst($ExplodeBuyer[2])) . ' ' . VerifyName(ucfirst($ExplodeBuyer[3]));
                                                    $LastNamePreDom = VerifyName(ucfirst($ExplodeBuyer[2]));
                                                }
                                                if (count($ExplodeBuyer) == 3) {
                                                    $ArrayParties['Buyer' . $count . 'FirstName'] = VerifyName(ucfirst($ExplodeBuyer[0]));
                                                    $ArrayParties['Buyer' . $count . 'MiddleName'] = VerifyName(ucfirst($ExplodeBuyer[1]));
                                                    $ArrayParties['Buyer' . $count . 'LastName'] = VerifyName(ucfirst($ExplodeBuyer[2]));
                                                    $LastNamePreDom = VerifyName(ucfirst($ExplodeBuyer[2]));
                                                }
                                                if (count($ExplodeBuyer) == 2) {
                                                    $ArrayParties['Buyer' . $count . 'FirstName'] = VerifyName(ucfirst($ExplodeBuyer[0]));
                                                    $ArrayParties['Buyer' . $count . 'LastName'] = VerifyName(ucfirst($ExplodeBuyer[1]));
                                                    $LastNamePreDom = VerifyName(ucfirst($ExplodeBuyer[1]));
                                                }
                                                if (count($ExplodeBuyer) == 1) {
                                                    $ArrayParties['Buyer' . $count . 'FirstName'] = VerifyName(ucfirst($ExplodeBuyer[0]));
                                                }
                                            }
                                            $count++;
                                        }
                                    }
                                    for ($j = 1; $j <= $count - 1; $j++) {
                                        if (!$ArrayParties['Buyer' . $j . 'LastName'] && $LastNamePreDom && !$ArrayParties['Buyer' . $count . 'Company']) {
                                            $ArrayParties['Buyer' . $j . 'LastName'] = $LastNamePreDom;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (strpos($value, 'The legal description') !== false) {
                        if (!$ArrayAddress['LegalDescription']) {
                            $LegalDescription = explode('is', $value);
                            $ArrayAddress['LegalDescription'] = '';
                            if (trim($LegalDescription[1])) {
                                $ArrayAddress['LegalDescription'] .= trim($LegalDescription[1]);
                            }
                            $legal1 = trim($TextContract[$key + 1]);
                            if (strpos($legal1, 'together with all existing') === false) {
                                if (!is_numeric(str_replace('*', '', trim($legal1))) && trim(str_replace('*', '', trim($legal1)))) {
                                    $ArrayAddress['LegalDescription'] .= ' ' . trim($legal1);
                                    if (strpos(trim($TextContract[$key + 2]), 'together with all existing') === false) {
                                        $legal1 = trim($TextContract[$key + 2]);
                                        if (!is_numeric(str_replace('*', '', trim($legal1))) && trim(str_replace('*', '', trim($legal1)))) {
                                            $ArrayAddress['LegalDescription'] .= ' ' . trim($TextContract[$key + 2]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (strpos($value, 'Other Personal Property items included') !== false) {
                        $PurchaseIn = explode('purchase are', $value);
                        $ArrayOthers['PropertyItemsIncludedInPurchase'] = '';
                        if (trim($PurchaseIn[1])) {
                            $ArrayOthers['PropertyItemsIncludedInPurchase'] = str_replace(':', '', trim($PurchaseIn[1]));
                        }//print_r($TextContract[$key + 1]);
                        if (strpos(strtolower($TextContract[$key + 1]), 'purchase price') === false && strlen(trim($TextContract[$key + 1])) > 1) {
                            if (!is_numeric(str_replace('*', '', trim($TextContract[$key + 1])))) {
                                $ArrayOthers['PropertyItemsIncludedInPurchase'] .= ' ' . trim($TextContract[$key + 1]);
                            }
                        }
                    }
                    if (strpos($value, 'following items are excluded') !== false) {
                        $PurchaseEx = explode('the purchase', $value);
                        $ArrayOthers['PropertyItemsExcludedInPurchase'] = '';
                        if (trim($PurchaseEx[1])) {
                            $ArrayOthers['PropertyItemsExcludedInPurchase'] = str_replace(':', '', trim($PurchaseEx[1]));
                        }
                        if (strpos($TextContract[$key + 1], 'PURCHASE PRICE AND CLOSING') === false && strlen(trim($TextContract[$key + 1])) > 1) {
                            if (!is_numeric(str_replace('*', '', trim($TextContract[$key + 1])))) {
                                $ArrayOthers['PropertyItemsExcludedInPurchase'] .= ' ' . trim($TextContract[$key + 1]);
                            }
                        }
                    }
                    if (strpos($value, 'is to be made within') !== false) {
                        $PurchaseEx = explode('is to be made within', $value);
                        $ArrayOthers['DepositDeliveredEscrowAgentIsToBeMadeWithin'] = '';
                        if (trim($PurchaseEx[1])) {
                            $PurchaseEx = explode('(', $PurchaseEx[1]);
                            if (VerifyNumeric(trim($PurchaseEx[0]))) {
                                $ArrayOthers['DepositDeliveredEscrowAgentIsToBeMadeWithin'] = str_replace(':', '', trim($PurchaseEx[0]));
                            } else {
                                $ArrayOthers['DepositDeliveredEscrowAgentIsToBeMadeWithin'] = 3;
                            }
                        }
                    }
                    if (strpos($value, 'Escrow Agent Information') !== false) {
                        $EscrowAgent = explode('Name', $value); //print_r($value);
                        $EscrowAgentName = trim(str_replace(array(':', '_'), '', $EscrowAgent[1]));
                        $baseKey = $key + 1;
                        if (!$EscrowAgentName) {
                            if (strpos(trim($TextContract[$baseKey]), 'Address') === false) {
                                $EscrowAgentName = trim($TextContract[$baseKey]);
                                $baseKey = $baseKey + 1;
                            }
                        }
                        if (strpos($EscrowAgentName, '/') !== false) {
                            $EscrowAgentName = explode('/', $EscrowAgentName);
                            $isCompany = false; //print_r($EscrowAgentName);print_r($ArrayCompanyTypes);
                            foreach ($ArrayCompanyTypes as $type) {
                                if (strpos(strtolower($EscrowAgentName[0]), $type) !== false || strpos(strtolower($EscrowAgentName[0]), 'prime') !== false || strpos(strtolower($EscrowAgentName[0]), 'florida') !== false) {
                                    $isCompany = true;
                                }
                            }
                            if ($isCompany) {
                                $ArrayOthers['EscrowAgentCompany'] = trim($EscrowAgentName[0]);
                                $ArrayOthers['EscrowAgentName'] = trim($EscrowAgentName[1]);
                            } else {
                                $ArrayOthers['EscrowAgentName'] = trim($EscrowAgentName[0]);
                            }
                        } else {
                            $isCompany = false;
                            foreach ($ArrayCompanyTypes as $type) {
                                if (strpos(strtolower($EscrowAgentName), $type) !== false || strpos(strtolower($EscrowAgentName), 'prime') !== false || strpos(strtolower($EscrowAgentName), 'florida') !== false) {
                                    $isCompany = true;
                                }
                            }
                            if ($isCompany) {
                                $ArrayOthers['EscrowAgentCompany'] = trim($EscrowAgentName);
                            } else {
                                $ArrayOthers['EscrowAgentName'] = trim($EscrowAgentName);
                            }
                        }
                        if (strpos(trim($TextContract[$baseKey]), 'Address') !== false) {
                            $EscrowAgentAddress = explode('Address', trim($TextContract[$baseKey]));
                            if (trim(str_replace(':', '', $EscrowAgentAddress[1]))) {
                                $ArrayOthers['EscrowAgentAddress'] = trim(str_replace(':', '', $EscrowAgentAddress[1]));
                            } else {
                                $baseKey = $baseKey + 1;
                                $ArrayOthers['EscrowAgentAddress'] = trim($TextContract[$baseKey]);
                            }
                        } else {
                            $baseKey = $baseKey + 1;
                            if (strpos(trim($TextContract[$baseKey]), 'Address') !== false) {
                                $EscrowAgentAddress = explode('Address', trim($TextContract[$baseKey]));
                                if (trim(str_replace(':', '', $EscrowAgentAddress[1]))) {
                                    $ArrayOthers['EscrowAgentAddress'] = trim(str_replace(':', '', $EscrowAgentAddress[1]));
                                } else {
                                    $baseKey = $baseKey + 1;
                                    $ArrayOthers['EscrowAgentAddress'] = trim($TextContract[$baseKey]);
                                }
                            }
                        }
                        $baseKey = $baseKey + 1;
                        $EmailE = true;
                        $FaxE = true;
                        if (strpos(trim($TextContract[$baseKey]), 'Phone') !== false) {
                            $EscrowData = explode('Phone', trim($TextContract[$baseKey]));
                            if (strpos(trim($TextContract[$baseKey]), 'E-mail') !== false) {
                                $EmailE = false;
                                $EscrowDataPhone = explode('E-mail', $EscrowData[1]);
                                $ArrayOthers['EscrowAgentPhone'] = VerifyPhone(trim(str_replace(':', '', $EscrowDataPhone[0])));
                                if (strpos(trim($TextContract[$baseKey]), 'Fax') !== false) {
                                    $FaxE = false;
                                    $EscrowDataFax = explode('Fax', $EscrowDataPhone[1]);
                                    $ArrayOthers['EscrowAgentE-mail'] = VerifyEmail(trim(str_replace(':', '', $EscrowDataFax[0])));
                                    $ArrayOthers['EscrowAgentFax'] = VerifyPhone(trim(str_replace(':', '', $EscrowDataFax[1])));
                                } else {
                                    $ArrayOthers['EscrowAgentE-mail'] = VerifyEmail(trim(str_replace(':', '', $EscrowDataPhone[1])));
                                }
                            } else {
                                $ArrayOthers['EscrowAgentPhone'] = VerifyPhone(trim(str_replace(':', '', $EscrowData[1])));
                            }
                        } else {
                            $baseKey = $baseKey + 1;
                            if (strpos(trim($TextContract[$baseKey]), 'Phone') !== false) {
                                $EscrowData = explode('Phone', trim($TextContract[$baseKey]));
                                if (strpos(trim($TextContract[$baseKey]), 'E-mail') !== false) {
                                    $EmailE = false;
                                    $EscrowDataPhone = explode('E-mail', $EscrowData[1]);
                                    $ArrayOthers['EscrowAgentPhone'] = VerifyPhone(trim(str_replace(':', '', $EscrowDataPhone[0])));
                                    if (strpos(trim($TextContract[$baseKey]), 'Fax') !== false) {
                                        $FaxE = false;
                                        $EscrowDataFax = explode('Fax', $EscrowDataPhone[1]);
                                        $ArrayOthers['EscrowAgentE-mail'] = VerifyEmail(trim(str_replace(':', '', $EscrowDataFax[0])));
                                        $ArrayOthers['EscrowAgentFax'] = VerifyPhone(trim(str_replace(':', '', $EscrowDataFax[1])));
                                    } else {
                                        $ArrayOthers['EscrowAgentE-mail'] = VerifyEmail(trim(str_replace(':', '', $EscrowDataPhone[1])));
                                    }
                                } else {
                                    $ArrayOthers['EscrowAgentPhone'] = VerifyPhone(trim(str_replace(':', '', $EscrowData[1])));
                                }
                            }
                        }
                        if ($EmailE) {
                            $baseKey = $baseKey + 1;
                            if (strpos(trim($TextContract[$baseKey]), 'E-mail') !== false) {
                                $EmailE = false;
                                $EscrowDataPhone = explode('E-mail', $EscrowData[1]);
                                $ArrayOthers['EscrowAgentPhone'] = VerifyPhone(trim(str_replace(':', '', $EscrowDataPhone[0])));
                                if (strpos(trim($TextContract[$baseKey]), 'Fax') !== false) {
                                    $FaxE = false;
                                    $EscrowDataFax = explode('Fax', $EscrowDataPhone[1]);
                                    $ArrayOthers['EscrowAgentE-mail'] = VerifyEmail(trim(str_replace(':', '', $EscrowDataFax[0])));
                                    $ArrayOthers['EscrowAgentFax'] = VerifyPhone(trim(str_replace(':', '', $EscrowDataFax[1])));
                                } else {
                                    $ArrayOthers['EscrowAgentE-mail'] = VerifyEmail(trim(str_replace(':', '', $EscrowDataPhone[1])));
                                }
                            } else {
                                $baseKey = $baseKey + 1;
                                if (strpos(trim($TextContract[$baseKey]), 'E-mail') !== false) {
                                    $EmailE = false;
                                    $EscrowDataPhone = explode('E-mail', $EscrowData[1]);
                                    $ArrayOthers['EscrowAgentPhone'] = VerifyPhone(trim(str_replace(':', '', $EscrowDataPhone[0])));
                                    if (strpos(trim($TextContract[$baseKey]), 'Fax') !== false) {
                                        $FaxE = false;
                                        $EscrowDataFax = explode('Fax', $EscrowDataPhone[1]);
                                        $ArrayOthers['EscrowAgentE-mail'] = VerifyEmail(trim(str_replace(':', '', $EscrowDataFax[0])));
                                        $ArrayOthers['EscrowAgentFax'] = VerifyPhone(trim(str_replace(':', '', $EscrowDataFax[1])));
                                    } else {
                                        $ArrayOthers['EscrowAgentE-mail'] = VerifyEmail(trim(str_replace(':', '', $EscrowDataPhone[1])));
                                    }
                                }
                            }
                        }
                        if ($FaxE) {
                            $baseKey = $baseKey + 1;
                            if (strpos(trim($TextContract[$baseKey]), 'Fax') !== false) {
                                $FaxE = false;
                                $EscrowDataFax = explode('Fax', $EscrowDataPhone[1]);
                                $ArrayOthers['EscrowAgentFax'] = VerifyPhone(trim(str_replace(':', '', $EscrowDataFax[1])));
                            } else {
                                $baseKey = $baseKey + 1;
                                if (strpos(trim($TextContract[$baseKey]), 'Fax') !== false) {
                                    $FaxE = false;
                                    $EscrowDataFax = explode('Fax', $EscrowDataPhone[1]);
                                    $ArrayOthers['EscrowAgentFax'] = VerifyPhone(trim(str_replace(':', '', $EscrowDataFax[1])));
                                }
                            }
                        }
                    }
                    if (strpos($value, 'Additional deposit to be delivered') !== false) {
                        $AdditionalDeposit = explode('Agent within', $value);
                        if (trim($AdditionalDeposit[1])) {
                            if (strpos(trim($AdditionalDeposit[1]), '(') !== false) {
                                $AdditionalDeposit = explode('(', $AdditionalDeposit[1]);
                                if (VerifyNumeric(trim($AdditionalDeposit[0]))) {
                                    $ArrayOthers['AdditionalDepositDays'] = trim($AdditionalDeposit[0]);
                                } else {
                                    $ArrayOthers['AdditionalDepositDays'] = 10;
                                }
                            } else {
                                $ArrayOthers['AdditionalDepositDays'] = trim($AdditionalDeposit[1]);
                            }
                        } else {
                            $ArrayOthers['AdditionalDepositDays'] = 10;
                        }
                        $AdditionalDepositAmount = $TextContract[$key + 2];
                        $AdditionalDepositAmount = explode('Effective Date', $AdditionalDepositAmount);
                        if (str_replace(array('', '', ''), array('', '', ''), $AdditionalDepositAmount[1])) {
                            $ArrayAmounts['AdditionalDepositAmount'] = str_replace(array('', '', ''), array('', '', ''), $AdditionalDepositAmount[1]);
                        } else {
                            $ArrayAmounts['AdditionalDepositAmount'] = 0;
                        }
                    }
                    if (strpos($value, 'Balance to close') !== false) {
                        $BalanceToClose = trim($TextContract[$key + 1]);
                        $BalanceToClose = explode('COLLECTED funds', $BalanceToClose);
                        if (strpos($BalanceToClose[1], '$') !== false) {
                            $BalanceToClose = explode('$', $BalanceToClose);
                            $ArrayAmounts['BalanceToClose'] = str_replace(array('', '', ''), array('', '', ''), trim($BalanceToClose[1]));
                        } else {
                            $BalanceToClose = trim($TextContract[$key + 2]);
                            if (strpos($BalanceToClose, 'For the definition') === false && strpos($BalanceToClose, '$') !== false) {
                                $BalanceToClose = explode('$', $BalanceToClose);
                                $ArrayAmounts['BalanceToClose'] = str_replace(array('', '', ''), array('', '', ''), trim($BalanceToClose[1]));
                            }
                        }
                    }
                    if (strpos($value, 'Contract is contingent upon Buyer') !== false) {
                        $ContractIsContingent = trim($TextContract[$key + 1]);
                        $ContractIsContingent = explode('(describe)', $ContractIsContingent);
                        $ArrayOthers['ContractIsContingentOtherDescription'] = $ContractIsContingent[0];
                        $ContractIsContingentDays = explode('within', $ContractIsContingent[1]);
                        if (trim($ContractIsContingentDays[1])) {
                            if (strpos(trim($ContractIsContingentDays[1]), '(') !== false) {
                                $ContractIsContingentDays = explode('(', trim($ContractIsContingentDays[1]));
                                if (VerifyNumeric(trim(str_replace('_', '', $ContractIsContingentDays[0])))) {
                                    $ArrayOthers['ContractIsContingentOtherDays'] = trim(str_replace('_', '', $ContractIsContingentDays[0]));
                                } else {
                                    $ArrayOthers['ContractIsContingentOtherDays'] = 30;
                                }
                            } else {
                                $ArrayOthers['ContractIsContingentOtherDays'] = $ContractIsContingentDays[1];
                            }
                        } else {
                            $ArrayOthers['ContractIsContingentOtherDays'] = 30;
                        }
                    }
                    if (strpos($value, 'initial interest rate') !== false) {
                        $InitialInterestRate = explode('not to exceed', $value);
                        if (strpos($InitialInterestRate[1], '%') !== false) {
                            $InitialInterestRate = explode('%', $InitialInterestRate[1]);
                            $ArrayOthers['InitialInterestRate'] = trim(str_replace('_', '', $InitialInterestRate[0]));
                        } else {
                            $ArrayOthers['InitialInterestRate'] = $InitialInterestRate[1];
                        }
                    }
                    if (strpos($value, 'and for a term of') !== false) {
                        $LoanTerms = explode('and for a term of', $value);
                        $LoanTerms = explode('(', $LoanTerms[1]); //print_r($LoanTerms);
                        if (VerifyNumeric(trim(str_replace(array('.', '_'), array('', ''), $LoanTerms[0])))) {
                            $ArrayOthers['LoanTerms'] = trim(str_replace(array('.', '_'), array('.', '_'), $LoanTerms[0]));
                        } else {
                            $ArrayOthers['LoanTerms'] = 30;
                        }
                    }
                    if (strpos($value, 'application for the Financing within') !== false) {
                        $ApplicationFinancingDays = explode('application for the Financing within', $value);
                        if (trim($ApplicationFinancingDays[1])) {
                            if (strpos($ApplicationFinancingDays[1], '(') !== false) {
                                $ApplicationFinancingDays = explode('(', $ApplicationFinancingDays[1]);
                                if (VerifyNumeric(trim(str_replace(array('.', '_'), array('', ''), $ApplicationFinancingDays[0])))) {
                                    $ArrayOthers['ApplicationFinancingDays'] = trim(str_replace(array('.', '_'), array('', ''), $ApplicationFinancingDays[0]));
                                } else {
                                    $ArrayOthers['ApplicationFinancingDays'] = 5;
                                }
                            } else {
                                $ArrayOthers['ApplicationFinancingDays'] = trim($ApplicationFinancingDays[1]);
                            }
                        } else {
                            $ArrayOthers['ApplicationFinancingDays'] = 5;
                        }
                    }
                    if (strpos($value, 'COSTS TO BE PAID BY SELLER') !== false) {
                        for ($j = 1; $j <= 20; $j++) {
                            if (strpos($TextContract[$key + $j], 'Other') !== false) {
                                $CostPaidBySellerOther = explode('Other:', $TextContract[$key + $j]);
                                $ArrayOthers['CostPaidBySellerOther'] = str_replace('_', '', $CostPaidBySellerOther[1]);
                                $j = 21;
                            }
                        }
                    }
                    if (strpos($value, 'COSTS TO BE PAID BY BUYER') !== false) {
                        for ($j = 1; $j <= 20; $j++) {
                            if (strpos($TextContract[$key + $j], 'Other') !== false) {
                                $CostPaidByBuyerOther = explode('Other:', $TextContract[$key + $j]);
                                $ArrayOthers['CostPaidByBuyerOther'] = str_replace('_', '', $CostPaidByBuyerOther[1]);
                                $j = 21;
                            }
                        }
                    }
                    if (strpos($value, 'TITLE EVIDENCE AND INSURANCE') !== false) {
                        $TitleEvidenceAndInsurance = explode('At least', $value);
                        $TitleEvidenceAndInsurance = explode('(', $TitleEvidenceAndInsurance[1]);
                        if (VerifyNumeric(trim($TitleEvidenceAndInsurance[0]))) {
                            $ArrayOthers['TitleEvidenceAndInsurance'] = $TitleEvidenceAndInsurance[0];
                        } else {
                            $ArrayOthers['TitleEvidenceAndInsurance'] = 15;
                        }
                    }
                    if (strpos($value, 'Seller shall not be obligated to pay') !== false) {
                        $TitleEvidenceAndInsurance = explode('$', str_replace('_', '', $value)); //print_r();
                        if (trim($TitleEvidenceAndInsurance[1])) {
                            $ArrayAmounts['SellerShallNotBeObligatedToPayMoreThan'] = trim($TitleEvidenceAndInsurance[1]);
                        } else {
                            $ArrayAmounts['SellerShallNotBeObligatedToPayMoreThan'] = 200;
                        }
                    }
                    if (strpos($value, 'HOME WARRANTY:') !== false) {
                        $HomeWarrantyBy = explode('at a cost not to exceed', $TextContract[$key + 1]);
                        $ArrayOthers['HomeWarrantyBy'] = str_replace(array('_', '', ''), array('', '', ''), trim($HomeWarrantyBy[0]));
                        $ArrayOthers['HomeWarrantyAmount'] = str_replace(array('_', '', ''), array('', '', ''), trim($HomeWarrantyBy[1]));
                    }
                    if (strpos($value, 'written notice to Seller within') !== false) {
                        $WrittenNoticeToSellerWithin = explode('written notice to Seller within', $value);
                        $WrittenNoticeToSellerWithin = explode('(if', $WrittenNoticeToSellerWithin[1]);
                        if (trim(str_replace(array('_'), array(''), $WrittenNoticeToSellerWithin[0]))) {
                            $ArrayOthers['WrittenNoticeToSellerWithin'] = $HomeWarrantyBy[0];
                        } else {
                            $ArrayOthers['WrittenNoticeToSellerWithin'] = 20;
                        }
                    }
                    if (strpos($value, 'Special Taxing District') !== false) {
                        $AdditionalTermsOther = trim($TextContract[$key + 2]);
                        $AdditionalTermsOther2 = trim($TextContract[$key + 3]);
                        $AdditionalTermsOther3 = trim($TextContract[$key + 4]);
                        $AdditionalTermsOther = explode('Other:', $AdditionalTermsOther);
                        $ArrayOthers['AdditionalTermsOtherLine1'] = trim($AdditionalTermsOther[1]);
                        $AddTermsIndex = $key + 2;
                        if (strpos($AdditionalTermsOther2, 'ADDITIONAL TERMS') === false) {
                            if (!is_numeric(trim(str_replace('*', '', $AdditionalTermsOther2)))) {
                                $ArrayOthers['AdditionalTermsOtherLine2'] = trim($AdditionalTermsOther2);
                            }
                            if (strpos($AdditionalTermsOther3, 'ADDITIONAL TERMS') === false) {
                                if (!is_numeric(trim(str_replace('*', '', $AdditionalTermsOther3)))) {
                                    $ArrayOthers['AdditionalTermsOtherLine3'] = trim($AdditionalTermsOther3);
                                }
                                $AddTermsIndex = $key + 5;
                            } else {
                                $AddTermsIndex = $key + 4;
                            }
                        } else {
                            $AddTermsIndex = $key + 3;
                        }
                    }
                    if (strpos($value, 'ADDITIONAL TERMS:') !== false) {
                        $AddTermsIndex = $key;
                        $AdditionalTermsLines = explode('ADDITIONAL TERMS:', trim($value));
                        if ($AdditionalTermsLines[1]) {
                            $ArrayOthers['AdditionalTermsLine0'] = trim($AdditionalTermsLines[1]);
                        }
                        for ($j = 1; $j <= 50; $j++) {
                            if (strpos(trim($TextContract[$AddTermsIndex + $j]), 'COUNTER-OFFER/REJECTION') === false) {
                                $msjtemp = trim($TextContract[$AddTermsIndex + $j]);
                                $msjtemp = trim(str_replace('*', '', $msjtemp));
                                if (!is_numeric($msjtemp) && trim($msjtemp) != '-DS') {
                                    $ArrayOthers['AdditionalTermsLine' . $j] = trim($TextContract[$AddTermsIndex + $j]);
                                }
                            } else {
                                $j = 51;
                            }
                        }
                    }

                    if (strpos($value, 'address for purposes of notice') !== false) {
                        if (strpos($value, 'Buyer') !== false) {
                            $msjtemp = str_replace('*', '', trim($TextContract[$key + 1]));
                            if (strpos($msjtemp, 'dotloop verified') === false && strpos($value, 'address for purposes of notice') === false && !is_numeric($msjtemp)) {
                                $ArrayOthers['BuyerAddress'] = trim($msjtemp);
                            } else {
                                $ArrayOthers['BuyerAddress'] = '';
                            }
                        }
                        if (strpos($value, 'Seller') !== false) {
                            $msjtemp = str_replace('*', '', trim($TextContract[$key + 1]));
                            if (strpos($msjtemp, 'dotloop verified') === false && !is_numeric($msjtemp)) {
                                $ArrayOthers['SellerAddress'] = trim($msjtemp);
                            } else {
                                $ArrayOthers['SellerAddress'] = '';
                            }
                        }
                    }
                    if (strpos($value, 'Cooperating Sales Associate') !== false) {
                        for ($j = 1; $j <= 3; $j++) {
                            $msjtemp = str_replace('*', '', trim($TextContract[$key - $j]));
                            if (!is_numeric($msjtemp) && !$ArrayOthers['CooperatingSalesAssociate']) {
                                $ArrayOthers['CooperatingSalesAssociate'] = $msjtemp;
                            }
                        }
                    }
                    if (strpos($value, 'Listing Sales Associate') !== false) {
                        $msjtemp = str_replace('*', '', trim($TextContract[$key - 1]));
                        if (!is_numeric($msjtemp)) {
                            $ArrayOthers['ListingSalesAssociate'] = $msjtemp;
                        }
                    }
                    if (strpos($value, 'Cooperating Broker, if any') !== false) {
                        $msjtemp = str_replace('*', '', trim($TextContract[$key - 1]));
                        if (!is_numeric($msjtemp)) {
                            $ArrayOthers['CooperatingBroker'] = $msjtemp;
                        }
                        $msjtemp = str_replace('*', '', trim($TextContract[$key + 1]));
                        if (!is_numeric($msjtemp)) {
                            $ArrayOthers['ListingBroker'] = $msjtemp;
                        }
                    }
                }

                //print_r($TextContract);
            } else {
                //echo 'Error : path not found';
            }
        }
        /* FormatMoney */
        $purchasePrice = 0;
        //print_r($ArrayAmounts);
        foreach ($ArrayAmounts as $key => $value) {
            //if($key == 'LoanAmount'){
            //}else{
            if (strpos($value, '%') === false) {
                $ArrayAmounts[$key] = VerifyAmounts($value);
                if ($key == 'PurchasePrice') {
                    if ($ArrayAmounts[$key]) {
                        $purchasePrice = $ArrayAmounts[$key];
                    } else {
                        $purchasePrice = '';
                    }
                }
            } else {
                $ArrayAmounts[$key] = $value;
            }
            //}
        }
        foreach ($ArrayAmounts as $key => $value) {
            //if($key == 'LoanAmount'){
            //}else{
            if (strpos($value, '%') !== false) {
                if ($purchasePrice) {
                    $purchasePrice = str_replace(array('$', ','), '', $purchasePrice);
                    $temp = ($purchasePrice / 100) * str_replace(array('%', '_', ''), '', trim($value));
                    $ArrayAmounts[$key] = money_format('%.2n', $temp);
                } else {
                    $ArrayAmounts[$key] = '';
                }
            } else {
                $ArrayAmounts[$key] = $value;
            }
            //}
        }
        /**/
        /* Correct Address */
        $ArrayAddress['Address'] = str_replace('_', '', trim(implode(' ', array_reverse(explode(' ', $ArrayAddress['Address'])))));
        /**/
        $CountAll = 0;
        $ArrayReturn['Address'] = json_encode($ArrayAddress);
        $CountAddress = 0;
        foreach ($ArrayAddress as $value) {
            if (!trim($value)) {
                $CountAddress++;
            }
        }
        if ((count($ArrayAddress) / 2) < $CountAddress) {
            $CountAll++;
        }
        $ArrayReturn['Amounts'] = json_encode($ArrayAmounts);
        $CountAmount = 0;
        foreach ($ArrayAmounts as $value) {
            if (!trim($value)) {
                $CountAmount++;
            }
        }
        if ((count($ArrayAmounts) / 2) < $CountAmount) {
            $CountAll++;
        }
        $ArrayReturn['Transaction'] = json_encode($ArrayTransaction);
        $CountTransaction = 0;
        foreach ($ArrayTransaction as $value) {
            if (!trim($value)) {
                $CountTransaction++;
            }
        }
        if ((count($ArrayTransaction) / 2) < $CountTransaction) {
            $CountAll++;
        }
        $ArrayReturn['Parties'] = json_encode($ArrayParties);
        $CountParty = 0;
        foreach ($ArrayParties as $value) {
            if (!trim($value)) {
                $CountParty++;
            }
        }
        if ((count($ArrayParties) / 2) < $CountParty) {
            $CountAll++;
        }
        if (!$ArrayParties) {
            $CountAll++;
        }
        $ArrayReturn['Other'] = json_encode($ArrayOthers);
        $CountOther = 0;
        foreach ($ArrayOthers as $value) {
            if (!trim($value)) {
                $CountOther++;
            }
        }
        if ((count($ArrayOthers) / 2) < $CountOther) {
            $CountAll++;
        }
        if ($CountAll > 2) {
            $ArrayReturn['no50'] = 'no50';
        }
        return json_encode($ArrayReturn);
    }
}

function VerifyAmounts($Amount) {
    $Amount = str_replace(array('_', ' '), '', $Amount);
    if (substr($Amount, -3) == '.00' || substr($Amount, -3) == ',00') {
        $Amount = substr($Amount, 0, -3);
    }
    $Amount = str_replace(array('$', '.', ','), '', $Amount);
    if (is_numeric($Amount)) {
        return money_format('%.2n', $Amount);
    } else {
        return '';
    }
}

function VerifyZip($Zip) {
    $Zip = str_replace(array('_', ' '), '', $Zip);
    if (strpos($Zip, '-') !== false) {
        $Zip = explode('-', $Zip);
        if (strlen($Zip[0]) == 5 && is_numeric($Zip[0]) && strlen($Zip[1]) == 4 && is_numeric($Zip[1])) {
            return $Zip[0] . '-' . $Zip[1];
        } else {
            return '';
        }
    } else {
        if (strlen($Zip) == 5 && is_numeric($Zip)) {
            return $Zip;
        } else {
            return '';
        }
    }
}

function VerifyState($State) {
    $State = str_replace(array('_', ' '), '', $State);
    if (ctype_alpha($State)) {
        return $State;
    } else {
        return '';
    }
}

function VerifyName($Name) {
    $Name = str_replace(array('_', ' '), '', $Name);
    if (ctype_alpha($Name)) {
        return $Name;
    } else {
        return '';
    }
}

function VerifyPhone($phone) {
    $temp = str_replace(array('_', ' ', '(', ')', '-', '.'), '', $phone);
    if (is_numeric($temp)) {
        $phone = str_replace(array('_', ' '), '', $phone);
        return $phone;
    } else {
        return '';
    }
}

function VerifyEmail($Email) {
    if (filter_var($Email, FILTER_VALIDATE_EMAIL) !== false) {
        return $Email;
    } else {
        return '';
    }
}

function VerifyNumeric($data) {
    $data = str_replace(array('_', ' '), '', $data);
    if (is_numeric($data)) {
        return $data;
    } else {
        return '';
    }
}

function verifyDate($Date) {
    $temp = str_replace(array('/', '-'), '', $Date);
    if (!is_numeric($temp)) {
        return '';
    }
    $now = date('m/d/Y');
    $datetime1 = new DateTime($now);
    $datetime2 = new DateTime($Date);
    $interval = $datetime2->diff($datetime1);
    $intervalMeses = $interval->format("%m");
    $intervalAnos = $interval->format("%y") * 12;
    if (($intervalMeses + $intervalAnos) > 12) {
        return '';
    } else {
        return $Date;
    }
}

// 02 
function VerifyAddress($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        $general_config_obj = $GetClass->GetClass('general_config');
        $general_config = $general_config_obj->getgeneral_configById(1);
        $dataLogin = json_decode($general_config->geternst(), true);
        $test = 'Test';
        if ($dataLogin['isteststamps'] == 'yes') {
            $url = $dataLogin['StampsUrl' . $test];
        } else {
            $url = $dataLogin['StampsUrl'] . '?wsdl';
        }
        $ID = $dataLogin['StampsID' . $test];
        $User = $dataLogin['StampsUser' . $test];
        $pass = $dataLogin['StampsPass' . $test];
        $credentials = array('Credentials' => array('IntegrationID' => $ID, 'Username' => $User, 'Password' => $pass));
        try {
            $client = new SoapClient($url);
            $oAuth = $client->AuthenticateUser($credentials);
            $oAuth = $oAuth->Authenticator;
        } catch (Exception $e) {
            die('Error : ' . $e->getmessage());
        }
        try {
            $AddressTo = array(
                'FullName' => 'Titlehost',
                'Address1' => $array['PropertyAddressRequest'],
                'Address2' => $array['PropertyAddress2Request'],
                'City' => $array['PropertyCityRequest'],
                'State' => $array['PropertyStateRequest'],
                'ZIPcode' => $array['PropertyZipRequest']);
            $CleanseAddress = $client->CleanseAddress(array('Authenticator' => $oAuth, 'Address' => $AddressTo));
            //print_r($CleanseAddress);
            $addressT = $CleanseAddress->Address;
            $arrayDiff = array();
            if ($addressT) {
                if ($addressT->Address1 != $array['PropertyAddressRequest']) {
                    $arrayDiff['PropertyAddressRequest'] = $addressT->Address1;
                }
                if ($addressT->Address2 != $array['PropertyAddress2Request']) {
                    $arrayDiff['PropertyAddress2Request'] = $addressT->Address2;
                }
                if ($addressT->City != $array['PropertyCityRequest']) {
                    $arrayDiff['PropertyCityRequest'] = $addressT->City;
                }
                if ($addressT->State != $array['PropertyStateRequest']) {
                    $arrayDiff['PropertyStateRequest'] = $addressT->State;
                }
                if ($addressT->ZIPCode != $array['PropertyZipRequest']) {
                    $arrayDiff['PropertyZipRequest'] = $addressT->ZIPCode;
                }
            }
            //if($arrayDiff){
            echo json_encode($arrayDiff);
            /* }else{
              echo 'ok';
              } */
        } catch (Exception $e) {
            die('Error : ' . $e->getmessage());
        }
    } else {
        echo "Error : An array expected";
    }
}

// 03 
function GetPropertyReport($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        $general_config_obj = $GetClass->GetClass('general_config');
        $general_config = $general_config_obj->getgeneral_configById(1);
        $gc_data = json_decode($general_config->getofficeinfo(), true);
        $user = $gc_data['corelogic_user'];
        $pass = $gc_data['corelogic_pass'];
        $taxid = trim(str_replace(array('-', '') . array(' ', ''), $array['PropertyTaxIdRequest']));
        $XMLRequest = '<?xml version = "1.0" encoding = "UTF-8"?>
                        <!DOCTYPE REQUEST_GROUP SYSTEM "C2DRequestv2.0.dtd">
                        <REQUEST_GROUP MISMOVersionID = "2.1">
                            <REQUEST LoginAccountIdentifier = "' . $user . '"
                                 LoginAccountPassword = "' . $pass . '"
                                 _JobIdentifier = "This is the 50 character alphanumeric job id field">
                                <REQUESTDATA>
                                    <PROPERTY_INFORMATION_REQUEST _ActionType = "Submit">
                                        <_CONNECT2DATA_PRODUCT ' . $array['ReportType'] . ' = "Y"
                                               _IncludePendingRecordsIndicator = "Y"
                                               _IncludeSearchCriteriaIndicator = "Y"
                                               _IncludePDFIndicator = "N"/>
                                        <_PROPERTY_CRITERIA _StreetAddress = "' . trim(str_replace(array('Rental'), '', $array['PropertyAddressRequest'])) . '"
                                                _StreetAddress2 = "' . $array['PropertyAddress2Request'] . '"
                                                _City = "' . $array['PropertyCityRequest'] . '"
                                                _State = "' . $array['PropertyStateRequest'] . '"
                                                _County = "' . str_replace('-', ' ', $array['PropertyCountyRequest']) . '"
                                                _PostalCode = "' . $array['PropertyZipRequest'] . '"
                                                _StrictMatch = "N">
                                            <PARSED_STREET_ADDRESS _HouseNumber = ""
                                               _DirectionPrefix = ""
                                               _StreetName = ""
                                               _StreetSuffix = ""
                                               _ApartmentOrUnit = ""
                                               _DirectionSuffix = "" />
                                        </_PROPERTY_CRITERIA>
                                        <_SEARCH_CRITERIA>
                                            <_SUBJECT_SEARCH _OwnerLastName = ""
                                             _OwnerFirstName = ""
                                             _AssessorsParcelIdentifier = ""
                                             _Municipality = ""
                                             _UnformattedParcelIdentifier = "' . $taxid . '"/>
                                        </_SEARCH_CRITERIA>
                                    </PROPERTY_INFORMATION_REQUEST>
                                </REQUESTDATA>
                            </REQUEST>
                        </REQUEST_GROUP>';
        $corelogic_obj = $GetClass->GetClass('corelogic');
        $Response = $corelogic_obj->curl_post($XMLRequest);
        /* For Test */
        /* $Response = "<?xml version='1.0' encoding='UTF-8'?><!DOCTYPE RESPONSE_GROUP SYSTEM 'https://xml.connect2data.com/C2DResponsev2.0.dtd'><RESPONSE_GROUP MISMOVersionID='2.1'><RESPONDING_PARTY _Name='CoreLogic' _StreetAddress='40 Pacific Suite 900' _City='Irvine' _State='CA' _PostalCode='92618' /><RESPOND_TO_PARTY _Name='' _StreetAddress='' _City='' _State='' _PostalCode=''/>
          <RESPONSE ResponseDateTime='04-08-2014 05:58:44' InternalAccountIdentifier='' _JobIdentifier='This is the 50 character alphanumeric job id field' _RecordIdentifier='' _CascadingAVMReferenceIdentifier='' _CascadingAVMReportType='Full' _CascadingAVMReturnType='SingleAVM' _CascadingReportTypeOtherDescription='' _CascadingReturnTypeOtherDescription='' _RESResponseIdentifier='1396961924147' _CustomerReferenceIdentifier1='' _CustomerReferenceIdentifier2='' _CustomerReferenceIdentifier3=''>
          <RESPONSE_DATA>
          <PROPERTY_INFORMATION_RESPONSE >

          <STATUS _Condition='SUCCESSFUL' _Code='0400' _Description='SUCCESSFULLY PROCESSED. NO RESPONSE-LEVEL ERRORS ENCOUNTERED'/>
          <_PRODUCT  _VoluntaryLienReport='Y'  _IncludePendingRecordsIndicator='Y'  _IncludeSearchCriteriaIndicator='Y' ><STATUS  _Condition='SUCCESSFUL'  _Code='0500'  _Description='REPORT RETURNED SUCCESSFULLY. NO ERRORS ENCOUNTERED.'  /><_SEARCH_CRITERIA><_SUBJECT_SEARCH  _StreetAddress='1160 SW 159 TER'  _StreetAddress2=''  _City='DAVIES'  _State='FL'  _PostalCode='33067'  _County='BRODWARE'  _CountyFIPSCode=''  _OwnerLastName=''  _OwnerFirstName=''  _AssessorsParcelIdentifier='514020062450'  _Municipality=''  _StrictMatch='N' ><_PARSED_STREET_ADDRESS  _StreetName=''  _StreetSuffix=''  _DirectionPrefix=''  _DirectionSuffix=''  _ApartmentOrUnit='' /></_SUBJECT_SEARCH><_RESPONSE_CRITERIA /></_SEARCH_CRITERIA></_PRODUCT><_PROPERTY_INFORMATION _ReportType='VoluntaryLienReport'><PROPERTY _StreetAddress='1160 SW 159TH TER'  _PostalCarrierRoute='C073'  _City='PEMBROKE PINES'  _State='FL'  _PostalCode='33027'  _PlusFourPostalCode='5011'  _County='BROWARD'  _AssessorsParcelIdentifier='51-40-20-06-2450' ><_PARSED_STREET_ADDRESS  _HouseNumber='1160'  _StandardizedHouseNumber='1160'  _ApartmentOrUnit=''  _StreetName='159TH'  _StreetSuffix='TER'  _DirectionPrefix='SW'  _DirectionSuffix='' /><PROPERTY_OWNER _OwnerName='BRUK YOSSEL / BRUK BORIS'  /><_PROPERTY_HISTORY><_COUNTY_RECORDING_HISTORY  _SalesHistoryStartDate='19600101'  _SalesHistoryThroughDate='20140401'  _AssignmentHistoryStartDate='20040102'  _AssignmentHistoryThroughDate='20140331'  _ReleaseHistoryStartDate='20041221'  _ReleaseHistoryThroughDate='20140331'  _ForeclosureHistoryStartDate='19741201'  _ForeclosureHistoryThroughDate='20140331'  _MortgageHistoryStartDate='19741201'  _MortgageHistoryThroughDate='20140331'  _StandAloneMortgageStartDate='19870801'  _StandAloneMortgageThroughDate='20140331' /><_VOLUNTARY_LIEN_HISTORY _OriginalDocNumberIdentifier='106698065'  _OriginalRecordingBookPageIdentifier='43326-1735'  _CurrentLenderName='CAPITAL ONE'  _CurrentLenderCode='044752'  _PreviousLenderName='MORTGAGE ELECTRONIC REGISTRATI'  _Borrower1Name='BRUK YOSSEL'  _Borrower2Name=''  _Borrower3Name=''  _Borrower4Name=''  _DocTypeDescription='ASSIGNMENT OF MORTGAGE'  _DocTypeDamarCode='AS'  _DocNumberIdentifier='000110070569'  _RecordingBookPageIdentifier='47949-277'  _RecordingDate='20110602'  _OriginalRecordingDate='20061226'  _VestingDescription='/  / SINGLE MAN'  _TransactionNumber='1'  _TransactionType='Assignment' /><_VOLUNTARY_LIEN_HISTORY _CaseIdentifier='07-32587-13'  _OriginalDocNumberIdentifier=''  _MortgageAmount=''  _LisPendensTypeDescription=''  _DefaultAmount=''  _DefaultDate=''  _OriginalRecordingBookPageIdentifier=''  _TrusteeSaleIdentifier=''  _TrusteeName=''  _TrusteePhoneNumber=''  _CurrentLenderName=''  _CurrentLenderCode=''  _TitleCompanyName='ATTORNEY ONLY'  _DocumentFilingDate='20090826'  _DocTypeDescription='FINAL JUDGEMENT'  _DocTypeDamarCode='FJ'  _DocNumberIdentifier='000108827493'  _RecordingBookPageIdentifier='46491-1783'  _RecordingDate='20090901'  _OriginalRecordingDate=''  _Plaintiff1Name='WELLS FARGO BANK NA'  _Plaintiff2Name=''  _Defendant1Name='BRUK BORIS'  _Defendant2Name='BRUK YOSSEL'  _Defendant3Name='GREENPOINT MTG FUNDING INC'  _Defendant4Name='PEMBROKE SHORES CMNTY ASSN INC'  _UnpaidBalanceAmount='622460.73'  _VestingDescription='EA /  /'  _TransactionNumber='2'  _TransactionType='Foreclosure' /><_VOLUNTARY_LIEN_HISTORY _OriginalDocNumberIdentifier=''  _OriginalRecordingBookPageIdentifier='40031-55'  _CurrentLenderName='WELLS FARGO BK NA'  _CurrentLenderCode='062858'  _PreviousLenderName='HOMEQ SVCG'  _Borrower1Name='BRUK YOSSEL'  _Borrower2Name=''  _Borrower3Name=''  _Borrower4Name=''  _DocTypeDescription='ASSIGNMENT OF MORTGAGE'  _DocTypeDamarCode='AS'  _DocNumberIdentifier='000108502227'  _RecordingBookPageIdentifier='46100-76'  _RecordingDate='20090402'  _OriginalRecordingDate='20050708'  _VestingDescription='/  / SINGLE MAN'  _TransactionNumber='3'  _TransactionType='Assignment' /><_VOLUNTARY_LIEN_HISTORY _CaseIdentifier='07-32587-13'  _OriginalDocNumberIdentifier=''  _MortgageAmount=''  _LisPendensTypeDescription=''  _DefaultAmount=''  _DefaultDate=''  _OriginalRecordingBookPageIdentifier=''  _TrusteeSaleIdentifier=''  _TrusteeName=''  _TrusteePhoneNumber=''  _CurrentLenderName=''  _CurrentLenderCode=''  _TitleCompanyName=''  _DocumentFilingDate=''  _DocTypeDescription='RELEASE OF LIS PENDENS/NOTICE'  _DocTypeDamarCode='RL'  _DocNumberIdentifier='000107760357'  _RecordingBookPageIdentifier='45182-1266'  _RecordingDate='20080313'  _OriginalRecordingDate=''  _Plaintiff1Name='WELLS FARGO BANK NA'  _Plaintiff2Name=''  _Defendant1Name='BRUK BORIS'  _Defendant2Name=''  _Defendant3Name=''  _Defendant4Name=''  _UnpaidBalanceAmount=''  _VestingDescription='EA /  /'  _TransactionNumber='4'  _TransactionType='Foreclosure' /><_VOLUNTARY_LIEN_HISTORY _CaseIdentifier='07-32587-13'  _OriginalDocNumberIdentifier=''  _MortgageAmount=''  _LisPendensTypeDescription='MTG'  _DefaultAmount=''  _DefaultDate=''  _OriginalRecordingBookPageIdentifier=''  _TrusteeSaleIdentifier=''  _TrusteeName=''  _TrusteePhoneNumber=''  _CurrentLenderName=''  _CurrentLenderCode=''  _TitleCompanyName='ATTORNEY ONLY'  _DocumentFilingDate='20071127'  _DocTypeDescription='LIS PENDENS'  _DocTypeDamarCode='LP'  _DocNumberIdentifier='000107544951'  _RecordingBookPageIdentifier='44872-534'  _RecordingDate='20071205'  _OriginalRecordingDate=''  _Plaintiff1Name='WELLS FARGO BANK NA'  _Plaintiff2Name=''  _Defendant1Name='BRUK BORIS'  _Defendant2Name='BRUK YOSSEL'  _Defendant3Name='GREENPOINT MTG FUNDING INC'  _Defendant4Name='PEMBROKE SHORES CMNTY ASSN INC'  _UnpaidBalanceAmount=''  _VestingDescription='EA /  /'  _TransactionNumber='5'  _TransactionType='Foreclosure' /><_TRANSACTION_HISTORY  _SaleDocumentNumberIdentifier='43781-501'  _SaleInstrumentNumber='000106930842'  _SaleBookPage='43781-501'  _SaleDeedTypeDescription='QUIT CLAIM DEED'  _SaleDeedTypeDamarCode='QC'  _SaleRecordingDate='20070321'  _SaleDate='20061220'  _OneSaleTypeDescription=''  _SaleStampAmount=''  _SalesPriceAmount=''  _SaleSellerName='BRUK YOSSEL'  _SaleBuyerName='BRUK BORIS'  _SaleBuyer1Name='BRUK BORIS'  _SaleBuyer2Name='BRUK YOSSEL'  _SaleBuyer3Name=''  _SaleBuyer4Name=''  _SaleTitleCompanyName=''  _SaleTransferDocumentNumberIdentifier=''  _SaleOwnerTransferIndicator='Y'  _CorporateBuyer=''  _FinanceCashDownAmount=''  _FinanceLoanAmount=''  _FinanceLoanType=''  _FinanceInterestRateTypeDescription=''  _FinanceMortgageTermNumber=''  _FinanceMortgageTermTypeDescription=''  _FinanceLenderName=''  _FinanceLenderCode=''  _FinanceDocumentNumberIdentifier=''  _FinanceBookPage=''  _FinanceTitleCompanyName=''  _FinanceTransferTypeDescription=''  _FinanceRecordingDate=''  _FinanceInterestRatePercent=''  _MortgageDate=''  _LienPosition=''  _FinanceTrusteeName=''  _FinanceOtherLoanType=''  _FinanceInstrumentNumber=''  _FinanceDocumentType=''  _FinanceDocumentTypeCode=''  _AdditionalFinanceDocumentType=''  _AdditionalFinanceDocumentTypeCode=''  _AdditionalFinanceCashDownAmount=''  _AdditionalFinanceLoanAmount=''  _AdditionalFinanceLoanType=''  _AdditionalFinanceInterestRateTypeDescription=''  _AdditionalFinanceMortgageTermNumber=''  _AdditionalFinanceMortgageTermTypeDescription=''  _AdditionalFinanceLenderName=''  _AdditionalFinanceLenderCode=''  _AdditionalFinanceDocumentNumberIdentifier=''  _AdditionalFinanceBookPage=''  _AdditionalFinanceTitleCompanyName=''  _AdditionalFinanceTransferTypeDescription=''  _AdditionalFinanceRecordingDate=''  _AdditionalFinanceInterestRatePercent=''  _AdditionalMortgageDate=''  _AdditionalLienPosition=''  _AdditionalFinanceTrusteeName=''  _AdditionalFinanceOtherLoanType=''  _AdditionalFinanceInstrumentNumber=''  _Borrower1Name=''  _Borrower2Name=''  _Borrower3Name=''  _Borrower4Name=''  _VestingDescription='/  /'  _LastSaleIndicator='N'  _TransactionNumber='6'  _TransactionType='Sale'  _FinanceStatus=''  _AdditionalFinanceStatus='' ></_TRANSACTION_HISTORY><_TRANSACTION_HISTORY  _SaleDocumentNumberIdentifier=''  _SaleInstrumentNumber=''  _SaleBookPage=''  _SaleDeedTypeDescription=''  _SaleDeedTypeDamarCode=''  _SaleRecordingDate=''  _SaleDate=''  _OneSaleTypeDescription=''  _SaleStampAmount=''  _SalesPriceAmount=''  _SaleSellerName=''  _SaleBuyerName=''  _SaleBuyer1Name=''  _SaleBuyer2Name=''  _SaleBuyer3Name=''  _SaleBuyer4Name=''  _SaleTitleCompanyName='COASTAL TITLE'  _SaleTransferDocumentNumberIdentifier=''  _SaleOwnerTransferIndicator=''  _CorporateBuyer=''  _FinanceCashDownAmount=''  _FinanceLoanAmount='85000'  _FinanceLoanType='CONVENTIONAL'  _FinanceInterestRateTypeDescription='ADJUSTABLE INT RATE LOAN'  _FinanceMortgageTermNumber='15'  _FinanceMortgageTermTypeDescription='YEARS'  _FinanceLenderName='GREENPOINT MTG FNDG'  _FinanceLenderCode='026393'  _FinanceDocumentNumberIdentifier='43326-1735'  _FinanceBookPage='43326-1735'  _FinanceTitleCompanyName='COASTAL TITLE'  _FinanceTransferTypeDescription='REFI'  _FinanceRecordingDate='20061226'  _FinanceInterestRatePercent=''  _MortgageDate='20061122'  _LienPosition='2'  _FinanceTrusteeName=''  _FinanceOtherLoanType='EQUITY LOAN'  _FinanceInstrumentNumber='000106698065'  _FinanceDocumentType='MORTGAGE'  _FinanceDocumentTypeCode='MG'  _AdditionalFinanceDocumentType=''  _AdditionalFinanceDocumentTypeCode=''  _AdditionalFinanceCashDownAmount=''  _AdditionalFinanceLoanAmount=''  _AdditionalFinanceLoanType=''  _AdditionalFinanceInterestRateTypeDescription=''  _AdditionalFinanceMortgageTermNumber=''  _AdditionalFinanceMortgageTermTypeDescription=''  _AdditionalFinanceLenderName=''  _AdditionalFinanceLenderCode=''  _AdditionalFinanceDocumentNumberIdentifier=''  _AdditionalFinanceBookPage=''  _AdditionalFinanceTitleCompanyName=''  _AdditionalFinanceTransferTypeDescription=''  _AdditionalFinanceRecordingDate=''  _AdditionalFinanceInterestRatePercent=''  _AdditionalMortgageDate=''  _AdditionalLienPosition=''  _AdditionalFinanceTrusteeName=''  _AdditionalFinanceOtherLoanType=''  _AdditionalFinanceInstrumentNumber=''  _Borrower1Name='BRUK YOSSEL'  _Borrower2Name=''  _Borrower3Name=''  _Borrower4Name=''  _VestingDescription='/  / SINGLE MAN'  _LastSaleIndicator='N'  _TransactionNumber='7'  _TransactionType='Finance'  _FinanceStatus=''  _AdditionalFinanceStatus='' ></_TRANSACTION_HISTORY><_VOLUNTARY_LIEN_HISTORY _OriginalDocNumberIdentifier='103914146'  _OriginalRecordingBookPageIdentifier='37286-687'  _DocTypeDescription='DEED OF RELEASE'  _DocTypeDamarCode='DR'  _DocNumberIdentifier='000105174691'  _RecordingBookPageIdentifier='40053-1858'  _RecordingDate='20050713'  _OriginalRecordingDate='20040421'  _TransactionNumber='8'  _TransactionType='Release' /><_TRANSACTION_HISTORY  _SaleDocumentNumberIdentifier='40031-54'  _SaleInstrumentNumber='000105165210'  _SaleBookPage='40031-54'  _SaleDeedTypeDescription='WARRANTY DEED'  _SaleDeedTypeDamarCode='WD'  _SaleRecordingDate='20050708'  _SaleDate='20050616'  _OneSaleTypeDescription=''  _SaleStampAmount='3850.0'  _SalesPriceAmount='550000.0'  _SaleSellerName='STAMBLER MAYER T'  _SaleBuyerName='BRUK YOSSEL'  _SaleBuyer1Name='BRUK YOSSEL'  _SaleBuyer2Name=''  _SaleBuyer3Name=''  _SaleBuyer4Name=''  _SaleTitleCompanyName='ATTORNEY ONLY'  _SaleTransferDocumentNumberIdentifier=''  _SaleOwnerTransferIndicator=''  _CorporateBuyer=''  _FinanceCashDownAmount=''  _FinanceLoanAmount='495000'  _FinanceLoanType='CONVENTIONAL'  _FinanceInterestRateTypeDescription='ADJUSTABLE INT RATE LOAN'  _FinanceMortgageTermNumber='30'  _FinanceMortgageTermTypeDescription='YEARS'  _FinanceLenderName='ARGENT MTG CO LLC'  _FinanceLenderCode='053244'  _FinanceDocumentNumberIdentifier='40031-55'  _FinanceBookPage='40031-55'  _FinanceTitleCompanyName=''  _FinanceTransferTypeDescription='RESALE'  _FinanceRecordingDate='20050708'  _FinanceInterestRatePercent='7.750'  _MortgageDate='20050622'  _LienPosition='1'  _FinanceTrusteeName=''  _FinanceOtherLoanType=''  _FinanceInstrumentNumber='000105165211'  _FinanceDocumentType='MORTGAGE'  _FinanceDocumentTypeCode='MG'  _AdditionalFinanceDocumentType=''  _AdditionalFinanceDocumentTypeCode=''  _AdditionalFinanceCashDownAmount=''  _AdditionalFinanceLoanAmount=''  _AdditionalFinanceLoanType=''  _AdditionalFinanceInterestRateTypeDescription=''  _AdditionalFinanceMortgageTermNumber=''  _AdditionalFinanceMortgageTermTypeDescription=''  _AdditionalFinanceLenderName=''  _AdditionalFinanceLenderCode=''  _AdditionalFinanceDocumentNumberIdentifier=''  _AdditionalFinanceBookPage=''  _AdditionalFinanceTitleCompanyName=''  _AdditionalFinanceTransferTypeDescription=''  _AdditionalFinanceRecordingDate=''  _AdditionalFinanceInterestRatePercent=''  _AdditionalMortgageDate=''  _AdditionalLienPosition=''  _AdditionalFinanceTrusteeName=''  _AdditionalFinanceOtherLoanType=''  _AdditionalFinanceInstrumentNumber=''  _Borrower1Name='BRUK YOSSEL'  _Borrower2Name=''  _Borrower3Name=''  _Borrower4Name=''  _VestingDescription='/  / SINGLE MAN'  _LastSaleIndicator='Y'  _TransactionNumber='9'  _TransactionType='Sale'  _FinanceStatus=''  _AdditionalFinanceStatus='' ></_TRANSACTION_HISTORY><_TRANSACTION_HISTORY  _SaleDocumentNumberIdentifier='37286-686'  _SaleInstrumentNumber='000103914145'  _SaleBookPage='37286-686'  _SaleDeedTypeDescription='WARRANTY DEED'  _SaleDeedTypeDamarCode='WD'  _SaleRecordingDate='20040421'  _SaleDate='20040319'  _OneSaleTypeDescription=''  _SaleStampAmount='2800.0'  _SalesPriceAmount='400000.0'  _SaleSellerName='ESPARROGOZA RAMIRO &amp; MARIA'  _SaleBuyerName='STAMBLER MAYER T'  _SaleBuyer1Name='STAMBLER MAYER T'  _SaleBuyer2Name=''  _SaleBuyer3Name=''  _SaleBuyer4Name=''  _SaleTitleCompanyName='ATTORNEY ONLY'  _SaleTransferDocumentNumberIdentifier=''  _SaleOwnerTransferIndicator=''  _CorporateBuyer=''  _FinanceCashDownAmount=''  _FinanceLoanAmount='360000'  _FinanceLoanType='CONVENTIONAL'  _FinanceInterestRateTypeDescription='ADJUSTABLE INT RATE LOAN'  _FinanceMortgageTermNumber='30'  _FinanceMortgageTermTypeDescription='YEARS'  _FinanceLenderName='AMERICAS WHOLESALE LENDER'  _FinanceLenderCode='002999'  _FinanceDocumentNumberIdentifier='37286-687'  _FinanceBookPage='37286-687'  _FinanceTitleCompanyName=''  _FinanceTransferTypeDescription='RESALE'  _FinanceRecordingDate='20040421'  _FinanceInterestRatePercent='5.500'  _MortgageDate='20040319'  _LienPosition=''  _FinanceTrusteeName=''  _FinanceOtherLoanType=''  _FinanceInstrumentNumber='000103914146'  _FinanceDocumentType='MORTGAGE'  _FinanceDocumentTypeCode='MG'  _AdditionalFinanceDocumentType=''  _AdditionalFinanceDocumentTypeCode=''  _AdditionalFinanceCashDownAmount=''  _AdditionalFinanceLoanAmount=''  _AdditionalFinanceLoanType=''  _AdditionalFinanceInterestRateTypeDescription=''  _AdditionalFinanceMortgageTermNumber=''  _AdditionalFinanceMortgageTermTypeDescription=''  _AdditionalFinanceLenderName=''  _AdditionalFinanceLenderCode=''  _AdditionalFinanceDocumentNumberIdentifier=''  _AdditionalFinanceBookPage=''  _AdditionalFinanceTitleCompanyName=''  _AdditionalFinanceTransferTypeDescription=''  _AdditionalFinanceRecordingDate=''  _AdditionalFinanceInterestRatePercent=''  _AdditionalMortgageDate=''  _AdditionalLienPosition=''  _AdditionalFinanceTrusteeName=''  _AdditionalFinanceOtherLoanType=''  _AdditionalFinanceInstrumentNumber=''  _Borrower1Name='STAMBLER MAYER T'  _Borrower2Name=''  _Borrower3Name=''  _Borrower4Name=''  _VestingDescription='/  / SINGLE MAN'  _LastSaleIndicator='N'  _TransactionNumber='10'  _TransactionType='Sale'  _FinanceStatus=''  _AdditionalFinanceStatus='' ></_TRANSACTION_HISTORY><_TRANSACTION_HISTORY  _SaleDocumentNumberIdentifier='35089-1390'  _SaleInstrumentNumber='000102888897'  _SaleBookPage='35089-1390'  _SaleDeedTypeDescription='WARRANTY DEED'  _SaleDeedTypeDamarCode='WD'  _SaleRecordingDate='20030505'  _SaleDate='20030418'  _OneSaleTypeDescription=''  _SaleStampAmount='2100.0'  _SalesPriceAmount='300000.0'  _SaleSellerName='BRUK BORIS &amp; LERIZ'  _SaleBuyerName='ESPARROGOZA RAMIRO &amp; MARIA'  _SaleBuyer1Name='ESPARROGOZA RAMIRO'  _SaleBuyer2Name='ESPARROGOZA MARIA'  _SaleBuyer3Name=''  _SaleBuyer4Name=''  _SaleTitleCompanyName='ATTORNEY ONLY'  _SaleTransferDocumentNumberIdentifier=''  _SaleOwnerTransferIndicator=''  _CorporateBuyer=''  _FinanceCashDownAmount=''  _FinanceLoanAmount='270000'  _FinanceLoanType='CONVENTIONAL'  _FinanceInterestRateTypeDescription='FIXED RATE LOAN'  _FinanceMortgageTermNumber='30'  _FinanceMortgageTermTypeDescription='YEARS'  _FinanceLenderName='MARKET STREET MTG CORP'  _FinanceLenderCode='001517'  _FinanceDocumentNumberIdentifier='35089-1391'  _FinanceBookPage='35089-1391'  _FinanceTitleCompanyName=''  _FinanceTransferTypeDescription='RESALE'  _FinanceRecordingDate='20030505'  _FinanceInterestRatePercent=''  _MortgageDate='20030418'  _LienPosition=''  _FinanceTrusteeName=''  _FinanceOtherLoanType=''  _FinanceInstrumentNumber='000102888898'  _FinanceDocumentType='MORTGAGE'  _FinanceDocumentTypeCode='MG'  _AdditionalFinanceDocumentType=''  _AdditionalFinanceDocumentTypeCode=''  _AdditionalFinanceCashDownAmount=''  _AdditionalFinanceLoanAmount=''  _AdditionalFinanceLoanType=''  _AdditionalFinanceInterestRateTypeDescription=''  _AdditionalFinanceMortgageTermNumber=''  _AdditionalFinanceMortgageTermTypeDescription=''  _AdditionalFinanceLenderName=''  _AdditionalFinanceLenderCode=''  _AdditionalFinanceDocumentNumberIdentifier=''  _AdditionalFinanceBookPage=''  _AdditionalFinanceTitleCompanyName=''  _AdditionalFinanceTransferTypeDescription=''  _AdditionalFinanceRecordingDate=''  _AdditionalFinanceInterestRatePercent=''  _AdditionalMortgageDate=''  _AdditionalLienPosition=''  _AdditionalFinanceTrusteeName=''  _AdditionalFinanceOtherLoanType=''  _AdditionalFinanceInstrumentNumber=''  _Borrower1Name='ESPARROGOZA RAMIRO'  _Borrower2Name='ESPARROGOZA MARIA'  _Borrower3Name=''  _Borrower4Name=''  _VestingDescription='/  / HUSBAND/WIFE'  _LastSaleIndicator='N'  _TransactionNumber='11'  _TransactionType='Sale'  _FinanceStatus=''  _AdditionalFinanceStatus='' ></_TRANSACTION_HISTORY><_TRANSACTION_HISTORY  _SaleDocumentNumberIdentifier='31019-1589'  _SaleInstrumentNumber=''  _SaleBookPage='31019-1589'  _SaleDeedTypeDescription='WARRANTY DEED'  _SaleDeedTypeDamarCode='WD'  _SaleRecordingDate='20001114'  _SaleDate='20001109'  _OneSaleTypeDescription='UNKNOWN'  _SaleStampAmount=''  _SalesPriceAmount='260100.0'  _SaleSellerName='PASADENA HOMES INC'  _SaleBuyerName='BRUK BORIS &amp; LERIZ M'  _SaleBuyer1Name='BRUK BORIS'  _SaleBuyer2Name='BRUK LERIZ M'  _SaleBuyer3Name=''  _SaleBuyer4Name=''  _SaleTitleCompanyName='ENTERPRISE TITLE'  _SaleTransferDocumentNumberIdentifier=''  _SaleOwnerTransferIndicator=''  _CorporateBuyer=''  _FinanceCashDownAmount=''  _FinanceLoanAmount='23400'  _FinanceLoanType='CONVENTIONAL'  _FinanceInterestRateTypeDescription='FIXED RATE LOAN'  _FinanceMortgageTermNumber='30'  _FinanceMortgageTermTypeDescription='YEARS'  _FinanceLenderName='MARKET STREET MTG CORP'  _FinanceLenderCode='001517'  _FinanceDocumentNumberIdentifier='31019-1590'  _FinanceBookPage='31019-1590'  _FinanceTitleCompanyName=''  _FinanceTransferTypeDescription='1ST TIME SALE'  _FinanceRecordingDate='20001114'  _FinanceInterestRatePercent=''  _MortgageDate='20001110'  _LienPosition=''  _FinanceTrusteeName=''  _FinanceOtherLoanType=''  _FinanceInstrumentNumber=''  _FinanceDocumentType='MORTGAGE'  _FinanceDocumentTypeCode='MG'  _AdditionalFinanceDocumentType=''  _AdditionalFinanceDocumentTypeCode=''  _AdditionalFinanceCashDownAmount=''  _AdditionalFinanceLoanAmount=''  _AdditionalFinanceLoanType=''  _AdditionalFinanceInterestRateTypeDescription=''  _AdditionalFinanceMortgageTermNumber=''  _AdditionalFinanceMortgageTermTypeDescription=''  _AdditionalFinanceLenderName=''  _AdditionalFinanceLenderCode=''  _AdditionalFinanceDocumentNumberIdentifier=''  _AdditionalFinanceBookPage=''  _AdditionalFinanceTitleCompanyName=''  _AdditionalFinanceTransferTypeDescription=''  _AdditionalFinanceRecordingDate=''  _AdditionalFinanceInterestRatePercent=''  _AdditionalMortgageDate=''  _AdditionalLienPosition=''  _AdditionalFinanceTrusteeName=''  _AdditionalFinanceOtherLoanType=''  _AdditionalFinanceInstrumentNumber=''  _Borrower1Name='BRUK BORIS'  _Borrower2Name='BRUK LERIZ M'  _Borrower3Name=''  _Borrower4Name=''  _VestingDescription='/  / HUSBAND/WIFE'  _LastSaleIndicator='N'  _TransactionNumber='12'  _TransactionType='Sale'  _FinanceStatus=''  _AdditionalFinanceStatus='' ></_TRANSACTION_HISTORY></_PROPERTY_HISTORY></PROPERTY></_PROPERTY_INFORMATION>
          </PROPERTY_INFORMATION_RESPONSE></RESPONSE_DATA></RESPONSE></RESPONSE_GROUP>"; */
        /**/
        $Result = $corelogic_obj->xmlresponsetoarrayjson_b($Response); //print_r($Result);
        $Result = transforma($Result);
        if ($Result) {
            $Result = json_encode($Result, true);
            echo $Result;
            exit();
        } else {
            $Result = $corelogic_obj->xmlresponsetoarrayjson($Response);
        }
        //print_r($Result);
        if ($Result["STATUS"] != '') {
            $arraystatus = json_decode($Result['STATUS'], true);
            if ($arraystatus["_Condition"] == "SUCCESSFUL") {
                $property_obj = $GetClass->GetClass('property');
                $PropertyInfo = json_decode($Result["PROPERTY"], true);
                $property_obj->set_StreetAddress($PropertyInfo['_StreetAddress']);
                foreach ($PropertyInfo as $Camp => $val) {
                    if ($Camp != '_StreetAddress') {
                        $function = 'update' . $Camp;
                        $property_obj->$function($property_obj->getidproperty(), $val);
                    }
                }
                $idproperty = $property_obj->getidproperty();
                /**/
                $ObjectClass = $GetClass->GetClass('_subject_search');
                $ObjectClass->setidproperty($idproperty);
                $searchid = $ObjectClass->getid_subject_search();
                /**/
                $function2 = 'setid_subject_search';
                $function3 = 'setid_search_criteria';
                foreach ($Result as $KeyClass => $DataForInsert) {
                    $ObjectClass = $GetClass->GetClass($KeyClass);
                    if ($ObjectClass && strtolower($KeyClass) != '_parsed_street_address' && strtolower($KeyClass) != '_basement') {
                        $AllFuntionsClass = get_class_methods(strtolower($KeyClass));
                        $DataInsert = json_decode($DataForInsert, true);
                        $function = 'setidproperty';
                        $functionGet = 'getid' . strtolower($KeyClass);
                        if (in_array($function, $AllFuntionsClass)) {
                            if ($KeyClass == '_SUBJECT_SEARCH') {
                                $idObject = $searchid;
                            } else {
                                $ObjectClass->$function($idproperty);
                                $idObject = $ObjectClass->$functionGet();
                            }
                        }
                        if ($searchid) {
                            if (in_array($function2, $AllFuntionsClass)) {
                                if ($function2) {
                                    $ObjectClass->$function2($searchid);
                                    $idObject = $ObjectClass->$functionGet();
                                    $function2 = '';
                                }
                            }
                            if (in_array($function3, $AllFuntionsClass)) {
                                if ($function3) {
                                    $ObjectClass->$function3($searchid);
                                    $idObject = $ObjectClass->$functionGet();
                                    $function3 = '';
                                }
                            }
                        }
                        foreach ($DataInsert as $fn => $value) {
                            $function = 'update' . $fn;
                            if (in_array($function, $AllFuntionsClass)) {
                                if ($idObject) {
                                    $ObjectClass->$function($idObject, $value);
                                }
                            }
                        }
                    }
                }
                /* PDF */

                class PDFReport extends FPDF {

                    function Header() {
                        $this->SetTextColor(0);
                        $this->Image('Server/developer/images/logo.png', 155, 6, 50);
                        $this->SetFont('Arial', 'B', 15);
                        $this->Cell(80, 10, 'Property Report Result Date : ' . date("Y-m-d"), 0, 0, 'L');
                        $this->Ln(15);
                    }

                    function VoluntaryLienReport($data) {
                        $this->SetDrawColor(128, 132, 151);
                        $this->SetLineWidth(.3);
                        $this->SetFillColor(131, 173, 224);
                        $this->SetTextColor(0);
                        $this->Cell(50, 5, 'For Property Located At :', '', 0, 'L');
                        $this->Ln();
                        $property = $data['PROPERTY']['_PROPERTY_INFORMATION'];
                        $address = $property['_StreetAddress'] . " " . $property['_City'] . " " . $property['_County'] . " " . $property['_State'] . "," . $property['_PostalCode'] . " " . $property['_PlusFourPostalCode'];
                        $this->SetFont('helvetica', 'B', 12);
                        $this->Cell(50, 10, $address, '', 0, 'L');
                        $this->Ln();
                        $this->SetFont('helvetica', 'I', 10);
                        $this->Cell(20, 5, 'APN : ', '', 0, 'L');
                        $this->Cell(20, 5, '? ', '', 0, 'L');
                        $this->Ln();
                        $this->Cell(20, 5, 'BLOCK : ', '', 0, 'L');
                        $this->Cell(20, 5, '? ', '', 0, 'L');
                        $this->Cell(20, 5, 'LOT : ', '', 0, 'L');
                        $this->Cell(20, 5, '? ', '', 0, 'L');
                        $this->Ln(10);
                    }

                    function history($dato) {
                        $this->SetDrawColor(128, 132, 151);
                        $this->SetFont('helvetica', 'B', 15);
                        $this->SetLineWidth(.3);
                        $this->SetFillColor(131, 173, 224);
                        $this->SetTextColor(0);
                        $unik = true;
                        $this->SetFont('helvetica', 'B', 15);
                        foreach ($dato as $key => $value) {
                            $sub_dato = json_decode($value);
                            if ($unik) {
                                $y = $this->GetY();
                                $pru = 269 - $y;
                                if (30 > $pru) {
                                    $this->AddPage('P', 'Letter');
                                }
                                $this->Cell(194, 10, 'Recording Information for BROWARD, FL', 0, 0, 'C', false);
                                $this->Ln();
                                $this->SetFont('helvetica', '', 9);
                                $unik = false;
                                $this->Cell(44, 10, '', 1, 0, 'C', true);
                                $this->Cell(30, 10, 'Sales', 1, 0, 'C', true);
                                $this->Cell(30, 10, 'Mortgages', 1, 0, 'C', true);
                                $this->Cell(30, 10, 'Assignments', 1, 0, 'C', true);
                                $this->Cell(30, 10, 'Releases', 1, 0, 'C', true);
                                $this->Cell(30, 10, 'Foreclosures', 1, 0, 'C', true);
                                $this->Ln();
                            }
                            foreach ($sub_dato as $keyb => $valueb) {
                                if ($keyb == '_SalesHistoryStartDate') {
                                    $sales_h = $valueb;
                                }
                                if ($keyb == '_SalesHistoryThroughDate') {
                                    $sales_t = $valueb;
                                }
                                if ($keyb == '_AssignmentHistoryStartDate') {
                                    $assig_h = $valueb;
                                }
                                if ($keyb == '_AssignmentHistoryThroughDate') {
                                    $assig_t = $valueb;
                                }
                                if ($keyb == '_ReleaseHistoryStartDate') {
                                    $rele_h = $valueb;
                                }
                                if ($keyb == '_ReleaseHistoryThroughDate') {
                                    $rele_t = $valueb;
                                }
                                if ($keyb == '_ForeclosureHistoryStartDate') {
                                    $fore_h = $valueb;
                                }
                                if ($keyb == '_ForeclosureHistoryThroughDate') {
                                    $fore_t = $valueb;
                                }
                                if ($keyb == '_MortgageHistoryStartDate') {
                                    $mort_h = $valueb;
                                }
                                if ($keyb == '_MortgageHistoryThroughDate') {
                                    $mort_t = $valueb;
                                }
                            }
                        }
                        $this->SetFillColor(224, 235, 255);
                        $this->Cell(44, 10, 'History Start Date', 1, 0, 'C', false);
                        $this->Cell(30, 10, date_format(date_create($sales_h), 'd-m-Y'), 1, 0, 'C', false);
                        $this->Cell(30, 10, date_format(date_create($mort_h), 'd-m-Y'), 1, 0, 'C', false);
                        $this->Cell(30, 10, date_format(date_create($assig_h), 'd-m-Y'), 1, 0, 'C', false);
                        $this->Cell(30, 10, date_format(date_create($rele_h), 'd-m-Y'), 1, 0, 'C', false);
                        $this->Cell(30, 10, date_format(date_create($fore_h), 'd-m-Y'), 1, 0, 'C', false);
                        $this->Ln();
                        $this->Cell(44, 10, 'Rec. thru. Date', 1, 0, 'C', true);
                        $this->Cell(30, 10, date_format(date_create($sales_t), 'd-m-Y'), 1, 0, 'C', true);
                        $this->Cell(30, 10, date_format(date_create($mort_t), 'd-m-Y'), 1, 0, 'C', true);
                        $this->Cell(30, 10, date_format(date_create($assig_t), 'd-m-Y'), 1, 0, 'C', true);
                        $this->Cell(30, 10, date_format(date_create($rele_t), 'd-m-Y'), 1, 0, 'C', true);
                        $this->Cell(30, 10, date_format(date_create($fore_t), 'd-m-Y'), 1, 0, 'C', true);
                        $this->Ln();
                    }

                    function detail($dato) {
                        $this->SetDrawColor(128, 132, 151);
                        $this->SetFont('helvetica', 'B', 15);
                        $this->SetLineWidth(.3);
                        $this->SetFillColor(131, 173, 224);
                        $this->SetTextColor(0);
                        $salto = false;
                        $newpag = 0;
                        $this->Cell(194, 10, 'Transaction Details', 0, 0, 'C', false);
                        $this->Ln();
                        $this->SetFont('helvetica', 'B', 9);
                        $histor = $dato['HISTORY']['_TRANSACTION_HISTORY'];
                        $tam = count($histor);
                        $count = 1;
                        $key = 0;
                        foreach ($histor as $key => $value) {
                            $valueb = $value;
                            $newpag = 0;
                            foreach ($valueb as $key2 => $value2) {
                                if ($key2 == '_TransactionType') {
                                    $tipo = $value2;
                                    $newpag++;
                                }
                                if ($value2 != '') {
                                    $newpag++;
                                }
                            }
                            $tot = (($newpag / 2) * 7) + 28;
                            $y = $this->GetY();
                            $pru = 269 - $y;
                            if ($tot > $pru) {
                                //$this->Cell(30,10,$tot.'-'.$pru.'-'.$newpag,'LT',0,'L',true);
                                $this->AddPage('P', 'Letter');
                                $newpag = 0;
                            }
                            $this->SetFillColor(131, 173, 224);
                            $this->Ln();
                            $this->SetFont('helvetica', 'B', 9);
                            $this->Cell(30, 10, 'History Record #', 'LT', 0, 'L', true);
                            $this->Cell(164, 10, $key + 1, 'TR', 0, 'L', true);
                            $this->Ln();
                            $this->SetFillColor(224, 235, 255);
                            $this->Cell(194, 8, $tipo, 1, 0, 'L', true);
                            $this->Ln();
                            $this->SetFont('helvetica', '', 7);
                            $this->SetFillColor(224, 235, 255);
                            $tit = '';
                            foreach ($value as $keyc => $valuec) {
                                $tit = '';
                                if ($valuec != '') {
                                    $keyc = str_replace('_', '', $keyc);
                                    for ($i = 0; $i <= strlen($keyc) - 1; $i++) {
                                        if (ctype_upper($keyc[$i])) {
                                            if ($i != 0) {
                                                $tit = $tit . ' ' . $keyc[$i];
                                            } else {
                                                $tit = $tit . $keyc[$i];
                                            }
                                        } else {
                                            $tit = $tit . $keyc[$i];
                                        }
                                    }
                                    $this->SetFont('helvetica', '', 6);
                                    $this->Cell(45, 7, $tit, 1, 0, 'L', true);
                                    $tit = '';
                                    $this->SetFont('helvetica', 'B', 7);
                                    $this->Cell(52, 7, $valuec, 1, 0, 'L', false);
                                    if ($salto) {
                                        $this->Ln();
                                        $salto = !$salto;
                                    } else {
                                        $salto = !$salto;
                                    }
                                }
                            }if ($salto) {
                                $this->Cell(45, 7, '', 1, 0, 'L', true);
                                $this->Cell(52, 7, '', 1, 0, 'L', false);
                                $this->Ln();
                                $salto = !$salto;
                            }
                        }$this->Ln(10);
                    }

                    function sumary($dato) {
                        $this->SetDrawColor(128, 132, 151);
                        $this->SetFont('helvetica', 'B', 15);
                        $this->SetLineWidth(.3);
                        $this->SetFillColor(131, 173, 224);
                        $this->SetTextColor(0);
                        $buyborrw = "";
                        $this->Cell(194, 10, 'Summary of Transactions', 0, 0, 'C', false);
                        $this->Ln(20);
                        $this->SetDrawColor(128, 132, 151);
                        $this->SetLineWidth(.3);
                        $this->SetFillColor(224, 235, 255);
                        $fill = true;
                        $dato_b = $dato['HISTORY']['_TRANSACTION_HISTORY'];
                        foreach ($dato_b as $key => $value) {
                            $valor2 = $value;
                            foreach ($valor2 as $key2 => $value2) {
                                if ($key2 == "_MortgageDate") {
                                    $fecha = $value2;
                                }
                                if ($key2 == "_FinanceDocumentType" || $key2 == '_DocTypeDescription') {
                                    $doctype = $value2;
                                }
                                if ($key2 == "_FinanceDocumentNumberIdentifier" || $key2 == "_RecordingBookPageIdentifier") {
                                    $docnumb = $value2;
                                }
                                if ($key2 == "_BookPage") {
                                    $docnumb2 = $value2;
                                }

                                if ($key2 == "_BorrowerName" || $key2 == "_Borrower1Name") {
                                    $borr = $value2;
                                }
                                if ($key2 == "_Borrower1Name") {
                                    $borr1 = $value2;
                                }
                                if ($key2 == "_Borrower2Name") {
                                    $borr2 = $value2;
                                }
                                if ($key2 == "_SaleSellerName") {
                                    $seller = $value2;
                                }
                                if ($key2 == "_SaleSeller1Name") {
                                    $seller1 = $value2;
                                }
                                if ($key2 == "_SaleSeller2Name") {
                                    $seller2 = $value2;
                                }
                                if ($key2 == "_SaleBuyerName") {
                                    $buyer = $value2;
                                }
                                if ($key2 == "_SaleBuyer1Name") {
                                    $buyer1 = $value2;
                                }
                                if ($key2 == "_SaleBuyer2Name") {
                                    $buyer2 = $value2;
                                }
                                if ($key2 == "_FinanceLenderName" || $key2 == "_CurrentLenderName") {
                                    $lender = $value2;
                                }
                            }
                            //$num=(int)$key;
                            if (is_numeric($key)) {
                                $this->SetFillColor(131, 173, 224);
                                $this->SetFont('helvetica', '', 9);
                                $this->Cell(9, 5, 'Rec', 1, '', 'C', true);
                                $this->Cell(20, 5, 'Date', 1, 0, 'C', true);
                                $this->Cell(50, 5, 'Doc Type', 1, 0, 'J', true);
                                $this->Cell(15, 5, 'Book Page #', 1, 0, 'J', true);
                                $this->Cell(100, 5, 'Buyer / Borrower ', 1, 0, 'J', true);
                                $this->Ln();

                                $this->SetFillColor(224, 235, 255);
                                $this->SetFont('helvetica', '', 7);
                                if ($fill) {
                                    $xa = $this->GetX();
                                    $ya = $this->GetY();
                                    $this->Rect($xa, $ya, 194, 10, 'DF');
                                } else {
                                    $xa = $this->GetX();
                                    $ya = $this->GetY();
                                    $this->Rect($xa, $ya, 194, 10, 'D');
                                }
                                $this->Cell(9, 10, $key + 1, 1, '', 'C', false);
                                $this->Cell(20, 10, date_format(date_create($fecha), 'd-m-Y') . '', 1, 'J', 'C', false);
                                $this->Cell(50, 10, $doctype . ' ', 1, 'L', false);
                                $this->Cell(15, 10, $docnumb . ' ', 1, 'L', false);
                                if ($buyer != '') {
                                    $buyborrw = $buyborrw . $buyer . "/";
                                }
                                if ($borr1 != '') {
                                    if ($buyer == $borr1) {
                                        $buyborrw = $borr1;
                                    } else {
                                        $buyborrw = $buyborrw . $borr1;
                                    }
                                }
                                $this->MultiCell(100, 5, trim($buyborrw) . ' ', 'TR', 'L', false);
                                if (strlen($buyborrw) < 65) {
                                    $this->Ln();
                                }
                                $this->SetFillColor(131, 173, 224);
                                $this->SetFont('helvetica', '', 9);
                                $this->Cell(94, 5, 'Seller ', 1, 0, 'J', true);
                                $this->Cell(85, 5, 'Lender ', 1, 0, 'J', true);
                                $this->Cell(15, 5, 'Orig Doc', 1, 0, 'L', true);
                                $this->Ln();
                                $this->SetFillColor(224, 235, 255);
                                $this->SetFont('helvetica', '', 7);

                                if ($fill) {
                                    $xa = $this->GetX();
                                    $ya = $this->GetY();
                                    $this->Rect($xa, $ya, 194, 10, 'DF');
                                }
                                $this->Cell(94, 10, $seller . ' ', 1, 'L', false);
                                $this->Cell(85, 10, $lender . ' ', 1, 'L', false);
                                $this->Cell(15, 10, $origdoc . ' ', 1, 'L', false);
                                $this->Ln();
                            }$fill = !$fill;
                        }
                        ///fin de sumary
                        $this->Cell(194, .3, '', 'T', 'J', false);
                        $this->Ln(10);
                    }

                    function DetailedSubjectReport($data) {
                        $this->SetDrawColor(128, 132, 151);
                        $this->SetLineWidth(.3);
                        $this->SetFont('helvetica', '', 15);
                        $w = array(49, 147, 40, 45);
                        $w2 = array(49, 49, 49, 49);
                        //$this->Cell(50);
                        $op = false;
                        $op2 = 0;
                        $address = '';
                        $tax = '';
                        $taxid = '';
                        $fill = false;
                        $cm = 55;
                        $cmneed = 0;
                        foreach ($data as $a => $value2) {
                            if ($a != 'STATUS' && $a != '_PRODUCT' && $a != '_SUBJECT_SEARCH' && $a != '_PARSED_STREET_ADDRESS' && $a != '_RESPONSE_CRITERIA' && $a != '_PROPERTY_INFORMATION') {
                                $address2 = '';
                                $valor2 = json_decode($value2);
                                $b = str_replace('_', " ", $a);
                                $this->SetFillColor(131, 173, 224);
                                $this->SetTextColor(255);
                                $this->SetFont('helvetica', 'B', 10);
                                if ($op) {
                                    $this->SetFillColor(131, 173, 224);
                                    $this->SetTextColor(0);
                                    $this->Cell($w[1], 6, 'For Property Located At :', '', 0, 'L');
                                    $this->Ln();
                                    $this->SetFont('helvetica', 'B', 12);
                                    $this->Cell($w[1], 6, $address, '', 0, 'L');
                                    $this->Ln();
                                    $this->Cell($w[1], 6, $taxid, '', 0, 'L');
                                    $this->SetFont('helvetica', 'B', 10);
                                    $this->Ln(10);
                                    $op = !$op;
                                    $this->SetFillColor(131, 173, 224);
                                    $this->SetTextColor(255);
                                }
                                $valor3 = $valor2;
                                $vacio = 0;
                                foreach ($valor3 as $key3 => $value3) {
                                    if (trim($value3) != "") {
                                        $vacio++;
                                    }
                                    $cmneed = ($vacio * 6) + 10;
                                }
                                $dif = $cm + $cmneed;

                                if ($op2 == 2) {
                                    $this->SetFillColor(224, 235, 255);
                                    $this->Cell($w2[$op2], 6, '', 'LRTB', 0, 'L', true);
                                    $op2++;
                                    $this->SetFillColor(131, 173, 224);
                                    $this->Cell($w2[$op2], 6, '', 'LRTB', 0, 'L', false);
                                    $op2++;
                                    $fill = !$fill;
                                    $this->Ln();
                                    $op2 = 0;
                                }
                                if ($dif > 270) {
                                    $this->AddPage('P', 'Letter');
                                    $cm = 55;
                                }
                                if ($a != 'PROPERTY' && $vacio != 0) {
                                    if ($op2 == 2) {
                                        $this->Ln();
                                        $this->Cell(196, 10, $b, 'LRTB', 0, 'L', true);
                                        $cm = $cm + 10;
                                        $this->Ln();
                                        $op2 = 0;
                                    } else {
                                        $this->Cell(196, 10, $b, 'LRTB', 0, 'L', true);
                                        $cm = $cm + 10;
                                        $this->Ln();
                                    }
                                }
                                foreach ($valor2 as $key => $value) {
                                    $this->SetFillColor(224, 235, 255);
                                    $this->SetTextColor(0);
                                    $this->SetFont('helvetica', '', 7);
                                    if ($a == 'PROPERTY') {
                                        if ($key == '_StreetAddress' || $key == '_City' || $key == '_State' || $key == '_PostalCode' || $key == '_PlusFourPostalCode') {
                                            if ($key == '_City' && trim($value) != "") {
                                                $address = $address . $value . ' ';
                                            } elseif ($key == '_PostalCode' && trim($value) != "") {
                                                $address = $address . $value . '-';
                                            } elseif ($key == '_PlusFourPostalCode' && trim($value) != "") {
                                                $address = $address . $value;
                                            } else {
                                                $address = $address . $value . ',';
                                            }
                                        }
                                        if ($key == '_AssessorsParcelIdentifier' && trim($value) != "") {
                                            $taxid = 'TAX ID : ' . str_replace('-', '', $value);
                                        }
                                        $op = !$op;
                                    } else {
                                        if ($a == 'PROPERTY_OWNER') {
                                            if ($key == '_OwnerName' && trim($value) != "") {
                                                $key = str_replace('_', " ", $key);
                                                for ($i = 0; $i <= strlen($key) - 1; $i++) {
                                                    if (ctype_upper($key[$i])) {
                                                        if ($i != 0) {
                                                            $tit = $tit . ' ' . $key[$i];
                                                        } else {
                                                            $tit = $tit . $key[$i];
                                                        }
                                                    } else {
                                                        $tit = $tit . $key[$i];
                                                    }
                                                }
                                                $this->Cell($w[0], 6, $tit, 'LRTB', 0, 'L', true);
                                                $tit = '';
                                                $this->SetFont('helvetica', 'B', 8);
                                                $this->Cell($w[1], 6, $value, 'LRTB', 0, 'L', false);
                                                $this->SetFont('helvetica', '', 7);
                                                $cm = $cm + 6;
                                                $this->Ln();
                                                $fill = !$fill;
                                            }
                                            if ($key == '_MailingAddress' || $key == '_MailingCityAndState' || $key == '_MailingPostalCode') {
                                                $address2 = $address2 . $value . " ";
                                            }
                                            if ($key == '_MailingCarrierRouteIdentifier') {
                                                $this->Cell($w[0], 6, ' Mailing Address', 'LRTB', 0, 'L', true);
                                                $this->SetFont('helvetica', 'B', 8);
                                                $this->Cell($w[1], 6, $address2 . ' ' . $value, 'LRTB', 0, 'L', false);
                                                $this->SetFont('helvetica', '', 7);
                                                $cm = $cm + 6;
                                                $this->Ln();
                                                $fill = !$fill;
                                            }
                                            if ($key == '_VestingOwner' && trim($value) != "") {
                                                $key = str_replace('_', " ", $key);
                                                for ($i = 0; $i <= strlen($key) - 1; $i++) {
                                                    if (ctype_upper($key[$i])) {
                                                        if ($i != 0) {
                                                            $tit = $tit . ' ' . $key[$i];
                                                        } else {
                                                            $tit = $tit . $key[$i];
                                                        }
                                                    } else {
                                                        $tit = $tit . $key[$i];
                                                    }
                                                }
                                                $this->Cell($w[0], 6, $tit, 'LRTB', 0, 'L', true);
                                                $tit = '';
                                                $this->SetFont('helvetica', 'B', 8);
                                                $this->Cell($w[1], 6, $value, 'LRTB', 0, 'L', false);
                                                $this->SetFont('helvetica', '', 7);
                                                $cm = $cm + 6;
                                                $this->Ln();
                                                $fill = !$fill;
                                            }
                                        } else {
                                            if ($a == '_LEGAL_DESCRIPTION') {
                                                $key = str_replace('_', " ", $key);
                                                if ($key == '_TextDescription') {
                                                    $value = $address;
                                                }
                                                if (trim($value) != "") {
                                                    for ($i = 0; $i <= strlen($key) - 1; $i++) {
                                                        if (ctype_upper($key[$i])) {
                                                            if ($i != 0) {
                                                                $tit = $tit . ' ' . $key[$i];
                                                            } else {
                                                                $tit = $tit . $key[$i];
                                                            }
                                                        } else {
                                                            $tit = $tit . $key[$i];
                                                        }
                                                    }
                                                    if (strlen($value) > 90) {
                                                        $anh = 12;
                                                    } else {
                                                        $anh = 6;
                                                    }
                                                    $this->Cell($w[0], $anh, $tit, 'LRTB', 0, 'L', true);
                                                    $tit = '';
                                                    $this->SetFont('helvetica', 'B', 8);
                                                    $this->MultiCell($w[1], 6, $value, 1, 'L', false);
                                                    $this->SetFont('helvetica', '', 7);
                                                    $cm = $cm + 6;
                                                    $this->Ln();
                                                    $fill = !$fill;
                                                }
                                            } else {
                                                if ($key == '_SellerName' || $key == '_BuyerName') {
                                                    $bu_se = true;
                                                    $temp = 147;
                                                } else {
                                                    $bu_se = false;
                                                    $temp = $w2[0];
                                                }
                                                $key = str_replace('_', " ", $key);
                                                if (trim($value) != "" && $key != 'Title Company Name') {
                                                    for ($i = 0; $i <= strlen($key) - 1; $i++) {
                                                        if (ctype_upper($key[$i])) {
                                                            if ($i != 0) {
                                                                $tit = $tit . ' ' . $key[$i];
                                                            } else {
                                                                $tit = $tit . $key[$i];
                                                            }
                                                        } else {
                                                            $tit = $tit . $key[$i];
                                                        }
                                                    }
                                                    $strlen = strlen($tit);
                                                    if ($strlen > 38) {
                                                        $h = 6;
                                                        $op2 = 0;
                                                        $y_0 = $this->GetY();
                                                        $this->MultiCell($w2[$op2], $h, $tit, 1, 'L', true);
                                                        $op2++;
                                                        $tit = '';
                                                        $x = $this->GetX();
                                                        $y = $this->GetY();
                                                        $this->SetY($y - 12);
                                                        $this->SetX($x + ($w2[$op2] * 3));
                                                        $this->SetFont('helvetica', 'B', 8);
                                                        $strlen2 = strlen($value);
                                                        if ($strlen2 > 38) {
                                                            $this->MultiCell($temp, 6, $value, 'LRTB', 'L', false);
                                                            $op2++;
                                                            $y = $this->GetY();
                                                            $this->SetY($y - 6);
                                                        } else {
                                                            $this->Cell($temp, 12, $value, 'LRTB', 0, 'L', false);
                                                            $op2++;
                                                            $y = $this->GetY();
                                                            $this->SetY($y - 6);
                                                            $this->Ln();
                                                        }
                                                    } else {
                                                        $strlen2 = strlen($value);
                                                        if ($strlen2 > 38) {
                                                            $this->Cell($w2[$op2], 12, $tit, 1, 0, 'L', true);
                                                            $op2++;
                                                            $tit = '';
                                                            $this->SetFont('helvetica', 'B', 8);
                                                            $this->MultiCell($temp, 6, $value, 'LRTB', 'L', false);
                                                            $op2++;
                                                            $y = $this->GetY();
                                                            $this->SetY($y - 6);
                                                            $op2 = 2;
                                                        } else {
                                                            $this->Cell($w2[$op2], 6, $tit, 1, 0, 'L', true);
                                                            $op2++;
                                                            $tit = '';
                                                            $this->SetFont('helvetica', 'B', 8);
                                                            $this->Cell($temp, 6, $value, 'LRTB', 0, 'L', false);
                                                            $op2++;
                                                        }
                                                    }
                                                    if ($bu_se) {
                                                        $op2 = 4;
                                                    }
                                                    $this->SetFont('helvetica', '', 7);
                                                    $fill = !$fill;
                                                    if ($op2 == 4) {
                                                        $this->Ln();
                                                        $cm = $cm + 6;
                                                        $op2 = 0;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
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

                $path = 'temp/' . $dbname . '-' . $array['path'] . '.pdf';
                $pdf = new PDFReport();
                $pdf->SetFont('helvetica', '', 10);
                $pdf->AliasNbPages();
                $pdf->AddPage('P', 'Letter');
                $tipo_a = $Result['_PRODUCT'];
                $tipo = json_decode($tipo_a, true);
                $tipo['_VoluntaryLienReport'] = 'Y';

                $pdf->DetailedSubjectReport($Result);
                $pdf2 = $pdf;
                $nombre_del_reporte = $array['path'];
                $pdf->Output($path, '');

                $file_obj = $GetClass->GetClass('file');
                if (!isset($_SESSION)) {
                    session_start();
                }
                $idlogin = $_SESSION['jigowatt']['user_id'];
                if (is_object($file_obj) && file_exists($path)) {
                    $fp = fopen($path, "r") or die("can't open File");
                    $contenido = fread($fp, filesize($path));
                    fclose($fp);
                    $file_obj->settitle('Report');
                    $file_obj->updateidlogin($file_obj->getidfile(), $idlogin);
                    $file_obj->updatename($file_obj->getidfile(), $dbname . '-' . $nombre_del_reporte);
                    $file_obj->updatetype($file_obj->getidfile(), '.pdf');
                    $file_obj->updatecontent($file_obj->getidfile(), $contenido);
                    $file_obj->updateidsection($file_obj->getidfile(), '1');
                    $idfile = $file_obj->getidfile();
                }
                $ArrayReturn = array();
                $ArrayReturn['PropertyId'] = $idproperty;
                $ArrayReturn['ReportId'] = $idfile;
                $ArrayReturn['IdFileReport'] = $idfile;
                echo json_encode($ArrayReturn);
                /**/
            } else {
                echo "Error : SOMETHING WENT WRONG " . $arraystatus['_Description'];
            }
        } else {
            echo "Error : An Error has Ocurred Status Result are Empty";
        }
    } else {
        echo "Error : An array expected";
    }
}

function transforma($data) {
    $data = json_decode($data);
    //$data=str_replace('@attributes','',$data);
    foreach ($data as $key => $value) {
        if ($key == 'RESPONSE') {
            foreach ($value as $k => $v) {
                if ($k == 'RESPONSE_DATA') {
                    foreach ($v as $k2 => $v2) {
                        foreach ($v2 as $k3 => $v3) {
                            if ($k3 != '@attributes') {
                                $result2[$k3] = $v3;
                            }
                        }
                    }
                }
            }
        }
    }
    $resp = $result2["STATUS"];
    foreach ($resp as $n => $m) {
        foreach ($m as $o => $p) {
            if ($o == '_Code') {
                $resp2 = $p;
            }
        }
    }
    $re = $result2['_PROPERTY_INFORMATION'];
    foreach ($re as $key => $value) {
        if ($key == '_MULTIPLE_RECORDS') {
            $re = $value;
        }
    }$ind = 0;
    foreach ($re as $key5 => $value5) {
        foreach ($value5 as $key6 => $value6) {
            if ($key6 == 'PROPERTY') {
                foreach ($value6 as $key7 => $value7) {
                    if ($ind == 0) {
                        $re2[] = $value7;
                    };
                    if ($ind == 1 || $ind == 2) {
                        foreach ($value7 as $key8 => $value8) {
                            $re2[] = $value8;
                        }
                    };
                    $ind++;
                }$ind = 0;
            }
        }
    }
    if ($resp2 == '0400') {
        return $re2;
    } else {
        return false;
    }
}

function voluntary($result) {
    $result = json_decode($result, true); //echo gettype($result);
    //echo var_dump($result);
    foreach ($result as $key => $value) {
        //echo $key.'--';
        if ($key == 'RESPONSE') {
            $result2 = $value;
        }
    }//echo json_encode($result,true);
    foreach ($result2 as $key2 => $value2) {
        //echo $key.'**';
        if ($key2 == 'RESPONSE_DATA') {
            $result3 = $value2;
        }
    }

    $result = $result3;
    $rest = array();
    $result = $result['PROPERTY_INFORMATION_RESPONSE'];
    $result_status = $result['STATUS'];
    $status = $result_status['@attributes'];
    $final['STATUS'] = $status; //echo json_encode($result2,true);
    $result_property['_PROPERTY_INFORMATION'] = $result['_PROPERTY_INFORMATION']['PROPERTY']['@attributes'];
    $result_property['PROPERTY_OWNER'] = $result['_PROPERTY_INFORMATION']['PROPERTY']['PROPERTY_OWNER']['@attributes'];
    $result_property['_PARSED_STREET_ADDRESS'] = $result['_PROPERTY_INFORMATION']['PROPERTY']['_PARSED_STREET_ADDRESS']['@attributes'];
    $result_history['_COUNTY_RECORDING_HISTORY'] = $result['_PROPERTY_INFORMATION']['PROPERTY']['_PROPERTY_HISTORY']['_COUNTY_RECORDING_HISTORY']['@attributes'];
    $result_transaction['_TRANSACTION_HISTORY'] = $result[]['PROPERTY']['_PROPERTY_HISTORY']['_TRANSACTION_HISTORY'];
    $result_transaction2['_VOLUNTARY_LIEN_HISTORY'] = $result['_PROPERTY_INFORMATION']['PROPERTY']['_PROPERTY_HISTORY']['_VOLUNTARY_LIEN_HISTORY'];
    $num = 0;
    foreach ($result_transaction2 as $k4 => $v4) {
        foreach ($v4 as $k5 => $v5) {
            foreach ($v5 as $k6 => $v6) {
                $rest[$num] = $v6;
                $num++;
            }
        }
    }
    foreach ($result_transaction as $k => $v) {
        foreach ($v as $k2 => $v2) {
            foreach ($v2 as $k3 => $v3) {
                $rest[$num] = $v3;
                $num++;
            }
        }
    }
    $result_history['_TRANSACTION_HISTORY'] = $rest;
    $result = '';
    $result['STATUS'] = $status;
    $result['PROPERTY'] = $result_property;
    $result['HISTORY'] = $result_history;
    return $result;
}

// 04 
function GetPropertyData($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {//print_r($array);
        if ($array['PropertyId']) {
            $transaction_obj = $GetClass->GetClass('transaction');
            $property_obj = $GetClass->GetClass('property');
            $_legal_description_obj = $GetClass->GetClass('_legal_description');
            $property_owner_obj = $GetClass->GetClass('_property_owner');
            $_property_tax_obj = $GetClass->GetClass('_property_tax');
            $ArrayReturn = array();
            //$transaction = $transaction_obj->gettransactionById($array['idtransaction']);
            if ($array['PropertyId']) {
                $idproperty = $array['PropertyId'];
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
                }
                if ($_property_tax) {
                    foreach ($_property_tax as $pt) {
                        $ArrayReturn['TotalAssePanel'] = $pt->get_TotalAssessedValueAmount() . '||' . $pt->getid_property_tax();
                        $ArrayReturn['TaxYearPanel'] = $pt->get_TaxYear() . '||' . $pt->getid_property_tax();
                        $ArrayReturn['TotalTaxablePanel'] = $pt->get_TotalTaxableValueAmount() . '||' . $pt->getid_property_tax();
                        $ArrayReturn['RealStateTaxPanel'] = $pt->get_RealEstateTotalTaxAmount() . '||' . $pt->getid_property_tax();
                    }
                }
                if ($property_owner) {
                    foreach ($property_owner as $po) {
                        $ArrayReturn['OwnerNamePanel'] = $po->get_OwnerName() . '||' . $po->getidproperty_owner();
                        $ArrayReturn['OwnerVestingPanel'] = $po->get_VestingName() . '||' . $po->getidproperty_owner();
                        $ArrayReturn['OwnerAddressPanel'] = $po->get_MailingAddress() . '||' . $po->getidproperty_owner();
                    }
                }
                echo json_encode($ArrayReturn);
            } else {
                echo 'Error : Property Not Found';
            }
            /**/
        } else {
            echo "Error, Property Not Found";
        }
    } else {
        echo "Error : An array expected";
    }
}

// 05 
function CreateTransaction($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    include_once 'FunctionsHelper.php';
    if (is_array($array)) {//print_r($array);
        //if ($array['PropertyId']) {
        if (true) {
            $transaction_obj = $GetClass->GetClass('transaction');
            $file_obj = $GetClass->GetClass('file');
            $general_config_obj = $GetClass->GetClass('general_config');
            $qb_request_obj = $GetClass->GetClass('qb_request');
            $login_users_obj = $GetClass->GetClass('login_users');
            $property_obj = $GetClass->GetClass('property');
            $task_obj = $GetClass->GetClass('task');
            $cdhud_obj = $GetClass->GetClass('cdhud');
            $alert_obj = $GetClass->GetClass('alert');
            $contact_obj = $GetClass->GetClass('contact');
            $deposit_obj = $GetClass->GetClass('deposit');
            $rolelist_obj = $GetClass->GetClass('rolelist');
            $requeriment_list_obj = $GetClass->GetClass('requeriment_list');
            $transaction_contact_obj = $GetClass->GetClass('transaction_contact');
            $TransactionListRepeat = $transaction_obj->getAlltransactionForColumnValue('name', "'" . $array['TransactionName'] . "'");
            if ($TransactionListRepeat) {
                die('Error : Transaction Name Exist, Please Change and Try Again');
            }
            $TransactionListRepeat = $transaction_obj->getAlltransactionForColumnValue('transactionnumber', "'" . $array['ExternalNumber'] . "'");
            if ($TransactionListRepeat) {
                die('Error : External Number Exist, Please Change and Try Again');
            }
            /* Zimbra */
            if (IsZServer() != 'disabled') {
                $_zids = posZpstodb('18', $data_p);
                $_zids = json_decode($_zids, true); //var_dump($_zids);
                if ($_zids) {
                    //$array['zid'] = $_zids['id'];
                    if ($array['one_calendar_enable'] == 1) {
                        $array['zcalid'] = $_zids['zcalid'];
                    } else {
                        $obj_gc = $general_config_obj->getgeneral_configById(1);
                        if (is_object($obj_gc)) {
                            $gc_json = json_decode($obj_gc->getzcalendar(), true);
                            if (is_array($gc_json)) {
                                $array['zcalid'] = $gc_json['zcalid'];
                            }
                        }
                    }
                    $array['zfolder'] = $_zids['zfolderid'];
                } else {
                    die('Error : Zimbra has error , please try again');
                }
            }
            /**/
            /* QB */
            $idq = '';
            if (quickbook_e_d() == 'enabled') {
                /**/
                $QBHelper_obj = new QBHelper($dbname);
                /**/
                $qb_request_obj = $GetClass->GetClass('qb_request');
                $qb_request_obj->setoperation('new account, Script: ' . basename(__FILE__) . ', Func.:' . __FUNCTION__);
                $qb_request_obj->updaterequest($qb_request_obj->getidqb_request(), $theData);
                $qb_request_obj->updatestatus($qb_request_obj->getidqb_request(), 'created');
                $qb_request_obj->updateidlogin($qb_request_obj->getidqb_request(), $idlogin);
                $qb_request_obj->updateidoperation($qb_request_obj->getidqb_request(), '05');

                //if ($quickbooks_is_connected) {
                $transactionDesc = $array['TransactionName'] . '_' . $array['ExternalNumber'];
                $transactionDesc = (strlen($transactionDesc) <= 100) ? $transactionDesc : substr($transactionDesc, 0, 100);
                $transactionName = str_replace(array('"', ':'), '', $array['ExternalNumber']);
                $params = array(
                    'Name' => $transactionName,
                    'Description' => $transactionDesc,
                );
                $response = $QBHelper_obj->AccountQB('Create', $params);
                if ($Result = json_decode($response, true)) {
                    if ($Result['Code'] == 200) {
                        $qb_request_obj->updateresponse($qb_request_obj->getidqb_request(), $response);
                        $qb_request_obj->updatestatus($qb_request_obj->getidqb_request(), 'checked');
                        $idq = $response;
                    } else {
                        $str_info = 'Error to create new Account Transaction <br>';
                        $obj_lu_m = $login_users_obj->getlogin_usersById($_SESSION['jigowatt']['user_id']);
                        $lu_email = '';
                        if (is_object($obj_lu_m)) {
                            $lu_email = $obj_lu_m->getemail();
                        }
                        $_email = explode('@', $lu_email);
                        $rep_email = (is_array($_email) && count($_email) > 1) ? $_email[1] : '';
                        $str_info .= 'Domain: ' . $rep_email . ' <br>';
                        $str_info .= 'Email user logged: ' . $lu_email . ' <br>';
                        $str_info .= 'Req. list ID: ' . $array['TransactionType'] . ' <br>';
                        $str_info .= 'Transaction: ' . $array['TransactionName'] . ' <br>';
                        //$str_info .= 'Transaction ID: ' . $transs->getidtransaction() . ' <br>';
                        sendGeneralEmail('Error QB request ID: ' . $qb_request_obj->getidqb_request(), '', 'notification', "$str_info $response");

                        $response = ($response) ? $response : 'empty';
                        $qb_request_obj->updateresponse($qb_request_obj->getidqb_request(), "Response: $response");
                        $qb_request_obj->updatestatus($qb_request_obj->getidqb_request(), 'failed');
                    }
                } else {
                    $str_info = 'Error to create new Account Transaction <br>';
                    $obj_lu_m = $login_users_obj->getlogin_usersById($_SESSION['jigowatt']['user_id']);
                    $lu_email = '';
                    if (is_object($obj_lu_m)) {
                        $lu_email = $obj_lu_m->getemail();
                    }
                    $_email = explode('@', $lu_email);
                    $rep_email = (is_array($_email) && count($_email) > 1) ? $_email[1] : '';
                    $str_info .= 'Domain: ' . $rep_email . ' <br>';
                    $str_info .= 'Email user logged: ' . $lu_email . ' <br>';
                    $str_info .= 'Req. list ID: ' . $array['TransactionType'] . ' <br>';
                    $str_info .= 'Transaction: ' . $array['TransactionName'] . ' <br>';
                    //$str_info .= 'Transaction ID: ' . $transs->getidtransaction() . ' <br>';
                    sendGeneralEmail('Error QB request ID: ' . $qb_request_obj->getidqb_request(), '', 'notification', "$str_info $response");

                    $response = ($response) ? $response : 'empty';
                    $qb_request_obj->updateresponse($qb_request_obj->getidqb_request(), "Response: $response");
                    $qb_request_obj->updatestatus($qb_request_obj->getidqb_request(), 'failed');
                }
                //}
            }
            /**/
            /* Trasnsaction */
            $transaction_obj->setidlogin($idlogin);
            $idTransaction = $transaction_obj->getidtransaction();
            $transaction_obj->updatename($idTransaction, $array['TransactionName']);
            $transaction_obj->updatezfolder($idTransaction, $array['zfolder']);
            $transaction_obj->updateidrequirementslist($idTransaction, $array['TransactionType']);
            $transaction_obj->updatezcalid($idTransaction, $array['zcalid']);
            $transaction_obj->updateidprocessor($idTransaction, $array['TransactionProcessor']);
            $transaction_obj->updatestatus($idTransaction, 'Opened');
            $transaction_obj->updatetransactionnumber($idTransaction, $array['ExternalNumber']);
            $transaction_obj->updateclosingdate($idTransaction, date('Y-m-d', strtotime($array['ClosingDate'])));
            /* Create Event Closing Date */
            $event_obj = $GetClass->GetClass('event');
            $event_obj->setidtransaction($idTransaction);
            $idEvent1 = $event_obj->getidevent();
            $event_obj->updatestart_date($idEvent1, date('Y-m-d H:i:s', strtotime($array['ClosingDate'])));
            $event_obj->updateend_date($idEvent1, date('Y-m-d H:i:s', strtotime($array['ClosingDate'])));
            $event_obj->updatesubject($idEvent1, 'Closing Date');
            /**/
            /**/
            $ArrayExec = array();
            $ArrayExec['FullExecutedDate'] = $array['FullExecutedDate'];
            if ($ArrayExec['FullExecutedDate']) {
                $event_obj->setidtransaction($idTransaction);
                $idEvent2 = $event_obj->getidevent();
                $event_obj->updatestart_date($idEvent2, date('Y-m-d H:i:s', strtotime($array['FullExecutedDate'])));
                $event_obj->updateend_date($idEvent2, date('Y-m-d H:i:s', strtotime($array['FullExecutedDate'])));
                $event_obj->updatesubject($idEvent2, 'Full Executed Date');
            }
            $ArrayExec['InspectionPeriod'] = $array['InspectionPeriod'];
            if ($ArrayExec['FullExecutedDate'] && $array['InspectionPeriod']) {
                $NewDate = strtotime('+' . $array['InspectionPeriod'] . ' day', strtotime($array['FullExecutedDate']));
                $NewDate = date('Y-m-d H:I:s', $NewDate);
                $event_obj->setidtransaction($idTransaction);
                $idEvent3 = $event_obj->getidevent();
                $event_obj->updatestart_date($idEvent3, $NewDate);
                $event_obj->updateend_date($idEvent3, $NewDate);
                $event_obj->updatesubject($idEvent3, 'Inspection Period');
            }
            $ArrayExec['LoanApprovalPeriod'] = $array['LoanApprovalPeriod'];
            if ($ArrayExec['FullExecutedDate'] && $array['LoanApprovalPeriod']) {
                $NewDate = strtotime('+' . $array['LoanApprovalPeriod'] . ' day', strtotime($array['FullExecutedDate']));
                $NewDate = date('Y-m-d H:I:s', $NewDate);
                $event_obj->setidtransaction($idTransaction);
                $idEvent4 = $event_obj->getidevent();
                $event_obj->updatestart_date($idEvent4, $NewDate);
                $event_obj->updateend_date($idEvent4, $NewDate);
                $event_obj->updatesubject($idEvent4, 'Loan Approval Period');
            }
            $ArrayExec['AppraisalContingency'] = $array['AppraisalContingency'];
            if ($ArrayExec['FullExecutedDate'] && $array['AppraisalContingency']) {
                $NewDate = strtotime('+' . $array['AppraisalContingency'] . ' day', strtotime($array['FullExecutedDate']));
                $NewDate = date('Y-m-d H:I:s', $NewDate);
                $event_obj->setidtransaction($idTransaction);
                $idEvent5 = $event_obj->getidevent();
                $event_obj->updatestart_date($idEvent5, $NewDate);
                $event_obj->updateend_date($idEvent5, $NewDate);
                $event_obj->updatesubject($idEvent5, 'Appraisal Contingency');
            }
            $transaction_obj->updatewarning($idTransaction, json_encode($ArrayExec));
            /**/
            $transaction_obj->updatelistprice($idTransaction, 'byproperty');
            if ($idq) {
                $transaction_obj->updateidqaccount($idTransaction, $idq);
            }
            if ($array['IdFileReport'] && $array['IdFileReport'] != '"ManualInsert"') {
                $file_obj->updateidtransaction($array['IdFileReport'], $idTransaction);
            }
            if ($array['IdFileContract']) {
                $file_obj->updateidtransaction($array['IdFileContract'], $idTransaction);
            }
            if ($array['PropertyId']) {
                $transaction_obj->updateidproperty($idTransaction, $array['PropertyId']);
                /**/
                $property = $property_obj->getpropertyById($array['PropertyId']);
                if ($property) {
                    $event_obj->updatelocation($idEvent1, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                    if ($idEvent2) {
                        $event_obj->updatelocation($idEvent2, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                    }
                    if ($idEvent3) {
                        $event_obj->updatelocation($idEvent3, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                    }
                    if ($idEvent4) {
                        $event_obj->updatelocation($idEvent4, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                    }
                    if ($idEvent5) {
                        $event_obj->updatelocation($idEvent5, $property->get_StreetAddress() . ' ' . $property->get_City() . ' ' . $property->get_County() . ' ' . $property->get_State() . ' ' . $property->get_PostalCode());
                    }
                }
                /**/
            } else {
                $_legal_description_obj = $GetClass->GetClass('_legal_description');
                $property_owner_obj = $GetClass->GetClass('_property_owner');
                $_property_tax_obj = $GetClass->GetClass('_property_tax');
                $property_obj->set_AssessorsParcelIdentifier($array['TaxIdPanel']);
                $idProperty = $property_obj->getidproperty();
                $property_obj->update_StreetAddress($idProperty, $array['AddressPanel']);
                $property_obj->update_StreetAddress2($idProperty, $array['Address2Panel']);
                $property_obj->update_State($idProperty, $array['StatePanel']);
                $property_obj->update_County($idProperty, $array['CountyPanel']);
                $property_obj->update_City($idProperty, $array['CityPanel']);
                $property_obj->update_Municipality($idProperty, $array['MunicipalityPanel']);
                $property_obj->update_PostalCode($idProperty, $array['ZipPanel']);

                $event_obj->updatelocation($idEvent1, $array['AddressPanel'] . ' ' . $array['CityPanel'] . ' ' . $array['CountyPanel'] . ' ' . $array['StatePanel'] . ' ' . $array['ZipPanel']);
                if ($idEvent2) {
                    $event_obj->updatelocation($idEvent2, $array['AddressPanel'] . ' ' . $array['CityPanel'] . ' ' . $array['CountyPanel'] . ' ' . $array['StatePanel'] . ' ' . $array['ZipPanel']);
                }
                if ($idEvent3) {
                    $event_obj->updatelocation($idEvent3, $array['AddressPanel'] . ' ' . $array['CityPanel'] . ' ' . $array['CountyPanel'] . ' ' . $array['StatePanel'] . ' ' . $array['ZipPanel']);
                }
                if ($idEvent4) {
                    $event_obj->updatelocation($idEvent4, $array['AddressPanel'] . ' ' . $array['CityPanel'] . ' ' . $array['CountyPanel'] . ' ' . $array['StatePanel'] . ' ' . $array['ZipPanel']);
                }
                if ($idEvent5) {
                    $event_obj->updatelocation($idEvent5, $array['AddressPanel'] . ' ' . $array['CityPanel'] . ' ' . $array['CountyPanel'] . ' ' . $array['StatePanel'] . ' ' . $array['ZipPanel']);
                }

                if ($array['ShortLegalPanel']) {
                    $_legal_description_obj->set_TextDescription($array['ShortLegalPanel']);
                    $idlegal = $_legal_description_obj->getid_legal_description();
                }
                if ($array['FullLegalPanel']) {
                    if ($idlegal) {
                        $_legal_description_obj->update_LegalAndVestingTextDescription($idlegal, $array['FullLegalPanel']);
                    } else {
                        $_legal_description_obj->set_LegalAndVestingTextDescription($array['FullLegalPanel']);
                        $idlegal = $_legal_description_obj->getid_legal_description();
                    }
                }
                if ($idlegal) {
                    $_legal_description_obj->updateidproperty($idlegal, $idProperty);
                }
                ////////////////////////////////////////////////////////////////////
                if ($array['OwnerNamePanel']) {
                    $property_owner_obj->set_OwnerName($array['OwnerNamePanel']);
                    $idproperty_owner = $property_owner_obj->getidproperty_owner();
                }
                if ($array['OwnerVestingPanel']) {
                    if ($idproperty_owner) {
                        $property_owner_obj->update_VestingName($idlegal, $array['OwnerVestingPanel']);
                    } else {
                        $property_owner_obj->set_VestingName($array['OwnerVestingPanel']);
                        $idproperty_owner = $property_owner_obj->getidproperty_owner();
                    }
                }
                if ($array['OwnerAddressPanel']) {
                    if ($idproperty_owner) {
                        $property_owner_obj->update_MailingAddress($idlegal, $array['OwnerAddressPanel']);
                    } else {
                        $property_owner_obj->set_MailingAddress($array['OwnerAddressPanel']);
                        $idproperty_owner = $property_owner_obj->getidproperty_owner();
                    }
                }
                if ($idproperty_owner) {
                    $property_owner_obj->updateidproperty($idproperty_owner, $idProperty);
                }
                ///////////////////////////////////////////////////////////////////////
                if ($array['TotalAssePanel']) {
                    $_property_tax_obj->set_TotalAssessedValueAmount($array['TotalAssePanel']);
                    $id_property_tax = $_property_tax_obj->getid_property_tax();
                }
                if ($array['TaxYearPanel']) {
                    if ($id_property_tax) {
                        $_property_tax_obj->update_TaxYear($idlegal, $array['TaxYearPanel']);
                    } else {
                        $_property_tax_obj->set_TaxYear($array['TaxYearPanel']);
                        $id_property_tax = $_property_tax_obj->getid_property_tax();
                    }
                }
                if ($array['TotalTaxablePanel']) {
                    if ($id_property_tax) {
                        $_property_tax_obj->update_TotalTaxableValueAmount($idlegal, $array['TotalTaxablePanel']);
                    } else {
                        $_property_tax_obj->set_TotalTaxableValueAmount($array['TotalTaxablePanel']);
                        $id_property_tax = $_property_tax_obj->getid_property_tax();
                    }
                }
                if ($array['RealStateTaxPanel']) {
                    if ($id_property_tax) {
                        $_property_tax_obj->update_RealEstateTotalTaxAmount($idlegal, $array['RealStateTaxPanel']);
                    } else {
                        $_property_tax_obj->set_RealEstateTotalTaxAmount($array['RealStateTaxPanel']);
                        $id_property_tax = $_property_tax_obj->getid_property_tax();
                    }
                }
                if ($id_property_tax) {
                    $_property_tax_obj->updateidproperty($id_property_tax, $idProperty);
                }
                //////////////////////////////////////////
                $transaction_obj->updateidproperty($idTransaction, $idProperty);
            }
            //Generateics();
            /**/
            /* Alerts */
            for ($j = 0; $j <= 20; $j++) {
                if ($array['AddWarning_' . $j]) {
                    $alert_obj->setidtransaction($idTransaction);
                    $alert_obj->updatetype($alert_obj->getidalert(), 'system');
                    $alert_obj->updatetext($alert_obj->getidalert(), $array['AddWarning_' . $j]);
                    $alert_obj->updatedatealert($alert_obj->getidalert(), date('m/d/Y') . ' by System');
                    $alert_obj->updateidlogin($alert_obj->getidalert(), $idlogin);
                    $alert_obj->updatedata($alert_obj->getidalert(), json_encode(array('TituloAlert' => 'Alert Generate by System')));
                }
                if ($array['NewAlerts' . $j]) {
                    $alert_obj->setidtransaction($idTransaction);
                    $alert_obj->updatetype($alert_obj->getidalert(), 'system');
                    $alert_obj->updatetext($alert_obj->getidalert(), $array['NewAlerts' . $j]);
                    $alert_obj->updatedatealert($alert_obj->getidalert(), date('m/d/Y') . ' by System');
                    $alert_obj->updateidlogin($alert_obj->getidalert(), $idlogin);
                    $alert_obj->updatedata($alert_obj->getidalert(), json_encode(array('TituloAlert' => 'Alert Generate by System')));
                }
            }
            /**/
            /* Parties */
            $idBuyerTransaction = '';
            $idSellerTransaction = '';
            for ($k = 1; $k <= $array['PartyNumber']; $k++) {
                if ($array['PartyRole' . $k]) {
                    if (trim($array['PartyFirstName' . $k] . $array['PartyMiddleName' . $k] . $array['PartyLastName' . $k] . $array['PartyCompanyName' . $k])) {
                        if (!$array['PartyIdName' . $k]) {
                            $array['PartyIdName' . $k] = CreaContactForCheck($array['PartyFirstName' . $k], $array['PartyMiddleName' . $k], $array['PartyLastName' . $k], $array['PartyCompanyName' . $k]);
                        }
                        /* ActualizarDatosContact */
                        if (is_numeric($array['PartyIdName' . $k])) {
                            $contact_obj->updateaddress1($array['PartyIdName' . $k], $array['PartyAddress' . $k]);
                            $contact_obj->updateemail($array['PartyIdName' . $k], $array['PartyEmailName' . $k]);
                            $contact_obj->updatephone($array['PartyIdName' . $k], $array['PartyPhoneName' . $k]);
                            $contact_obj->updatemobile($array['PartyIdName' . $k], $array['PartyMobileName' . $k]);
                            $contact_obj->updatelicense($array['PartyIdName' . $k], $array['PartyLic' . $k]);
                            /* AddParty */
                            $transaction_contact_obj->setidtransaction($idTransaction);
                            $transaction_contact_obj->updateidcontact($transaction_contact_obj->getidtransaction_contact(), $array['PartyIdName' . $k]);
                            $transaction_contact_obj->updateidrole($transaction_contact_obj->getidtransaction_contact(), $array['PartyRole' . $k]);
                            $transaction_contact_obj->updateside($transaction_contact_obj->getidtransaction_contact(), $array['PartySide' . $k]);
                            $transaction_contact_obj->updateidlogin($transaction_contact_obj->getidtransaction_contact(), $idlogin);
                            $transaction_contact_obj->updatedate($transaction_contact_obj->getidtransaction_contact(), date('Y-m-d H:i:s'));
                            /**/
                            /* For Id Contact Transaction */
                            $rolelist = $rolelist_obj->getrolelistById($array['PartyRole' . $k]);
                            if (strtolower($rolelist->getname()) == strtolower('Buyer')) {
                                if (!$idBuyerTransaction) {
                                    $transaction_obj->updateidcontactbuyer($idTransaction, $array['PartyIdName' . $k]);
                                    $idBuyerTransaction = $array['PartyIdName' . $k];
                                }
                            }
                            if (strtolower($rolelist->getname()) == strtolower('Seller')) {
                                if (!$idSellerTransaction) {
                                    $transaction_obj->updateidcontactseller($idTransaction, $array['PartyIdName' . $k]);
                                    $idSellerTransaction = $array['PartyIdName' . $k];
                                }
                            }
                            /**/
                        }
                        /**/
                    }
                }
            }
            /**/
            /* Save Data Extra */
            $cdhud_obj->setidtransaction($idTransaction);
            $cdhud_obj->updatejsoninformation($cdhud_obj->getidcdhud(), json_encode(array('t19' => $array['InitialInterestRate'])));
            $cdhud_obj->updateLoanTerm($cdhud_obj->getidcdhud(), array('year_terms' => $array['LoanTerms']));
            $cdhud_obj->updatebankaccount($cdhud_obj->getidcdhud(), $array['BankAccount']);
            $cdhud_obj->updateSalesPrice($cdhud_obj->getidcdhud(), json_encode(array('SalesPriceDialog' => $array['PurchasePrice'])));
            $cdhud_obj->updateLoanAmount($cdhud_obj->getidcdhud(), json_encode(array('LoanAmountDialog' => $array['LoanAmount'])));
            /**/
            /* Escrow Deposit */
            if ($array['EscrowDeposit'] || $array['AditionalDeposit']) {
                if ($array['EscrowDeposit']) {
                    $Amount1 = str_replace(array('$', ','), '', $array['EscrowDeposit']);
                    if ($array['AditionalDeposit']) {
                        $Amount2 = str_replace(array('$', ','), '', $array['AditionalDeposit']);
                    }
                } else {
                    $Amount1 = str_replace(array('$', ','), '', $array['AditionalDeposit']);
                }
                if ($Amount1) {
                    $TotalAmount = $Amount1;
                    $ArrayDeposit = array();
                    $ArrayDeposit['DepositToAccountRef'] = $array['BankAccount'];
                    $ArrayDeposit['AmountDeposit1'] = str_replace('USD ', '$', money_format('%i', $Amount1));
                    $ArrayDeposit['DescriptionDeposit1'] = $array['Escrow Deposit'];
                    $ArrayDeposit['txnDate'] = date('Y-m-d H:i:s');
                    $ArrayDeposit['hudlineDeposit'] = 'L-01';
                    if ($Amount2) {
                        $TotalAmount = $Amount1 + $Amount2;
                        $ArrayDeposit['NumberDeposits'] = '2';
                        $ArrayDeposit['AmountDeposit2'] = str_replace('USD ', '$', money_format('%i', $Amount2));
                    }
                    $ArrayDeposit['TotalDeposit'] = str_replace('USD ', '$', money_format('%i', $TotalAmount));

                    $deposit_obj->setidtransaction($idTransaction);
                    $iddeposit = $deposit_obj->getiddeposit();
                    $deposit_obj->updatetotal_amount($iddeposit, str_replace(array('$', ','), array('', ''), $TotalAmount));
                    $deposit_obj->updatedata($iddeposit, json_encode($ArrayDeposit));
                    $deposit_obj->updatedeposittoaccountref($iddeposit, $array['BankAccount']);
                    $deposit_obj->updatetxnDate($iddeposit, date('Y-m-d H:i:s'));
                    $deposit_obj->updateidlogin($iddeposit, $idlogin);
                    $deposit_obj->updateidtransaction($iddeposit, $idTransaction);
                    //$deposit_obj->updateidcontact($iddeposit, $array['IdContact9']);
                    $deposit_obj->updatecreated_at($iddeposit, date('Y-m-d H:i:s'));
                }
            }
            /**/
            /* Tasks */
            $general_config = $general_config_obj->getgeneral_configById(1);
            $officeinfo = json_decode($general_config->getechosign(), true);
            if ($officeinfo['TaskType'] == 'NoModel') {
                $requeriment_list = $requeriment_list_obj->getrequeriment_listById($array['TransactionType']);
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
                                if ($order) {
                                    $arraylist['o'] = 'yes';
                                }
                                $arraylist['p'] = $percent;
                                $task_obj->setsubject($reqlist[$k]);
                                $task_obj->updatelocation($task_obj->getidtask(), '');
                                $task_obj->updatestart_date($task_obj->getidtask(), $start_date);
                                //$task_obj->updateend_date($task_obj->getidtask(), $end_date);
                                $task_obj->updatestatus($task_obj->getidtask(), 'Not Started');
                                $task_obj->updateprogress_status($task_obj->getidtask(), 'Created');
                                $task_obj->updateidtransaction($task_obj->getidtask(), $idTransaction);
                                $task_obj->updateiduser($task_obj->getidtask(), $_SESSION['jigowatt']['user_id']);
                                $task_obj->updatenote($task_obj->getidtask(), '');
                                $task_obj->updatetask_list($task_obj->getidtask(), json_encode($arraylist));
                            }
                        }
                    }
                }
            } else {
                $arrayIgnoerAut = array('1', '2', '1-1', '1-3', '1-5', '1-6-1');
                /* Create Task Condo */
                if (strpos(strtolower($array['ShortLegalPanel']), 'condo') !== false) {
                    $Condo = 'yes';
                } else {
                    $Condo = 'no';
                }
                $task_obj->setidtransaction($idTransaction);
                $task_obj->updatesubject($task_obj->getidtask(), 'If Condo');
                $task_obj->updatestatus($task_obj->getidtask(), $Condo);
                $task_obj->updatestart_date($task_obj->getidtask(), date('Y-m-d H:i:s'));
                $task_obj->updateend_date($task_obj->getidtask(), date('Y-m-d H:i:s'));
                $note = '<p><strong>By System : </strong></p><p>Task Created based on the Legal Description</p>';
                $task_obj->updatenote($task_obj->getidtask(), $note);
                $task_obj->updatez_id($task_obj->getidtask(), '1');
                $task_obj->updateidlogin($task_obj->getidtask(), $idlogin);
                /**/
                /* Create Task REFI */
                if (strpos(strtolower($array['TransactionType']), 'refi') !== false) {
                    $Refi = 'yes';
                } else {
                    $Refi = 'no';
                }
                $task_obj->setidtransaction($idTransaction);
                $task_obj->updatesubject($task_obj->getidtask(), 'If Refi');
                $task_obj->updatestatus($task_obj->getidtask(), $Refi);
                $task_obj->updatestart_date($task_obj->getidtask(), date('Y-m-d H:i:s'));
                $task_obj->updateend_date($task_obj->getidtask(), date('Y-m-d H:i:s'));
                $note = '<p><strong>By System : </strong></p><p>Task Created based on the Transaction Type</p>';
                $task_obj->updatenote($task_obj->getidtask(), $note);
                $task_obj->updatez_id($task_obj->getidtask(), '2');
                $task_obj->updateidlogin($task_obj->getidtask(), $idlogin);
                /**/
                /* Create Task cash */
                if ($Refi == 'no') {
                    if (strpos(strtolower($array['TransactionType']), 'cash') !== false) {
                        $Cash = 'yes';
                    } else {
                        $Cash = 'no';
                    }
                    $task_obj->setidtransaction($idTransaction);
                    $task_obj->updatesubject($task_obj->getidtask(), 'If Cash');
                    $task_obj->updatestatus($task_obj->getidtask(), $Cash);
                    $task_obj->updatestart_date($task_obj->getidtask(), date('Y-m-d H:i:s'));
                    $task_obj->updateend_date($task_obj->getidtask(), date('Y-m-d H:i:s'));
                    $note = '<p><strong>By System : </strong></p><p>Task Created based on the Transaction Type</p>';
                    $task_obj->updatenote($task_obj->getidtask(), $note);
                    $task_obj->updatez_id($task_obj->getidtask(), '2-1');
                    $task_obj->updateidlogin($task_obj->getidtask(), $idlogin);
                }
                /**/
                /* Create Task HOA */
                $task_obj->setidtransaction($idTransaction);
                $task_obj->updatesubject($task_obj->getidtask(), 'if HOA');
                if ($array['StoppelOrder']) {
                    $task_obj->updatestatus($task_obj->getidtask(), 'yes');
                } else {
                    $task_obj->updatestatus($task_obj->getidtask(), 'no');
                }
                $task_obj->updatestart_date($task_obj->getidtask(), date('Y-m-d H:i:s'));
                $task_obj->updateend_date($task_obj->getidtask(), date('Y-m-d H:i:s'));
                $note = '<p><strong>By System : </strong></p><p>Task Created based on Checkbox Estoppel for HOA</p>';
                $task_obj->updatenote($task_obj->getidtask(), $note);
                $task_obj->updatez_id($task_obj->getidtask(), '1-6');
                $task_obj->updateidlogin($task_obj->getidtask(), $idlogin);
                /**/

                $requeriment_list = $requeriment_list_obj->getAllrequeriment_listForColumnValue('namer', '"Model1"');
                if ($requeriment_list) {
                    $requeriment_list = $requeriment_list[0];
                    $json = json_decode($requeriment_list->getrequerimentsjson(), true);
                    $order = json_decode($json['Order'], true);
                    $content = json_decode($json['Content'], true);
                    $array['idtransaction'] = $idTransaction;
                    CreateTaskAut($order, $content, $idTransaction, $array);
                } else {
                    /* Email Not Have Model */
                    sendGeneralEmail('Error on New Transaction', "System", 'notification', 'Error : Not have Model on client ' . $dbname);
                    /**/
                }
                /**/
            }





            /* For Automatics orders */
            /* $Questionnaries id= 1-1 */

            /**/
            /**/
            echo $idTransaction;
        } else {
            echo "Error, Property Not Found";
        }
    } else {
        echo "Error : An array expected";
    }
}

function Generateics() {
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb(); // PHP_EOL
    $event_obj = $GetClass->GetClass('event');
    $transaction_obj = $GetClass->GetClass('transaction');
    $AllEvent = $event_obj->getAllevents();
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

function CreateTaskAut($array, $data, $idtransaction, $datapost) {
    include_once 'FunctionsHelper.php';
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $task_obj = $GetClass->GetClass('task');
    $tasks = $task_obj->getAlltaskForColumnValue('idtransaction', $idtransaction);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    $arrayTasks = array();
    $TasksAvailables = array();
    foreach ($tasks as $task) {
        $arrayTasks[$task->getz_id()] = $task->getstatus();
    }
    /* Get Fee Additional */
    //$general_config_obj = new general_config($dbname);
    $general_config_obj = $GetClass->GetClass('general_config');
    $general_config = $general_config_obj->getgeneral_configById(1);
    $JsonFees = json_decode($general_config->getgetleads(), true);
    /**/
    foreach ($array as $key => $value) {
        $contenReq = json_decode($data['Data' . $value['id']], true);
        $json_next = json_decode($contenReq['ChildItem'], true);
        $jsonCondition = json_decode($contenReq['ConditionItem'], true);
        foreach ($json_next as $jsonkey => $jsonvalue) {
            if ($jsonkey == $arrayTasks[$value['id']]) {
                $jsonids = explode(',', $jsonvalue);
                foreach ($jsonids as $ids) {
                    $TasksAvailables[] = $ids;
                }
            }
        }
        /* condition */
        $Condition = true;
        if ($jsonCondition) {
            foreach ($Condition as $keyC => $valueC) {
                if (strtolower($arrayTasks[$keyC]) != strtolower($valueC)) {
                    $Condition = false;
                }
            }
        }
        /**/
        if ($contenReq['AutoItem'] && $contenReq['RolesAutoItem']) {
            $AdditionalFee = 0;
            $datapost['idtask'] = $value['id'];
            if ($Condition) {
                $response = 'Task Not Created for User';
                switch ($contenReq['RolesAutoItem']) {
                    case 'SendQuestionaries':
                        $response = SendQuestionaries($datapost);
                        break;
                    case 'SendWireInstruction':
                        $response = SendWireInstruction($datapost);
                        break;
                    case 'AddCommitmentTasks':
                        $response = AddCommitmentTasks($datapost);
                        break;
                    case 'OrderTitleSearch':
                        //if($datapost['LienOrder']){
                        $response = OrderTitleSearch($datapost);
                        // }
                        break;
                    case 'OrderCommitment':
                        //if($datapost['LienOrder']){
                        $response = OrderCommitment($datapost);
                        //}
                        break;
                    case 'OrderSurvey':
                        if ($datapost['SurveyOrder']) {
                            if ($JsonFees['OrderSurvey']) {
                                $AdditionalFee = $JsonFees['OrderSurvey'];
                            }
                            if ($AdditionalFee > 0) {
                                if (!DeductCharge($AdditionalFee, $contenReq['RolesAutoItem'], $contenReq['NameItem'] . ' to Transaction ' . $idtransaction)) {
                                    $response = 'Error: The Order was not generated due to insufficient Credit, and the refusal to recharge it, please check your settings or contact the system administrator';
                                } else {
                                    $response = OrderSurvey($datapost);
                                }
                            } else {
                                $response = OrderSurvey($datapost);
                            }
                        }
                        break;
                    case 'OrderEstoppel':
                        if ($datapost['StoppelOrder']) {
                            if ($JsonFees['OrderEstoppel']) {
                                $AdditionalFee = $JsonFees['OrderEstoppel'];
                            }
                            if ($AdditionalFee > 0) {
                                if (!DeductCharge($AdditionalFee, $contenReq['RolesAutoItem'], $contenReq['NameItem'] . ' to Transaction ' . $idtransaction)) {
                                    $response = 'Error: The Order was not generated due to insufficient Credit, and the refusal to recharge it, please check your settings or contact the system administrator';
                                } else {
                                    $response = OrderEstoppel($datapost);
                                }
                            } else {
                                $response = OrderEstoppel($datapost);
                            }
                        }
                        break;
                    case 'OrderLienSearch':
                        if ($datapost['LienOrder']) {
                            if ($JsonFees['OrderLienSearch']) {
                                $AdditionalFee = $JsonFees['OrderLienSearch'];
                            }
                            if ($AdditionalFee > 0) {
                                if (!DeductCharge($AdditionalFee, $contenReq['RolesAutoItem'], $contenReq['NameItem'] . ' to Transaction ' . $idtransaction)) {
                                    $response = 'Error: The Order was not generated due to insufficient Credit, and the refusal to recharge it, please check your settings or contact the system administrator';
                                } else {
                                    $response = OrderLienSearch($datapost);
                                }
                            } else {
                                $response = OrderLienSearch($datapost);
                            }
                        }
                        break;
                    default :
                        break;
                }
            } else {
                $response = 'Error : the condition has not been met';
            }
            /* Create Task */
            $task_obj->setidtransaction($idtransaction);
            $task_obj->updatesubject($task_obj->getidtask(), $contenReq['NameItem']);
            $task_obj->updatestart_date($task_obj->getidtask(), date('Y-m-d H:i:s'));
            if (strpos(strtolower($contenReq['NameItem']), 'order') !== false) {
                if (strpos($response, 'Error') !== false) {
                    $task_obj->updatestatus($task_obj->getidtask(), 'pending');
                } else {
                    $task_obj->updatestatus($task_obj->getidtask(), 'ordered');
                    $task_obj->updateoderdate($task_obj->getidtask(), date('Y-m-d H:i:s'));
                }
            } else {
                if (strpos($response, 'Error') !== false) {
                    $task_obj->updatestatus($task_obj->getidtask(), 'pending');
                } else {
                    $task_obj->updatestatus($task_obj->getidtask(), 'completed');
                    $task_obj->updateend_date($task_obj->getidtask(), date('Y-m-d H:i:s'));
                }
            }
            $note = '<p><strong>By System : </strong></p><p>Task Created with response : ' . $response . '</p>';
            $task_obj->updatenote($task_obj->getidtask(), $note);
            $task_obj->updatez_id($task_obj->getidtask(), $value['id']);
            $task_obj->updateidlogin($task_obj->getidtask(), $idlogin);

            /**/
        }
        //print_r($TasksAvailables);
        if (in_array($value['id'], $TasksAvailables)) {
            //print_r($contenReq['AutoItem'] .'-*-'. $contenReq['RolesAutoItem']);
            if ($value['children']) {
                CreateTaskAut($value['children'], $data, $idtransaction, $datapost);
            }
        }
    }
}

// 06
function TransactionSumary($theData) {
    error_reporting(E_ERROR);
    ini_set('display_errors', 1);
    $jSEND = new jSEND();
    $theData = $jSEND->getData($theData);
    $m = GetClass('dbname');
    $dbname = $m->getdbname();
    $GetClass = GetClassPsToDb();
    $array = json_decode($theData, true);
    $idlogin = $_SESSION['jigowatt']['user_id'];
    if (is_array($array)) {
        echo CreateTransactionSumary($array);
    } else {
        echo "Error : An array expected";
    }
}

function CreateTransactionSumary($data) {
    include_once ('Server/developer/fpdf.php');

    class PDF extends FPDF {

        function Header() {
            $this->SetTextColor(0);
            /**/
            $m = new dbname();
            $dbname = $m->getdbname();
            $GetClass = GetClassPsToDb();
            $general_config_obj = $GetClass->GetClass('general_config');
            $idgeneral_config = 1;
            $general_config = $general_config_obj->getgeneral_configById($idgeneral_config);
            $pathlogo = 'temp/Logo' . $dbname . '.png';
            if ($general_config->getlogo1()) {
                $fh = fopen($pathlogo, 'w') or die("can't open file");
                $stringData = $general_config->getlogo1();
                fwrite($fh, $stringData);
                /**/
                $this->Image($pathlogo, 155, 6, 50);
            }
            $this->Ln(10);
        }

        function Body($data, $background, $color) {
            $background = explode(',', $background);
            $this->SetFont('Arial', '', 12);
            $this->Cell(194, 10, 'TRANSACTION SUMMARY', 0, 0, 'C');
            $this->Ln(8);
            $this->SetFont('Arial', '', 8);
            $this->Cell(194, 6, $data['PropertyAddress'] . '', 0, 0, 'L');
            $this->Ln();
            $this->Cell(97, 6, 'MLS # : ' . $data['MLS'], 0, 0, 'L');
            $this->Cell(97, 6, 'REX # : ' . $data['ExternalNumber'], 0, 0, 'L');
            $this->Ln(10);

            $this->HeaderBox('CONTRACT INFORMATION', $background);
            $this->LineBox2Lines('Purchase Price : ', $data['PurchasePrice'], 'Efective Date : ', $data['EfectiveDate']);
            $this->LineBox2Lines('1st Escrow Deposit :', $data['Escrow1'], 'Closing Date : ', $data['ClosingDate']);
            $this->LineBox2Lines('2nd Escrow Deposit : ', $data['Escrow2'], '', $data['']);
            $this->LineBox2Lines('Financing Type : ', $data['FinancingType'], '', $data[''], true);
            $this->Ln(2);

            $this->HeaderBox('CONTRACT DEADLINES', $background);
            $this->LineBox1Line('1st Escrow deposit ' . $data['Escrow1'], $data['Escrow1Date']);
            //$this->LineBox1Line('Association application submitted ',$data['AssociateDate']);
            $this->LineBox1Line('Loan application due ', $data['LoanApplicationDate']);
            $this->LineBox1Line('HOA/Condo application due ', $data['TitleCommDate']);
            $this->LineBox1Line('Inspection period ends ', $data['InspectionDate']);
            $this->LineBox1Line('2nd Escrow deposit ' . $data['Escrow2'], $data['Escrow2Date']);
            $this->LineBox1Line('Loan approval due  ', $data['LoanApprovalDate']);
            $this->LineBox1Line('HOA/CONDO approval due  ', $data['HoaCondoDate']);
            $this->Ln(2);

            $this->HeaderBox('PROPERTY DETAILS', $background);
            $this->LineBox2Lines('County :', $data['County'], 'Governing Bodies : ', $data['HOA']);
            $this->LineBox2Lines('Property Type : ', $data['PropertyType'], 'HOA/Condo approval :', $data['HOAApproval']);
            $this->LineBox2Lines('Year Built : ', $data['YearBuilt'], 'HOPA : ', $data['HOPA'], true);
            $this->Ln(2);

            $this->HeaderBox('BUYERS', $background);
            $this->MultiCell(194, 6, $data['Buyers'], 'LR', 'L');
            $this->HeaderBox('SELLERS', $background);
            $this->MultiCell(194, 6, $data['Sellers'], 'LR', 'L');
            $this->LineBox2LineBack("Buyer's Agent : " . $data['BuyerAgent'], "Seller's Agent : " . $data['SellerAgent'], true);
            $this->LineBox2LineBack($data['BuyerAgentOffice'], $data['SellerAgentOffice']);
            $this->LineBox2LineBack($data['BuyerAgentOfficeAddress'], $data['SellerAgentOfficeAddress']);
            $this->LineBox2LineBack('Agent Lic # : ' . $data['BuyerAgentLic'], 'Agent Lic # : ' . $data['SellerAgentLic']);
            $this->LineBox2LineBack('Direct : ' . $data['BuyerAgentCell'], 'Direct : ' . $data['SellerAgentCell']);
            $this->LineBox2LineBack('Email : ' . $data['BuyerAgentEmail'], 'Email : ' . $data['SellerAgentEmail'], false, true);
            $this->Ln(2);

            $this->HeaderBox('TRANSACTION COORDINATOR', $background);
            $this->LineBox2LineBack($data['BuyerTransactionCoordinator'], $data['SellerTransactionCoordinator']);
            $this->LineBox2LineBack($data['BuyerTransactionCoordinatorTel'], $data['SellerTransactionCoordinatorTel']);
            $this->LineBox2LineBack($data['BuyerTransactionCoordinatorEmail'], $data['SellerTransactionCoordinatorEmail'], false, true);
            $this->Ln(2);

            $this->HeaderBox('TITLE COMPANY', $background);
            $this->LineBox2Lines($data['TitleCompanyName'], '', 'Direct : ', $data['TitleCompanyCell']);
            $this->LineBox2Lines($data['TitleCompanyAddress'], '', 'Email : ', $data['TitleCompanyEmail'], true);
            $this->Ln(2);

            $this->HeaderBox('ESCROW HOLDER', $background);
            $this->LineBox2Lines($data['EscrowHolderName'], '', 'Direct : ', $data['EscrowHolderCell']);
            $this->LineBox2Lines($data['EscrowHolderAddress'], '', 'Email : ', $data['EscrowHolderEmail'], true);
            $this->Ln(2);

            $this->HeaderBox('HOA / CONDO INFORMATION', $background);
            $this->SetFont('Arial', '', 8);
            $this->Cell(97, 6, $data['HOAName'], 'LB', 0, 'L');
            $this->Cell(53, 6, $data['HOAEmail'], 'LB', 0, 'L');
            $this->Cell(44, 6, $data['HOACell'], 'LBR', 0, 'L');
            $this->Ln();
            $this->Ln(2);

            $this->HeaderBox('LENDER INFORMATION', $background);
            $this->LineBox2Lines($data['LenderName'], '', 'Direct : ', $data['LenderCell']);
            $this->LineBox2Lines($data['LenderAddress'], '', 'Email : ', $data['LenderEmail'], true);
            $this->Ln(2);

            $this->HeaderBox('COMMISSIONS, FEES', $background);
            $this->LineBox2Lines('Buyer agent Commission: ' . $data['BuyerCommission'], '', 'Listing agent Commission : ' . $data['SellerCommission'], '');
            $this->LineBox2Lines('Transaction fee : ' . $data['BuyerTransactionfee'], '', 'Transaction fee  : ' . $data['SellerTransactionfee'], '', true);
            $this->Ln(2);

            $this->HeaderBox('MISCELLANEOUS INFORMATION', $background);
            $this->MultiCell(194, 6, $data['Miscellaneous'], 'LBR', 'L');
        }

        function LineBox2LineBack($text1, $text2, $isback = false, $isend = false) {
            $height = 5;
            $border = 'LR';
            $border2 = 'R';
            $fill = 0;
            $add = '';
            if ($isend) {
                $add = 'B';
            }
            if ($isback) {
                $height = 6;
                $border = '1';
                $border2 = 'RTB';
                $this->SetTextColor(255, 255, 255);
                $fill = 1;
                $this->SetFont('Arial', 'B', 8);
            }
            $this->Cell(97, $height, $text1, $border . $add, 0, 'L', $fill);
            $this->Cell(97, $height, $text2, $border2 . $add, 1, 'L', $fill);
            $this->SetFont('Arial', '', 8);
            $this->SetTextColor(0, 0, 0);
        }

        function LineBox1Line($label, $text) {
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(144, 5, $label, 'LB', 0, 'L');
            $this->SetFont('Arial', '', 8);
            $this->Cell(50, 5, $text, 'LRB', 0, 'C');
            $this->Ln();
        }

        function LineBox2Lines($label1, $text1, $label2, $text2, $end = false) {
            $add = '';
            if ($end) {
                $add = 'B';
            }
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(37, 5, $label1, 'L' . $add, 0, 'L');
            $this->SetFont('Arial', '', 8);
            $this->Cell(60, 5, $text1, '' . $add, 0, 'L');
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(37, 5, $label2, '' . $add, 0, 'L');
            $this->SetFont('Arial', '', 8);
            $this->Cell(60, 5, $text2, 'R' . $add, 0, 'L');
            $this->Ln();
        }

        function HeaderBox($text, $background) {
            $this->SetFillColor($background[0], $background[1], $background[2]);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(194, 6, $text, 1, 1, 'C', 1);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Arial', '', 8);
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

    $pdf = new PDF();
    $pdf->SetFont('helvetica', '', 10);
    $pdf->AliasNbPages();
    $pdf->AddPage('P', 'Legal');
    $pdf->Body($data, '223,24,40', 'white');
    $name = 'temp/TransactionSumary' . date('mdYHis') . '.pdf';
    $pdf->Output($name, '');
    return $name;
}
