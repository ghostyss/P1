<?php

error_reporting(E_ERROR);
ini_set('display_errors', 1);
session_start();
include_once ($_SERVER['DOCUMENT_ROOT'] . '/mrt/security/classes/conf.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/mrt/QB/vendor/autoload.php');

use QuickBooksOnline\API\Core\CoreHelper;
use QuickBooksOnline\API\Core\Http\Serialization\IEntitySerializer;
use QuickBooksOnline\API\Core\HttpClients\FaultHandler;
use QuickBooksOnline\API\Core\HttpClients\RestHandler;
use QuickBooksOnline\API\Core\ServiceContext;
use QuickbooksOnline\API\Core\CoreConstants;
use QuickBooksOnline\API\Core\HttpClients\SyncRestHandler;
use QuickBooksOnline\API\Core\HttpClients\RequestParameters;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Exception\IdsException;
use QuickBooksOnline\API\Exception\ServiceException;
use QuickBooksOnline\API\Exception\IdsExceptionManager;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Diagnostics\TraceLevel;
use QuickBooksOnline\API\Diagnostics\ContentWriter;
use QuickBooksOnline\API\XSD2PHP\src\com\mikebevz\xsd2php\Php2Xml;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;
use QuickBooksOnline\API\Core\HttpClients\ClientFactory;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Vendor;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\Facades\Purchase;
use QuickBooksOnline\API\Facades\Deposit;

if (!class_exists('GetClassHelper')) {
    include_once ('GetClassHelper.php');
}

// actividad de logueo, historial de compras E-Dis

class QBHelper {

    public $IPP;
    public $quickbooks_is_connected;
    public $realm;
    private $Context;
    private $QuickbookObj;
    public $db;
    private $conn;
    public $quickbooks_CompanyInfo;
    public $dataService;
    public $ObjectToken;
    public $OAuth2LoginHelper;

