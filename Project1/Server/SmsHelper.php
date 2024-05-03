<?php

require './twilio-php-v5/Twilio/autoload.php';

if (!class_exists('GetClassHelper')) {
    include_once ('GetClassHelper.php');
}
use Twilio\Rest\Client;
class SmsHelper {

    //use Twilio\Rest\Client;

    private $dbname;
    private $conn;
    private $MServiceSid;
    private $Client;
    private $Payment;
    private $CostSms;
    private $TwilioNumber;

    function __construct($dbname) {
        $this->dbname = $dbname;
        $this->conn = new GetClassHelper($dbname);
        date_default_timezone_set('America/New_York');
        $general_config_obj = $this->conn->GetClass('general_config');
        $general_config = $general_config_obj->getgeneral_configById(1);
        $this->CostSms = $general_config->getsms_price();
        $mytwilio_obj = $this->conn->GetClass('mytwilio');
        $mytwilio = $mytwilio_obj->getmytwilioById('1');
        $this->TwilioNumber = $mytwilio->gettwilionumber();
        $this->MServiceSid = $mytwilio->getappSid();
        $this->Client = new Client($mytwilio->getaccountSid(), $mytwilio->getauthToken());
        $this->Payment = $this->conn->GetClass('PaymentPrePost');
    }

    public function SendSms($To, $Body) {
        if (!$this->Payment->Balance($this->CostSms, 'Sms')) {
            $Return = array('Code' => '500', 'Msj' => 'Insufficient Balance');
        } else {
            $To = trim(str_replace(array('(', ')', ' ', '-'), '', $To));
            try {
                $Data = array();
                $Data['AmountDebit'] = $this->CostSms;
                $Data['Function'] = 'Sms';
                $Data['Type'] = 'Sms';
                $Data['Description'] = 'Sms to ' . $To;
                $Data['Rate'] = $this->CostSms;
                $Data['Serv'] = 2;
                $Data['Qty'] = 1;
                $payment = $this->Payment->ProcessPayment($Data);
                if ($payment) {
                    $payment = json_decode($payment, true);
                    if ($payment['Code'] == 500) {
                        $Return = array('Code' => '500', 'Msj' => $payment['Msj']);
                    }
                    if ($payment['Code'] == 200) {
                        $Response = $this->Client->messages
                                ->create("+$To",
                                [
                                    "body" => "$Body",
                                    "from" => "+$this->TwilioNumber",
                                    "messagingServiceSid" => "$this->MServiceSid"
                        ]);
                        $Return = array('Code' => '200', 'Msj' => $Response->sid);
                    }
                } else {
                    $Return = array('Code' => '500', 'Msj' => 'Error on process payment, please contact with support -> '.$payment);
                }
            } catch (Exception $e) {
                $Return = array('Code' => '500', 'Msj' => $e->getMessage());
            }
        }

        return json_encode($Return);
    }

}
