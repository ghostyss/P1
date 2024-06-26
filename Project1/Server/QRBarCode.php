<?php

class QRBarCode {

    private $googleChartAPI = 'http://chart.apis.google.com/chart';
    private $codeData;

    public function url($url = null) {
        $this->codeData = preg_match("#^https?://#", $url) ? $url : "http://{$url}";
    }

    public function text($text) {
        $this->codeData = $text;
    }

    public function email($email = null, $subject = null, $message = null) {
        $this->codeData = "MATMSG:TO:{$email};SUB:{$subject};BODY:{$message};;";
    }

    public function phone($phone) {
        $this->codeData = "TEL:{$phone}";
    }

    public function sms($phone = null, $msg = null) {
        $this->codeData = "SMSTO:{$phone}:{$msg}";
    }

    public function contact($name = null, $address = null, $phone = null, $email = null, $note = null, $url = null) {
        $this->codeData = "MECARD:N:{$name};ADR:{$address};TEL:{$phone};EMAIL:{$email};NOTE:{$note};URL:{$url};;";
    }

    public function content($type = null, $size = null, $content = null) {
        $this->codeData = "CNTS:TYPE:{$type};LNG:{$size};BODY:{$content};;";
    }

    public function qrCode($size = 200, $filename = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->googleChartAPI);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "chs={$size}x{$size}&cht=qr&chl=" . urlencode($this->codeData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $img = curl_exec($ch);
        curl_close($ch);

        if ($img) {
            if ($filename) {
                if (!preg_match("#.png$#i", $filename)) {
                    $filename .= ".png";
                }

                return file_put_contents($filename, $img);
            } else {
                /*header("Content-type: image/png");
                print $img;*/
                return $img;
            }
        }
        return false;
    }

}