    function __construct($dbname = '') {
        //echo '1';
        if (!class_exists('dbname')) {
            //echo '2';
            include_once ('dbname.php');
        }
        //echo '3';
        $m = new dbname();
        $dbnameBase = $m->getdbname();
        //echo '1';
        $this->conn = new GetClassHelper($dbname);
        //echo '2';
        $quickbook_obj = $this->conn->GetClass('quickbook');
        $this->QuickbookObj = $quickbook_obj->getquickbookById('1');
        //print_r($this->QuickbookObj);
        if (!$dbname) {
            $this->db = $dbnameBase;
        }else{
            $this->db = $dbname;
        }
        $oauth_client_id = $this->QuickbookObj->getconsumer_key();
        $oauth_client_secret = $this->QuickbookObj->getconsumer_secret();
        $this->realm = $this->QuickbookObj->getrealm_id();
        $scope = 'com.intuit.quickbooks.accounting openid profile email';
        $quickbooks_oauth_url = 'https://' . $_SERVER['SERVER_NAME'] . '/mrt/QB/callback.php';
        $quickbooks_oauth_url2 = 'https://' . $_SERVER['SERVER_NAME'] . '/mrt/QB/callback2.php';
        $quickbooks_OpenId_url = 'https://' . $_SERVER['SERVER_NAME'] . '/mrt/QB/OAuthOpenID.php';
        $quickbooks_success_url = 'https://' . $_SERVER['SERVER_NAME'] . '/mrt/QB/Success.php';
        $quickbooks_refresh_url = 'https://' . $_SERVER['SERVER_NAME'] . '/mrt/QB/RefreshToken.php';
        if($dbname == 'manager'){
            $quickbooks_oauth_url = str_replace('/'.$dbnameBase.'.','/manager.',$quickbooks_oauth_url2);
        }
        
        $this->dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $oauth_client_id,
                    'ClientSecret' => $oauth_client_secret,
                    'RedirectURI' => $quickbooks_oauth_url,
                    'scope' => 'com.intuit.quickbooks.accounting openid profile email phone address',
                    //'scope' => 'com.intuit.quickbooks.accounting',
                    'baseUrl' => "https://quickbooks.api.intuit.com"
        ));
        //print_r($this->QuickbookObj);
        $this->dataService->throwExceptionOnError(true);
        $this->quickbooks_is_connected = false;
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        $this->OAuth2LoginHelper = $OAuth2LoginHelper;
        try {
            if ($this->QuickbookObj->getconfig()) {
                $OAuth2LoginHelper->faultHandler = false;
                $OAuth2LoginHelper->oauth2AccessToken = $OAuth2LoginHelper->parseNewAccessTokenFromResponse($this->QuickbookObj->getconfig(), $this->QuickbookObj->getrealm_id(), $this->db);
                $this->ObjectToken = $OAuth2LoginHelper->refreshToken();
                $this->dataService->updateOAuth2Token($this->ObjectToken);
                $this->quickbooks_is_connected = true;
                //$this->RefreshQB();
            }
        } catch (Exception $e) {
            $_SESSION['SessionClient'] = $e->getMessage();
            $this->quickbooks_is_connected = false;
        }
    }

    public function RefreshQB() {
        try {
            $this->ObjectToken = $_SESSION['sessionAccessToken'];
            //print_r($this->ObjectToken);
            $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
            //print_r($OAuth2LoginHelper);
            $newAccessTokenObj = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($this->ObjectToken->getRefreshToken());
            $newAccessTokenObj->setRealmID($this->ObjectToken->getRealmID());
            $newAccessTokenObj->setBaseURL($this->ObjectToken->getBaseURL());
            $_SESSION['sessionAccessToken'] = $newAccessTokenObj;
            $this->ObjectToken = $newAccessTokenObj;
            $this->quickbooks_is_connected = true;
        } catch (SdkException $e) {
            echo $e->getTraceAsString();
        }
    }

    /* Customer */

    public function MessageError($data) {
        if (strpos($data, '[<?xml') !== false) {
            $data = explode('[<?xml', $data);
            $data = $data[1];
            $data = substr('<?xml' . $data, 0, -2);
            $data = simplexml_load_string($data);
            $error = $data->Fault->Error->Message->__toString();
            $Code = $data->Fault->Error->attributes()->code->__toString();
            $return = array('Code' => $Code, 'Msj' => $error);
        } else {
            $return = array('Code' => 500, 'Msj' => 'SDKError : ' . $data);
        }

        return json_encode($return);
    }

    public function CodeDecode($String) {
        $result = json_encode($String);
        return json_decode($result, true);
    }

    public function CustomerQB($Function, $Parameters) {
        //print_r(Customer);
        //$this->RefreshQB();
        if (!$this->quickbooks_is_connected) {
            return 'QuickBooks is not connected';
        }
        $this->dataService->updateOAuth2Token($this->ObjectToken);
        $data = [
            "BillAddr" => [
                "Line1" => $Parameters['Line1'],
                "City" => $Parameters['City'],
                //"Country" => $Parameters['Country'],
                "Country" => 'USA',
                "CountrySubDivisionCode" => $Parameters['SubCode'],
                "PostalCode" => $Parameters['PostalCode']
            ],
            "Notes" => $Parameters[''],
            "Title" => $Parameters['Title'],
            "GivenName" => $Parameters['GivenName'],
            "MiddleName" => $Parameters['MiddleName'],
            "FamilyName" => $Parameters['FamilyName'],
            "Suffix" => $Parameters['Suffix'],
            "FullyQualifiedName" => $Parameters['DisplayName'],
            "CompanyName" => $Parameters['CompanyName'],
            "DisplayName" => $Parameters['DisplayName'],
            "PrimaryPhone" => [
                "FreeFormNumber" => $Parameters['PrimaryPhone']
            ],
            "PrimaryEmailAddr" => [
                "Address" => $Parameters['PrimaryEmail']
            ]
        ];
        try {
            $return = array('Code' => 500, 'Msj' => 'Not Found Function');
            switch ($Function) {
                case 'Create':$theResourceObj = Customer::create($data);
                    $result = $this->CodeDecode($this->dataService->Add($theResourceObj));
                    $return = array('Code' => 200, 'Msj' => $result['Id']);
                    break;
                case 'Update':
                    $data['Id'] = $Parameters['IdQ'];
                    $entities = $this->dataService->Query("SELECT * FROM Customer where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $theCustomer = reset($entities);
                        $theResourceObj = Customer::update($theCustomer, $data);
                        $result = $this->CodeDecode($this->dataService->Update($theResourceObj));
                        if ($result['Id'] == $Parameters['IdQ']) {
                            $return = array('Code' => 200, 'Msj' => 'Updated');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Customer not Found');
                    }
                    break;
                case 'Delete':
                    $data['Id'] = $Parameters['IdQ'];
                    $data['Active'] = false;
                    $entities = $this->dataService->Query("SELECT * FROM Customer where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $theCustomer = reset($entities);
                        $theResourceObj = Customer::update($theCustomer, $data);
                        $result = $this->CodeDecode($this->dataService->Update($theResourceObj));
                        if ($result['Id'] == $Parameters['IdQ']) {
                            $return = array('Code' => 200, 'Msj' => 'Updated Active to False');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 300, 'Msj' => 'Customer not Exist');
                    }
                    break;
                case 'GetId':
                    $data['Id'] = $Parameters['IdQ'];
                    $entities = $this->dataService->Query("SELECT * FROM Customer where DisplayName='" . $Parameters['DisplayName'] . "'");
                    if ($entities) {
                        $result = $this->CodeDecode($entities[0]);
                        $return = array('Code' => 200, 'Msj' => $result['Id']);
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Customer not Found');
                    }
                    break;
            }
            return json_encode($return);
        } catch (Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function BankQB($Function, $Parameters = array()) {
        //$this->RefreshQB();
        if (!$this->quickbooks_is_connected) {
            return 'QuickBooks is not connected';
        }
        $data = array();
        $data['Name'] = $Parameters['Name'];
        $data['Description'] = $Parameters['Description'];
        $data['AccountType'] = 'Bank';
        $data['AccountSubType'] = 'Checking';
        switch ($Function) {
            case 'Create':
                $theResourceObj = Account::create($data, true);
                $resultingInvoiceObj = $this->dataService->Add($theResourceObj);
                $result = $this->CodeDecode($resultingInvoiceObj);
                if ($result['Id']) {
                    $return = array('Code' => 200, 'Msj' => $result['Id']);
                }
                break;
            case 'Update':
                $return = array('Code' => 500, 'Msj' => 'Function not Available');
                break;
            case 'Delete':
                $return = array('Code' => 500, 'Msj' => 'Function not Available');
                break;
            case 'FindAll':
                $entities = $this->dataService->Query("SELECT * FROM Account where AccountType='Bank'");
                //print_r($entities);
                if ($entities) {
                    $result = $this->CodeDecode($entities);
                    $options = '';
                    foreach ($result as $Item) {
                        $options .= '<option value="' . $Item['Id'] . '">' . $Item['FullyQualifiedName'] . '</option>';
                    }
                    $return = array('Code' => 200, 'Msj' => $options);
                } else {
                    $return = array('Code' => 500, 'Msj' => 'Vendor not Found');
                }
                break;
        }
        return json_encode($return);
    }

    public function VendorQB($Function, $Parameters) {
        //print_r(Vendor);
        //$this->RefreshQB();
        if (!$this->quickbooks_is_connected) {
            return 'QuickBooks is not connected';
        }

        $data = [
            "Title" => $Parameters['Title'],
            "GivenName" => $Parameters['GivenName'],
            "MiddleName" => $Parameters['MiddleName'],
            "FamilyName" => $Parameters['FamilyName'],
            "Suffix" => $Parameters['Suffix'],
            "FullyQualifiedName" => $Parameters['DisplayName'],
            "CompanyName" => $Parameters['CompanyName'],
            "DisplayName" => $Parameters['DisplayName'],
            "PrimaryPhone" => [
                "FreeFormNumber" => $Parameters['PrimaryPhone']
            ],
            "PrimaryEmailAddr" => [
                "Address" => $Parameters['PrimaryEmail']
            ]
        ];
        try {
            $return = array('Code' => 500, 'Msj' => 'Not Found Function');
            switch ($Function) {
                case 'Create':$theResourceObj = Vendor::create($data);
                    $result = $this->CodeDecode($this->dataService->Add($theResourceObj));
                    $return = array('Code' => 200, 'Msj' => $result['Id']);
                    break;
                case 'Update':
                    $data['Id'] = $Parameters['IdQ'];
                    $entities = $this->dataService->Query("SELECT * FROM Vendor where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $theVendor = reset($entities);
                        $theResourceObj = Vendor::update($theVendor, $data);
                        $result = $this->CodeDecode($this->dataService->Update($theResourceObj));
                        if ($result['Id'] == $Parameters['IdQ']) {
                            $return = array('Code' => 200, 'Msj' => 'Updated');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Vendor not Found');
                    }
                    break;
                case 'Delete':
                    $data['Id'] = $Parameters['IdQ'];
                    $data['Active'] = false;
                    $entities = $this->dataService->Query("SELECT * FROM Vendor where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $theVendor = reset($entities);
                        $theResourceObj = Vendor::update($theVendor, $data);
                        $result = $this->CodeDecode($this->dataService->Update($theResourceObj));
                        if ($result['Id'] == $Parameters['IdQ']) {
                            $return = array('Code' => 200, 'Msj' => 'Updated Active to False');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 300, 'Msj' => 'Vendor not Exist');
                    }
                    break;
                case 'GetId':
                    $data['Id'] = $Parameters['IdQ'];
                    $entities = $this->dataService->Query("SELECT * FROM Vendor where DisplayName='" . $Parameters['DisplayName'] . "'");
                    if ($entities) {
                        $result = $this->CodeDecode($entities[0]);
                        $return = array('Code' => 200, 'Msj' => $result['Id']);
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Vendor not Found');
                    }
                    break;
            }
            return json_encode($return);
        } catch (Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    /* Invoice */

    public function InvoiceQB($Function, $Parameters) {
        //$this->RefreshQB();
        if (!$this->quickbooks_is_connected) {
            return 'QuickBooks is not connected';
        }

        $data = array();
        $Lines = array();
        $countinv = 1;
        if (is_array($Parameters['Amount'])) {
            foreach ($Parameters['Amount'] as $index => $valueobj) {
                $Line = array();
                $Line['Id'] = $countinv;
                $Line['LineNum'] = $countinv;
                $countinv++;
                $Line['Amount'] = doubleval($valueobj) * intval($Parameters['Qyt'][$index]);
                $Line['DetailType'] = 'SalesItemLineDetail';
                $Line['Description'] = $Parameters['Description'][$index];
                $SalesItemLineDetail = array();
                $SalesItemLineDetail['ItemRef'] = array('value' => $Parameters['ItemRef'][$index]);
                $SalesItemLineDetail['UnitPrice'] = doubleval($valueobj);
                $SalesItemLineDetail['Qty'] = intval($Parameters['Qyt'][$index]);
                $Line['SalesItemLineDetail'] = $SalesItemLineDetail;
                $Lines[] = $Line;
            }
        } else {
            $Line = array();
            $Line['Id'] = $countinv;
            $Line['LineNum'] = $countinv;
            $Line['Amount'] = doubleval($Parameters['Amount']) * intval($Parameters['Qyt']);
            $Line['DetailType'] = 'SalesItemLineDetail';
            $Line['Description'] = $Parameters['Description'];
            $SalesItemLineDetail = array();
            $SalesItemLineDetail['ItemRef'] = array('value' => $Parameters['ItemRef']);
            $SalesItemLineDetail['UnitPrice'] = doubleval($Parameters['Amount']);
            $SalesItemLineDetail['Qty'] = intval($Parameters['Qyt']);
            $Line['SalesItemLineDetail'] = $SalesItemLineDetail;
            $Lines[] = $Line;
        }
        $data['Line'] = $Lines;
        $ArrayBillAddress = array();
        if ($Parameters['Line1']) {
            $ArrayBillAddress['Line1'] = $Parameters['Line1'];
        }
        if ($Parameters['Line2']) {
            $ArrayBillAddress['Line2'] = $Parameters['Line2'];
        }
        if ($Parameters['Line3']) {
            $ArrayBillAddress['Line3'] = $Parameters['Line3'];
        }
        if ($ArrayBillAddress) {
            $data['BillAddr'] = $ArrayBillAddress;
        }
        $data['BillEmail'] = array('Address' => $Parameters['BillEmail']);
        $data['EmailStatus'] = 'EmailSent';
        $data['CustomerRef'] = array('value' => $Parameters['CustomerRef']);
        if ($Parameters['DueDate']) {
            $data['DueDate'] = $Parameters['DueDate'];
        }
        if ($Parameters['CustomerMemo']) {
            $data['CustomerMemo'] = $Parameters['CustomerMemo'];
        }
        if ($Parameters['PrivateNote']) {
            //$data['PrivateNote'] = $Parameters['PrivateNote'];
        }
        $isOnlinePayment = isset($Parameters['NoPaymentOnline']) ? false : true;
        //$data['AllowOnlinePayment'] = $isOnlinePayment;
        $data['AllowOnlineCreditCardPayment'] = $isOnlinePayment;
        $data['AllowOnlineACHPayment'] = $isOnlinePayment;
        //$data['SalesTermRef'] = $Parameters['SalesTermRef'];
        try {
            switch ($Function) {
                case 'Create':
                    //print_r($data);
                    $theResourceObj = Invoice::create($data, true);
                    //print_r($theResourceObj);
                    $resultingInvoiceObj = $this->dataService->Add($theResourceObj);
                    $result = $this->CodeDecode($resultingInvoiceObj);
                    if ($result['Id']) {
                        $resultingMailObj = $this->dataService->sendEmail($resultingInvoiceObj, $resultingInvoiceObj->BillEmail->Address);
                        $result2 = $this->CodeDecode($resultingMailObj);
                        //print_r($result2);
                        $return = array('Code' => 200, 'Msj' => $result['Id']);
                    }
                    break;
                case 'Update':
                    $data['Id'] = $Parameters['IdQ'];
                    $entities = $this->dataService->Query("SELECT * FROM Invoice where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $theInvoice = reset($entities);
                        $theResourceObj = Invoice::update($theInvoice, $data);
                        $resultingInvoiceObj = $this->dataService->Update($theResourceObj);
                        $result = $this->CodeDecode($resultingInvoiceObj);
                        if ($result['Id'] == $Parameters['IdQ']) {
                            $resultingMailObj = $this->dataService->sendEmail($resultingInvoiceObj, $resultingInvoiceObj->BillEmail->Address);
                            $return = array('Code' => 200, 'Msj' => 'Updated');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Invoice not Found');
                    }
                    break;
                case 'Status':
                    $data['Id'] = $Parameters['IdQ'];
                    $entities = $this->dataService->Query("SELECT * FROM Invoice where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $theInvoice = reset($entities);
                        $result = $this->CodeDecode($theInvoice);
                        if ($result['EInvoiceStatus'] == 'Paid') {
                            $return = array('Code' => 200, 'Msj' => 'Paid');
                        } else {
                            $return = array('Code' => 200, 'Msj' => 'Pending');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Invoice not Found');
                    }
                    break;
                case 'ReSend':
                    if ($Parameters['IdQ']) {
                        $entities = $this->dataService->Query("SELECT * FROM Invoice where Id='" . $Parameters['IdQ'] . "'");
                        if ($entities) {
                            $resultingInvoiceObj = $entities[0];
                            $resultingMailObj = $this->dataService->sendEmail($resultingInvoiceObj, $resultingInvoiceObj->BillEmail->Address);
                            $return = array('Code' => 200, 'Msj' => 'Success');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Invoice Not Found');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Id Invoice is Required');
                    }
                    break;
            }
            return json_encode($return);
        } catch (Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    /**/

    public function GetItemsServices() {
        //$this->RefreshQB();
        if (!$this->quickbooks_is_connected) {
            return 'QuickBooks is not connected';
        }

        $entities = $this->dataService->Query("SELECT * FROM Item");
        $result = $this->CodeDecode($entities);
        $return = '';
        foreach ($result as $Item) {
            $return .= '<option value="' . $Item['Id'] . '">' . $Item['FullyQualifiedName'] . '</option>';
        }
        $Response = array('Code' => '200', 'Msj' => $return);
        return json_encode($Response);
    }

    /* Account */

    public function AccountQB($Function, $Parameters) {
        //$this->RefreshQB();
        if (!$this->quickbooks_is_connected) {
            return 'QuickBooks is not connected';
        }

        $data = array();
        $data['Name'] = $Parameters['Name'];
        $data['Description'] = $Parameters['Description'];
        $data['AccountType'] = 'OtherCurrentLiability';
        $data['AccountSubType'] = 'TrustAccountsLiabilities';
        try {
            switch ($Function) {
                case 'Create':
                    //print_r($data);
                    $theResourceObj = Account::create($data, true);
                    $resultingInvoiceObj = $this->dataService->Add($theResourceObj);
                    $result = $this->CodeDecode($resultingInvoiceObj);
                    if ($result['Id']) {
                        $return = array('Code' => 200, 'Msj' => $result['Id']);
                    }
                    break;
                case 'Update':
                    $data['Id'] = $Parameters['IdQ'];
                    $entities = $this->dataService->Query("SELECT * FROM Account where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $theAccount = reset($entities);
                        $theResourceObj = Account::update($theAccount, $data);
                        $resultingAccountObj = $this->dataService->Update($theResourceObj);
                        $result = $this->CodeDecode($resultingAccountObj);
                        if ($result['Id'] == $Parameters['IdQ']) {
                            $return = array('Code' => 200, 'Msj' => 'Updated');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Account not Found');
                    }
                    break;
                case 'Delete':
                    $return = array('Code' => 500, 'Msj' => 'Function not Available');
                    break;
            }
            return json_encode($return);
        } catch (Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    /**/
    /* Purchase */

    public function PurchaseQB($Function, $Parameters) {
        //$this->RefreshQB();
        if (!$this->quickbooks_is_connected) {
            return 'QuickBooks is not connected';
        }

        $data = array();
        $LinesSended = is_array($Parameters['lines']) ? $Parameters['lines'] : json_decode($Parameters['lines'], true);
        $Lines = array();
        foreach ($LinesSended as $LineSended) {
            $Line = array();
            $Line['Id'] = $Count;
            $Line['LineNum'] = $Count;
            $Count++;
            $Line['Description'] = $LineSended['description'];
            $Line['Amount'] = $LineSended['amount'];
            $Line['DetailType'] = 'AccountBasedExpenseLineDetail';
            $Line['AccountBasedExpenseLineDetail'] = array('AccountRef' => $LineSended['account'], 'BillableStatus' => 'NotBillable');
            $Lines[] = $Line;
        }
        $data['PaymentType'] = 'Check';
        //$data['DocNumber'] = $Parameters['DocNumber'];
        $data['TxnDate'] = $Parameters['TxnDate'];
        $data['PrivateNote'] = $Parameters['PrivateNote'];
        $data['EntityRef'] = $Parameters['EntityRef'];
        $data['AccountRef'] = array('value' => $Parameters['AccountRef']);
        $data['Line'] = $Lines;
        try {
            switch ($Function) {
                case 'Create':
                    $theResourceObj = Purchase::create($data, true);
                    $resultingInvoiceObj = $this->dataService->Add($theResourceObj);
                    $result = $this->CodeDecode($resultingInvoiceObj);
                    if ($result['Id']) {
                        $return = array('Code' => 200, 'Msj' => $result['Id']);
                    }
                    break;
                case 'Update':
                    $data['Id'] = $Parameters['IdQ'];
                    $entities = $this->dataService->Query("SELECT * FROM Purchase where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $thePurchase = reset($entities);
                        $theResourceObj = Purchase::update($thePurchase, $data);
                        $resultingPurchaseObj = $this->dataService->Update($theResourceObj);
                        $result = $this->CodeDecode($resultingPurchaseObj);
                        if ($result['Id'] == $Parameters['IdQ']) {
                            $return = array('Code' => 200, 'Msj' => 'Updated');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Check not Found');
                    }
                    break;
                case 'Delete':
                    $data['Id'] = $Parameters['IdQ'];
                    $entities = $this->dataService->Query("SELECT * FROM Purchase where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $thePurchase = reset($entities);
                        $crudResultObj = $this->dataService->Delete($thePurchase);
                        if ($crudResultObj) {
                            $return = array('Code' => 200, 'Msj' => 'Deleted');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Check not Found');
                    }
                    break;
                case 'Void':
                    $entities = $this->dataService->Query("SELECT * FROM Purchase where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $thePurchase = reset($entities);
                        $crudResultObj = $this->dataService->Void($thePurchase);
                        if ($crudResultObj) {
                            print_r($crudResultObj);
                            $return = array('Code' => 200, 'Msj' => 'Voided');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Check not Found');
                    }
                    break;
            }
            return json_encode($return);
        } catch (Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    /**/
    /* Deposit */

    public function DepositQB($Function, $Parameters) {
        //$this->RefreshQB();
        if (!$this->quickbooks_is_connected) {
            return 'QuickBooks is not connected';
        }
        $data = array();
        $LinesSended = is_array($Parameters['lines']) ? $Parameters['lines'] : json_decode($Parameters['lines'], true);
        $Lines = array();
        $Count = 1;
        foreach ($LinesSended as $LineSended) {
            $Line = array();
            $Line['Id'] = $Count;
            $Line['LineNum'] = $Count;
            $Count++;
            $Line['Description'] = $LineSended['description'];
            $Line['Amount'] = $LineSended['amount'];
            $Line['DetailType'] = 'DepositLineDetail';
            $Line['DepositLineDetail'] = array('AccountRef' => $LineSended['account'], 'Entity' => $LineSended['Entity'], 'PaymentMethodRef' => $LineSended['PaymentMethodRef'], 'CheckNum' => $LineSended['CheckNum']);
            $Lines[] = $Line;
        }
        $data['DepositToAccountRef'] = $Parameters['DepositToAccountRef'];
        $data['TxnDate'] = $Parameters['TxnDate'];
        $data['TotalAmt'] = $Parameters['TotalAmt'];
        $data['PrivateNote'] = $Parameters['PrivateNote'];
        $data['Line'] = $Lines;
        try {
            switch ($Function) {
                case 'Create':
                    $theResourceObj = Deposit::create($data, true);
                    $resultingInvoiceObj = $this->dataService->Add($theResourceObj);
                    $result = $this->CodeDecode($resultingInvoiceObj);
                    if ($result['Id']) {
                        $return = array('Code' => 200, 'Msj' => $result['Id']);
                    }
                    break;
                case 'Update':
                    $data['Id'] = $Parameters['IdQ'];
                    $entities = $this->dataService->Query("SELECT * FROM Deposit where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $theDeposit = reset($entities);
                        $theResourceObj = Deposit::update($theDeposit, $data);
                        $resultingDepositObj = $this->dataService->Update($theResourceObj);
                        $result = $this->CodeDecode($resultingDepositObj);
                        print_r($result);
                        if ($result['Id'] == $Parameters['IdQ']) {
                            $return = array('Code' => 200, 'Msj' => 'Updated');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Check not Found');
                    }
                    break;
                case 'Delete':
                    $entities = $this->dataService->Query("SELECT * FROM Deposit where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $theDeposit = reset($entities);
                        $crudResultObj = $this->dataService->Delete($theDeposit);
                        if ($crudResultObj) {
                            $return = array('Code' => 200, 'Msj' => 'Deleted');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Check not Found');
                    }
                    break;
                case 'Void':
                    $entities = $this->dataService->Query("SELECT * FROM Deposit where Id='" . $Parameters['IdQ'] . "'");
                    if ($entities) {
                        $theDeposit = reset($entities);
                        $crudResultObj = $this->dataService->Void($theDeposit);
                        if ($crudResultObj) {
                            print_r($crudResultObj);
                            $return = array('Code' => 200, 'Msj' => 'Voided');
                        } else {
                            $return = array('Code' => 500, 'Msj' => 'Han error has ocurred please try again');
                        }
                    } else {
                        $return = array('Code' => 500, 'Msj' => 'Check not Found');
                    }
                    break;
            }
            return json_encode($return);
        } catch (Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    /**/
}
